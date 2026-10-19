<?php

namespace App\Exports;

use App\Models\TrainingHistory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TrainingHistoryExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function __construct(
        private readonly ?int $year = null,
        private readonly ?string $nip = null,
    ) {}

    public function query()
    {
        return TrainingHistory::query()
            ->with(['participant', 'training'])
            ->when($this->nip, function ($query) {
                $query->whereHas('participant', function ($participant) {
                    $participant->where('nip', $this->nip);
                });
            })
            ->when($this->year, function ($query) {
                $query->whereHas('training', function ($training) {
                    $training->whereYear('start_date', $this->year);
                });
            })
            ->orderBy('participant_id')
            ->orderBy('training_id');
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Peserta',
            'Instansi',
            'Unit Kerja',
            'Kabupaten/Kota',
            'Nama Pelatihan',
            'Bidang',
            'Penyelenggara',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Durasi Hari',
            'Nomor Sertifikat',
            'Status',
        ];
    }

    public function map($history): array
    {
        $participant = $history->participant;
        $training = $history->training;

        return [
            $participant?->nip,
            $participant?->name,
            $participant?->institution,
            $participant?->unit,
            $participant?->district,
            $training?->name,
            $training?->field,
            $training?->organizer,
            $training?->start_date?->format('Y-m-d'),
            $training?->end_date?->format('Y-m-d'),
            $training?->duration_days,
            $history->certificate_number,
            $history->status,
        ];
    }
}