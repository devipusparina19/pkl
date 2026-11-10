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

class TrainingImport implements ToCollection
{
    public int $createdParticipants = 0;
    public int $updatedParticipants = 0;
    public int $createdTrainings = 0;
    public int $createdHistories = 0;

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
    ) {}

    /**
     * Memproses data peserta dari Excel.
     *
     * Baris 7  = header.
     * Baris 8+ = data peserta.
     */
    public function collection(Collection $rows): void
    {
        $district = trim($this->district);

        if ($district === '') {
            $this->errors[] =
                'Kabupaten/Kota/Mitra/Mandiri wajib dipilih sebelum import.';
            return;
        }

        // Header peserta berada pada baris Excel ke-7.
        $headerRow = $rows->get(6);

        if (!$headerRow instanceof Collection) {
            $this->errors[] =
                'Header peserta tidak ditemukan pada baris 7.';
            return;
        }

        $headers = $this->normalizeHeaders($headerRow);

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
            $this->errors[] =
                'Kolom NAMA tidak ditemukan pada baris 7 Excel.';
            return;
        }

        if ($nipColumn === null && $nikColumn === null) {
            $this->errors[] =
                'Kolom NIP atau NIK tidak ditemukan pada Excel.';
            return;
        }

        try {
            $training = $this->findOrCreateTraining();
        } catch (\Throwable $e) {
            $this->errors[] =
                'Gagal menyimpan data pelatihan: ' . $e->getMessage();
            return;
        }

        // Data peserta dimulai dari baris Excel ke-8.
        foreach ($rows->slice(7)->values() as $index => $row) {
            $excelRow = $index + 8;

            if (!$row instanceof Collection) {
                continue;
            }

            try {
                $data = $this->mapRow($row, $headers);

                $name = trim((string) ($data['name'] ?? ''));
                $nip = trim((string) ($data['nip'] ?? ''));
                $nik = trim((string) ($data['nik'] ?? ''));

                // Lewati baris yang benar-benar kosong.
                if ($name === '' && $nip === '' && $nik === '') {
                    continue;
                }

                if ($name === '') {
                    $this->errors[] =
                        "Baris {$excelRow}: nama peserta kosong.";
                    continue;
                }

                if ($nip === '' && $nik === '') {
                    $this->errors[] =
                        "Baris {$excelRow}: NIP atau NIK wajib diisi.";
                    continue;
                }

                $result = DB::transaction(function () use (
                    $data,
                    $name,
                    $nip,
                    $nik,
                    $training,
                    $district
                ): array {
                    $participant = null;

                    // Cari peserta berdasarkan NIP terlebih dahulu.
                    if (
                        $nip !== '' &&
                        Schema::hasColumn('participants', 'nip')
                    ) {
                        $participant = Participant::query()
                            ->where('nip', $nip)
                            ->first();
                    }

                    // Jika belum ditemukan, cari berdasarkan NIK.
                    if (
                        !$participant &&
                        $nik !== '' &&
                        Schema::hasColumn('participants', 'nik')
                    ) {
                        $participant = Participant::query()
                            ->where('nik', $nik)
                            ->first();
                    }

                    $isNew = !$participant;

                    if (!$participant) {
                        $participant = new Participant();
                    }

                    // Identitas peserta.
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

                    // Data pribadi dan pekerjaan.
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
                     * SINKRONISASI WILAYAH PESERTA
                     *
                     * Wilayah yang dipilih pada form import
                     * disimpan ke kolom participants.district.
                     *
                     * Jika peserta sudah ada, wilayah lamanya
                     * diperbarui dengan pilihan wilayah terbaru.
                     */
                    $this->setParticipantColumn(
                        $participant,
                        'district',
                        $district,
                        true
                    );

                    $participant->save();

                    /*
                     * Hubungkan peserta dengan pelatihan.
                     * Riwayat tidak dibuat berulang jika sudah ada.
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

                // Hitung hasil hanya setelah transaksi berhasil.
                if ($result['is_new']) {
                    $this->createdParticipants++;
                } else {
                    $this->updatedParticipants++;
                }

                if ($result['history_created']) {
                    $this->createdHistories++;
                }
            } catch (\Throwable $e) {
                $this->errors[] =
                    "Baris {$excelRow}: " . $e->getMessage();
            }
        }
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
     * Normalisasi teks agar pencocokan lebih konsisten.
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
     * Mencari indeks kolom berdasarkan beberapa nama alternatif.
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
            if (in_array($header, $normalizedAliases, true)) {
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
            $column = $this->findColumn($headers, $names);

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

        // Wilayah penyelenggaraan pelatihan.
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
            $start = new \DateTimeImmutable($this->startDate);
            $end = new \DateTimeImmutable($this->endDate);

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
     *
     * $allowEmpty = true digunakan untuk kolom yang memang
     * harus diperbarui walaupun nilainya kosong.
     */
    private function setParticipantColumn(
        Participant $participant,
        string $column,
        mixed $value,
        bool $allowEmpty = false
    ): void {
        if (!Schema::hasColumn($participant->getTable(), $column)) {
            return;
        }

        if (!$allowEmpty && ($value === null || $value === '')) {
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
        if (Schema::hasColumn($training->getTable(), $column)) {
            $training->{$column} = $value;
        }
    }
}