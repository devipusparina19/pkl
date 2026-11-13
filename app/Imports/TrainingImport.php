<?php

namespace App\Imports;

use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Throwable;

class TrainingImport implements ToCollection
{
    public int $createdParticipants = 0;
    public int $updatedParticipants = 0;
    public int $createdTrainings = 0;
    public int $createdHistories = 0;

    /**
     * Total baris peserta yang tidak kosong dan diperiksa.
     * Termasuk baris yang berhasil maupun gagal.
     */
    public int $processedParticipants = 0;

    /**
     * Jumlah baris peserta yang gagal.
     */
    public int $failedParticipants = 0;

    /**
     * Jumlah baris peserta yang berhasil disimpan.
     */
    public int $successfulParticipants = 0;

    /**
     * Format error:
     *
     * [
     *     'row' => 8,
     *     'name' => 'Nama peserta',
     *     'column' => 'Email (Kolom G)',
     *     'value' => 'email-salah',
     *     'message' => 'Format email tidak valid.',
     * ]
     */
    public array $errors = [];

    public function __construct(
        public string $batchNumber,
        public string $fundingSource,
        public string $trainingName,
        public string $facilitation = '',
        public string $organizer = '',
        public ?string $startDate = null,
        public ?string $endDate = null,
        public string $district = '',
        public ?int $trainingCategoryId = null,
        public ?int $jpl = null,
    ) {
    }

    /**
     * Baris 7  = header peserta.
     * Baris 8+ = data peserta.
     */
    public function collection(Collection $rows): void
    {
        $district = trim($this->district);

        if ($district === '') {
            $this->addError(
                0,
                '-',
                'Kabupaten/Kota/Mitra/Mandiri',
                '',
                'Wilayah wajib dipilih sebelum import.'
            );

            return;
        }

        /*
         * Header peserta berada pada baris Excel ke-7.
         */
        $headerRow = $rows->get(6);

        if (!$headerRow instanceof Collection) {
            $this->addError(
                7,
                '-',
                'Baris header',
                '',
                'Header peserta tidak ditemukan pada baris 7 Excel. Pastikan template yang digunakan benar.'
            );

            return;
        }

        $headers = $this->normalizeHeaders($headerRow);

        /*
         * Pastikan kolom wajib tersedia.
         */
        $nameColumn = $this->findColumn($headers, [
            'nama lengkap',
            'nama peserta',
            'nama',
            'name',
        ]);

        $nipColumn = $this->findColumn($headers, [
            'nip',
            'nomor induk pegawai',
        ]);

        $nikColumn = $this->findColumn($headers, [
            'nik',
            'nomor induk kependudukan',
        ]);

        if ($nameColumn === null) {
            $this->addError(
                7,
                '-',
                'Nama peserta',
                '',
                'Kolom nama tidak ditemukan pada baris header. Gunakan nama kolom "Nama Lengkap", "Nama Peserta", atau "Nama".'
            );

            return;
        }

        if ($nipColumn === null && $nikColumn === null) {
            $this->addError(
                7,
                '-',
                'NIP / NIK',
                '',
                'Kolom NIP maupun NIK tidak ditemukan. Tambahkan salah satu kolom tersebut pada template Excel.'
            );

            return;
        }

        /*
         * Buat atau temukan data pelatihan satu kali.
         */
        try {
            $training = $this->findOrCreateTraining();
        } catch (Throwable $e) {
            $this->addError(
                0,
                '-',
                'Data pelatihan',
                $this->trainingName,
                'Gagal menyimpan data pelatihan: ' .
                    $this->explainError($e)
            );

            return;
        }

        /*
         * Data peserta dimulai dari baris Excel ke-8.
         */
        foreach ($rows->slice(7)->values() as $index => $row) {
            $excelRow = $index + 8;

            if (!$row instanceof Collection) {
                continue;
            }

            $data = $this->mapRow($row, $headers);

            $name = trim((string) ($data['name'] ?? ''));
            $nip = trim((string) ($data['nip'] ?? ''));
            $nik = trim((string) ($data['nik'] ?? ''));

            /*
             * Lewati baris yang benar-benar kosong.
             */
            if ($this->isEmptyRow($data)) {
                continue;
            }

            /*
             * Hitung sebelum validasi agar baris gagal
             * tetap masuk dalam jumlah peserta diproses.
             */
            $this->processedParticipants++;

            /*
             * Validasi nama.
             */
            if ($name === '') {
                $this->addParticipantError(
                    $excelRow,
                    '-',
                    $this->getColumnLabel($headers, 'name'),
                    '',
                    'Nama peserta wajib diisi. Lengkapi kolom nama pada baris ini.'
                );

                continue;
            }

            /*
             * Validasi identitas.
             */
            if ($nip === '' && $nik === '') {
                $this->addParticipantError(
                    $excelRow,
                    $name,
                    'NIP / NIK',
                    '',
                    'NIP atau NIK wajib diisi. Lengkapi minimal salah satu nomor identitas peserta.'
                );

                continue;
            }

            /*
             * Validasi email jika diisi.
             */
            $email = trim((string) ($data['email'] ?? ''));

            if (
                $email !== '' &&
                !filter_var($email, FILTER_VALIDATE_EMAIL)
            ) {
                $this->addParticipantError(
                    $excelRow,
                    $name,
                    $this->getColumnLabel($headers, 'email'),
                    $email,
                    'Format email tidak valid. Contoh format yang benar: nama@email.com.'
                );

                continue;
            }

            /*
             * Validasi NIP dan NIK.
             */
            $identityError = $this->validateIdentity(
                $nip,
                $nik,
                $name,
                $excelRow
            );

            if ($identityError) {
                continue;
            }

            try {
                /*
                 * Setiap peserta menggunakan transaksi sendiri.
                 *
                 * Jika satu peserta gagal, transaksi peserta
                 * tersebut dibatalkan, tetapi peserta lainnya
                 * tetap dapat diproses.
                 */
                $result = DB::transaction(function () use (
                    $data,
                    $name,
                    $nip,
                    $nik,
                    $training,
                    $district
                ): array {
                    $participant = $this->findParticipant(
                        $nip,
                        $nik
                    );

                    $isNew = !$participant;

                    if (!$participant) {
                        $participant = new Participant();
                    }

                    /*
                     * Data utama peserta.
                     */
                    $participant->name = $name;

                    $this->setParticipantColumn(
                        $participant,
                        'nip',
                        $nip
                    );

                    $this->setParticipantColumn(
                        $participant,
                        'nik',
                        $nik
                    );

                    /*
                     * Data pribadi dan pekerjaan.
                     */
                    $this->setParticipantColumn(
                        $participant,
                        'gender',
                        $data['gender'] ?? null
                    );

                    $this->setParticipantColumn(
                        $participant,
                        'rank',
                        $data['rank'] ?? null
                    );

                    $this->setParticipantColumn(
                        $participant,
                        'profession',
                        $data['profession'] ?? null
                    );

                    $this->setParticipantColumn(
                        $participant,
                        'email',
                        $data['email'] ?? null
                    );

                    $this->setParticipantColumn(
                        $participant,
                        'phone',
                        $data['phone'] ?? null
                    );

                    $this->setParticipantColumn(
                        $participant,
                        'institution',
                        $data['institution'] ?? null
                    );

                    /*
                     * Sinkronisasi wilayah peserta.
                     */
                    $this->setParticipantColumn(
                        $participant,
                        'district',
                        $district,
                        true
                    );

                    $participant->save();

                    /*
                     * Buat riwayat pelatihan jika belum ada.
                     */
                    $history = TrainingHistory::firstOrCreate(
                        [
                            'participant_id' => $participant->id,
                            'training_id' => $training->id,
                        ],
                        [
                            'status' => 'selesai',
                        ]
                    );

                    return [
                        'is_new' => $isNew,
                        'history_created' => $history->wasRecentlyCreated,
                    ];
                });

                /*
                 * Penghitung hanya bertambah setelah transaksi
                 * peserta berhasil diselesaikan.
                 */
                if ($result['is_new']) {
                    $this->createdParticipants++;
                } else {
                    $this->updatedParticipants++;
                }

                if ($result['history_created']) {
                    $this->createdHistories++;
                }

                $this->successfulParticipants++;

            } catch (Throwable $e) {
                /*
                 * Error satu peserta dicatat.
                 * Proses kemudian lanjut ke baris berikutnya.
                 */
                [$column, $value] = $this->identifyErrorField(
                    $e,
                    $data,
                    $headers
                );

                $this->addParticipantError(
                    $excelRow,
                    $name,
                    $column,
                    $value,
                    $this->explainError($e)
                );
            }
        }
    }

    /**
     * Memeriksa apakah NIP atau NIK sudah digunakan
     * oleh peserta lain yang berbeda.
     */
    private function validateIdentity(
        string $nip,
        string $nik,
        string $name,
        int $excelRow
    ): bool {
        $participantByNip = null;
        $participantByNik = null;

        if (
            $nip !== '' &&
            Schema::hasColumn('participants', 'nip')
        ) {
            $participantByNip = Participant::query()
                ->where('nip', $nip)
                ->first();
        }

        if (
            $nik !== '' &&
            Schema::hasColumn('participants', 'nik')
        ) {
            $participantByNik = Participant::query()
                ->where('nik', $nik)
                ->first();
        }

        /*
         * NIP dan NIK mengarah ke dua peserta berbeda.
         */
        if (
            $participantByNip &&
            $participantByNik &&
            $participantByNip->id !== $participantByNik->id
        ) {
            $this->addParticipantError(
                $excelRow,
                $name,
                'NIP / NIK',
                "NIP: {$nip}; NIK: {$nik}",
                'NIP dan NIK pada baris ini terdaftar pada dua peserta yang berbeda. Periksa kembali nomor identitas dan pastikan keduanya milik orang yang sama.'
            );

            return true;
        }

        return false;
    }

    /**
     * Mencari peserta berdasarkan NIP terlebih dahulu,
     * kemudian NIK.
     */
    private function findParticipant(
        string $nip,
        string $nik
    ): ?Participant {
        $participant = null;

        if (
            $nip !== '' &&
            Schema::hasColumn('participants', 'nip')
        ) {
            $participant = Participant::query()
                ->where('nip', $nip)
                ->first();
        }

        if (
            !$participant &&
            $nik !== '' &&
            Schema::hasColumn('participants', 'nik')
        ) {
            $participant = Participant::query()
                ->where('nik', $nik)
                ->first();
        }

        return $participant;
    }

    /**
     * Menambahkan error umum.
     *
     * Error header atau metadata tidak menambah
     * penghitung failedParticipants.
     */
    private function addError(
        int $row,
        string $name,
        string $column,
        mixed $value,
        string $message
    ): void {
        $this->errors[] = [
            'row' => $row,
            'name' => $name,
            'column' => $column,
            'value' => $value === null ? '' : (string) $value,
            'message' => $message,
        ];
    }

    /**
     * Menambahkan error peserta dan menghitung kegagalannya.
     */
    private function addParticipantError(
        int $row,
        string $name,
        string $column,
        mixed $value,
        string $message
    ): void {
        $this->addError(
            $row,
            $name,
            $column,
            $value,
            $message
        );

        $this->failedParticipants++;
    }

    /**
     * Memeriksa apakah seluruh kolom utama pada baris kosong.
     */
    private function isEmptyRow(array $data): bool
    {
        $fields = [
            'name',
            'nip',
            'nik',
            'gender',
            'rank',
            'profession',
            'email',
            'phone',
            'institution',
        ];

        foreach ($fields as $field) {
            if (
                trim((string) ($data[$field] ?? '')) !== ''
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Normalisasi nama kolom Excel.
     */
    private function normalizeHeaders(Collection $row): array
    {
        $headers = [];

        foreach ($row as $index => $value) {
            $headers[$index] = $this->normalizeText($value);
        }

        return $headers;
    }

    /**
     * Normalisasi teks agar pencocokan konsisten.
     */
    private function normalizeText(mixed $value): string
    {
        return Str::of((string) $value)
            ->lower()
            ->replace(['/', '.', '-', '_'], ' ')
            ->squish()
            ->toString();
    }

    /**
     * Mencari indeks kolom berdasarkan nama alternatif.
     */
    private function findColumn(
        array $headers,
        array $aliases
    ): ?int {
        $normalizedAliases = array_map(
            fn ($alias) => $this->normalizeText($alias),
            $aliases
        );

        foreach ($headers as $index => $header) {
            if (
                in_array(
                    $header,
                    $normalizedAliases,
                    true
                )
            ) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Membaca data satu baris Excel.
     */
    private function mapRow(
        Collection $row,
        array $headers
    ): array {
        $aliases = [
            'name' => [
                'nama lengkap',
                'nama peserta',
                'nama',
                'name',
            ],

            'nip' => [
                'nip',
                'nomor induk pegawai',
            ],

            'nik' => [
                'nik',
                'nomor induk kependudukan',
            ],

            'gender' => [
                'jenis kelamin',
                'gender',
                'jk',
            ],

            'rank' => [
                'pangkat golongan',
                'pangkat gol',
                'golongan',
            ],

            'profession' => [
                'profesi nakes',
                'profesi',
                'jenis tenaga kesehatan',
            ],

            'email' => [
                'email',
                'alamat email',
            ],

            'phone' => [
                'nomor telepon',
                'no telepon',
                'telepon',
                'no hp',
                'nomor hp',
            ],

            'institution' => [
                'instansi unit kerja',
                'instansi',
                'unit kerja',
            ],
        ];

        $result = [];

        foreach ($aliases as $field => $names) {
            $column = $this->findColumn(
                $headers,
                $names
            );

            $value = $column !== null
                ? $row->get($column)
                : null;

            $result[$field] = $value === null
                ? null
                : trim((string) $value);
        }

        return $result;
    }

    /**
     * Menghasilkan label kolom berdasarkan header Excel.
     */
    private function getColumnLabel(
        array $headers,
        string $field
    ): string {
        $aliases = $this->getFieldAliases();

        $columnIndex = $this->findColumn(
            $headers,
            $aliases[$field] ?? []
        );

        if ($columnIndex === null) {
            return strtoupper($field);
        }

        $headerName = trim(
            (string) ($headers[$columnIndex] ?? '')
        );

        $excelColumn = Coordinate::stringFromColumnIndex(
            $columnIndex + 1
        );

        $label = $headerName !== ''
            ? Str::title($headerName)
            : strtoupper($field);

        return "{$label} (Kolom {$excelColumn})";
    }

    /**
     * Daftar alias untuk setiap field peserta.
     */
    private function getFieldAliases(): array
    {
        return [
            'name' => [
                'nama lengkap',
                'nama peserta',
                'nama',
                'name',
            ],

            'nip' => [
                'nip',
                'nomor induk pegawai',
            ],

            'nik' => [
                'nik',
                'nomor induk kependudukan',
            ],

            'gender' => [
                'jenis kelamin',
                'gender',
                'jk',
            ],

            'rank' => [
                'pangkat golongan',
                'pangkat gol',
                'golongan',
            ],

            'profession' => [
                'profesi nakes',
                'profesi',
                'jenis tenaga kesehatan',
            ],

            'email' => [
                'email',
                'alamat email',
            ],

            'phone' => [
                'nomor telepon',
                'no telepon',
                'telepon',
                'no hp',
                'nomor hp',
            ],

            'institution' => [
                'instansi unit kerja',
                'instansi',
                'unit kerja',
            ],
        ];
    }

    /**
     * Mendeteksi field yang bermasalah berdasarkan pesan database.
     */
    private function identifyErrorField(
        Throwable $e,
        array $data,
        array $headers
    ): array {
        $message = strtolower($e->getMessage());

        $columnMap = [
            'name' => [
                'participants.name',
                'name',
            ],

            'nip' => [
                'participants.nip',
                'nip',
            ],

            'nik' => [
                'participants.nik',
                'nik',
            ],

            'gender' => [
                'gender',
                'jenis kelamin',
            ],

            'rank' => [
                'rank',
                'pangkat',
                'golongan',
            ],

            'profession' => [
                'profession',
                'profesi',
            ],

            'email' => [
                'email',
            ],

            'phone' => [
                'phone',
                'telepon',
                'no_hp',
            ],

            'institution' => [
                'institution',
                'instansi',
                'unit_kerja',
            ],

            'district' => [
                'district',
                'wilayah',
            ],
        ];

        foreach ($columnMap as $field => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    $column = $this->getColumnLabel(
                        $headers,
                        $field
                    );

                    $value = $data[$field] ?? '';

                    return [
                        $column,
                        (string) $value,
                    ];
                }
            }
        }

        if (
            str_contains($message, 'duplicate') ||
            str_contains($message, 'unique constraint')
        ) {
            return [
                'NIP / NIK',
                'NIP: ' . ($data['nip'] ?? '') .
                    '; NIK: ' . ($data['nik'] ?? ''),
            ];
        }

        if (
            str_contains($message, 'foreign key') ||
            str_contains($message, 'constraint')
        ) {
            return [
                'Relasi data peserta / pelatihan',
                '',
            ];
        }

        return [
            'Data peserta (periksa format atau struktur kolom)',
            '',
        ];
    }

    /**
     * Mengubah exception menjadi penjelasan yang mudah dipahami.
     */
    private function explainError(Throwable $e): string
    {
        $message = $e->getMessage();
        $lower = strtolower($message);

        if (
            str_contains($lower, 'duplicate entry') ||
            str_contains($lower, 'unique constraint') ||
            str_contains($lower, 'unique violation')
        ) {
            return 'Data memiliki nomor identitas yang sudah digunakan dan ditolak oleh aturan unik database. Periksa NIP atau NIK peserta dan pastikan tidak digunakan oleh peserta lain.';
        }

        if (
            str_contains($lower, 'foreign key constraint') ||
            str_contains($lower, 'foreign key')
        ) {
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
            return 'Ada kolom wajib pada database yang belum memiliki nilai. Lengkapi data yang diperlukan.';
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

        if (
            str_contains($lower, 'no such column') ||
            str_contains($lower, 'unknown column')
        ) {
            return 'Kolom yang dibutuhkan tidak ditemukan pada database. Periksa nama kolom dan struktur tabel.';
        }

        if (
            str_contains($lower, 'connection refused') ||
            str_contains($lower, 'could not connect')
        ) {
            return 'Koneksi ke database bermasalah. Periksa koneksi database lalu coba kembali.';
        }

        /*
         * Pesan asli disertakan untuk membantu penelusuran
         * masalah teknis yang belum dikenali.
         */
        return 'Data gagal disimpan: ' . $message;
    }

    /**
     * Membuat atau menemukan data pelatihan.
     */
    private function findOrCreateTraining(): Training
    {
        $batchNumber = trim($this->batchNumber);
        $district = trim($this->district);

        $codeSource = implode('|', [
            trim($this->trainingName),
            $this->startDate ?? '',
            $this->endDate ?? '',
            trim($this->organizer),
            $batchNumber,
            trim($this->fundingSource),
            $district,
            (string) ($this->trainingCategoryId ?? ''),
            (string) ($this->jpl ?? ''),
        ]);

        $code = 'IMP-' . strtoupper(
            substr(hash('sha256', $codeSource), 0, 16)
        );

        $training = Training::firstOrNew([
            'code' => $code,
        ]);

        $isNew = !$training->exists;

        $training->name = trim($this->trainingName);

        $this->setTrainingColumn(
            $training,
            'batch_number',
            $batchNumber
        );

        $this->setTrainingColumn(
            $training,
            'funding_source',
            trim($this->fundingSource)
        );

        $this->setTrainingColumn(
            $training,
            'district',
            $district
        );

        $this->setTrainingColumn(
            $training,
            'organizer',
            trim($this->organizer)
        );

        $this->setTrainingColumn(
            $training,
            'training_category_id',
            $this->trainingCategoryId
        );

        $this->setTrainingColumn(
            $training,
            'jpl',
            $this->jpl
        );

        if ($this->startDate) {
            $this->setTrainingColumn(
                $training,
                'start_date',
                $this->startDate
            );
        }

        if ($this->endDate) {
            $this->setTrainingColumn(
                $training,
                'end_date',
                $this->endDate
            );
        }

        if ($this->facilitation !== '') {
            $this->setTrainingColumn(
                $training,
                'description',
                $this->facilitation
            );
        }

        if ($this->startDate && $this->endDate) {
            $start = new \DateTimeImmutable(
                $this->startDate
            );

            $end = new \DateTimeImmutable(
                $this->endDate
            );

            $duration = $start->diff($end)->days + 1;

            $this->setTrainingColumn(
                $training,
                'duration_days',
                $duration
            );
        }

        $training->save();

        if ($isNew) {
            $this->createdTrainings++;
        }

        return $training;
    }

    /**
     * Mengisi kolom peserta jika kolom tersedia.
     */
    private function setParticipantColumn(
        Participant $participant,
        string $column,
        mixed $value,
        bool $allowEmpty = false
    ): void {
        if (
            !Schema::hasColumn(
                $participant->getTable(),
                $column
            )
        ) {
            return;
        }

        if (
            !$allowEmpty &&
            ($value === null || $value === '')
        ) {
            return;
        }

        $participant->{$column} = $value;
    }

    /**
     * Mengisi kolom pelatihan jika tersedia di database.
     */
    private function setTrainingColumn(
        Training $training,
        string $column,
        mixed $value
    ): void {
        if (
            Schema::hasColumn(
                $training->getTable(),
                $column
            )
        ) {
            $training->{$column} = $value;
        }
    }
}