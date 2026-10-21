<?php

namespace App\Exports;

use App\Models\Participant;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TrainingHistoryExport implements FromQuery, WithHeadings, WithMapping
{
    protected int $year;

    protected ?string $search;

    public function __construct(int $year, ?string $search = null)
    {
        $this->year = $year;
        $this->search = $search;
    }

    public function query(): Builder
    {
        $search = trim($this->search ?? '');

        return Participant::query()
            ->withCount([
                'histories as participations_count' => function ($query) {
                    $query->whereHas('training', function ($training) {
                        $training->whereYear('start_date', $this->year);
                    });
                },
            ])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('participations_count')
            ->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama Peserta',
            'NIP',
            'Jumlah Keikutsertaan',
            'Tahun Laporan',
        ];
    }

    public function map($participant): array
    {
        static $number = 0;
        $number++;

        return [
            $number,
            $participant->name,
            $participant->nip,
            $participant->participations_count,
            $this->year,
        ];
    }
}