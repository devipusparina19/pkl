<?php

namespace App\Http\Controllers;

use App\Imports\TrainingImport;
use App\Models\District;
use App\Models\FundingSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ImportController extends Controller
{
    public function index()
    {
        $fundingSources = FundingSource::where('is_active', true)
            ->orderBy('name')
            ->get();

        $districts = District::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('imports.index', compact(
            'fundingSources',
            'districts'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],
            'angkatan' => [
                'required',
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
            ],
        ], [
            'file.required' => 'Silakan pilih file Excel.',
            'file.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
            'angkatan.required' => 'Angkatan wajib diisi.',
            'kabupaten_kota_mitra.required' =>
                'Kabupaten/Kota/Mitra wajib dipilih.',
            'kabupaten_kota_mitra.exists' =>
                'Kabupaten/Kota/Mitra tidak valid atau tidak aktif.',
            'sumber_dana.required' =>
                'Sumber dana wajib dipilih.',
            'sumber_dana.exists' =>
                'Sumber dana tidak valid atau tidak aktif.',
        ]);

        try {
            $district = District::where(
                'name',
                $request->input('kabupaten_kota_mitra')
            )
                ->where('is_active', true)
                ->firstOrFail();

            $fundingSource = FundingSource::where(
                'name',
                $request->input('sumber_dana')
            )
                ->where('is_active', true)
                ->firstOrFail();

            $spreadsheet = IOFactory::load(
                $request->file('file')->getRealPath()
            );

            $sheet = $spreadsheet->getActiveSheet();

            /*
             * Excel:
             * Baris 2 = nama pelatihan
             * Baris 3 = fasilitasi
             * Baris 5 = rentang tanggal
             *
             * Cari seluruh sel pada baris terkait agar tidak
             * bergantung pada C2, C3, atau C5 saja.
             */
            $trainingName = $this->readRowText($sheet, 2);

            $facilitation = $this->readRowText($sheet, 3);

            $dateText = $this->readRowText($sheet, 5);

            if ($trainingName === '') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'file' =>
                            'Nama pelatihan tidak ditemukan pada baris 2 Excel.',
                    ]);
            }

            [$startDate, $endDate] = $this->parseDateRange($dateText);

            /*
             * Kolom KERJA SAMA dibaca oleh TrainingImport
             * dari header tabel peserta pada baris 7.
             * Nilai kerja sama diteruskan untuk disimpan
             * ke kolom trainings.organizer oleh TrainingImport.
             */
            $import = new TrainingImport(
                batchNumber: $request->input('angkatan'),
                fundingSource: $fundingSource->name,
                trainingName: $trainingName,
                facilitation: $facilitation,
                organizer: '',
                startDate: $startDate,
                endDate: $endDate,
                district: $district->name,
            );

            Excel::import($import, $request->file('file'));

            return redirect()
                ->route('imports.index')
                ->with('import_result', [
                    'created_participants' => $import->createdParticipants,
                    'updated_participants' => $import->updatedParticipants,
                    'created_trainings' => $import->createdTrainings,
                    'created_histories' => $import->createdHistories,
                    'errors' => $import->errors,
                ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Import Excel SIRAPIK gagal', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'file' =>
                        'Import gagal: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Membaca teks pertama yang terisi pada baris Excel.
     */
    private function readRowText($sheet, int $rowNumber): string
    {
        $highestColumn = Coordinate::columnIndexFromString(
            $sheet->getHighestDataColumn()
        );

        for ($column = 1; $column <= $highestColumn; $column++) {
            $cellAddress = Coordinate::stringFromColumnIndex($column)
                . $rowNumber;

            $value = trim((string) $sheet
                ->getCell($cellAddress)
                ->getFormattedValue());

            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

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

        $normalized = strtolower($text);

        foreach ($months as $indo => $english) {
            $normalized = str_replace(
                $indo,
                $english,
                $normalized
            );
        }

        $normalized = preg_replace('/\s+/', ' ', $normalized);

        $parts = preg_split('/\s*[-–—]\s*/', $normalized);

        if (count($parts) !== 2) {
            throw new \RuntimeException(
                'Format tanggal pada bagian atas Excel tidak dikenali. ' .
                'Contoh: 18 MEI 2026 - 23 MEI 2026.'
            );
        }

        $start = strtotime(trim($parts[0]));
        $end = strtotime(trim($parts[1]));

        if ($start === false || $end === false) {
            throw new \RuntimeException(
                'Tanggal mulai atau tanggal selesai pada Excel tidak valid.'
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