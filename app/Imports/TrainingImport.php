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
        // Header Excel ada pada baris 7 (indeks 6).
        $headerRow = $rows->get(6);

        if (!$headerRow) {
            $this->errors[] =
                'Header peserta tidak ditemukan pada baris 7.';
            return;
        }

        $headers = $this->normalizeHeaders($headerRow);

        $nameColumn = $this->findColumn($headers, [
            'nama lengkap',
            'nama',
            'name',
        ]);

        if ($nameColumn === null) {
            $this->errors[] =
                'Kolom NAMA LENGKAP tidak ditemukan.';
            return;
        }

        $nipColumn = $this->findColumn($headers, [
            'nip',
            'nomor induk pegawai',
        ]);

        $nikColumn = $this->findColumn($headers, [
            'nik',
            'nomor induk kependudukan',
        ]);

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

        /*
         * values() mengatur ulang indeks setelah slice.
         * Dengan demikian data pertama tetap dilaporkan sebagai
         * baris Excel 8, bukan baris 15.
         */
        foreach ($rows->slice(7)->values() as $index => $row) {
            $excelRow = $index + 8;

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
                ) {
                    $participant = null;

                    if ($nip !== '') {
                        $participant = Participant::where('nip', $nip)
                            ->first();
                    }

                    if (!$participant && $nik !== '') {
                        if (Schema::hasColumn('participants', 'nik')) {
                            $participant = Participant::where('nik', $nik)
                                ->first();
                        }
                    }

                    $isNew = !$participant;

                    if (!$participant) {
                        $participant = new Participant();
                    }

                    $participant->name = $name;

                    if ($nip !== '') {
                        $participant->nip = $nip;
                    }

                    if (
                        $nik !== '' &&
                        Schema::hasColumn(
                            $participant->getTable(),
                            'nik'
                        )
                    ) {
                        $participant->nik = $nik;
                    }

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

                    // Cegah riwayat yang sama dibuat berulang.
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
                ? ($row[$column] ?? null)
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
            $this->trainingName,
            $this->startDate ?? '',
            $this->endDate ?? '',
            $this->organizer,
            $this->batchNumber,
            $this->fundingSource,
            $this->district,
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

        // Simpan nama wilayah yang dipilih dari master data.
        $this->setTrainingColumn(
            $training,
            'district',
            $this->district
        );

        if ($this->organizer !== '') {
            $this->setTrainingColumn(
                $training,
                'organizer',
                $this->organizer
            );
        }

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

            $this->setTrainingColumn(
                $training,
                'duration',
                $start->diff($end)->days + 1
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
            Schema::hasColumn(
                $participant->getTable(),
                $column
            )
        ) {
            $participant->{$column} = $value;
        }
    }
}