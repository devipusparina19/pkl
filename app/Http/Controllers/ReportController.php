<?php

namespace App\Http\Controllers;

use App\Exports\TrainingHistoryExport;
use App\Models\Participant;
use App\Models\Training;
use App\Models\TrainingHistory;
use App\Models\TrainingYear;
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
        $year = (int) $request->input('year', now()->year);
        $minimum = max(1, min(100, (int) $request->input('minimum', 4)));
        $search = trim((string) $request->input('search', ''));

        /*
         * PILIHAN TAHUN DARI MASTER DATA SUPER ADMIN
         */
        $years = TrainingYear::query()
            ->where('is_active', true)
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($item) => (int) $item);

        // Jika master tahun belum diisi, ambil tahun dari data pelatihan.
        if ($years->isEmpty()) {
            $years = Training::query()
                ->whereNotNull('start_date')
                ->selectRaw('YEAR(start_date) as year')
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
         * QUERY RIWAYAT PELATIHAN PADA TAHUN TERPILIH
         */
        $historyInYear = function ($query) use ($year) {
            $query->whereHas('training', function ($trainingQuery) use ($year) {
                $trainingQuery->whereYear('start_date', $year);
            });
        };

        /*
         * TABEL PESERTA
         *
         * Hanya peserta yang benar-benar memiliki riwayat
         * pelatihan pada tahun yang dipilih.
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
         * TOTAL KEIKUTSERTAAN
         *
         * Satu riwayat peserta pada satu pelatihan dihitung
         * sebagai satu keikutsertaan.
         */
        $totalParticipations = TrainingHistory::query()
            ->whereHas('training', function ($query) use ($year) {
                $query->whereYear('start_date', $year);
            })
            ->count();

        /*
         * TOTAL PESERTA UNIK YANG SUDAH MENGIKUTI PELATIHAN
         */
        $totalTrainedParticipants = TrainingHistory::query()
            ->whereHas('training', function ($query) use ($year) {
                $query->whereYear('start_date', $year);
            })
            ->distinct('participant_id')
            ->count('participant_id');

        /*
         * JUMLAH PESERTA YANG PERLU DITINJAU
         *
         * Hanya peserta dengan keikutsertaan pada tahun terpilih
         * dan jumlahnya mencapai batas minimum.
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
         *
         * Menghitung jumlah pelatihan berdasarkan kolom trainings.district.
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
         * PARTISIPASI TENAGA KESEHATAN BERDASARKAN PROFESI
         *
         * Peserta dihitung satu kali per jenis profesi,
         * walaupun mengikuti beberapa pelatihan pada tahun yang sama.
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
            ->get();

        /*
         * NORMALISASI NAMA PROFESI MENGGUNAKAN MASTER DATA
         */
        $professionNames = HealthWorkerType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name')
            ->mapWithKeys(function ($name) {
                $cleanName = trim((string) $name);

                return [
                    mb_strtolower($cleanName) => $cleanName,
                ];
            });

        $nakesByType = $nakesCounts
            ->map(function ($item) use ($professionNames) {
                $key = mb_strtolower(trim((string) $item->name));

                return (object) [
                    'name' => $professionNames->get($key, trim((string) $item->name)),
                    'total' => (int) $item->total,
                ];
            })
            ->sortByDesc('total')
            ->values();

        /*
         * 5 PROFESI DENGAN PESERTA TERBANYAK
         */
        $topNakes = $nakesByType
            ->take(5)
            ->values();

        /*
         * 5 PROFESI DENGAN PESERTA PALING SEDIKIT
         *
         * Hanya profesi yang benar-benar memiliki peserta
         * pada tahun terpilih yang ditampilkan.
         */
        $bottomNakes = $nakesByType
            ->sortBy('total')
            ->take(5)
            ->values();

        /*
         * TOTAL PELATIHAN PADA TAHUN TERPILIH
         */
        $totalTrainings = Training::query()
            ->whereYear('start_date', $year)
            ->count();

        return view('reports.index', compact(
            'year',
            'years',
            'minimum',
            'search',
            'participants',
            'totalParticipations',
            'totalTrainedParticipants',
            'participantsToReview',
            'totalTrainings',
            'districtRecap',
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
}