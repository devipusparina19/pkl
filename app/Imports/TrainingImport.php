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
    ) {}

    public function collection(Collection $rows): void
    {
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

        $cooperationColumn = $this->findColumn($headers, [
            'kerja sama',
            'kerjasama',
            'kerja sama dengan',
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

        if ($cooperationColumn === null) {
            $this->errors[] =
                'Kolom KERJA SAMA tidak ditemukan pada baris 7 Excel.';
            return;
        }

        /*
         * Ambil nilai KERJA SAMA pertama yang terisi.
         * Nilai tersebut berlaku untuk data pelatihan ini.
         */
        $cooperation = '';

        foreach ($rows->slice(7) as $row) {
            if (!$row instanceof Collection) {
                continue;
            }

            $value = trim(
                (string) ($row->get($cooperationColumn) ?? '')
            );

            if ($value !== '') {
                $cooperation = $value;
                break;
            }
        }

        if ($cooperation === '') {
            $this->errors[] =
                'Kolom KERJA SAMA ditemukan, tetapi nilainya kosong.';
            return;
        }

        // Kolom KERJA SAMA Excel disimpan sebagai organizer.
        $this->organizer = $cooperation;

        try {
            $training = $this->findOrCreateTraining();
        } catch (\Throwable $e) {
            $this->errors[] =
                'Gagal menyimpan data pelatihan: ' . $e->getMessage();
            return;
        }

        /*
         * Proses peserta dimulai dari baris Excel ke-8.
         */
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

                DB::transaction(function () use (
                    $data,
                    $name,
                    $nip,
                    $nik,
                    $training
                ): void {
                    $participant = null;

                    if ($nip !== '') {
                        $participant = Participant::where('nip', $nip)
                            ->first();
                    }

                    if (
                        !$participant &&
                        $nik !== '' &&
                        Schema::hasColumn('participants', 'nik')
                    ) {
                        $participant = Participant::where('nik', $nik)
                            ->first();
                    }

                    $isNew = !$participant;

                    if (!$participant) {
                        $participant = new Participant();
                    }

                    $participant->name = $name;

                    if ($nip !== '') {
                        $participant->nip = $nip;
                    }

                    $this->setIfColumnExists(
                        $participant,
                        'nik',
                        $nik
                    );

                    $this->setIfColumnExists(
                        $participant,
                        'gender',
                        $data['gender'] ?? null
                    );

                    $this->setIfColumnExists(
                        $participant,
                        'rank',
                        $data['rank'] ?? null
                    );

                    $this->setIfColumnExists(
                        $participant,
                        'profession',
                        $data['profession'] ?? null
                    );

                    $this->setIfColumnExists(
                        $participant,
                        'email',
                        $data['email'] ?? null
                    );

                    $this->setIfColumnExists(
                        $participant,
                        'phone',
                        $data['phone'] ?? null
                    );

                    $this->setIfColumnExists(
                        $participant,
                        'institution',
                        $data['institution'] ?? null
                    );

                    $participant->save();

                    if ($isNew) {
                        $this->createdParticipants++;
                    } else {
                        $this->updatedParticipants++;
                    }

                    // Hindari riwayat peserta-pelatihan duplikat.
                    $history = TrainingHistory::firstOrCreate([
                        'participant_id' => $participant->id,
                        'training_id' => $training->id,
                    ]);

                    if ($history->wasRecentlyCreated) {
                        $this->createdHistories++;
                    }
                });
            } catch (\Throwable $e) {
                $this->errors[] =
                    "Baris {$excelRow}: " . $e->getMessage();
            }
        }
    }

    private function normalizeHeaders(Collection $row): array
    {
        $headers = [];

        foreach ($row as $index => $value) {
            $headers[$index] = Str::of((string) $value)
                ->lower()
                ->replace(['/', '.', '-', '_'], ' ')
                ->squish()
                ->toString();
        }

        return $headers;
    }

    private function findColumn(
        array $headers,
        array $aliases
    ): ?int {
        foreach ($headers as $index => $header) {
            if (in_array($header, $aliases, true)) {
                return $index;
            }
        }

        return null;
    }

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

    private function findOrCreateTraining(): Training
    {
        $codeSource = implode('|', [
            trim($this->trainingName),
            $this->startDate ?? '',
            $this->endDate ?? '',
            trim($this->organizer),
            trim($this->batchNumber),
            trim($this->fundingSource),
            trim($this->district),
        ]);

        $code = 'IMP-' . strtoupper(
            substr(hash('sha256', $codeSource), 0, 16)
        );

        $training = Training::firstOrNew([
            'code' => $code,
        ]);

        $isNew = !$training->exists;

        $training->name = $this->trainingName;

        $this->setTrainingColumn(
            $training,
            'batch_number',
            $this->batchNumber
        );

        $this->setTrainingColumn(
            $training,
            'angkatan',
            $this->batchNumber
        );

        $this->setTrainingColumn(
            $training,
            'funding_source',
            $this->fundingSource
        );

        $this->setTrainingColumn(
            $training,
            'sumber_dana',
            $this->fundingSource
        );

        $this->setTrainingColumn(
            $training,
            'district',
            $this->district
        );

        // PENTING: kerja sama Excel disimpan di kolom organizer.
        $this->setTrainingColumn(
            $training,
            'organizer',
            $this->organizer
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

            // Nama kolom yang sesuai dengan migration.
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

    private function setTrainingColumn(
        Training $training,
        string $column,
        mixed $value
    ): void {
        if (Schema::hasColumn($training->getTable(), $column)) {
            $training->{$column} = $value;
        }
    }

    private function setIfColumnExists(
        Participant $participant,
        string $column,
        mixed $value
    ): void {
        if (
            $value !== null &&
            $value !== '' &&
            Schema::hasColumn($participant->getTable(), $column)
        ) {
            $participant->{$column} = $value;
        }
    }
}