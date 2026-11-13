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
use Throwable;

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
            'file.required' =>
                'Silakan pilih file Excel.',

            'file.mimes' =>
                'File harus berformat XLSX, XLS, atau CSV.',

            'file.max' =>
                'Ukuran file maksimal 10 MB.',

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

        $spreadsheet = null;

        try {
            /*
             * 1. Ambil wilayah dari master data aktif.
             */
            $district = District::query()
                ->where(
                    'name',
                    $validated['kabupaten_kota_mitra']
                )
                ->where('is_active', true)
                ->firstOrFail();

            $districtName = trim($district->name);

            /*
             * 2. Baca metadata Excel.
             */
            $filePath = $request->file('file')->getRealPath();

            if (!$filePath) {
                throw new \RuntimeException(
                    'File Excel tidak dapat dibaca.'
                );
            }

            $spreadsheet = IOFactory::load($filePath);

            $sheet = $spreadsheet->getActiveSheet();

            /*
             * BARIS 2: NAMA PELATIHAN.
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
                throw new \RuntimeException(
                    'Nama pelatihan tidak ditemukan pada baris 2 Excel. ' .
                    'Periksa kolom nama pelatihan.'
                );
            }

            /*
             * BARIS 3: KERJA SAMA.
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
             * BARIS 5: RENTANG TANGGAL PELATIHAN.
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
             * 3. Data tambahan dari form.
             */
            $batchNumber = trim(
                (string) ($validated['angkatan'] ?? '')
            );

            $trainingCategoryId = !empty(
                $validated['training_category_id']
            )
                ? (int) $validated['training_category_id']
                : null;

            $jpl = isset($validated['jpl'])
                ? (int) $validated['jpl']
                : null;

            /*
             * 4. Buat importer.
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

            /*
             * 5. Jalankan import.
             *
             * TrainingImport harus menangani error per baris
             * agar baris valid tetap bisa diproses.
             */
            Excel::import(
                $import,
                $request->file('file')
            );

            /*
             * 6. Ambil dan seragamkan error.
             */
            $errors = $this->normalizeImportErrors(
                $import->errors ?? []
            );

            /*
             * 7. Ambil jumlah peserta baru dan diperbarui.
             */
            $createdParticipants = (int) (
                $import->createdParticipants ?? 0
            );

            $updatedParticipants = (int) (
                $import->updatedParticipants ?? 0
            );

            $createdTrainings = (int) (
                $import->createdTrainings ?? 0
            );

            $createdHistories = (int) (
                $import->createdHistories ?? 0
            );

            /*
             * 8. Hitung jumlah seluruh baris peserta yang diproses.
             *
             * PENTING:
             * Jangan hanya menjumlahkan peserta baru dan diperbarui.
             * Baris yang gagal juga harus masuk dalam jumlah diproses.
             */
            if (isset($import->processedParticipants)) {
                $processedParticipants = (int) (
                    $import->processedParticipants
                );
            } else {
                /*
                 * Fallback jika importer belum memiliki
                 * properti processedParticipants.
                 */
                $processedParticipants =
                    $createdParticipants + $updatedParticipants;
            }

            /*
             * 9. Hitung baris peserta yang gagal.
             *
             * Error metadata/file tidak dihitung sebagai
             * kegagalan peserta.
             *
             * Nomor baris Excel harus berupa angka dan
             * dimulai dari baris data peserta (baris 8).
             */
            $failedRows = (int) (
    $import->failedParticipants
    ?? $this->countFailedParticipantRows($errors)
);

            /*
             * 10. Hitung peserta yang berhasil.
             */
           $successfulRows = (int) (
    $import->successfulParticipants
    ?? max(0, $processedParticipants - $failedRows)
);

            /*
             * 11. Tentukan status import.
             */
            if ($failedRows > 0 && $successfulRows > 0) {
                $status = 'partial';
            } elseif ($failedRows > 0 && $successfulRows === 0) {
                $status = 'failed';
            } else {
                $status = 'success';
            }

            /*
             * 12. Simpan log hasil import.
             */
            Log::info('Import Excel SIRAPIK selesai', [
                'training_name' => $trainingName,
                'district' => $districtName,

                'created_participants' =>
                    $createdParticipants,

                'updated_participants' =>
                    $updatedParticipants,

                'processed_participants' =>
                    $processedParticipants,

                'successful_rows' =>
                    $successfulRows,

                'failed_rows' =>
                    $failedRows,

                'created_trainings' =>
                    $createdTrainings,

                'created_histories' =>
                    $createdHistories,

                'errors' => $errors,
            ]);

            /*
             * 13. Kirim hasil ke halaman import.
             */
            return redirect()
                ->route('imports.index')
                ->with('import_result', [
                    'status' => $status,

                    'created_participants' =>
                        $createdParticipants,

                    'updated_participants' =>
                        $updatedParticipants,

                    'processed_participants' =>
                        $processedParticipants,

                    'successful_rows' =>
                        $successfulRows,

                    'failed_rows' =>
                        $failedRows,

                    'created_trainings' =>
                        $createdTrainings,

                    'created_histories' =>
                        $createdHistories,

                    'errors' =>
                        $errors,

                    'training_name' =>
                        $trainingName,

                    'file_name' =>
                        $request->file('file')
                            ->getClientOriginalName(),
                ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;

        } catch (Throwable $e) {
            /*
             * Kegagalan proses utama atau metadata.
             * Bukan kegagalan satu peserta.
             */
            Log::error('Import Excel SIRAPIK gagal', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('imports.index')
                ->withInput()
                ->with('import_result', [
                    'status' => 'failed',

                    'created_participants' => 0,
                    'updated_participants' => 0,
                    'processed_participants' => 0,
                    'successful_rows' => 0,

                    /*
                     * Error file/metadata bukan baris peserta.
                     */
                    'failed_rows' => 0,

                    'created_trainings' => 0,
                    'created_histories' => 0,

                    'errors' => [
                        [
                            'row' => '-',
                            'name' => '-',
                            'column' => 'File / Metadata Pelatihan',
                            'message' => $e->getMessage(),
                            'value' => '',
                        ],
                    ],

                    'training_name' => null,

                    'file_name' => $request->file('file')
                        ? $request->file('file')->getClientOriginalName()
                        : null,
                ]);
        } finally {
            /*
             * Lepaskan memori PhpSpreadsheet.
             */
            if ($spreadsheet !== null) {
                $spreadsheet->disconnectWorksheets();

                unset($spreadsheet);
            }
        }
    }

    /**
     * Menyeragamkan format error dari TrainingImport.
     *
     * Format hasil:
     * [
     *     'row' => 8,
     *     'name' => 'Nama peserta',
     *     'column' => 'NIK',
     *     'message' => 'NIK wajib diisi.',
     *     'value' => '',
     * ]
     */
    private function normalizeImportErrors(array $errors): array
    {
        $normalized = [];

        foreach ($errors as $error) {
            /*
             * Format error lama berupa string.
             */
            if (is_string($error)) {
                $normalized[] = [
                    'row' => '-',
                    'name' => '-',
                    'column' => 'Data peserta',
                    'message' => $error,
                    'value' => '',
                ];

                continue;
            }

            /*
             * Jika error berupa object, ubah menjadi array.
             */
            if (is_object($error)) {
                $error = method_exists($error, 'toArray')
                    ? $error->toArray()
                    : (array) $error;
            }

            if (!is_array($error)) {
                continue;
            }

            $normalized[] = [
                'row' => $error['row']
                    ?? $error['excel_row']
                    ?? '-',

                'name' => $error['name']
                    ?? $error['participant_name']
                    ?? '-',

                'column' => $error['column']
                    ?? $error['attribute']
                    ?? 'Data peserta',

                'message' => $error['message']
                    ?? $error['error']
                    ?? 'Data tidak dapat diproses.',

                'value' => $error['value']
                    ?? '',
            ];
        }

        return $normalized;
    }

    /**
     * Menghitung jumlah baris peserta yang gagal.
     *
     * Setiap nomor baris hanya dihitung satu kali.
     * Error file atau metadata tidak dihitung.
     */
    private function countFailedParticipantRows(
        array $errors
    ): int {
        $failedRows = [];

        foreach ($errors as $error) {
            $row = $error['row'] ?? '-';

            /*
             * Hanya nomor baris Excel numerik yang dihitung.
             * Data peserta dimulai dari baris 8.
             */
            if (
                !is_numeric($row) ||
                (int) $row < 8
            ) {
                continue;
            }

            $failedRows[] = (int) $row;
        }

        return count(
            array_unique($failedRows)
        );
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

        /*
         * Cari label metadata pada baris.
         */
        foreach ($cells as $col => $value) {
            if ($value === '') {
                continue;
            }

            $normalizedValue = $this->normalizeText(
                $value
            );

            foreach ($normalizedLabels as $index => $label) {
                if (
                    $normalizedValue === $label ||
                    str_starts_with(
                        $normalizedValue,
                        $label . ' '
                    )
                ) {
                    $originalLabel = $labels[$index];

                    /*
                     * Format satu sel:
                     * Nama Pelatihan: Pelatihan Laravel
                     */
                    $pattern = '/^' .
                        preg_quote($originalLabel, '/') .
                        '\s*[:\-]\s*(.+)$/iu';

                    if (
                        preg_match(
                            $pattern,
                            $value,
                            $matches
                        )
                    ) {
                        $inlineValue = trim($matches[1]);

                        if ($inlineValue !== '') {
                            return $inlineValue;
                        }
                    }

                    /*
                     * Format dua sel:
                     * Sel pertama = label
                     * Sel berikutnya = nilai
                     */
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

        /*
         * Fallback: ambil nilai pertama yang bukan label.
         */
        foreach ($cells as $value) {
            if ($value === '') {
                continue;
            }

            $normalizedValue = $this->normalizeText(
                $value
            );

            $isLabel = false;

            foreach ($normalizedLabels as $label) {
                if (
                    $normalizedValue === $label ||
                    str_starts_with(
                        $normalizedValue,
                        $label . ' '
                    )
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

        return preg_replace(
            '/\s+/u',
            ' ',
            $text
        ) ?? $text;
    }

    /**
     * Membaca rentang tanggal pelatihan.
     *
     * Contoh:
     * 18 MEI 2026 - 23 MEI 2026
     */
    private function parseDateRange(string $text): array
    {
        $text = trim($text);

        if ($text === '') {
            throw new \RuntimeException(
                'Tanggal pelaksanaan tidak ditemukan pada baris 5 Excel.'
            );
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
            '/\s+/u',
            ' ',
            $normalized
        ) ?? $normalized;

        /*
         * Pisahkan tanggal mulai dan tanggal selesai.
         */
        $parts = preg_split(
            '/\s*[-–—]\s*/u',
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