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

    protected int $number = 0;

    public function __construct(int $year, ?string $search = null)
    {
        $this->year = $year;
        $this->search = $search;
    }

    /**
     * Query data peserta yang mengikuti pelatihan pada tahun terpilih.
     */
    public function query(): Builder
    {
        $search = trim($this->search ?? '');

        return Participant::query()
            ->whereHas('histories', function ($query) {
                $query->whereHas('training', function ($trainingQuery) {
                    $trainingQuery->whereYear('start_date', $this->year);
                });
            })
            ->withCount([
                'histories as participations_count' => function ($query) {
                    $query->whereHas('training', function ($trainingQuery) {
                        $trainingQuery->whereYear('start_date', $this->year);
                    });
                },
            ])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('participations_count')
            ->orderBy('name');
    }

    /**
     * Judul kolom Excel.
     */
    public function headings(): array
    {
        return [
            'No.',
            'Nama Peserta',
            'NIP',
            'NIK',
            'Profesi',
            'Kabupaten/Kota',
            'Jumlah Keikutsertaan',
            'Tahun Laporan',
        ];
    }

    /**
     * Isi setiap baris Excel.
     */
    public function map($participant): array
    {
        $this->number++;

        return [
            $this->number,
            $participant->name ?? '-',
            $participant->nip ?? '-',
            $participant->nik ?? '-',
            $participant->profession ?? '-',
            $participant->district ?? '-',
            (int) $participant->participations_count,
            $this->year,
        ];
    }
}