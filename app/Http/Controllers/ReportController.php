<?php

namespace App\Http\Controllers;

use App\Exports\TrainingHistoryExport;
use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use App\Models\HealthWorkerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Halaman laporan pelatihan.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER UTAMA
        |--------------------------------------------------------------------------
        */

        $years = $this->getAvailableTrainingYears();

        $year = $this->resolveYear($request, $years);

        // Default 1 agar semua peserta yang memiliki riwayat pelatihan tampil.
        $minimum = max(
            1,
            min(100, (int) $request->input('minimum', 1))
        );

        $search = trim((string) $request->input('search', ''));

        $districtFilter = trim(
            (string) $request->input('district', '')
        );

        $institutionFilter = trim(
            (string) $request->input('institution', '')
        );


        /*
        |--------------------------------------------------------------------------
        | PILIHAN FILTER KABUPATEN/KOTA DAN INSTANSI
        |--------------------------------------------------------------------------
        */

        $districts = Participant::query()
            ->whereNotNull('district')
            ->whereRaw("TRIM(district) != ''")
            ->selectRaw('TRIM(district) AS name')
            ->distinct()
            ->orderBy('name')
            ->pluck('name');

        $institutions = Participant::query()
            ->whereNotNull('institution')
            ->whereRaw("TRIM(institution) != ''")
            ->selectRaw('TRIM(institution) AS name')
            ->distinct()
            ->orderBy('name')
            ->pluck('name');


        /*
        |--------------------------------------------------------------------------
        | QUERY FILTER PESERTA
        |--------------------------------------------------------------------------
        */

        $historyInYear = function ($query) use ($year) {
            $query->whereHas('training', function ($trainingQuery) use ($year) {
                $trainingQuery->whereYear('start_date', $year);
            });
        };

        $applyParticipantFilters = function ($query) use (
            $search,
            $districtFilter,
            $institutionFilter
        ) {
            if ($districtFilter !== '') {
                $query->whereRaw(
                    'TRIM(participants.district) = ?',
                    [$districtFilter]
                );
            }

            if ($institutionFilter !== '') {
                $query->whereRaw(
                    'TRIM(participants.institution) = ?',
                    [$institutionFilter]
                );
            }

            if ($search !== '') {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('participants.name', 'like', "%{$search}%")
                        ->orWhere('participants.nik', 'like', "%{$search}%")
                        ->orWhere('participants.nip', 'like', "%{$search}%");
                });
            }

            return $query;
        };


        /*
        |--------------------------------------------------------------------------
        | TOTAL PELATIHAN PADA TAHUN TERPILIH
        |--------------------------------------------------------------------------
        */

        $totalTrainings = Training::query()
            ->whereYear('start_date', $year)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | DAFTAR PESERTA DAN JUMLAH KEIKUTSERTAAN
        |--------------------------------------------------------------------------
        */

        $participantQuery = Participant::query()
            ->whereHas('histories', $historyInYear)
            ->withCount([
                'histories as participations_count' => $historyInYear,
            ]);

        $applyParticipantFilters($participantQuery);

        $participants = $participantQuery
            ->orderByDesc('participations_count')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | TOTAL PESERTA UNIK DAN TOTAL KEIKUTSERTAAN
        |--------------------------------------------------------------------------
        */

        $summaryQuery = Participant::query()
            ->whereHas('histories', $historyInYear);

        $applyParticipantFilters($summaryQuery);

        $filteredParticipantIds = (clone $summaryQuery)->select('participants.id');

        $totalTrainedParticipants = (clone $summaryQuery)->count();

        $totalParticipations = TrainingHistory::query()
            ->whereIn('participant_id', $filteredParticipantIds)
            ->whereHas('training', function ($query) use ($year) {
                $query->whereYear('start_date', $year);
            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK JENIS KELAMIN
        |--------------------------------------------------------------------------
        */

        $genderQuery = DB::table('training_histories')
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
            ->whereYear('trainings.start_date', $year);

        if ($districtFilter !== '') {
            $genderQuery->whereRaw(
                'TRIM(participants.district) = ?',
                [$districtFilter]
            );
        }

        if ($institutionFilter !== '') {
            $genderQuery->whereRaw(
                'TRIM(participants.institution) = ?',
                [$institutionFilter]
            );
        }

        if ($search !== '') {
            $genderQuery->where(function ($query) use ($search) {
                $query->where('participants.name', 'like', "%{$search}%")
                    ->orWhere('participants.nik', 'like', "%{$search}%")
                    ->orWhere('participants.nip', 'like', "%{$search}%");
            });
        }

        $genderCounts = $genderQuery
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

        $genderStats = [
            'L' => (int) ($genderCounts->get('L')->total ?? 0),
            'P' => (int) ($genderCounts->get('P')->total ?? 0),
        ];

        $maleCount = $genderStats['L'];
        $femaleCount = $genderStats['P'];

        $genderTotal = $maleCount + $femaleCount;

        $malePercent = $genderTotal > 0
            ? round(($maleCount / $genderTotal) * 100, 1)
            : 0;

        $femalePercent = $genderTotal > 0
            ? round(($femaleCount / $genderTotal) * 100, 1)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | REKAP PESERTA BERDASARKAN KABUPATEN/KOTA
        |--------------------------------------------------------------------------
        */

        $districtQuery = DB::table('training_histories')
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
            ->whereRaw("TRIM(participants.district) != ''");

        if ($districtFilter !== '') {
            $districtQuery->whereRaw(
                'TRIM(participants.district) = ?',
                [$districtFilter]
            );
        }

        if ($institutionFilter !== '') {
            $districtQuery->whereRaw(
                'TRIM(participants.institution) = ?',
                [$institutionFilter]
            );
        }

        if ($search !== '') {
            $districtQuery->where(function ($query) use ($search) {
                $query->where('participants.name', 'like', "%{$search}%")
                    ->orWhere('participants.nik', 'like', "%{$search}%")
                    ->orWhere('participants.nip', 'like', "%{$search}%");
            });
        }

        $districtRecap = $districtQuery
            ->selectRaw("
                TRIM(participants.district) AS name,
                COUNT(DISTINCT training_histories.participant_id) AS total
            ")
            ->groupByRaw('TRIM(participants.district)')
            ->orderByDesc('total')
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | REKAP PESERTA BERDASARKAN PROFESI
        |--------------------------------------------------------------------------
        */

        $professionQuery = DB::table('training_histories')
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
            ->whereNotNull('participants.profession')
            ->whereRaw("TRIM(participants.profession) != ''");

        if ($districtFilter !== '') {
            $professionQuery->whereRaw(
                'TRIM(participants.district) = ?',
                [$districtFilter]
            );
        }

        if ($institutionFilter !== '') {
            $professionQuery->whereRaw(
                'TRIM(participants.institution) = ?',
                [$institutionFilter]
            );
        }

        if ($search !== '') {
            $professionQuery->where(function ($query) use ($search) {
                $query->where('participants.name', 'like', "%{$search}%")
                    ->orWhere('participants.nik', 'like', "%{$search}%")
                    ->orWhere('participants.nip', 'like', "%{$search}%");
            });
        }

        $nakesCounts = $professionQuery
            ->selectRaw("
                TRIM(participants.profession) AS name,
                COUNT(DISTINCT training_histories.participant_id) AS total
            ")
            ->groupByRaw('TRIM(participants.profession)')
            ->orderByDesc('total')
            ->get()
            ->keyBy(fn ($item) => mb_strtolower(trim($item->name)));


        /*
        |--------------------------------------------------------------------------
        | GABUNGKAN MASTER PROFESI DAN DATA PESERTA
        |--------------------------------------------------------------------------
        */

        $professionNames = collect();

        $healthWorkerTypes = HealthWorkerType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        foreach ($healthWorkerTypes as $type) {
            $name = trim((string) $type->name);

            if ($name !== '') {
                $professionNames->put(mb_strtolower($name), $name);
            }
        }

        foreach ($nakesCounts as $key => $item) {
            $name = trim((string) $item->name);

            if ($name !== '' && !$professionNames->has($key)) {
                $professionNames->put($key, $name);
            }
        }

        $nakesByType = $professionNames
            ->map(function ($name, $key) use ($nakesCounts) {
                $count = $nakesCounts->get($key);

                return (object) [
                    'name' => $name,
                    'total' => $count ? (int) $count->total : 0,
                ];
            })
            ->sortByDesc('total')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | PROFESI TERBANYAK DAN PALING SEDIKIT
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE BLADE
        |--------------------------------------------------------------------------
        */

        return view('reports.index', compact(
            'year',
            'years',
            'minimum',
            'search',
            'districtFilter',
            'institutionFilter',
            'districts',
            'institutions',
            'participants',
            'totalTrainings',
            'totalParticipations',
            'totalTrainedParticipants',
            'genderStats',
            'maleCount',
            'femaleCount',
            'genderTotal',
            'malePercent',
            'femalePercent',
            'districtRecap',
            'nakesByType',
            'topNakes',
            'bottomNakes'
        ));
    }


    /**
     * Export laporan Excel.
     */
    public function export(Request $request)
    {
        $years = $this->getAvailableTrainingYears();

        $year = $this->resolveYear($request, $years);

        $minimum = max(
            1,
            min(100, (int) $request->input('minimum', 1))
        );

        $search = trim((string) $request->input('search', ''));

        $districtFilter = trim(
            (string) $request->input('district', '')
        );

        $institutionFilter = trim(
            (string) $request->input('institution', '')
        );

        $filename = 'laporan-riwayat-pelatihan-' . $year . '.xlsx';

        return Excel::download(
            new TrainingHistoryExport(
                $year,
                $search,
                $minimum,
                $districtFilter,
                $institutionFilter
            ),
            $filename
        );
    }


    /**
     * Mengambil seluruh tahun pelatihan yang tersedia.
     */
    private function getAvailableTrainingYears()
    {
        $query = Training::query()
            ->whereNotNull('start_date');

        if (DB::connection()->getDriverName() === 'sqlite') {
            $query->selectRaw(
                "CAST(strftime('%Y', start_date) AS INTEGER) AS year"
            );
        } else {
            $query->selectRaw('YEAR(start_date) AS year');
        }

        return $query
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter(fn ($year) => (int) $year > 0)
            ->map(fn ($year) => (int) $year)
            ->values();
    }


    /**
     * Menentukan tahun laporan.
     */
    private function resolveYear(Request $request, $years): int
    {
        $requestedYear = (int) $request->input('year');

        if ($years->isEmpty()) {
            return $requestedYear > 0
                ? $requestedYear
                : (int) now()->year;
        }

        if ($requestedYear > 0 && $years->contains($requestedYear)) {
            return $requestedYear;
        }

        return (int) $years->first();
    }
}