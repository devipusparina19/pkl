<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PdfExportController extends Controller
{
    /**
     * =====================================================
     * HELPER: MENENTUKAN TAHUN LAPORAN
     * =====================================================
     */
    private function resolveYear(Request $request): ?int
    {
        if ($request->filled('year')) {
            $year = $request->validate([
                'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            ])['year'];

            return (int) $year;
        }

        $driver = DB::connection()->getDriverName();

        $expression = $driver === 'sqlite'
            ? "MAX(CAST(strftime('%Y', start_date) AS INTEGER))"
            : 'MAX(YEAR(start_date))';

        $latestYear = Training::query()
            ->whereNotNull('start_date')
            ->selectRaw($expression . ' AS latest_year')
            ->value('latest_year');

        return $latestYear ? (int) $latestYear : null;
    }

    /**
     * =====================================================
     * HELPER: QUERY PESERTA BERDASARKAN TAHUN
     * Mengikuti TrainingHistoryExport.php
     * Satu peserta hanya muncul satu kali.
     * =====================================================
     */
    private function participantQuery(
        ?int $year,
        ?string $search = null
    ): Builder {
        $search = trim($search ?? '');

        return Participant::query()
            ->whereHas('histories', function ($query) use ($year) {
                $query->whereHas('training', function ($trainingQuery) use ($year) {
                    if ($year) {
                        $trainingQuery->whereYear('start_date', $year);
                    }
                });
            })
            ->withCount([
                'histories as participations_count' => function ($query) use ($year) {
                    $query->whereHas('training', function ($trainingQuery) use ($year) {
                        if ($year) {
                            $trainingQuery->whereYear('start_date', $year);
                        }
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
     * =====================================================
     * 1. EXPORT PDF DATA PESERTA
     * =====================================================
     */
    public function participants(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
        ]);

        $search = trim($validated['search'] ?? '');
        $district = $validated['district'] ?? null;

        $participants = Participant::query()
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('institution', 'like', "%{$search}%");
                });
            })
            ->when($district, function (Builder $query) use ($district) {
                $query->where('district', $district);
            })
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('pdf.participants', [
            'participants' => $participants,
            'search' => $search,
            'district' => $district,
            'printedAt' => Carbon::now(),
            'total' => $participants->count(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            'Data_Peserta_SIRAPIK_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    /**
     * =====================================================
     * 2. EXPORT PDF RIWAYAT PELATIHAN
     * Satu baris untuk setiap riwayat keikutsertaan.
     * =====================================================
     */
    public function trainingHistory(Request $request)
    {
        $year = $this->resolveYear($request);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $search = trim($validated['search'] ?? '');

        $histories = TrainingHistory::query()
            ->with(['participant', 'training'])
            ->whereHas('participant')
            ->whereHas('training', function ($query) use ($year) {
                if ($year) {
                    $query->whereYear('start_date', $year);
                }
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('participant', function ($participantQuery) use ($search) {
                    $participantQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                });
            })
            ->orderBy('id')
            ->get()
            ->sortBy(function ($history) {
                return optional($history->participant)->name;
            })
            ->values();

        $pdf = Pdf::loadView('pdf.training-history', [
            'histories' => $histories,
            'search' => $search,
            'year' => $year,
            'printedAt' => Carbon::now(),
            'total' => $histories->count(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            'Riwayat_Pelatihan_SIRAPIK_' .
            ($year ?: 'Semua_Tahun') . '_' .
            now()->format('Ymd_His') . '.pdf'
        );
    }

    /**
     * =====================================================
     * 3. EXPORT PDF LAPORAN REKAPITULASI
     * =====================================================
     */
    public function report(Request $request)
    {
        $year = $this->resolveYear($request);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $search = trim($validated['search'] ?? '');

        $participants = $this->participantQuery($year, $search)->get();

        $totalParticipants = $participants->count();

        $totalParticipations = (int) $participants->sum(
            'participations_count'
        );

        $totalTrainings = Training::query()
            ->when($year, function ($query) use ($year) {
                $query->whereYear('start_date', $year);
            })
            ->count();

        // Rekap gender peserta unik.
        $maleCount = $participants
            ->where('gender', 'L')
            ->count();

        $femaleCount = $participants
            ->where('gender', 'P')
            ->count();

        // Rekap wilayah berdasarkan participants.district.
        $districtRecap = $participants
            ->groupBy(function ($participant) {
                return trim($participant->district ?? '');
            })
            ->filter(function ($items, $district) {
                return $district !== '';
            })
            ->map(function ($items, $district) {
                return (object) [
                    'name' => $district,
                    'total' => $items->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Rekap profesi peserta unik.
        $professionRecap = $participants
            ->groupBy(function ($participant) {
                return trim($participant->profession ?? '') ?: 'Tidak diisi';
            })
            ->map(function ($items, $profession) {
                return (object) [
                    'name' => $profession,
                    'total' => $items->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        $pdf = Pdf::loadView('pdf.report', [
            'year' => $year,
            'search' => $search,
            'participants' => $participants,
            'totalTrainings' => $totalTrainings,
            'totalParticipations' => $totalParticipations,
            'totalParticipants' => $totalParticipants,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'districtRecap' => $districtRecap,
            'professionRecap' => $professionRecap,
            'printedAt' => Carbon::now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            'Laporan_SIRAPIK_' .
            ($year ?: 'Semua_Tahun') . '_' .
            now()->format('Ymd_His') . '.pdf'
        );
    }

    /**
     * =====================================================
     * 4. EXPORT PDF REKAP KABUPATEN/KOTA/MITRA/MANDIRI
     * =====================================================
     */
    public function districtRecap(Request $request)
    {
        $year = $this->resolveYear($request);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $search = trim($validated['search'] ?? '');

        $participants = $this->participantQuery($year, $search)->get();

        $districtRecap = $participants
            ->groupBy(function ($participant) {
                return trim($participant->district ?? '');
            })
            ->filter(function ($items, $district) {
                return $district !== '';
            })
            ->map(function ($items, $district) {
                return (object) [
                    'name' => $district,
                    'total' => $items->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        $totalParticipants = $districtRecap->sum('total');

        $pdf = Pdf::loadView('pdf.district-recap', [
            'districtRecap' => $districtRecap,
            'year' => $year,
            'search' => $search,
            'totalParticipants' => $totalParticipants,
            'printedAt' => Carbon::now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download(
            'Rekap_Wilayah_SIRAPIK_' .
            ($year ?: 'Semua_Tahun') . '_' .
            now()->format('Ymd_His') . '.pdf'
        );
    }
}