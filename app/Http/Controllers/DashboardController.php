<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use App\Models\District;
use App\Models\FundingSource;
use App\Models\TrainingYear;
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

        /*
         * Pilihan kabupaten/kota/mitra berasal dari master data.
         * Data lama dari tabel trainings tetap digunakan sebagai
         * cadangan jika tabel master belum memiliki data aktif.
         */
        $districts = District::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        if ($districts->isEmpty()) {
            $districts = Training::query()
                ->whereNotNull('district')
                ->where('district', '!=', '')
                ->distinct()
                ->orderBy('district')
                ->pluck('district');
        }

        /*
         * Pilihan sumber dana berasal dari master data.
         */
        $fundingSources = FundingSource::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        if ($fundingSources->isEmpty()) {
            $fundingSources = Training::query()
                ->whereNotNull('funding_source')
                ->where('funding_source', '!=', '')
                ->distinct()
                ->orderBy('funding_source')
                ->pluck('funding_source');
        }

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

        /*
         * Tahun pelatihan diambil dari master data Super Admin.
         */
        $years = TrainingYear::query()
            ->where('is_active', true)
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($item) => (int) $item);

        /*
         * Jika master data tahun masih kosong, gunakan tahun
         * yang tersedia dari data pelatihan.
         */
        if ($years->isEmpty()) {
            $yearsQuery = Training::query()
                ->whereNotNull('start_date');

            if (DB::connection()->getDriverName() === 'sqlite') {
                $yearsQuery->selectRaw(
                    "CAST(strftime('%Y', start_date) AS INTEGER) as year"
                );
            } else {
                $yearsQuery->selectRaw(
                    'YEAR(start_date) as year'
                );
            }

            $years = $yearsQuery
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year')
                ->map(fn ($item) => (int) $item);
        }

        if (!$years->contains($year)) {
            $years->push($year);
        }

        $years = $years->unique()->sortDesc()->values();

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
     * Dashboard internal berdasarkan role.
     */
    public function index()
    {
        $user = Auth::user();

        abort_unless($user, 403);

        $role = strtolower(trim((string) $user->role));
        $role = str_replace('-', '_', $role);

        if ($role === 'super_admin') {
            return redirect()->route('super-admin.dashboard');
        }

        abort_unless(
            in_array($role, ['admin', 'pimpinan'], true),
            403,
            'Akun ini tidak memiliki akses ke dashboard internal.'
        );

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