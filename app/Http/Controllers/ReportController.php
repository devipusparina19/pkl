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
         * FILTER TAHUN
         * Menggunakan daftar tahun yang benar-benar memiliki data
         * pelatihan, sama seperti Dashboard Admin.
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

        $minimum = max(1, min(100, (int) $request->input('minimum', 4)));
        $search = trim((string) $request->input('search', ''));

        /*
         * QUERY PELATIHAN TAHUN TERPILIH
         */
        $trainingIds = Training::query()
            ->whereYear('start_date', $year)
            ->select('id');

        /*
         * TOTAL PELATIHAN
         * Sama dengan Dashboard Admin.
         */
        $totalTrainings = Training::query()
            ->whereYear('start_date', $year)
            ->count();

        /*
         * TOTAL KEIKUTSERTAAN
         * Satu baris training_histories dihitung satu keikutsertaan.
         */
        $totalParticipations = TrainingHistory::query()
            ->whereIn('training_id', $trainingIds)
            ->count();

        /*
         * TOTAL PESERTA TERLATIH
         * Peserta yang sama hanya dihitung satu kali dalam satu tahun.
         */
        $totalTrainedParticipants = TrainingHistory::query()
            ->whereIn('training_id', $trainingIds)
            ->distinct()
            ->count('participant_id');

        /*
         * STATISTIK JENIS KELAMIN
         * Mengikuti rumus Dashboard Admin:
         * peserta dihitung unik dan variasi penulisan gender dinormalisasi.
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
         * FILTER RIWAYAT PESERTA TAHUN TERPILIH
         */
        $historyInYear = function ($query) use ($year) {
            $query->whereHas('training', function ($trainingQuery) use ($year) {
                $trainingQuery->whereYear('start_date', $year);
            });
        };

        /*
         * TABEL PESERTA
         * Hanya peserta yang memiliki riwayat pada tahun terpilih.
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
         * PESERTA YANG PERLU DITINJAU
         * Berdasarkan batas minimal jumlah keikutsertaan.
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
         * REKAP KABUPATEN / KOTA / MITRA / MANDIRI
         * Menghitung jumlah kegiatan pelatihan per wilayah.
         */
        $districtRecap = Training::query()
            ->whereYear('start_date', $year)
            ->whereNotNull('district')
            ->whereRaw("TRIM(district) != ''")
            ->selectRaw('TRIM(district) as name, COUNT(*) as total')
            ->groupByRaw('TRIM(district)')
            ->orderByDesc('total')
            ->orderBy('name')
            ->get();

        /*
         * PESERTA UNIK BERDASARKAN PROFESI
         * Sama seperti Dashboard Admin.
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
                TRIM(participants.profession) as name,
                COUNT(DISTINCT training_histories.participant_id) as total
            ")
            ->groupByRaw('TRIM(participants.profession)')
            ->orderByDesc('total')
            ->get()
            ->keyBy(fn ($item) => mb_strtolower(trim($item->name)));

        /*
         * GABUNGKAN MASTER PROFESI DAN PROFESI DALAM DATA PESERTA.
         * Profesi yang belum memiliki peserta tetap ditampilkan dengan nilai 0.
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
         * 5 PROFESI DENGAN PESERTA TERBANYAK.
         */
        $topNakes = $nakesByType
            ->filter(fn ($item) => $item->total > 0)
            ->sortByDesc('total')
            ->take(5)
            ->values();

        /*
         * 5 PROFESI DENGAN PESERTA PALING SEDIKIT.
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
     * Ekspor laporan ke Excel.
     */
    public function export(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $search = trim((string) $request->input('search', ''));

        $filename = 'laporan-riwayat-pelatihan-' . $year . '.xlsx';

        return Excel::download(
            new TrainingHistoryExport($year, $search),
            $filename
        );
    }

    /**
     * Mengambil tahun yang benar-benar memiliki data pelatihan.
     * Dibuat sama dengan DashboardController.
     */
    private function getAvailableTrainingYears()
    {
        $yearsQuery = Training::query()
            ->whereNotNull('start_date');

        if (DB::connection()->getDriverName() === 'sqlite') {
            $yearsQuery->selectRaw(
                "CAST(strftime('%Y', start_date) AS INTEGER) as year"
            );
        } else {
            $yearsQuery->selectRaw('YEAR(start_date) as year');
        }

        return $yearsQuery
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($item) => (int) $item)
            ->values();
    }
}