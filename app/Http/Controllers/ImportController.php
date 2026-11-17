<?php

namespace App\Http\Controllers;

use App\Imports\TrainingImport;
use App\Models\FundingSource;
use App\Models\TrainingCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

/**
 * Import Excel ARTA.
 *
 * Alur:
 *   1. index()   : halaman form (tanpa dropdown Kabupaten/Kota).
 *   2. preview() : file disimpan sementara, dibaca, divalidasi, lalu ditampilkan
 *                  sebagai pratinjau. TIDAK ada data yang disimpan ke database.
 *   3. store()   : setelah pengguna mengonfirmasi, file sementara dibaca ulang
 *                  dan baris valid disimpan. Kabupaten/Kota diambil dari
 *                  setiap baris Excel.
 *   4. cancel()  : membuang file sementara.
 */
class ImportController extends Controller
{
    private const ALLOWED_EXTENSIONS = ['xlsx', 'xls', 'csv'];

    private const TMP_DIR = 'imports/tmp';

    private const TMP_DISK = 'local';

    /**
     * Menampilkan halaman import Excel ARTA.
     */
    public function index()
    {
        return view('imports.index', $this->formData());
    }

    /**
     * Langkah 1: membaca file dan menampilkan pratinjau.
     */
    public function preview(Request $request)
    {
        $validated = $request->validate(
            array_merge([
                'file' => [
                    'required',
                    'file',
                    'mimes:xlsx,xls,csv',
                    'max:10240',
                ],
            ], $this->commonRules()),
            $this->validationMessages()
        );

        $file = $request->file('file');

        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            $extension = 'xlsx';
        }

        $token = (string) Str::uuid();

        $relativePath = $file->storeAs(
            self::TMP_DIR,
            $token . '.' . $extension,
            self::TMP_DISK
        );

        if (!$relativePath) {
            return back()
                ->withInput($request->except('file'))
                ->withErrors(['file' => 'File tidak dapat disimpan sementara untuk dibaca. Coba unggah kembali.']);
        }

        try {
            $meta = $this->readMetadata(
                Storage::disk(self::TMP_DISK)->path($relativePath)
            );

            $import = $this->makeImport($meta, $validated, true);

            Excel::import($import, $relativePath, self::TMP_DISK);
        } catch (Throwable $e) {
            $this->deleteTempFile($relativePath);

            Log::warning('Pratinjau import Excel ARTA gagal', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput($request->except('file'))
                ->withErrors(['file' => $e->getMessage()]);
        }

        // File tidak bisa diproses sama sekali (header / kolom Kabupaten/Kota tidak ada).
        if ($import->fatal) {
            $this->deleteTempFile($relativePath);

            $messages = collect($this->normalizeImportErrors($import->errors))
                ->pluck('message')
                ->filter()
                ->values()
                ->all();

            return back()
                ->withInput($request->except('file'))
                ->withErrors(['file' => $messages ?: ['File Excel tidak dapat dibaca.']]);
        }

        // Token hanya sah untuk sesi pengguna ini.
        session()->put('import_tmp.' . $token, [
            'path' => $relativePath,
            'name' => $file->getClientOriginalName(),
        ]);

        $errors = $this->normalizeImportErrors($import->errors);

        $districtSummary = $import->districtSummary;
        ksort($districtSummary, SORT_NATURAL | SORT_FLAG_CASE);

        $validRows = collect($import->previewRows)->where('status', 'ok')->count();

        $request->flashExcept('file');

        return view('imports.index', array_merge($this->formData(), [
            'preview' => [
                'token' => $token,
                'file_name' => $file->getClientOriginalName(),
                'training_name' => $meta['training_name'],
                'organizer' => $meta['cooperation'],
                'start_date' => $meta['start_date'],
                'end_date' => $meta['end_date'],

                'total_rows' => $import->processedParticipants,
                'valid_rows' => $validRows,
                'invalid_rows' => $import->failedParticipants,
                'new_rows' => $import->previewNew,
                'update_rows' => $import->previewUpdate,
                'empty_district_rows' => $import->emptyDistrictRows,
                'unrecognized_districts' => $import->unrecognizedDistricts,

                'district_summary' => $districtSummary,
                'rows' => $import->previewRows,
                'errors' => $errors,

                // Dikirim ulang sebagai hidden input pada form konfirmasi.
                'params' => [
                    'angkatan' => $validated['angkatan'] ?? '',
                    'sumber_dana' => $validated['sumber_dana'],
                    'training_category_id' => $validated['training_category_id'] ?? '',
                    'jpl' => $validated['jpl'] ?? '',
                ],
            ],
        ]));
    }

    /**
     * Langkah 2: menyimpan data setelah pengguna mengonfirmasi pratinjau.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            array_merge([
                'token' => ['required', 'string', 'regex:/^[0-9a-fA-F\-]{36}$/'],
            ], $this->commonRules()),
            array_merge($this->validationMessages(), [
                'token.required' => 'Sesi pratinjau tidak ditemukan. Unggah ulang file Excel.',
                'token.regex' => 'Sesi pratinjau tidak valid. Unggah ulang file Excel.',
            ])
        );

        $token = $validated['token'];
        $tmp = session('import_tmp.' . $token);

        if (
            !is_array($tmp) ||
            empty($tmp['path']) ||
            !Storage::disk(self::TMP_DISK)->exists($tmp['path'])
        ) {
            session()->forget('import_tmp.' . $token);

            return redirect()
                ->route('imports.index')
                ->withErrors(['file' => 'Sesi pratinjau sudah berakhir. Unggah ulang file Excel untuk melanjutkan.']);
        }

        $relativePath = $tmp['path'];
        $originalName = $tmp['name'] ?? basename($relativePath);

        try {
            $meta = $this->readMetadata(
                Storage::disk(self::TMP_DISK)->path($relativePath)
            );

            $import = $this->makeImport($meta, $validated, false);

            Excel::import($import, $relativePath, self::TMP_DISK);

            $errors = $this->normalizeImportErrors($import->errors ?? []);

            $createdParticipants = (int) ($import->createdParticipants ?? 0);
            $updatedParticipants = (int) ($import->updatedParticipants ?? 0);
            $createdTrainings = (int) ($import->createdTrainings ?? 0);
            $createdHistories = (int) ($import->createdHistories ?? 0);

            $processedParticipants = (int) $import->processedParticipants;
            $failedRows = (int) $import->failedParticipants;
            $successfulRows = (int) $import->successfulParticipants;

            if ($import->fatal && $successfulRows === 0) {
                $status = 'failed';
            } elseif ($failedRows > 0 && $successfulRows > 0) {
                $status = 'partial';
            } elseif ($failedRows > 0 || $import->fatal) {
                $status = 'failed';
            } else {
                $status = 'success';
            }

            $districtSummary = $import->districtSummary;
            ksort($districtSummary, SORT_NATURAL | SORT_FLAG_CASE);

            Log::info('Import Excel ARTA selesai', [
                'training_name' => $meta['training_name'],
                'created_participants' => $createdParticipants,
                'updated_participants' => $updatedParticipants,
                'processed_participants' => $processedParticipants,
                'successful_rows' => $successfulRows,
                'failed_rows' => $failedRows,
                'created_trainings' => $createdTrainings,
                'created_histories' => $createdHistories,
                'district_summary' => $districtSummary,
                'errors' => $errors,
            ]);

            return redirect()
                ->route('imports.index')
                ->with('import_result', [
                    'status' => $status,
                    'created_participants' => $createdParticipants,
                    'updated_participants' => $updatedParticipants,
                    'processed_participants' => $processedParticipants,
                    'successful_rows' => $successfulRows,
                    'failed_rows' => $failedRows,
                    'created_trainings' => $createdTrainings,
                    'created_histories' => $createdHistories,
                    'district_summary' => $districtSummary,
                    'errors' => $errors,
                    'training_name' => $meta['training_name'],
                    'file_name' => $originalName,
                ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            // Kegagalan proses utama atau metadata, bukan kegagalan satu peserta.
            Log::error('Import Excel ARTA gagal', [
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
                    'failed_rows' => 0,
                    'created_trainings' => 0,
                    'created_histories' => 0,
                    'district_summary' => [],

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
                    'file_name' => $originalName,
                ]);
        } finally {
            // File sementara selalu dibuang, berhasil maupun gagal.
            $this->deleteTempFile($relativePath);
            session()->forget('import_tmp.' . $token);
        }
    }

    /**
     * Membatalkan pratinjau dan membuang file sementara.
     */
    public function cancel(Request $request)
    {
        $token = (string) $request->input('token', '');

        if (preg_match('/^[0-9a-fA-F\-]{36}$/', $token)) {
            $tmp = session('import_tmp.' . $token);

            if (is_array($tmp) && !empty($tmp['path'])) {
                $this->deleteTempFile($tmp['path']);
            }

            session()->forget('import_tmp.' . $token);
        }

        return redirect()->route('imports.index');
    }

    /* =====================================================
       FORM & VALIDASI
    ===================================================== */

    private function formData(): array
    {
        $fundingSources = FundingSource::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $categories = TrainingCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return compact('fundingSources', 'categories');
    }

    /**
     * Aturan untuk isian form selain file.
     * Kabupaten/Kota tidak ada di sini: sumbernya adalah isi file Excel.
     */
    private function commonRules(): array
    {
        return [
            'angkatan' => ['nullable', 'string', 'max:100'],

            'sumber_dana' => [
                'required',
                'string',
                'max:255',
                Rule::exists('funding_sources', 'name')->where('is_active', true),
            ],

            'training_category_id' => [
                'nullable',
                'integer',
                Rule::exists('training_categories', 'id')->where('is_active', true),
            ],

            'jpl' => ['nullable', 'integer', 'min:0'],
        ];
    }

    private function validationMessages(): array
    {
        return [
            'file.required' => 'Silakan pilih file Excel.',
            'file.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
            'file.max' => 'Ukuran file maksimal 10 MB.',

            'sumber_dana.required' => 'Sumber dana wajib dipilih.',
            'sumber_dana.exists' => 'Sumber dana tidak valid atau tidak aktif.',

            'training_category_id.exists' => 'Kategori pelatihan tidak valid atau tidak aktif.',

            'jpl.integer' => 'JPL harus berupa angka.',
            'jpl.min' => 'JPL tidak boleh kurang dari 0.',
        ];
    }

    /* =====================================================
       IMPORT
    ===================================================== */

    private function makeImport(array $meta, array $validated, bool $dryRun): TrainingImport
    {
        return new TrainingImport(
            batchNumber: trim((string) ($validated['angkatan'] ?? '')),
            fundingSource: $validated['sumber_dana'],
            trainingName: $meta['training_name'],
            facilitation: '',
            organizer: $meta['cooperation'],
            startDate: $meta['start_date'],
            endDate: $meta['end_date'],
            trainingCategoryId: !empty($validated['training_category_id'])
                ? (int) $validated['training_category_id']
                : null,
            jpl: isset($validated['jpl']) && $validated['jpl'] !== ''
                ? (int) $validated['jpl']
                : null,
            dryRun: $dryRun,
        );
    }

    /**
     * Membaca metadata pelatihan (nama, kerja sama, tanggal) dari bagian atas Excel.
     *
     * @return array{training_name: string, cooperation: string, start_date: string, end_date: string}
     */
    private function readMetadata(string $absolutePath): array
    {
        $spreadsheet = null;

        try {
            $spreadsheet = IOFactory::load($absolutePath);
            $sheet = $spreadsheet->getActiveSheet();

            // BARIS 2: nama pelatihan.
            $trainingName = $this->getRowValue($sheet, 2, [
                'nama pelatihan',
                'judul pelatihan',
                'nama kegiatan',
            ]);

            if ($trainingName === '') {
                throw new \RuntimeException(
                    'Nama pelatihan tidak ditemukan pada baris 2 Excel. ' .
                    'Periksa kolom nama pelatihan.'
                );
            }

            // BARIS 3: kerja sama.
            $cooperation = $this->getRowValue($sheet, 3, [
                'kerja sama',
                'kerjasama',
                'mitra kerja sama',
                'mitra kerjasama',
            ]);

            // BARIS 5: rentang tanggal pelatihan.
            $dateText = $this->getRowValue($sheet, 5, [
                'tanggal pelaksanaan',
                'tanggal pelatihan',
                'waktu pelaksanaan',
                'tanggal',
            ]);

            [$startDate, $endDate] = $this->parseDateRange($dateText);

            return [
                'training_name' => $trainingName,
                'cooperation' => $cooperation,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ];
        } finally {
            if ($spreadsheet !== null) {
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
            }
        }
    }

    private function deleteTempFile(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }

        try {
            Storage::disk(self::TMP_DISK)->delete($relativePath);
        } catch (Throwable $e) {
            Log::warning('File sementara import tidak dapat dihapus', [
                'path' => $relativePath,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Menyeragamkan format error dari TrainingImport.
     */
    private function normalizeImportErrors(array $errors): array
    {
        $normalized = [];

        foreach ($errors as $error) {
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

            if (is_object($error)) {
                $error = method_exists($error, 'toArray')
                    ? $error->toArray()
                    : (array) $error;
            }

            if (!is_array($error)) {
                continue;
            }

            $normalized[] = [
                'row' => $error['row'] ?? $error['excel_row'] ?? '-',
                'name' => $error['name'] ?? $error['participant_name'] ?? '-',
                'column' => $error['column'] ?? $error['attribute'] ?? 'Data peserta',
                'message' => $error['message'] ?? $error['error'] ?? 'Data tidak dapat diproses.',
                'value' => $error['value'] ?? '',
            ];
        }

        return $normalized;
    }

    /* =====================================================
       PEMBACAAN METADATA EXCEL
    ===================================================== */

    /**
     * Membaca nilai dari baris Excel tertentu.
     */
    private function getRowValue($sheet, int $rowNumber, array $labels): string
    {
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

        // Cari label metadata pada baris.
        foreach ($cells as $col => $value) {
            if ($value === '') {
                continue;
            }

            $normalizedValue = $this->normalizeText($value);

            foreach ($normalizedLabels as $index => $label) {
                if (
                    $normalizedValue === $label ||
                    str_starts_with($normalizedValue, $label . ' ')
                ) {
                    $originalLabel = $labels[$index];

                    // Format satu sel: "Nama Pelatihan: Pelatihan Laravel"
                    $pattern = '/^' .
                        preg_quote($originalLabel, '/') .
                        '\s*[:\-]\s*(.+)$/iu';

                    if (preg_match($pattern, $value, $matches)) {
                        $inlineValue = trim($matches[1]);

                        if ($inlineValue !== '') {
                            return $inlineValue;
                        }
                    }

                    // Format dua sel: sel pertama label, berikutnya nilai.
                    for ($nextCol = $col + 1; $nextCol <= $lastColumn; $nextCol++) {
                        if ($cells[$nextCol] !== '') {
                            return $cells[$nextCol];
                        }
                    }
                }
            }
        }

        // Fallback: ambil nilai pertama yang bukan label.
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

        $text = str_replace([':', '/', '-', '_'], ' ', $text);

        return preg_replace('/\s+/u', ' ', $text) ?? $text;
    }

    /**
     * Membaca rentang tanggal pelatihan.
     *
     * Contoh: 18 MEI 2026 - 23 MEI 2026
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
            $normalized = str_replace($indo, $english, $normalized);
        }

        $normalized = preg_replace('/\s+/u', ' ', $normalized) ?? $normalized;

        // Pisahkan tanggal mulai dan tanggal selesai.
        $parts = preg_split('/\s*[-–—]\s*/u', $normalized);

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