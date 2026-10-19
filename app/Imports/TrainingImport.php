<?php

namespace App\Imports;

use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class TrainingImport implements ToCollection
{
    public int $processed = 0;
    public int $createdParticipants = 0;
    public int $createdTrainings = 0;
    public int $createdHistories = 0;
    public int $skipped = 0;
    public array $errors = [];

    public function __construct(
        private readonly ?string $batchNumber = null,
        private readonly ?string $fundingSource = null,
    ) {}

    public function collection(Collection $rows): void
    {
        // Metadata workbook berada di bagian atas lembar Excel.
        $trainingName = $this->cell($rows, 1, 1);
        $facilitation = $this->cell($rows, 2, 1);
        $organizer = $this->cell($rows, 3, 1);
        $dateRange = $this->cell($rows, 4, 1);

        if (! $trainingName) {
            $this->errors[] = [
                'baris' => 2,
                'pesan' => 'Nama pelatihan pada sel A2 tidak ditemukan.',
            ];
            return;
        }

        if (! $this->batchNumber || ! $this->fundingSource) {
            $this->errors[] = [
                'baris' => 1,
                'pesan' => 'Angkatan dan sumber dana wajib diisi sebelum impor.',
            ];
            return;
        }

        try {
            [$startDate, $endDate] = $this->parseDateRange($dateRange);
        } catch (\Throwable $exception) {
            $this->errors[] = [
                'baris' => 5,
                'pesan' => $exception->getMessage(),
            ];
            return;
        }

        $durationDays = $startDate && $endDate
            ? $startDate->diffInDays($endDate) + 1
            : 0;

        // Header peserta berada pada baris Excel ke-7.
        $headerRow = $rows->get(6);

        if (! $headerRow) {
            $this->errors[] = [
                'baris' => 7,
                'pesan' => 'Header peserta pada baris 7 tidak ditemukan.',
            ];
            return;
        }

        $headers = $headerRow->toArray();
        $headerMap = [];

        foreach ($headers as $column => $header) {
            $normalized = Str::snake(trim((string) $header));

            if ($normalized !== '') {
                $headerMap[$normalized] = $column;
            }
        }

        $training = null;

        foreach ($rows as $index => $row) {
            // Lewati metadata dan header, proses mulai baris Excel ke-8.
            if ($index < 7) {
                continue;
            }

            $line = $index + 1;
            $values = $row->toArray();

            try {
                $data = [];

                foreach ($headerMap as $header => $column) {
                    $data[$header] = $values[$column] ?? null;
                }

                $name = $this->value($data, [
                    'nama_lengkap',
                    'nama',
                    'nama_peserta',
                ]);

                $nip = $this->value($data, [
                    'nip',
                    'nomor_induk_pegawai',
                    'nomor_induk',
                ]);

                // Abaikan baris kosong, tetapi laporkan baris berisi data
                // yang tidak memiliki nama atau NIP.
                if (! $name && ! $nip) {
                    continue;
                }

                if (! $name || ! $nip) {
                    throw new \RuntimeException(
                        'Nama lengkap dan NIP wajib diisi.'
                    );
                }

                $nip = preg_replace('/\s+/', '', $nip);
                $nik = $this->value($data, ['nik', 'nomor_induk_kependudukan']);

                $participant = DB::transaction(function () use (
                    $data,
                    $name,
                    $nip,
                    $nik,
                    &$training,
                    $trainingName,
                    $facilitation,
                    $organizer,
                    $startDate,
                    $endDate,
                    $durationDays
                ) {
                    $participant = Participant::where('nip', $nip)->first();

                    // NIK menjadi kunci pencocokan alternatif bila NIP
                    // belum ditemukan.
                    if (! $participant && $nik) {
                        $participant = Participant::where('nik', $nik)->first();
                    }

                    $isNewParticipant = ! $participant;

                    if (! $participant) {
                        $participant = new Participant();
                    }

                    $participant->nip = $nip;
                    $participant->name = $name;

                    $this->setIfPresent(
                        $participant,
                        'nik',
                        $nik
                    );

                    $this->setIfPresent(
                        $participant,
                        'gender',
                        $this->value($data, ['jenis_kelamin', 'gender'])
                    );

                    $this->setIfPresent(
                        $participant,
                        'rank',
                        $this->value($data, [
                            'pangkat_golongan',
                            'pangkat',
                            'golongan',
                        ])
                    );

                    $this->setIfPresent(
                        $participant,
                        'profession',
                        $this->value($data, [
                            'profesi_nakes',
                            'profesi',
                            'jenis_profesi',
                        ])
                    );

                    $this->setIfPresent(
                        $participant,
                        'email',
                        $this->value($data, ['email'])
                    );

                    $this->setIfPresent(
                        $participant,
                        'phone',
                        $this->value($data, [
                            'nomor_telepon',
                            'no_telepon',
                            'telepon',
                            'phone',
                        ])
                    );

                    $this->setIfPresent(
                        $participant,
                        'institution',
                        $this->value($data, [
                            'instansi_unit_kerja',
                            'instansi',
                            'unit_kerja',
                        ])
                    );

                    // Jangan menimpa data lama dengan nilai kosong.
                    if (! $participant->province) {
                        $participant->province = 'Kalimantan Selatan';
                    }

                    $participant->save();

                    if ($isNewParticipant) {
                        $this->createdParticipants++;
                    }

                    if (! $training) {
                        $identity = implode('|', [
                            Str::lower(trim($trainingName)),
                            $startDate?->toDateString() ?? '',
                            $endDate?->toDateString() ?? '',
                            Str::lower(trim((string) $organizer)),
                            trim($this->batchNumber ?? ''),
                        ]);

                        $code = 'IMP-' . strtoupper(
                            substr(hash('sha256', $identity), 0, 24)
                        );

                        // Cari kode impor yang sama supaya impor ulang
                        // tidak membuat pelatihan duplikat.
                        $training = Training::firstOrNew([
                            'code' => $code,
                        ]);

                        $isNewTraining = ! $training->exists;

                        $training->name = $trainingName;
                        $training->batch_number = $this->batchNumber;
                        $training->funding_source = $this->fundingSource;
                        $training->organizer = $organizer ?: $training->organizer;
                        $training->start_date = $startDate;
                        $training->end_date = $endDate;
                        $training->duration_days = $durationDays;

                        // Kolom field bukan kolom fasilitasi. Simpan
                        // keterangan fasilitasi di description.
                        $training->description = $facilitation
                            ? 'Fasilitasi: ' . $facilitation
                            : $training->description;

                        $training->save();

                        if ($isNewTraining) {
                            $this->createdTrainings++;
                        }
                    }

                    $exists = TrainingHistory::where(
                        'participant_id',
                        $participant->id
                    )->where(
                        'training_id',
                        $training->id
                    )->exists();

                    if ($exists) {
                        $this->skipped++;
                        return $participant;
                    }

                    TrainingHistory::create([
                        'participant_id' => $participant->id,
                        'training_id' => $training->id,
                        'source_file' => 'Impor Excel',
                        'status' => 'selesai',
                    ]);

                    $this->createdHistories++;

                    return $participant;
                });

                $this->processed++;
            } catch (\Throwable $exception) {
                $this->errors[] = [
                    'baris' => $line,
                    'pesan' => $exception->getMessage(),
                ];
            }
        }
    }

    private function cell(Collection $rows, int $rowIndex, int $columnIndex): ?string
    {
        $row = $rows->get($rowIndex);
        $value = $row?->get($columnIndex);

        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return trim((string) $value);
    }

    private function value(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = $row[Str::snake($key)] ?? null;

            if ($value !== null && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return null;
    }

    private function setIfPresent(
        Participant $participant,
        string $attribute,
        ?string $value
    ): void {
        if ($value !== null && trim($value) !== '') {
            $participant->{$attribute} = trim($value);
        }
    }

    private function parseDateRange(?string $value): array
    {
        if (! $value) {
            throw new \RuntimeException(
                'Rentang tanggal pelatihan pada sel A5 tidak ditemukan.'
            );
        }

        // Mendukung format seperti "18 MEI 2026 - 23 MEI 2026".
        $parts = preg_split('/\s+-\s+/', trim($value));

        if (count($parts) !== 2) {
            throw new \RuntimeException(
                "Format rentang tanggal tidak valid: {$value}"
            );
        }

        return [
            $this->parseIndonesianDate($parts[0]),
            $this->parseIndonesianDate($parts[1]),
        ];
    }

    private function parseIndonesianDate(string $value): Carbon
    {
        $value = Str::lower(trim($value));

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

        $value = str_replace(array_keys($months), array_values($months), $value);

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            throw new \RuntimeException(
                "Tanggal tidak dapat dibaca: {$value}"
            );
        }
    }
}