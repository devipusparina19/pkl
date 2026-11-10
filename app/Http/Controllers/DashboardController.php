<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
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
        /*
         * PILIHAN TAHUN.
         */
        $years = $this->getAvailableTrainingYears();

        $requestedYear = (int) $request->input('year', now()->year);

        if ($years->isNotEmpty()) {
            $year = $years->contains($requestedYear)
                ? $requestedYear
                : (int) $years->first();
        } else {
            $year = $requestedYear ?: now()->year;
        }

        /*
         * FILTER WILAYAH DAN SUMBER DANA.
         *
         * district mengacu ke participants.district,
         * bukan trainings.district.
         */
        $district = trim((string) $request->input('district', ''));
        $fundingSource = trim((string) $request->input('funding_source', ''));

        $district = $district !== '' ? $district : null;
        $fundingSource = $fundingSource !== '' ? $fundingSource : null;

        /*
         * QUERY DASAR PELATIHAN.
         *
         * Tahun dan sumber dana berasal dari data pelatihan.
         * Filter wilayah dilakukan melalui data peserta.
         */
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

        /*
         * STATISTIK UTAMA.
         *
         * Jika filter wilayah dipilih, jumlah pelatihan hanya menghitung
         * pelatihan yang memiliki peserta dari wilayah tersebut.
         */
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

        /*
         * PILIHAN KABUPATEN/KOTA/MITRA/MANDIRI.
         *
         * Menggabungkan master wilayah aktif dan wilayah yang masih
         * tercatat pada data peserta, termasuk data lama.
         */
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

        /*
         * PILIHAN SUMBER DANA.
         */
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

        /*
         * JUMLAH PESERTA UNIK BERDASARKAN KABUPATEN/KOTA/MITRA/MANDIRI.
         *
         * Sumber wilayah: participants.district.
         * Peserta yang mengikuti beberapa pelatihan tetap dihitung satu kali.
         */
        $byDistrict = DB::table('training_histories')
            ->join(
                'trainings',
                'trainings.id',
                '=',
                'training_histories.training_id'
            )
            ->join(
                'participants',
                'participants.id',
                '=',
                'training_histories.participant_id'
            )
            ->whereYear('trainings.start_date', $year)
            ->when($fundingSource, function ($query) use ($fundingSource) {
                $query->where('trainings.funding_source', $fundingSource);
            })
            ->when($district, function ($query) use ($district) {
                $query->whereRaw(
                    'TRIM(participants.district) = ?',
                    [$district]
                );
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

        /*
         * PERTAHANKAN NAMA VARIABEL UNTUK KOMPATIBILITAS VIEW.
         *
         * Catatan: byDistrict dan trainingByDistrict sekarang berisi
         * jumlah peserta unik per wilayah, bukan jumlah pelatihan.
         */
        $trainingByDistrict = $byDistrict;

        /*
         * PELATIHAN TERBARU.
         *
         * Jika filter wilayah dipilih, tampilkan hanya pelatihan
         * yang memiliki peserta dari wilayah tersebut.
         */
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

        /*
         * MASTER JENIS TENAGA KESEHATAN.
         */
        $healthWorkerTypes = HealthWorkerType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        /*
         * JUMLAH PESERTA UNIK BERDASARKAN PROFESI.
         */
        $nakesCounts = DB::table('training_histories')
            ->join(
                'trainings',
                'trainings.id',
                '=',
                'training_histories.training_id'
            )
            ->join(
                'participants',
                'participants.id',
                '=',
                'training_histories.participant_id'
            )
            ->whereYear('trainings.start_date', $year)
            ->when($fundingSource, function ($query) use ($fundingSource) {
                $query->where('trainings.funding_source', $fundingSource);
            })
            ->when($district, function ($query) use ($district) {
                $query->whereRaw(
                    'TRIM(participants.district) = ?',
                    [$district]
                );
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

        /*
         * GABUNGKAN MASTER PROFESI DAN PROFESI DALAM DATA PESERTA.
         */
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

        /*
         * LIMA PROFESI DENGAN JUMLAH PESERTA TERBANYAK.
         */
        $topNakes = $nakesByType
            ->filter(fn ($item) => $item->total > 0)
            ->sortByDesc('total')
            ->take(5)
            ->values();

        /*
         * LIMA PROFESI DENGAN JUMLAH PESERTA PALING SEDIKIT.
         */
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
         * FILTER TAHUN DAN URUTAN.
         */
        $requestedYear = (int) $request->input('year', now()->year);

        $sort = $request->input('sort', 'desc');

        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'desc';
        }

        $selectedProfession = trim(
            (string) $request->input('profession', '')
        );

        /*
         * PILIHAN TAHUN.
         */
        $years = $this->getAvailableTrainingYears();

        if ($years->isNotEmpty()) {
            $year = $years->contains($requestedYear)
                ? $requestedYear
                : (int) $years->first();
        } else {
            $year = $requestedYear ?: now()->year;
        }

        /*
         * QUERY PELATIHAN TAHUN TERPILIH.
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
         * Peserta dihitung satu kali pada tahun terpilih.
         */
        $genderCounts = DB::table('training_histories')
            ->join(
                'trainings',
                'trainings.id',
                '=',
                'training_histories.training_id'
            )
            ->join(
                'participants',
                'participants.id',
                '=',
                'training_histories.participant_id'
            )
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
         * REKAP PESERTA UNIK BERDASARKAN KABUPATEN/KOTA/MITRA/MANDIRI.
         *
         * Sumber wilayah selalu participants.district.
         */
        $districtRecap = DB::table('training_histories')
            ->join(
                'trainings',
                'trainings.id',
                '=',
                'training_histories.training_id'
            )
            ->join(
                'participants',
                'participants.id',
                '=',
                'training_histories.participant_id'
            )
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
         * STATISTIK BIDANG/KATEGORI PELATIHAN.
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
            ->selectRaw('
                training_categories.name AS name,
                COUNT(trainings.id) AS total
            ')
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

        /*
         * RIWAYAT TERBARU.
         * Variabel dipertahankan untuk kompatibilitas view.
         */
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
     * Mengambil tahun yang benar-benar memiliki data pelatihan.
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
            ->map(fn ($item) => (int) $item)
            ->values();
    }
}