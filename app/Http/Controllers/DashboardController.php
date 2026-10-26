<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use App\Models\District;
use App\Models\FundingSource;
use App\Models\TrainingYear;
use App\Models\HealthWorkerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard publik.
     */
    public function publicIndex(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $district = $request->input('district');
        $fundingSource = $request->input('funding_source');

        $base = Training::query()
            ->whereYear('start_date', $year)
            ->when($district, fn ($query) =>
                $query->where('district', $district)
            )
            ->when($fundingSource, fn ($query) =>
                $query->where('funding_source', $fundingSource)
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

        $years = TrainingYear::query()
            ->where('is_active', true)
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($item) => (int) $item);

        if ($years->isEmpty()) {
            $yearsQuery = Training::query()
                ->whereNotNull('start_date');

            if (DB::connection()->getDriverName() === 'sqlite') {
                $yearsQuery->selectRaw(
                    "CAST(strftime('%Y', start_date) AS INTEGER) as year"
                );
            } else {
                $yearsQuery->selectRaw('YEAR(start_date) as year');
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

        $healthWorkerTypes = HealthWorkerType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $nakesCounts = DB::table('training_histories')
            ->join(
                'participants',
                'participants.id',
                '=',
                'training_histories.participant_id'
            )
            ->whereIn('training_histories.training_id', $trainingIds)
            ->whereNotNull('participants.profession')
            ->whereRaw("TRIM(participants.profession) != ''")
            ->selectRaw(
                'TRIM(participants.profession) as name,
                 COUNT(DISTINCT training_histories.participant_id) as total'
            )
            ->groupByRaw('TRIM(participants.profession)')
            ->orderByDesc('total')
            ->get()
            ->keyBy(fn ($item) => mb_strtolower(trim($item->name)));

        $nakesNames = collect();

        foreach ($healthWorkerTypes as $type) {
            $name = trim((string) $type->name);

            if ($name !== '') {
                $nakesNames->put(mb_strtolower($name), $name);
            }
        }

        foreach ($nakesCounts as $key => $item) {
            $name = trim((string) $item->name);

            if ($name !== '' && !$nakesNames->has($key)) {
                $nakesNames->put($key, $name);
            }
        }

        $nakesByType = $nakesNames
            ->map(function ($name, $key) use ($nakesCounts) {
                $count = $nakesCounts->get($key);

                return (object) [
                    'name' => $name,
                    'total' => $count ? (int) $count->total : 0,
                ];
            })
            ->sortBy('name')
            ->values();

        $topNakes = $nakesByType
            ->filter(fn ($item) => $item->total > 0)
            ->sortByDesc('total')
            ->take(5)
            ->values();

        $bottomNakes = $nakesByType
            ->filter(fn ($item) => $item->total > 0)
            ->sortBy('total')
            ->take(5)
            ->values();

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
            'recentTrainings',
            'healthWorkerTypes',
            'nakesByType',
            'topNakes',
            'bottomNakes'
        ));
    }

    /**
     * Dashboard internal Admin dan Pimpinan.
     */
    public function index(Request $request)
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

        /*
         * FILTER TAHUN DAN URUTAN
         */
        $year = (int) $request->input('year', now()->year);
        $sort = $request->input('sort', 'desc');

        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'desc';
        }

        $selectedProfession = trim(
            (string) $request->input('profession', '')
        );

        /*
         * PILIHAN TAHUN
         */
        $years = TrainingYear::query()
            ->where('is_active', true)
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($item) => (int) $item);

        if ($years->isEmpty()) {
            $yearsQuery = Training::query()
                ->whereNotNull('start_date');

            if (DB::connection()->getDriverName() === 'sqlite') {
                $yearsQuery->selectRaw(
                    "CAST(strftime('%Y', start_date) AS INTEGER) as year"
                );
            } else {
                $yearsQuery->selectRaw('YEAR(start_date) as year');
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

        /*
         * STATISTIK BERDASARKAN TAHUN TERPILIH
         *
         * Peserta dihitung jika mengikuti pelatihan pada tahun itu.
         * Pelatihan dihitung berdasarkan tanggal mulai pelatihan.
         * Keikutsertaan dihitung dari riwayat pelatihan pada tahun itu.
         */
        $trainingIdsThisYear = Training::query()
            ->whereYear('start_date', $year)
            ->select('id');

        $totalTrainings = Training::query()
            ->whereYear('start_date', $year)
            ->count();

        $totalParticipations = TrainingHistory::query()
            ->whereIn('training_id', $trainingIdsThisYear)
            ->count();

        $totalParticipants = TrainingHistory::query()
            ->whereIn('training_id', $trainingIdsThisYear)
            ->distinct()
            ->count('participant_id');

        $totalTrainingsSelectedYear = $totalTrainings;
        $participationsThisYear = $totalParticipations;

        /*
         * JUMLAH PELATIHAN SETIAP PESERTA
         *
         * Semua peserta tetap dapat ditampilkan.
         * Peserta yang tidak mengikuti pelatihan pada tahun terpilih
         * tetap memiliki participations_count = 0.
         */
        $frequentParticipants = Participant::query()
            ->withCount([
                'histories as participations_count' => fn ($query) =>
                    $query->whereHas('training', fn ($trainingQuery) =>
                        $trainingQuery->whereYear('start_date', $year)
                    ),
            ])
            ->orderBy('participations_count', $sort)
            ->orderBy('name')
            ->limit(8)
            ->get();

        /*
         * STATISTIK BIDANG PELATIHAN PADA TAHUN TERPILIH
         */
        $fieldStats = Training::query()
            ->select('field', DB::raw('COUNT(*) as total'))
            ->whereYear('start_date', $year)
            ->whereNotNull('field')
            ->where('field', '!=', '')
            ->groupBy('field')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        /*
         * PILIHAN PROFESI DARI MASTER DATA
         */
        $professionOptions = HealthWorkerType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        /*
         * DAFTAR PESERTA TERLATIH BERDASARKAN TAHUN DAN PROFESI
         */
        $trainedParticipants = collect();

        $professionExists = $selectedProfession !== ''
            && $professionOptions->contains(
                fn ($profession) =>
                    mb_strtolower(trim((string) $profession))
                    === mb_strtolower($selectedProfession)
            );

        if ($professionExists) {
            $trainedParticipants = Participant::query()
                ->whereRaw('LOWER(TRIM(profession)) = ?', [
                    mb_strtolower($selectedProfession),
                ])
                ->whereHas('histories', fn ($query) =>
                    $query->whereHas('training', fn ($trainingQuery) =>
                        $trainingQuery->whereYear('start_date', $year)
                    )
                )
                ->withCount([
                    'histories as participations_count' => fn ($query) =>
                        $query->whereHas('training', fn ($trainingQuery) =>
                            $trainingQuery->whereYear('start_date', $year)
                        ),
                ])
                ->orderBy('name')
                ->get();
        } else {
            $selectedProfession = '';
        }

        /*
         * Riwayat terbaru sudah digantikan oleh bagian profesi.
         */
        $recentHistories = collect();

        $data = compact(
            'totalParticipants',
            'totalTrainings',
            'totalParticipations',
            'participationsThisYear',
            'totalTrainingsSelectedYear',
            'frequentParticipants',
            'fieldStats',
            'recentHistories',
            'year',
            'years',
            'sort',
            'selectedProfession',
            'professionOptions',
            'trainedParticipants'
        );

        if ($role === 'admin') {
            return view('dashboard', $data);
        }

        return view('dashboards.pimpinan', $data);
    }
}