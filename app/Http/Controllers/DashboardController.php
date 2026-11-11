<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use App\Models\TrainingYear;
use App\Models\District;
use App\Models\FundingSource;
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
        // Dashboard publik tetap menggunakan tahun yang memiliki data pelatihan.
        $years = $this->getAvailableTrainingYears();

        $requestedYear = (int) $request->input('year', now()->year);

        $year = $years->isNotEmpty()
            ? ($years->contains($requestedYear) ? $requestedYear : (int) $years->first())
            : ($requestedYear ?: now()->year);

        $district = trim((string) $request->input('district', ''));
        $fundingSource = trim((string) $request->input('funding_source', ''));

        $district = $district !== '' ? $district : null;
        $fundingSource = $fundingSource !== '' ? $fundingSource : null;

        $base = Training::query()
            ->whereYear('start_date', $year)
            ->when($fundingSource, function ($query) use ($fundingSource) {
                $query->where('funding_source', $fundingSource);
            })
            ->when($district, function ($query) use ($district) {
                $query->whereHas('histories.participant', function ($participantQuery) use ($district) {
                    $participantQuery->whereRaw(
                        'TRIM(participants.district) = ?',
                        [$district]
                    );
                });
            });

        $trainingIds = (clone $base)->select('id');

        $totalTrainings = (clone $base)->count();

        $historyQuery = TrainingHistory::query()
            ->whereIn('training_id', $trainingIds)
            ->when($district, function ($query) use ($district) {
                $query->whereHas('participant', function ($participantQuery) use ($district) {
                    $participantQuery->whereRaw(
                        'TRIM(participants.district) = ?',
                        [$district]
                    );
                });
            });

        $totalParticipations = (clone $historyQuery)->count();

        $totalParticipants = (clone $historyQuery)
            ->distinct()
            ->count('participant_id');

        $activeDistricts = District::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        $participantDistricts = Participant::query()
            ->whereNotNull('district')
            ->whereRaw("TRIM(district) != ''")
            ->selectRaw('TRIM(district) AS district_name')
            ->distinct()
            ->orderBy('district_name')
            ->pluck('district_name');

        $districts = $activeDistricts
            ->merge($participantDistricts)
            ->map(fn ($name) => trim((string) $name))
            ->filter(fn ($name) => $name !== '')
            ->unique()
            ->sort()
            ->values();

        $fundingSources = FundingSource::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        if ($fundingSources->isEmpty()) {
            $fundingSources = Training::query()
                ->whereNotNull('funding_source')
                ->whereRaw("TRIM(funding_source) != ''")
                ->select('funding_source')
                ->distinct()
                ->orderBy('funding_source')
                ->pluck('funding_source');
        }

        $byDistrict = DB::table('training_histories')
            ->join('trainings', 'trainings.id', '=', 'training_histories.training_id')
            ->join('participants', 'participants.id', '=', 'training_histories.participant_id')
            ->whereYear('trainings.start_date', $year)
            ->when($fundingSource, function ($query) use ($fundingSource) {
                $query->where('trainings.funding_source', $fundingSource);
            })
            ->when($district, function ($query) use ($district) {
                $query->whereRaw('TRIM(participants.district) = ?', [$district]);
            })
            ->whereNotNull('participants.district')
            ->whereRaw("TRIM(participants.district) != ''")
            ->selectRaw("
                TRIM(participants.district) AS district,
                COUNT(DISTINCT training_histories.participant_id) AS total
            ")
            ->groupByRaw('TRIM(participants.district)')
            ->orderByDesc('total')
            ->orderBy('district')
            ->get();

        $trainingByDistrict = $byDistrict;

        $recentTrainings = (clone $base)
            ->withCount([
                'histories' => function ($query) use ($district) {
                    $query->when($district, function ($historyQuery) use ($district) {
                        $historyQuery->whereHas('participant', function ($participantQuery) use ($district) {
                            $participantQuery->whereRaw(
                                'TRIM(participants.district) = ?',
                                [$district]
                            );
                        });
                    });
                },
            ])
            ->orderByDesc('start_date')
            ->limit(8)
            ->get();

        $healthWorkerTypes = HealthWorkerType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $nakesCounts = DB::table('training_histories')
            ->join('trainings', 'trainings.id', '=', 'training_histories.training_id')
            ->join('participants', 'participants.id', '=', 'training_histories.participant_id')
            ->whereYear('trainings.start_date', $year)
            ->when($fundingSource, function ($query) use ($fundingSource) {
                $query->where('trainings.funding_source', $fundingSource);
            })
            ->when($district, function ($query) use ($district) {
                $query->whereRaw('TRIM(participants.district) = ?', [$district]);
            })
            ->whereNotNull('participants.profession')
            ->whereRaw("TRIM(participants.profession) != ''")
            ->selectRaw("
                TRIM(participants.profession) AS name,
                COUNT(DISTINCT training_histories.participant_id) AS total
            ")
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
         * PILIHAN TAHUN DARI MASTER DATA SUPER ADMIN.
         *
         * Hanya tahun dengan is_active = true yang dapat dipilih.
         */
        $years = $this->getActiveTrainingYears();

        $requestedYear = $request->input('year');

        if (
            $requestedYear !== null
            && ctype_digit((string) $requestedYear)
            && $years->contains((int) $requestedYear)
        ) {
            $year = (int) $requestedYear;
        } else {
            // Tahun aktif terbaru menjadi pilihan default.
            $year = $years->isNotEmpty()
                ? (int) $years->first()
                : (int) now()->year;
        }

        /*
         * FILTER URUTAN DAN PROFESI.
         */
        $sort = $request->input('sort', 'desc');

        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'desc';
        }

        $selectedProfession = trim(
            (string) $request->input('profession', '')
        );

        /*
         * QUERY PELATIHAN PADA TAHUN TERPILIH.
         */
        $trainingIdsThisYear = Training::query()
            ->whereYear('start_date', $year)
            ->select('id');

        /*
         * STATISTIK UTAMA.
         */
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
         * STATISTIK JENIS KELAMIN.
         */
        $genderCounts = DB::table('training_histories')
            ->join('trainings', 'trainings.id', '=', 'training_histories.training_id')
            ->join('participants', 'participants.id', '=', 'training_histories.participant_id')
            ->whereYear('trainings.start_date', $year)
            ->whereRaw("
                LOWER(TRIM(participants.gender)) IN (
                    'l',
                    'p',
                    'laki-laki',
                    'laki laki',
                    'laki',
                    'perempuan'
                )
            ")
            ->selectRaw("
                CASE
                    WHEN LOWER(TRIM(participants.gender))
                        IN ('l', 'laki-laki', 'laki laki', 'laki')
                        THEN 'L'
                    WHEN LOWER(TRIM(participants.gender))
                        IN ('p', 'perempuan')
                        THEN 'P'
                END AS gender_code,
                COUNT(DISTINCT training_histories.participant_id) AS total
            ")
            ->groupBy('gender_code')
            ->get()
            ->keyBy('gender_code');

        $maleRow = $genderCounts->get('L');
        $femaleRow = $genderCounts->get('P');

        $genderStats = [
            'L' => (int) ($maleRow->total ?? 0),
            'P' => (int) ($femaleRow->total ?? 0),
        ];

        /*
         * REKAP PESERTA UNIK BERDASARKAN WILAYAH.
         */
        $districtRecap = DB::table('training_histories')
            ->join('trainings', 'trainings.id', '=', 'training_histories.training_id')
            ->join('participants', 'participants.id', '=', 'training_histories.participant_id')
            ->whereYear('trainings.start_date', $year)
            ->whereNotNull('participants.district')
            ->whereRaw("TRIM(participants.district) != ''")
            ->selectRaw("
                TRIM(participants.district) AS name,
                COUNT(DISTINCT training_histories.participant_id) AS total
            ")
            ->groupByRaw('TRIM(participants.district)')
            ->orderByDesc('total')
            ->orderBy('name')
            ->get();

        /*
         * JUMLAH KEIKUTSERTAAN SETIAP PESERTA.
         */
        $frequentParticipants = Participant::query()
            ->whereHas('histories', function ($query) use ($year) {
                $query->whereHas('training', function ($trainingQuery) use ($year) {
                    $trainingQuery->whereYear('start_date', $year);
                });
            })
            ->withCount([
                'histories as participations_count' => function ($query) use ($year) {
                    $query->whereHas('training', function ($trainingQuery) use ($year) {
                        $trainingQuery->whereYear('start_date', $year);
                    });
                },
            ])
            ->orderBy('participations_count', $sort)
            ->orderBy('name')
            ->paginate(8, ['*'], 'participant_page')
            ->withQueryString();

        /*
         * DISTRIBUSI KATEGORI PELATIHAN.
         */
        $fieldStats = DB::table('trainings')
            ->leftJoin(
                'training_categories',
                'training_categories.id',
                '=',
                'trainings.training_category_id'
            )
            ->whereYear('trainings.start_date', $year)
            ->whereNotNull('training_categories.name')
            ->where('training_categories.name', '!=', '')
            ->selectRaw("
                training_categories.name AS name,
                COUNT(trainings.id) AS total
            ")
            ->groupBy(
                'training_categories.id',
                'training_categories.name'
            )
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        /*
         * PILIHAN PROFESI.
         */
        $professionOptions = HealthWorkerType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name');

        /*
         * PESERTA TERLATIH BERDASARKAN PROFESI.
         */
        $trainedParticipants = collect();

        $professionExists = $selectedProfession !== ''
            && $professionOptions->contains(function ($profession) use ($selectedProfession) {
                return mb_strtolower(trim((string) $profession))
                    === mb_strtolower($selectedProfession);
            });

        if ($professionExists && $totalTrainings > 0) {
            $trainedParticipants = Participant::query()
                ->whereRaw('LOWER(TRIM(profession)) = ?', [
                    mb_strtolower($selectedProfession),
                ])
                ->whereHas('histories', function ($query) use ($year) {
                    $query->whereHas('training', function ($trainingQuery) use ($year) {
                        $trainingQuery->whereYear('start_date', $year);
                    });
                })
                ->withCount([
                    'histories as participations_count' => function ($query) use ($year) {
                        $query->whereHas('training', function ($trainingQuery) use ($year) {
                            $trainingQuery->whereYear('start_date', $year);
                        });
                    },
                ])
                ->orderBy('name')
                ->paginate(10, ['*'], 'profession_page')
                ->withQueryString();
        } else {
            $selectedProfession = '';
        }

        $recentHistories = collect();

        /*
         * DATA UNTUK VIEW.
         */
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
            'trainedParticipants',
            'genderStats',
            'districtRecap'
        );

        if ($role === 'admin') {
            return view('dashboard', $data);
        }

        return view('dashboards.pimpinan', $data);
    }

    /**
     * Mengambil daftar tahun aktif dari master data Super Admin.
     */
    private function getActiveTrainingYears()
    {
        return TrainingYear::query()
            ->where('is_active', true)
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->values();
    }

    /**
     * Mengambil tahun yang memiliki data pelatihan.
     * Digunakan khusus untuk dashboard publik.
     */
    private function getAvailableTrainingYears()
    {
        $yearsQuery = Training::query()
            ->whereNotNull('start_date');

        if (DB::connection()->getDriverName() === 'sqlite') {
            $yearsQuery->selectRaw(
                "CAST(strftime('%Y', start_date) AS INTEGER) AS year"
            );
        } else {
            $yearsQuery->selectRaw('YEAR(start_date) AS year');
        }

        return $yearsQuery
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year)
            ->values();
    }
}