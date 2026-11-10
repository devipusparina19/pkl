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
         * DAFTAR TAHUN OTOMATIS DARI DATABASE.
         */
        $years = $this->getAvailableTrainingYears();

        /*
         * TAHUN TERPILIH.
         * Default menggunakan tahun terbaru yang memiliki data.
         */
        $year = $this->resolveYear($request, $years);

        $minimum = max(1, min(100, (int) $request->input('minimum', 4)));
        $search = trim((string) $request->input('search', ''));

        /*
         * ID PELATIHAN PADA TAHUN TERPILIH.
         */
        $trainingIds = Training::query()
            ->whereYear('start_date', $year)
            ->select('id');

        /*
         * TOTAL PELATIHAN.
         */
        $totalTrainings = Training::query()
            ->whereYear('start_date', $year)
            ->count();

        /*
         * TOTAL KEIKUTSERTAAN.
         */
        $totalParticipations = TrainingHistory::query()
            ->whereIn('training_id', $trainingIds)
            ->count();

        /*
         * TOTAL PESERTA UNIK.
         */
        $totalTrainedParticipants = TrainingHistory::query()
            ->whereIn('training_id', $trainingIds)
            ->distinct()
            ->count('participant_id');

        /*
         * STATISTIK JENIS KELAMIN.
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
         * FILTER RIWAYAT BERDASARKAN TAHUN TERPILIH.
         */
        $historyInYear = function ($query) use ($year) {
            $query->whereHas('training', function ($trainingQuery) use ($year) {
                $trainingQuery->whereYear('start_date', $year);
            });
        };

        /*
         * DATA PESERTA PADA TAHUN TERPILIH.
         */
        $participantQuery = Participant::query()
            ->whereHas('histories', $historyInYear)
            ->withCount([
                'histories as participations_count' => $historyInYear,
            ]);

        if ($search !== '') {
            $participantQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $participants = $participantQuery
            ->orderByDesc('participations_count')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        /*
         * PESERTA YANG PERLU DITINJAU.
         */
        $participantsToReviewQuery = Participant::query()
            ->whereHas('histories', $historyInYear)
            ->withCount([
                'histories as participations_count' => $historyInYear,
            ])
            ->having('participations_count', '>=', $minimum);

        if ($search !== '') {
            $participantsToReviewQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $participantsToReview = $participantsToReviewQuery->count();

        /*
         * REKAP PESERTA BERDASARKAN WILAYAH.
         *
         * Sumber wilayah peserta:
         * participants.district
         *
         * Bukan trainings.district.
         *
         * Satu peserta dihitung satu kali pada wilayah
         * terbarunya dalam tahun yang dipilih.
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
         * REKAP PESERTA BERDASARKAN PROFESI.
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
         * GABUNGKAN MASTER PROFESI DAN DATA PESERTA.
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
         * 5 PROFESI PESERTA TERBANYAK.
         */
        $topNakes = $nakesByType
            ->filter(fn ($item) => $item->total > 0)
            ->sortByDesc('total')
            ->take(5)
            ->values();

        /*
         * 5 PROFESI PESERTA PALING SEDIKIT.
         */
        $bottomNakes = $nakesByType
            ->filter(fn ($item) => $item->total > 0)
            ->sortBy('total')
            ->take(5)
            ->values();

        return view('reports.index', compact(
            'year',
            'years',
            'minimum',
            'search',
            'participants',
            'totalTrainings',
            'totalParticipations',
            'totalTrainedParticipants',
            'participantsToReview',
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
     * Export laporan Excel berdasarkan tahun terpilih.
     */
    public function export(Request $request)
    {
        $years = $this->getAvailableTrainingYears();

        $year = $this->resolveYear($request, $years);

        $search = trim((string) $request->input('search', ''));

        $filename = 'laporan-riwayat-pelatihan-' . $year . '.xlsx';

        return Excel::download(
            new TrainingHistoryExport($year, $search),
            $filename
        );
    }

    /**
     * Mengambil seluruh tahun yang memiliki data pelatihan.
     *
     * Tahun diambil dari trainings.start_date.
     * Hasil diurutkan dari tahun terbaru ke terlama.
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
     * Menentukan tahun yang digunakan halaman atau export.
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