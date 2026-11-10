<?php

namespace App\Http\Controllers;

use App\Imports\TrainingImport;
use App\Models\District;
use App\Models\FundingSource;
use App\Models\TrainingCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ImportController extends Controller
{
    /**
     * Menampilkan halaman import Excel.
     */
    public function index()
    {
        $fundingSources = FundingSource::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $districts = District::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $categories = TrainingCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('imports.index', compact(
            'fundingSources',
            'districts',
            'categories'
        ));
    }

    /**
     * Memproses import Excel.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],

            'angkatan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'kabupaten_kota_mitra' => [
                'required',
                'string',
                'max:255',
                Rule::exists('districts', 'name')
                    ->where('is_active', true),
            ],

            'sumber_dana' => [
                'required',
                'string',
                'max:255',
                Rule::exists('funding_sources', 'name')
                    ->where('is_active', true),
            ],

            'training_category_id' => [
                'nullable',
                'integer',
                Rule::exists('training_categories', 'id')
                    ->where('is_active', true),
            ],

            'jpl' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ], [
            'file.required' => 'Silakan pilih file Excel.',
            'file.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
            'file.max' => 'Ukuran file maksimal 10 MB.',

            'kabupaten_kota_mitra.required' =>
                'Kabupaten/Kota/Mitra wajib dipilih.',
            'kabupaten_kota_mitra.exists' =>
                'Kabupaten/Kota/Mitra tidak valid atau tidak aktif.',

            'sumber_dana.required' =>
                'Sumber dana wajib dipilih.',
            'sumber_dana.exists' =>
                'Sumber dana tidak valid atau tidak aktif.',

            'training_category_id.exists' =>
                'Kategori pelatihan tidak valid atau tidak aktif.',
            'jpl.integer' =>
                'JPL harus berupa angka.',
            'jpl.min' =>
                'JPL tidak boleh kurang dari 0.',
        ]);

        try {
            /*
             * Ambil wilayah dari master data.
             *
             * Nilai yang dikirim ke importer berasal dari
             * record District yang aktif, bukan teks bebas.
             */
            $district = District::query()
                ->where('name', $validated['kabupaten_kota_mitra'])
                ->where('is_active', true)
                ->firstOrFail();

            $districtName = trim($district->name);

            /*
             * Baca metadata Excel.
             */
            $filePath = $request->file('file')->getRealPath();

            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            /*
             * BARIS 2: NAMA PELATIHAN
             */
            $trainingName = $this->getRowValue(
                $sheet,
                2,
                [
                    'nama pelatihan',
                    'judul pelatihan',
                    'nama kegiatan',
                ]
            );

            if ($trainingName === '') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'file' =>
                            'Nama pelatihan tidak ditemukan pada baris 2 Excel.',
                    ]);
            }

            /*
             * BARIS 3: KERJA SAMA
             */
            $cooperation = $this->getRowValue(
                $sheet,
                3,
                [
                    'kerja sama',
                    'kerjasama',
                    'mitra kerja sama',
                    'mitra kerjasama',
                ]
            );

            /*
             * BARIS 5: RENTANG TANGGAL PELATIHAN
             */
            $dateText = $this->getRowValue(
                $sheet,
                5,
                [
                    'tanggal pelaksanaan',
                    'tanggal pelatihan',
                    'waktu pelaksanaan',
                    'tanggal',
                ]
            );

            [$startDate, $endDate] = $this->parseDateRange(
                $dateText
            );

            /*
             * Data tambahan dari form import.
             */
            $batchNumber = trim(
                (string) ($validated['angkatan'] ?? '')
            );

            $trainingCategoryId = isset(
                $validated['training_category_id']
            )
                ? (int) $validated['training_category_id']
                : null;

            $jpl = isset($validated['jpl'])
                ? (int) $validated['jpl']
                : null;

            /*
             * Kirim seluruh metadata ke TrainingImport.
             *
             * districtName akan digunakan untuk:
             * 1. participants.district
             * 2. trainings.district
             */
            $import = new TrainingImport(
                batchNumber: $batchNumber,
                fundingSource: $validated['sumber_dana'],
                trainingName: $trainingName,
                facilitation: '',
                organizer: $cooperation,
                startDate: $startDate,
                endDate: $endDate,
                district: $districtName,
                trainingCategoryId: $trainingCategoryId,
                jpl: $jpl,
            );

            Excel::import(
                $import,
                $request->file('file')
            );

            Log::info('Import Excel SIRAPIK selesai', [
                'training_name' => $trainingName,
                'district' => $districtName,
                'created_participants' => $import->createdParticipants,
                'updated_participants' => $import->updatedParticipants,
                'created_trainings' => $import->createdTrainings,
                'created_histories' => $import->createdHistories,
                'error_count' => count($import->errors),
            ]);

            return redirect()
                ->route('imports.index')
                ->with('import_result', [
                    'created_participants' =>
                        $import->createdParticipants,
                    'updated_participants' =>
                        $import->updatedParticipants,
                    'created_trainings' =>
                        $import->createdTrainings,
                    'created_histories' =>
                        $import->createdHistories,
                    'errors' => $import->errors,
                ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {
            Log::error('Import Excel SIRAPIK gagal', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'Import gagal: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Membaca nilai dari baris Excel tertentu.
     */
    private function getRowValue(
        $sheet,
        int $rowNumber,
        array $labels
    ): string {
        $lastColumn = Coordinate::columnIndexFromString(
            $sheet->getHighestDataColumn()
        );

        $cells = [];

        for ($col = 1; $col <= $lastColumn; $col++) {
            $cells[$col] = trim(
                (string) $sheet
                    ->getCell([$col, $rowNumber])
                    ->getFormattedValue()
            );
        }

        $normalizedLabels = array_map(
            fn ($label) => $this->normalizeText($label),
            $labels
        );

        foreach ($cells as $col => $value) {
            if ($value === '') {
                continue;
            }

            $normalizedValue = $this->normalizeText($value);

            foreach ($normalizedLabels as $label) {
                if (
                    $normalizedValue === $label ||
                    str_starts_with($normalizedValue, $label . ' ')
                ) {
                    foreach ($labels as $originalLabel) {
                        $pattern = '/^' .
                            preg_quote($originalLabel, '/') .
                            '\s*[:\-]\s*(.+)$/iu';

                        if (preg_match($pattern, $value, $matches)) {
                            $inlineValue = trim($matches[1]);

                            if ($inlineValue !== '') {
                                return $inlineValue;
                            }
                        }
                    }

                    for (
                        $nextCol = $col + 1;
                        $nextCol <= $lastColumn;
                        $nextCol++
                    ) {
                        if ($cells[$nextCol] !== '') {
                            return $cells[$nextCol];
                        }
                    }
                }
            }
        }

        foreach ($cells as $value) {
            if ($value === '') {
                continue;
            }

            $normalizedValue = $this->normalizeText($value);
            $isLabel = false;

            foreach ($normalizedLabels as $label) {
                if (
                    $normalizedValue === $label ||
                    str_starts_with($normalizedValue, $label . ' ')
                ) {
                    $isLabel = true;
                    break;
                }
            }

            if (!$isLabel) {
                return $value;
            }
        }

        return '';
    }

    /**
     * Menormalkan teks untuk pencocokan label Excel.
     */
    private function normalizeText(string $text): string
    {
        $text = mb_strtolower(trim($text));

        $text = str_replace(
            [':', '/', '-', '_'],
            ' ',
            $text
        );

        return preg_replace('/\s+/', ' ', $text) ?? $text;
    }

    /**
     * Membaca rentang tanggal pelatihan.
     */
    private function parseDateRange(string $text): array
    {
        $text = trim($text);

        if ($text === '') {
            return [null, null];
        }

        $months = [
            'januari' => 'January',
            'februari' => 'February',
            'maret' => 'March',
            'april' => 'April',
            'mei' => 'May',
            'juni' => 'June',
            'juli' => 'July',
            'agustus' => 'August',
            'september' => 'September',
            'oktober' => 'October',
            'november' => 'November',
            'desember' => 'December',
        ];

        $normalized = mb_strtolower($text);

        foreach ($months as $indo => $english) {
            $normalized = str_replace(
                $indo,
                $english,
                $normalized
            );
        }

        $normalized = preg_replace(
            '/\s+/',
            ' ',
            $normalized
        ) ?? $normalized;

        $parts = preg_split(
            '/\s*[-–—]\s*/',
            $normalized
        );

        if (!$parts || count($parts) !== 2) {
            throw new \RuntimeException(
                'Format tanggal pada baris 5 Excel tidak dikenali. ' .
                'Contoh: 18 MEI 2026 - 23 MEI 2026.'
            );
        }

        $start = strtotime(trim($parts[0]));
        $end = strtotime(trim($parts[1]));

        if ($start === false || $end === false) {
            throw new \RuntimeException(
                'Tanggal mulai atau tanggal selesai pada baris 5 Excel tidak valid.'
            );
        }

        if ($end < $start) {
            throw new \RuntimeException(
                'Tanggal selesai lebih awal daripada tanggal mulai.'
            );
        }

        return [
            date('Y-m-d', $start),
            date('Y-m-d', $end),
        ];
    }
}