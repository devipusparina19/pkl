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
     * Dashboard publik pada halaman utama.
     */
    public function publicIndex(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $district = $request->input('district');
        $fundingSource = $request->input('funding_source');

        /*
         * Filter data pelatihan.
         */
        $base = Training::query()
            ->whereYear('start_date', $year)
            ->when(
                $district,
                fn ($query) => $query->where('district', $district)
            )
            ->when(
                $fundingSource,
                fn ($query) => $query->where(
                    'funding_source',
                    $fundingSource
                )
            );

        $trainingIds = (clone $base)->select('id');

        $totalTrainings = (clone $base)->count();

        /*
         * Total riwayat keikutsertaan.
         */
        $totalParticipations = TrainingHistory::query()
            ->whereIn('training_id', $trainingIds)
            ->count();

        /*
         * Total peserta unik.
         */
        $totalParticipants = TrainingHistory::query()
            ->whereIn('training_id', $trainingIds)
            ->distinct()
            ->count('participant_id');

        /*
         * Pilihan kabupaten/kota.
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
         * Pilihan sumber dana.
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

        /*
         * Statistik pelatihan berdasarkan kabupaten/kota.
         */
        $byDistrict = (clone $base)
            ->select('district', DB::raw('COUNT(*) as total'))
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->groupBy('district')
            ->orderByDesc('total')
            ->get();

        /*
         * Daftar pelatihan terbaru.
         */
        $recentTrainings = (clone $base)
            ->withCount('histories')
            ->orderByDesc('start_date')
            ->limit(8)
            ->get();

        /*
         * Pilihan tahun pelatihan.
         */
        $years = TrainingYear::query()
            ->where('is_active', true)
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($item) => (int) $item);

        /*
         * Cadangan tahun dari data pelatihan.
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

        /*
         * Master jenis tenaga kesehatan.
         */
        $healthWorkerTypes = HealthWorkerType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        /*
         * Hitung peserta unik berdasarkan profesi.
         */
        $nakesCounts = DB::table('training_histories')
            ->join(
                'participants',
                'participants.id',
                '=',
                'training_histories.participant_id'
            )
            ->whereIn(
                'training_histories.training_id',
                $trainingIds
            )
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

        /*
         * Gabungkan master jenis tenaga kesehatan dengan data peserta.
         */
        $nakesNames = collect();

        foreach ($healthWorkerTypes as $type) {
            $name = trim((string) $type->name);

            if ($name !== '') {
                $nakesNames->put(
                    mb_strtolower($name),
                    $name
                );
            }
        }

        foreach ($nakesCounts as $key => $item) {
            $name = trim((string) $item->name);

            if ($name !== '' && !$nakesNames->has($key)) {
                $nakesNames->put($key, $name);
            }
        }

        /*
         * Format data untuk Blade.
         */
        $nakesByType = $nakesNames
            ->map(function ($name, $key) use ($nakesCounts) {
                $count = $nakesCounts->get($key);

                return (object) [
                    'name' => $name,
                    'total' => $count
                        ? (int) $count->total
                        : 0,
                ];
            })
            ->sortBy('name')
            ->values();

        /*
         * Lima jenis tenaga kesehatan terbanyak.
         */
        $topNakes = $nakesByType
            ->filter(fn ($item) => $item->total > 0)
            ->sortByDesc('total')
            ->take(5)
            ->values();

        /*
         * Lima jenis tenaga kesehatan tersedikit.
         */
        $bottomNakes = $nakesByType
            ->filter(fn ($item) => $item->total > 0)
            ->sortBy('total')
            ->take(5)
            ->values();

        /*
         * Nama variabel kompatibel dengan view.
         */
        $trainingByDistrict = $byDistrict;

        /*
         * Tampilkan halaman utama welcome.blade.php.
         */
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
                fn ($query) => $query->whereYear(
                    'start_date',
                    $year
                )
            )
            ->count();

        $frequentParticipants = Participant::withCount([
            'histories as participations_count' => fn ($query) =>
                $query->whereHas(
                    'training',
                    fn ($trainingQuery) =>
                        $trainingQuery->whereYear(
                            'start_date',
                            $year
                        )
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