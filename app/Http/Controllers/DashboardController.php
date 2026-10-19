<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard publik pada halaman utama.
     */
    public function publicIndex(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $district = $request->input('district');
        $fundingSource = $request->input('funding_source');

        $base = Training::query()
            ->whereYear('start_date', $year)
            ->when(
                $district,
                fn ($query) => $query->where('district', $district)
            )
            ->when(
                $fundingSource,
                fn ($query) => $query->where('funding_source', $fundingSource)
            );

        $trainingIds = (clone $base)->select('id');

        $totalTrainings = (clone $base)->count();

        $totalParticipations = TrainingHistory::query()
            ->whereIn('training_id', $trainingIds)
            ->count();

        $totalParticipants = TrainingHistory::query()
            ->whereIn('training_id', $trainingIds)
            ->distinct()
            ->count('participant_id');

        $districts = Training::query()
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        $fundingSources = Training::query()
            ->whereNotNull('funding_source')
            ->where('funding_source', '!=', '')
            ->distinct()
            ->orderBy('funding_source')
            ->pluck('funding_source');

        $byDistrict = (clone $base)
            ->select('district', DB::raw('COUNT(*) as total'))
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->groupBy('district')
            ->orderByDesc('total')
            ->get();

        $recentTrainings = (clone $base)
            ->withCount('histories')
            ->orderByDesc('start_date')
            ->limit(8)
            ->get();

        $years = Training::query()
            ->whereNotNull('start_date')
            ->selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if (! $years->contains($year)) {
            $years->push($year);
        }

        // Nama ini dipertahankan untuk kompatibilitas dengan view lama.
        $trainingByDistrict = $byDistrict;

        return view('welcome', compact(
            'year',
            'district',
            'fundingSource',
            'districts',
            'fundingSources',
            'years',
            'totalTrainings',
            'totalParticipants',
            'totalParticipations',
            'byDistrict',
            'trainingByDistrict',
            'recentTrainings'
        ));
    }

    /**
     * Pintu masuk dashboard berdasarkan role pengguna.
     */
    public function index()
    {
        $user = Auth::user();

        abort_unless($user, 403);

        // Normalisasi untuk mengantisipasi variasi penulisan role.
        $role = strtolower(trim((string) $user->role));

        if ($role === 'super-admin') {
            $role = 'super_admin';
        }

        // Dashboard Super Admin memiliki variabel statistik tersendiri.
        // Arahkan ke controller khusus agar data yang dikirim sesuai view.
        if ($role === 'super_admin') {
            return redirect()->route('super-admin.dashboard');
        }

        abort_unless(in_array($role, ['admin', 'pimpinan'], true), 403);

        $totalParticipants = Participant::count();
        $totalTrainings = Training::count();
        $totalParticipations = TrainingHistory::count();

        $year = now()->year;

        $participationsThisYear = TrainingHistory::query()
            ->whereHas(
                'training',
                fn ($query) => $query->whereYear('start_date', $year)
            )
            ->count();

        $frequentParticipants = Participant::withCount([
            'histories as participations_count' => fn ($query) =>
                $query->whereHas(
                    'training',
                    fn ($trainingQuery) =>
                        $trainingQuery->whereYear('start_date', $year)
                ),
        ])
            ->orderByDesc('participations_count')
            ->orderBy('name')
            ->limit(8)
            ->get();

        $fieldStats = Training::query()
            ->select('field', DB::raw('COUNT(*) as total'))
            ->whereNotNull('field')
            ->where('field', '!=', '')
            ->groupBy('field')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $recentHistories = TrainingHistory::with([
            'participant',
            'training',
        ])
            ->latest()
            ->limit(8)
            ->get();

        $data = compact(
            'totalParticipants',
            'totalTrainings',
            'totalParticipations',
            'participationsThisYear',
            'frequentParticipants',
            'fieldStats',
            'recentHistories',
            'year'
        );

        if ($role === 'admin') {
            return view('dashboard', $data);
        }

        return view('dashboards.pimpinan', $data);
    }
}