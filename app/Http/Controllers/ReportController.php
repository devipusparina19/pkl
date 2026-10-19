<?php

namespace App\Http\Controllers;

use App\Exports\TrainingHistoryExport;
use App\Models\Participant;
use App\Models\TrainingHistory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $minimum = max(1, (int) $request->input('minimum', 4));

        $query = Participant::query()
            ->withCount([
                'histories as participations_count' => function ($q) use ($year) {
                    $q->whereHas('training', function ($t) use ($year) {
                        $t->whereYear('start_date', $year);
                    });
                },
            ]);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $participants = $query
            ->orderByDesc('participations_count')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $totalParticipations = TrainingHistory::whereHas(
            'training',
            fn ($q) => $q->whereYear('start_date', $year)
        )->count();

        $participantsToReview = Participant::query()
            ->withCount([
                'histories as participations_count' => function ($q) use ($year) {
                    $q->whereHas('training', function ($t) use ($year) {
                        $t->whereYear('start_date', $year);
                    });
                },
            ])
            ->having('participations_count', '>=', $minimum)
            ->count();

        return view('reports.index', compact(
            'participants',
            'year',
            'minimum',
            'totalParticipations',
            'participantsToReview'
        ));
    }

    public function export(Request $request)
    {
        $year = (int) $request->input('year', now()->year);

        return Excel::download(
            new TrainingHistoryExport($year, $request->input('search')),
            'laporan-riwayat-pelatihan-' . $year . '.xlsx'
        );
    }
}