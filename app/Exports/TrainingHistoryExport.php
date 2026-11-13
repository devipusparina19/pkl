<?php

namespace App\Exports;

use App\Models\Participant;
use Illuminate\Database\Eloquent\Builder;
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
    private int $rowNumber = 0;

    public function __construct(
        private int $year,
        private string $search = '',
        private int $minimum = 1,
        private string $district = '',
        private string $institution = ''
    ) {
        $this->search = trim($this->search);
        $this->district = trim($this->district);
        $this->institution = trim($this->institution);

        $this->minimum = max(1, min(100, $this->minimum));
    }

    /**
     * Query peserta untuk Excel.
     */
    public function query(): Builder
    {
        $historyInYear = function ($query) {
            $query->whereHas('training', function ($trainingQuery) {
                $trainingQuery->whereYear('start_date', $this->year);
            });
        };

        $query = Participant::query()
            ->whereHas(
                'histories',
                $historyInYear,
                '>=',
                $this->minimum
            )
            ->withCount([
                'histories as participations_count' => $historyInYear,
            ]);

        // Filter kabupaten/kota.
        if ($this->district !== '') {
            $query->whereRaw(
                'TRIM(participants.district) = ?',
                [$this->district]
            );
        }

        // Filter instansi.
        if ($this->institution !== '') {
            $query->whereRaw(
                'TRIM(participants.institution) = ?',
                [$this->institution]
            );
        }

        // Filter pencarian nama, NIK, atau NIP.
        if ($this->search !== '') {
            $search = $this->search;

            $query->where(function ($subQuery) use ($search) {
                $subQuery
                    ->where('participants.name', 'like', "%{$search}%")
                    ->orWhere('participants.nik', 'like', "%{$search}%")
                    ->orWhere('participants.nip', 'like', "%{$search}%");
            });
        }

        return $query
            ->orderByDesc('participations_count')
            ->orderBy('participants.name');
    }

    /**
     * Judul kolom Excel.
     */
    public function headings(): array
    {
        return [
            'No.',
            'Nama Peserta',
            'NIK',
            'Instansi',
            'Kabupaten/Kota',
            'Jumlah Keikutsertaan',
        ];
    }

    /**
     * Isi setiap baris Excel.
     */
    public function map($participant): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $participant->name ?: '-',
            $participant->nik ?: '-',
            $participant->institution ?: '-',
            $participant->district ?: '-',
            (int) $participant->participations_count,
        ];
    }
}