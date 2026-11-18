<?php

namespace App\Imports;

use App\Models\District;
use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use App\Support\DistrictResolver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Throwable;

/**
 * Import peserta pelatihan.
 *
 * WAJIB   : Nama, NIK, Jenis Kelamin, Instansi, Kabupaten/Kota, Email
 * OPSIONAL: NIP, Pangkat, Profesi, Nomor Telepon
 *           (boleh kosong, berisi "-", atau kolomnya tidak ada)
 *
 * NIK adalah identitas utama peserta. NIP BUKAN pengganti NIK:
 *   - header "NIK" selalu dipetakan ke field nik (kolom database nik),
 *   - header "NIP" selalu dipetakan ke field nip (kolom database nip),
 *   - keduanya tidak pernah saling menggantikan.
 *
 * Kabupaten/Kota dibaca dari setiap baris Excel dan dicocokkan dengan
 * Master Data wilayah (tabel districts).
 *
 * Alur kerja:
 *   Tahap 1 (validasi)   : seluruh baris dibaca dan divalidasi, tanpa menulis ke database.
 *   Tahap 2 (penyimpanan): hanya jika $dryRun = false, baris valid disimpan.
 *
 * Seluruh sel dibaca sebagai TEKS (StringValueBinder) agar NIK 16 digit pada
 * file CSV tidak berubah menjadi notasi ilmiah.
 */
class TrainingImport extends StringValueBinder implements ToCollection, WithCustomValueBinder
{
    /** Jumlah baris teratas yang dipindai untuk mencari baris header. */
    private const HEADER_SCAN_LIMIT = 20;

    /** Panjang NIK. Ubah menjadi null jika panjang tidak ingin divalidasi. */
    private const NIK_LENGTH = 16;

    /** Field wajib => label untuk pesan error. */
    private const REQUIRED_FIELDS = [
        'name' => 'Nama',
        'nik' => 'NIK',
        'gender' => 'Jenis Kelamin',
        'institution' => 'Instansi',
        'district' => 'Kabupaten/Kota',
        'email' => 'Email',
    ];

    /** Field opsional (tidak pernah menggagalkan import). */
    private const OPTIONAL_FIELDS = [
        'nip' => 'NIP',
        'rank' => 'Pangkat',
        'profession' => 'Profesi',
        'phone' => 'Nomor Telepon',
    ];

    /**
     * Alias nama kolom (sudah dinormalisasi: huruf kecil, tanda baca jadi spasi).
     * Alias NIK dan NIP SENGAJA terpisah total dan dicocokkan persis.
     */
    private const FIELD_ALIASES = [
        'name' => ['nama lengkap', 'nama peserta', 'nama', 'name'],
        'nik' => ['nik', 'no nik', 'nomor nik', 'nik ktp', 'nomor induk kependudukan'],
        'nip' => ['nip', 'no nip', 'nomor nip', 'nomor induk pegawai'],
        'gender' => ['jenis kelamin', 'gender', 'jk', 'l p'],
        'rank' => ['pangkat golongan', 'pangkat gol', 'pangkat', 'golongan'],
        'profession' => ['profesi nakes', 'profesi', 'jenis tenaga kesehatan'],
        'email' => ['email', 'e mail', 'alamat email'],
        'phone' => ['nomor telepon', 'no telepon', 'telepon', 'no telp', 'telp', 'no hp', 'nomor hp'],
        'institution' => ['instansi unit kerja', 'nama instansi', 'instansi', 'unit kerja'],
        'district' => [
            'kabupaten kota',
            'kab kota',
            'kabupaten kota mitra mandiri',
            'kabupaten kota mitra',
            'kota kabupaten',
            'asal kabupaten kota',
            'wilayah',
        ],
    ];

    public int $createdParticipants = 0;
    public int $updatedParticipants = 0;
    public int $createdTrainings = 0;
    public int $createdHistories = 0;

    /** Total baris peserta tidak kosong yang diperiksa (berhasil + gagal). */
    public int $processedParticipants = 0;

    /** Jumlah baris peserta yang gagal / ditolak. */
    public int $failedParticipants = 0;

    /** Jumlah baris peserta yang berhasil disimpan. */
    public int $successfulParticipants = 0;

    /** True jika file tidak dapat diproses sama sekali (header/kolom tidak ada, dsb). */
    public bool $fatal = false;

    /**
     * Format error:
     * ['row' => 8, 'name' => '...', 'column' => '...', 'value' => '...', 'message' => '...']
     */
    public array $errors = [];

    /**
     * Hanya terisi pada mode pratinjau ($dryRun = true).
     * Tiap elemen: row, name, identity (NIK), nik, nip, district_raw, district,
     * status (ok|error), action (new|update|null), message.
     */
    public array $previewRows = [];

    /**
     * Jumlah peserta per Kabupaten/Kota (nama sesuai Master Data).
     *
     * @var array<string, int>
     */
    public array $districtSummary = [];

    /** Teks Kabupaten/Kota dari Excel yang tidak dikenali => jumlah baris. */
    public array $unrecognizedDistricts = [];

    /** Jumlah baris yang kolom Kabupaten/Kota-nya kosong. */
    public int $emptyDistrictRows = 0;

    /** Jumlah baris valid yang akan menjadi peserta baru / diperbarui (pratinjau). */
    public int $previewNew = 0;
    public int $previewUpdate = 0;

    private ?DistrictResolver $resolver = null;

    private bool $sheetHandled = false;

    /** Cache hasil Schema::hasColumn agar tidak query berulang per baris. */
    private static array $columnCache = [];

    public function __construct(
        public string $batchNumber,
        public string $fundingSource,
        public string $trainingName,
        public string $facilitation = '',
        public string $organizer = '',
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?int $trainingCategoryId = null,
        public ?int $jpl = null,
        public bool $dryRun = false,
    ) {
    }

    /* =====================================================
       TITIK MASUK
    ===================================================== */

    public function collection(Collection $rows): void
    {
        // Hanya sheet pertama (sama seperti getActiveSheet pada controller).
        if ($this->sheetHandled) {
            return;
        }
        $this->sheetHandled = true;

        if (!$this->hasColumn('participants', 'district')) {
            $this->fatalError(
                0,
                'Struktur database',
                'Kolom "district" belum ada pada tabel participants. Jalankan "php artisan migrate" terlebih dahulu.'
            );

            return;
        }

        if (!$this->hasColumn('participants', 'nik')) {
            $this->fatalError(
                0,
                'Struktur database',
                'Kolom "nik" belum ada pada tabel participants. Tambahkan kolom NIK melalui migration terlebih dahulu.'
            );

            return;
        }

        $list = $rows->values();

        $headerIndex = $this->locateHeaderIndex($list);

        if ($headerIndex === null) {
            $this->fatalError(
                0,
                'Baris header',
                'Baris header peserta tidak ditemukan pada ' . self::HEADER_SCAN_LIMIT .
                ' baris pertama. Pastikan ada kolom "Nama Lengkap" dan template yang digunakan benar.'
            );

            return;
        }

        $excelHeaderRow = $headerIndex + 1;
        $headers = $this->normalizeHeaders($list[$headerIndex]);

        // Semua kolom wajib harus ada pada header.
        $missingColumns = [];

        foreach (self::REQUIRED_FIELDS as $field => $label) {
            if ($this->findColumn($headers, self::FIELD_ALIASES[$field]) === null) {
                $missingColumns[] = $label;
            }
        }

        if ($missingColumns) {
            $this->fatalError(
                $excelHeaderRow,
                implode(', ', $missingColumns),
                'Kolom wajib tidak ditemukan pada baris header (baris ' . $excelHeaderRow . '): ' .
                implode(', ', $missingColumns) . '. ' .
                (in_array('NIK', $missingColumns, true)
                    ? 'Kolom NIP tidak dapat menggantikan kolom NIK. '
                    : '') .
                'Tambahkan kolom tersebut pada file Excel, lalu unggah kembali.'
            );

            return;
        }

        $this->resolver = new DistrictResolver(
            District::query()->where('is_active', true)->get()
        );

        /*
         * TAHAP 1: validasi semua baris (tanpa menulis ke database).
         */
        $entries = $this->validateRows($list, $headerIndex, $headers);

        if ($this->dryRun) {
            return;
        }

        if (count($entries) === 0) {
            // Tidak ada baris valid: jangan membuat data pelatihan kosong.
            return;
        }

        /*
         * Jika seluruh peserta valid berasal dari satu Kabupaten/Kota,
         * nilai itu juga dicatat pada pelatihan. Jika beragam, dikosongkan.
         */
        $distinctDistricts = array_values(array_unique(array_map(
            fn (array $entry): string => trim((string) $entry['district']->name),
            $entries
        )));

        try {
            $training = $this->findOrCreateTraining(
                count($distinctDistricts) === 1 ? $distinctDistricts[0] : null
            );
        } catch (Throwable $e) {
            $this->addError(
                0,
                '-',
                'Data pelatihan',
                $this->trainingName,
                'Gagal menyimpan data pelatihan: ' . $this->explainError($e)
            );

            $this->fatal = true;

            return;
        }

        /*
         * TAHAP 2: simpan baris valid. Satu transaksi per peserta.
         */
        $this->saveEntries($entries, $training, $headers);
    }

    /* =====================================================
       TAHAP 1: VALIDASI
    ===================================================== */

    /**
     * @return array<int, array<string, mixed>> Daftar baris valid.
     */
    private function validateRows(Collection $list, int $headerIndex, array $headers): array
    {
        $valid = [];
        $seenNik = [];

        for ($i = $headerIndex + 1; $i < $list->count(); $i++) {
            $row = $list[$i];
            $excelRow = $i + 1;

            if (!$row instanceof Collection) {
                continue;
            }

            $data = $this->mapRow($row, $headers);

            // Baris yang seluruh kolomnya kosong / "-" dilewati.
            if ($this->isEmptyRow($data)) {
                continue;
            }

            // Baris gagal pun tetap dihitung sebagai "diproses".
            $this->processedParticipants++;

            $name = (string) ($data['name'] ?? '');
            $nik = (string) ($data['nik'] ?? '');
            $nip = (string) ($data['nip'] ?? '');
            $email = (string) ($data['email'] ?? '');
            $rawDistrict = (string) ($data['district'] ?? '');

            /** @var array<int, array{column: string, value: string, message: string}> $rowErrors */
            $rowErrors = [];

            $fail = function (string $column, string $value, string $message) use (&$rowErrors): void {
                $rowErrors[] = [
                    'column' => $column,
                    'value' => $value,
                    'message' => $message,
                ];
            };

            // ---- Field wajib: semua dicek, semua kolom bermasalah dilaporkan ----
            foreach (self::REQUIRED_FIELDS as $field => $label) {
                if (trim((string) ($data[$field] ?? '')) !== '') {
                    continue;
                }

                if ($field === 'district') {
                    $this->emptyDistrictRows++;
                }

                $fail(
                    $this->getColumnLabel($headers, $field),
                    '',
                    $field === 'nik'
                        ? 'NIK wajib diisi. Kolom NIP tidak dapat digunakan sebagai pengganti NIK.'
                        : "Kolom {$label} wajib diisi. Lengkapi data pada baris ini."
                );
            }

            // ---- Format NIK ----
            if ($nik !== '') {
                if (preg_match('/e\s*\+/i', $nik)) {
                    $fail(
                        $this->getColumnLabel($headers, 'nik'),
                        $nik,
                        'NIK terbaca dalam notasi ilmiah. Ubah format kolom NIK pada Excel menjadi Teks, lalu isi ulang NIK.'
                    );
                } elseif (!ctype_digit($nik)) {
                    $fail(
                        $this->getColumnLabel($headers, 'nik'),
                        $nik,
                        'NIK hanya boleh berisi angka.'
                    );
                } elseif (self::NIK_LENGTH !== null && strlen($nik) !== self::NIK_LENGTH) {
                    $fail(
                        $this->getColumnLabel($headers, 'nik'),
                        $nik,
                        'NIK harus ' . self::NIK_LENGTH . ' digit (terbaca ' . strlen($nik) . ' digit).'
                    );
                } elseif (isset($seenNik[$nik])) {
                    $fail(
                        $this->getColumnLabel($headers, 'nik'),
                        $nik,
                        'NIK ini sudah muncul pada baris ' . $seenNik[$nik] .
                        ' di file yang sama. Hapus salah satu baris agar peserta tidak terdaftar ganda.'
                    );
                }
            }

            // ---- Format email (wajib, jadi harus valid) ----
            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $fail(
                    $this->getColumnLabel($headers, 'email'),
                    $email,
                    'Format email tidak valid. Contoh format yang benar: nama@email.com.'
                );
            }

            // ---- Kabupaten/Kota ----
            $district = null;

            if ($rawDistrict !== '') {
                $resolved = $this->resolver->resolve($rawDistrict);
                $district = $resolved['district'];

                if ($district === null) {
                    $districtLabel = $this->getColumnLabel($headers, 'district');
                    $cleanRaw = DistrictResolver::clean($rawDistrict);

                    if ($resolved['reason'] === DistrictResolver::REASON_EMPTY) {
                        $this->emptyDistrictRows++;

                        $fail(
                            $districtLabel,
                            '',
                            'Kabupaten/Kota pada baris ini masih kosong. Isi kolom Kabupaten/Kota, lalu unggah kembali.'
                        );
                    } elseif ($resolved['reason'] === DistrictResolver::REASON_AMBIGUOUS) {
                        $this->unrecognizedDistricts[$cleanRaw] = ($this->unrecognizedDistricts[$cleanRaw] ?? 0) + 1;

                        $fail(
                            $districtLabel,
                            $cleanRaw,
                            'Kabupaten/Kota cocok dengan lebih dari satu wilayah pada Master Data. Tulis nama lengkap, misalnya "Kabupaten ..." atau "Kota ...".'
                        );
                    } else {
                        $this->unrecognizedDistricts[$cleanRaw] = ($this->unrecognizedDistricts[$cleanRaw] ?? 0) + 1;

                        $fail(
                            $districtLabel,
                            $cleanRaw,
                            'Kabupaten/Kota tidak dikenali di Master Data ARTA. Periksa penulisannya, atau tambahkan wilayah tersebut di Master Data terlebih dahulu.'
                        );
                    }
                }
            }

            // ---- Pencocokan dengan peserta yang sudah ada (hanya jika NIK layak diperiksa) ----
            $existing = null;
            $nikIsUsable = $nik !== '' && !array_filter(
                $rowErrors,
                fn (array $e): bool => str_starts_with($e['column'], $this->getColumnLabel($headers, 'nik'))
            );

            if ($nikIsUsable) {
                $identityCheck = $this->checkIdentity($nik, $nip);

                if ($identityCheck['error'] !== null) {
                    $fail(
                        $identityCheck['column'] === 'NIP'
                            ? $this->getColumnLabel($headers, 'nip')
                            : $this->getColumnLabel($headers, 'nik'),
                        $identityCheck['column'] === 'NIP' ? $nip : $nik,
                        $identityCheck['error']
                    );
                } else {
                    $existing = $identityCheck['participant'];
                }
            }

            // ---- Ada error: baris ditolak, SEMUA masalahnya dilaporkan ----
            if ($rowErrors) {
                $this->failedParticipants++;

                foreach ($rowErrors as $error) {
                    $this->addError(
                        $excelRow,
                        $name !== '' ? $name : '-',
                        $error['column'],
                        $error['value'],
                        $error['message']
                    );
                }

                if ($this->dryRun) {
                    $this->previewRows[] = [
                        'row' => $excelRow,
                        'name' => $name !== '' ? $name : '-',
                        'identity' => $nik !== '' ? $nik : '-',
                        'nik' => $nik !== '' ? $nik : '-',
                        'nip' => $nip !== '' ? $nip : '-',
                        'district_raw' => $rawDistrict !== '' ? DistrictResolver::clean($rawDistrict) : '',
                        'district' => null,
                        'status' => 'error',
                        'action' => null,
                        'message' => implode(' ', array_map(
                            fn (array $e): string => '[' . $e['column'] . '] ' . $e['message'],
                            $rowErrors
                        )),
                    ];
                }

                continue;
            }

            // ---- Baris valid ----
            $seenNik[$nik] = $excelRow;

            $valid[] = [
                'row' => $excelRow,
                'name' => $name,
                'nik' => $nik,
                'nip' => $nip,
                'data' => $data,
                'district' => $district,
            ];

            if ($this->dryRun) {
                $districtName = trim((string) $district->name);

                $this->districtSummary[$districtName] = ($this->districtSummary[$districtName] ?? 0) + 1;

                $existing !== null ? $this->previewUpdate++ : $this->previewNew++;

                $this->previewRows[] = [
                    'row' => $excelRow,
                    'name' => $name,
                    'identity' => $nik,
                    'nik' => $nik,
                    'nip' => $nip !== '' ? $nip : '-',
                    'district_raw' => DistrictResolver::clean($rawDistrict),
                    'district' => $districtName,
                    'status' => 'ok',
                    'action' => $existing !== null ? 'update' : 'new',
                    'message' => '',
                ];
            }
        }

        return $valid;
    }

    /**
     * Mencocokkan peserta di database. NIK adalah kunci utama.
     *
     * - Peserta dicari berdasarkan NIK.
     * - NIP (jika ada) hanya dipakai sebagai pencarian cadangan untuk peserta
     *   lama yang belum punya NIK, dan untuk mendeteksi konflik data.
     *
     * @return array{error: ?string, column: ?string, participant: ?Participant}
     */
    private function checkIdentity(string $nik, string $nip): array
    {
        $byNik = Participant::query()->where('nik', $nik)->first();
        $byNip = null;

        if ($nip !== '' && $this->hasColumn('participants', 'nip')) {
            $byNip = Participant::query()->where('nip', $nip)->first();
        }

        // NIK dan NIP menunjuk dua peserta yang berbeda.
        if ($byNik && $byNip && (int) $byNik->id !== (int) $byNip->id) {
            return [
                'error' => 'NIK dan NIP pada baris ini terdaftar pada dua peserta yang berbeda. Periksa kembali kedua nomor tersebut dan pastikan milik orang yang sama.',
                'column' => 'NIK',
                'participant' => null,
            ];
        }

        // NIP sudah dipakai peserta lain yang memiliki NIK berbeda.
        if (!$byNik && $byNip) {
            $existingNik = trim((string) ($byNip->nik ?? ''));

            if ($existingNik !== '' && $existingNik !== $nik) {
                return [
                    'error' => 'NIP ini sudah terdaftar pada peserta dengan NIK ' . $existingNik .
                        '. Periksa kembali NIK dan NIP pada baris ini.',
                    'column' => 'NIP',
                    'participant' => null,
                ];
            }
        }

        return [
            'error' => null,
            'column' => null,
            'participant' => $byNik ?? $byNip,
        ];
    }

    /* =====================================================
       TAHAP 2: PENYIMPANAN
    ===================================================== */

    private function saveEntries(array $entries, Training $training, array $headers): void
    {
        foreach ($entries as $entry) {
            $data = $entry['data'];
            $name = $entry['name'];
            $nik = $entry['nik'];
            $nip = $entry['nip'];
            $districtName = trim((string) $entry['district']->name);

            try {
                /*
                 * Satu transaksi per peserta: jika satu peserta gagal,
                 * hanya peserta itu yang dibatalkan.
                 */
                $result = DB::transaction(function () use (
                    $data, $name, $nik, $nip, $training, $districtName
                ): array {
                    $participant = $this->findParticipant($nik, $nip);
                    $isNew = !$participant;

                    if (!$participant) {
                        $participant = new Participant();
                    }

                    // Field wajib: selalu ditulis.
                    $participant->name = $name;
                    $this->setParticipantColumn($participant, 'nik', $nik);
                    $this->setParticipantColumn($participant, 'gender', $data['gender'] ?? null);
                    $this->setParticipantColumn($participant, 'institution', $data['institution'] ?? null);
                    $this->setParticipantColumn($participant, 'email', $data['email'] ?? null);
                    $this->setParticipantColumn($participant, 'district', $districtName, true);

                    // Field opsional: hanya ditulis jika ada nilainya, sehingga
                    // "-" / kosong tidak menimpa data lama peserta.
                    $this->setParticipantColumn($participant, 'nip', $nip);
                    $this->setParticipantColumn($participant, 'rank', $data['rank'] ?? null);
                    $this->setParticipantColumn($participant, 'profession', $data['profession'] ?? null);
                    $this->setParticipantColumn($participant, 'phone', $data['phone'] ?? null);

                    $participant->save();

                    $history = TrainingHistory::firstOrCreate(
                        [
                            'participant_id' => $participant->id,
                            'training_id' => $training->id,
                        ],
                        [
                            'status' => 'selesai',
                        ]
                    );

                    // Catat wilayah peserta pada riwayat pelatihan ini (jika kolomnya ada)
                    // agar laporan per kegiatan tetap akurat walau peserta pindah wilayah.
                    if (
                        $this->hasColumn('training_histories', 'district') &&
                        $history->district !== $districtName
                    ) {
                        $history->district = $districtName;
                        $history->save();
                    }

                    return [
                        'is_new' => $isNew,
                        'history_created' => $history->wasRecentlyCreated,
                    ];
                });

                // Penghitung bertambah hanya setelah transaksi sukses.
                if ($result['is_new']) {
                    $this->createdParticipants++;
                } else {
                    $this->updatedParticipants++;
                }

                if ($result['history_created']) {
                    $this->createdHistories++;
                }

                $this->successfulParticipants++;

                $this->districtSummary[$districtName] = ($this->districtSummary[$districtName] ?? 0) + 1;
            } catch (Throwable $e) {
                [$column, $value] = $this->identifyErrorField($e, $data, $headers);

                $this->addParticipantError(
                    $entry['row'],
                    $name,
                    $column,
                    $value,
                    $this->explainError($e)
                );
            }
        }
    }

    /** NIK dicari lebih dulu. NIP hanya cadangan untuk peserta lama tanpa NIK. */
    private function findParticipant(string $nik, string $nip): ?Participant
    {
        $participant = Participant::query()->where('nik', $nik)->first();

        if (!$participant && $nip !== '' && $this->hasColumn('participants', 'nip')) {
            $participant = Participant::query()->where('nip', $nip)->first();
        }

        return $participant;
    }

    /* =====================================================
       ERROR
    ===================================================== */

    /** Error umum (header / metadata). Tidak menambah failedParticipants. */
    private function addError(int $row, string $name, string $column, mixed $value, string $message): void
    {
        $this->errors[] = [
            'row' => $row,
            'name' => $name,
            'column' => $column,
            'value' => $value === null ? '' : (string) $value,
            'message' => $message,
        ];
    }

    /** Error yang menghentikan seluruh proses. */
    private function fatalError(int $row, string $column, string $message): void
    {
        $this->addError($row, '-', $column, '', $message);
        $this->fatal = true;
    }

    /** Error peserta: dicatat dan dihitung sebagai baris gagal. */
    private function addParticipantError(int $row, string $name, string $column, mixed $value, string $message): void
    {
        $this->addError($row, $name, $column, $value, $message);
        $this->failedParticipants++;
    }

    /* =====================================================
       PEMBACAAN EXCEL
    ===================================================== */

    /**
     * Mencari baris header: baris pertama (dalam batas pindai) yang memuat kolom nama.
     */
    private function locateHeaderIndex(Collection $list): ?int
    {
        $limit = min(self::HEADER_SCAN_LIMIT, $list->count());

        for ($i = 0; $i < $limit; $i++) {
            $row = $list[$i];

            if (!$row instanceof Collection) {
                continue;
            }

            $headers = $this->normalizeHeaders($row);

            if ($this->findColumn($headers, self::FIELD_ALIASES['name']) !== null) {
                return $i;
            }
        }

        return null;
    }

    private function isEmptyRow(array $data): bool
    {
        // Kabupaten/Kota sengaja tidak ikut diperiksa: baris yang hanya berisi
        // wilayah (misalnya terisi otomatis di template) dianggap kosong.
        foreach (['name', 'nik', 'nip', 'gender', 'rank', 'profession', 'email', 'phone', 'institution'] as $field) {
            if (trim((string) ($data[$field] ?? '')) !== '') {
                return false;
            }
        }

        return true;
    }

    private function normalizeHeaders(Collection $row): array
    {
        $headers = [];

        foreach ($row as $index => $value) {
            $headers[$index] = $this->normalizeText($value);
        }

        return $headers;
    }

    private function normalizeText(mixed $value): string
    {
        return Str::of(DistrictResolver::clean($value))
            ->lower()
            ->replace(['/', '.', '-', '_'], ' ')
            ->squish()
            ->toString();
    }

    /** Pencocokan header PERSIS terhadap alias. Tidak ada fallback antar field. */
    private function findColumn(array $headers, array $aliases): ?int
    {
        $normalizedAliases = array_map(fn ($alias) => $this->normalizeText($alias), $aliases);

        foreach ($headers as $index => $header) {
            if (in_array($header, $normalizedAliases, true)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Membaca satu baris Excel berdasarkan nama header (bukan nomor kolom).
     * Nilai kosong atau hanya berisi tanda "-" dianggap kosong ('').
     */
    private function mapRow(Collection $row, array $headers): array
    {
        $result = [];

        foreach (self::FIELD_ALIASES as $field => $names) {
            $column = $this->findColumn($headers, $names);

            $value = $column !== null
                ? $this->cellToString($row->get($column))
                : '';

            // Nomor identitas: buang apostrof awal dan spasi (contoh "3171 0000 ...").
            if (in_array($field, ['nik', 'nip'], true)) {
                $value = ltrim($value, "'`");
                $value = preg_replace('/\s+/u', '', $value) ?? $value;
            }

            $result[$field] = $this->emptyIfDash($value);
        }

        return $result;
    }

    /** "-", "–", "—" (satu atau lebih) dianggap tidak ada nilai. */
    private function emptyIfDash(string $value): string
    {
        $value = trim($value);

        if ($value !== '' && preg_match('/^[\-–—]+$/u', $value)) {
            return '';
        }

        return $value;
    }

    /**
     * Mengubah nilai sel menjadi teks. Angka besar (NIK yang tersimpan
     * sebagai angka di Excel) tidak boleh berubah menjadi notasi ilmiah.
     */
    private function cellToString(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value) && floor($value) === $value && abs($value) < 1e20) {
            return sprintf('%.0f', $value);
        }

        return DistrictResolver::clean($value);
    }

    private function getColumnLabel(array $headers, string $field): string
    {
        $columnIndex = $this->findColumn($headers, self::FIELD_ALIASES[$field] ?? []);

        if ($columnIndex === null) {
            return strtoupper($field);
        }

        $headerName = trim((string) ($headers[$columnIndex] ?? ''));
        $excelColumn = Coordinate::stringFromColumnIndex($columnIndex + 1);

        $label = $headerName !== '' ? Str::title($headerName) : strtoupper($field);

        return "{$label} (Kolom {$excelColumn})";
    }

    /* =====================================================
       PENJELASAN ERROR DATABASE
    ===================================================== */

    private function identifyErrorField(Throwable $e, array $data, array $headers): array
    {
        $message = strtolower($e->getMessage());

        // Pelanggaran unique: periksa lebih dulu agar tidak salah tebak kolom.
        if (str_contains($message, 'duplicate') || str_contains($message, 'unique')) {
            if (str_contains($message, 'nip')) {
                return [$this->getColumnLabel($headers, 'nip'), (string) ($data['nip'] ?? '')];
            }

            return [$this->getColumnLabel($headers, 'nik'), (string) ($data['nik'] ?? '')];
        }

        $columnMap = [
            'nik' => ['participants.nik', 'nik'],
            'nip' => ['participants.nip', 'nip'],
            'name' => ['participants.name'],
            'gender' => ['gender', 'jenis kelamin'],
            'rank' => ['rank', 'pangkat', 'golongan'],
            'profession' => ['profession', 'profesi'],
            'email' => ['email'],
            'phone' => ['phone', 'telepon', 'no_hp'],
            'institution' => ['institution', 'instansi', 'unit_kerja'],
            'district' => ['district', 'wilayah'],
        ];

        foreach ($columnMap as $field => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    return [
                        $this->getColumnLabel($headers, $field),
                        (string) ($data[$field] ?? ''),
                    ];
                }
            }
        }

        if (str_contains($message, 'foreign key') || str_contains($message, 'constraint')) {
            return ['Relasi data peserta / pelatihan', ''];
        }

        return ['Data peserta (periksa format atau struktur kolom)', ''];
    }

    private function explainError(Throwable $e): string
    {
        $message = $e->getMessage();
        $lower = strtolower($message);

        if (
            str_contains($lower, 'duplicate entry') ||
            str_contains($lower, 'unique constraint') ||
            str_contains($lower, 'unique violation')
        ) {
            return 'Data memiliki nomor identitas yang sudah digunakan dan ditolak oleh aturan unik database. Periksa NIK atau NIP peserta dan pastikan tidak digunakan oleh peserta lain.';
        }

        if (str_contains($lower, 'foreign key constraint') || str_contains($lower, 'foreign key')) {
            return 'Data memiliki relasi yang tidak sesuai. Periksa kembali data peserta, pelatihan, dan relasi pada database.';
        }

        if (
            str_contains($lower, 'data too long') ||
            str_contains($lower, 'value too long') ||
            str_contains($lower, 'string data right truncation')
        ) {
            return 'Nilai terlalu panjang untuk kolom database. Persingkat isi data pada kolom tersebut.';
        }

        if (
            str_contains($lower, 'cannot be null') ||
            str_contains($lower, 'not null constraint') ||
            str_contains($lower, 'doesn\'t have a default value')
        ) {
            return 'Ada kolom pada database yang belum memiliki nilai. Jika kolom tersebut opsional (misalnya NIP), ubah menjadi nullable pada database.';
        }

        if (
            str_contains($lower, 'incorrect date') ||
            str_contains($lower, 'invalid datetime') ||
            str_contains($lower, 'date/time field value out of range')
        ) {
            return 'Format tanggal tidak sesuai. Periksa format tanggal pada file Excel.';
        }

        if (
            str_contains($lower, 'incorrect integer') ||
            str_contains($lower, 'invalid input syntax for type integer') ||
            str_contains($lower, 'out of range')
        ) {
            return 'Nilai harus berupa angka atau berada di luar batas yang diizinkan. Periksa kembali isi kolom tersebut.';
        }

        if (str_contains($lower, 'no such column') || str_contains($lower, 'unknown column')) {
            return 'Kolom yang dibutuhkan tidak ditemukan pada database. Periksa nama kolom dan struktur tabel (sudah menjalankan migration?).';
        }

        if (str_contains($lower, 'connection refused') || str_contains($lower, 'could not connect')) {
            return 'Koneksi ke database bermasalah. Periksa koneksi database lalu coba kembali.';
        }

        return 'Data gagal disimpan: ' . $message;
    }

    /* =====================================================
       DATA PELATIHAN
    ===================================================== */

    /**
     * Membuat atau menemukan data pelatihan.
     *
     * Kabupaten/Kota TIDAK dimasukkan ke dalam kode pelatihan, karena satu
     * file boleh berisi peserta dari banyak wilayah dan tetap menjadi
     * SATU kegiatan pelatihan.
     */
    private function findOrCreateTraining(?string $singleDistrict): Training
    {
        $batchNumber = trim($this->batchNumber);

        $codeSource = implode('|', [
            trim($this->trainingName),
            $this->startDate ?? '',
            $this->endDate ?? '',
            trim($this->organizer),
            $batchNumber,
            trim($this->fundingSource),
            (string) ($this->trainingCategoryId ?? ''),
            (string) ($this->jpl ?? ''),
        ]);

        $code = 'IMP-' . strtoupper(substr(hash('sha256', $codeSource), 0, 16));

        $training = Training::firstOrNew(['code' => $code]);

        $isNew = !$training->exists;

        $training->name = trim($this->trainingName);

        $this->setTrainingColumn($training, 'batch_number', $batchNumber);
        $this->setTrainingColumn($training, 'funding_source', trim($this->fundingSource));
        $this->setTrainingColumn($training, 'district', $singleDistrict);
        $this->setTrainingColumn($training, 'organizer', trim($this->organizer));
        $this->setTrainingColumn($training, 'training_category_id', $this->trainingCategoryId);
        $this->setTrainingColumn($training, 'jpl', $this->jpl);

        if ($this->startDate) {
            $this->setTrainingColumn($training, 'start_date', $this->startDate);
        }

        if ($this->endDate) {
            $this->setTrainingColumn($training, 'end_date', $this->endDate);
        }

        if ($this->facilitation !== '') {
            $this->setTrainingColumn($training, 'description', $this->facilitation);
        }

        if ($this->startDate && $this->endDate) {
            $start = new \DateTimeImmutable($this->startDate);
            $end = new \DateTimeImmutable($this->endDate);

            $this->setTrainingColumn($training, 'duration_days', $start->diff($end)->days + 1);
        }

        $training->save();

        if ($isNew) {
            $this->createdTrainings++;
        }

        return $training;
    }

    /* =====================================================
       HELPER KOLOM DATABASE
    ===================================================== */

    private function hasColumn(string $table, string $column): bool
    {
        $key = $table . '.' . $column;

        if (!array_key_exists($key, self::$columnCache)) {
            self::$columnCache[$key] = Schema::hasColumn($table, $column);
        }

        return self::$columnCache[$key];
    }

    private function setParticipantColumn(
        Participant $participant,
        string $column,
        mixed $value,
        bool $allowEmpty = false
    ): void {
        if (!$this->hasColumn($participant->getTable(), $column)) {
            return;
        }

        if (!$allowEmpty && ($value === null || $value === '')) {
            return;
        }

        $participant->{$column} = $value;
    }

    private function setTrainingColumn(Training $training, string $column, mixed $value): void
    {
        if ($this->hasColumn($training->getTable(), $column)) {
            $training->{$column} = $value;
        }
    }
}