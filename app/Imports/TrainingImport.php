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
        $headerRow = $rows->get(6);

        if (!$headerRow instanceof Collection) {
            $this->errors[] =
                'Header peserta tidak ditemukan pada baris 7.';
            return;
        }

        $headers = $this->normalizeHeaders($headerRow);

        $nameColumn = $this->findColumn($headers, [
            'nama lengkap', 'nama peserta', 'nama', 'name',
        ]);

        $nipColumn = $this->findColumn($headers, [
            'nip', 'nomor induk pegawai',
        ]);

        $nikColumn = $this->findColumn($headers, [
            'nik', 'nomor induk kependudukan',
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

                    if (
                        $nip !== '' &&
                        Schema::hasColumn('participants', 'nip')
                    ) {
                        $participant = Participant::where(
                            'nip',
                            $nip
                        )->first();
                    }

                    if (
                        !$participant &&
                        $nik !== '' &&
                        Schema::hasColumn('participants', 'nik')
                    ) {
                        $participant = Participant::where(
                            'nik',
                            $nik
                        )->first();
                    }

                    $isNew = !$participant;

                    if (!$participant) {
                        $participant = new Participant();
                    }

                    $participant->name = $name;

                    if (
                        $nip !== '' &&
                        Schema::hasColumn('participants', 'nip')
                    ) {
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

                    $this->setIfColumnExists(
                        $participant,
                        'district',
                        $this->district
                    );

                    $participant->save();

                    if ($isNew) {
                        $this->createdParticipants++;
                    } else {
                        $this->updatedParticipants++;
                    }

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
        $normalizedAliases = array_map(
            fn ($alias) => Str::of($alias)
                ->lower()
                ->replace(['/', '.', '-', '_'], ' ')
                ->squish()
                ->toString(),
            $aliases
        );

        foreach ($headers as $index => $header) {
            if (in_array($header, $normalizedAliases, true)) {
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
                'nama lengkap', 'nama peserta', 'nama', 'name',
            ],
            'nip' => [
                'nip', 'nomor induk pegawai',
            ],
            'nik' => [
                'nik', 'nomor induk kependudukan',
            ],
            'gender' => [
                'jenis kelamin', 'gender', 'jk',
            ],
            'rank' => [
                'pangkat golongan', 'pangkat gol', 'golongan',
            ],
            'profession' => [
                'profesi nakes', 'profesi',
                'jenis tenaga kesehatan',
            ],
            'email' => [
                'email', 'alamat email',
            ],
            'phone' => [
                'nomor telepon', 'no telepon', 'telepon',
                'no hp', 'nomor hp',
            ],
            'institution' => [
                'instansi unit kerja', 'instansi', 'unit kerja',
            ],
        ];

        $result = [];

        foreach ($aliases as $field => $names) {
            $column = $this->findColumn($headers, $names);
            $value = $column !== null ? $row->get($column) : null;

            $result[$field] = $value === null
                ? null
                : trim((string) $value);
        }

        return $result;
    }

    private function findOrCreateTraining(): Training
    {
        $batchNumber = trim($this->batchNumber);

        $codeSource = implode('|', [
            trim($this->trainingName),
            $this->startDate ?? '',
            $this->endDate ?? '',
            trim($this->organizer),
            $batchNumber,
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

        // Angkatan kosong disimpan sebagai string kosong.
        // Ini menghindari masalah jika kolom database NOT NULL.
        $storedBatch = $batchNumber !== '' ? $batchNumber : '';

        $this->setTrainingColumn(
            $training,
            'batch_number',
            $storedBatch
        );

        $this->setTrainingColumn(
            $training,
            'angkatan',
            $storedBatch
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