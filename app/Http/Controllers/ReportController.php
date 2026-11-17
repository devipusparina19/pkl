<?php

namespace App\Http\Controllers;

use App\Exports\ReportWorkbookExport;
use App\Models\HealthWorkerType;
use App\Models\Participant;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private const FUNDING_EXPR = "COALESCE(NULLIF(TRIM(trainings.funding_source), ''), 'Tidak disebutkan')";

    private const CATEGORY_EXPR = "COALESCE(NULLIF(TRIM(training_categories.name), ''), 'Tidak berkategori')";

    private const MAX_DETAIL_ROWS = 500;

    /** Jenis ekspor yang didukung. */
    public const EXPORT_SUMMARY = 'summary';
    public const EXPORT_FULL = 'full';

    private const TRAINING_EXTRA_FIELDS = [
        'organizer'     => 'Penyelenggara',
        'penyelenggara' => 'Penyelenggara',
        'batch'         => 'Angkatan',
        'angkatan'      => 'Angkatan',
        'hours'         => 'Jam pelajaran',
        'jp'            => 'Jam pelajaran',
        'total_hours'   => 'Jam pelajaran',
        'duration'      => 'Durasi',
        'quota'         => 'Kuota',
        'kuota'         => 'Kuota',
        'status'        => 'Status',
        'description'   => 'Deskripsi',
        'deskripsi'     => 'Deskripsi',
        'notes'         => 'Catatan',
        'keterangan'    => 'Keterangan',
    ];

    private ?array $trainingColumnCache = null;

    /* ============================================================
     | HALAMAN LAPORAN
     * ============================================================ */

    public function index(Request $request)
    {
        $years = $this->getAvailableTrainingYears();
        $year = $this->resolveYear($request, $years);

        $filters = $this->filtersFrom($request, $year);

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

        $participants = $this->participantQuery($filters)
            ->paginate(15)
            ->withQueryString();

        return view('reports.index', array_merge(
            $this->summaryData($filters),
            [
                'year'              => $year,
                'years'             => $years,
                'minimum'           => $filters['minimum'],   // null = semua
                'search'            => $filters['search'],
                'districtFilter'    => $filters['district'],
                'institutionFilter' => $filters['institution'],
                'districts'         => $districts,
                'institutions'      => $institutions,
                'participants'      => $participants,
                'frequencyRecap'    => $this->frequencyRecap($filters),
            ]
        ));
    }

    /* ============================================================
     | EKSPOR EXCEL
     | Parameter ?type=summary | full (default: full, perilaku lama).
     * ============================================================ */

    public function export(Request $request)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(180);

        $years = $this->getAvailableTrainingYears();
        $year = $this->resolveYear($request, $years);

        $filters = $this->filtersFrom($request, $year);
        $type = $this->resolveType($request);

        if ($type === self::EXPORT_SUMMARY) {
            // Ringkasan: tanpa identitas peserta, tidak memuat data peserta sama sekali.
            $data = $this->summaryData($filters);
            $data['trainingList'] = collect();
            $data['participantsAll'] = collect();
            $data['historyRows'] = collect();
        } else {
            $data = $this->exportData($filters);
            $data['historyRows'] = $this->historyRows($filters);
        }

        $data['frequencyRows'] = $this->frequencyRows($filters);

        return Excel::download(
            new ReportWorkbookExport(
                $this->workbookPayload($data, $filters, $type)
            ),
            $this->exportFilename($type, $year, 'xlsx')
        );
    }

    /* ============================================================
     | EKSPOR PDF
     | Parameter ?type=summary | full (default: full, perilaku lama).
     * ============================================================ */

    public function pdf(Request $request)
    {
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            abort(501, 'Paket barryvdh/laravel-dompdf belum terpasang.');
        }

        @ini_set('memory_limit', '1024M');
        @set_time_limit(300);

        $years = $this->getAvailableTrainingYears();
        $year = $this->resolveYear($request, $years);

        $filters = $this->filtersFrom($request, $year);
        $type = $this->resolveType($request);

        $frequencyRows = $this->frequencyRows($filters);

        /* ---------- RINGKASAN & LENGKAP ----------
         * Isi PDF mengikuti payload Excel yang sama, sehingga:
         *  - Ringkasan : statistik + rekap, tanpa identitas peserta
         *  - Lengkap   : semua isi ringkasan + daftar peserta + rincian
         * Keduanya memakai satu view: reports.pdf
         */

        if ($type === self::EXPORT_SUMMARY) {
            // Ringkasan: tidak memuat data peserta sama sekali.
            $data = $this->summaryData($filters);
            $data['trainingList'] = collect();
            $data['participantsAll'] = collect();
            $data['historyRows'] = collect();
        } else {
            $data = $this->exportData($filters);
            $data['historyRows'] = $this->historyRows($filters);
        }

        $data['frequencyRows'] = $frequencyRows;

        $report = $this->workbookPayload($data, $filters, $type);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', [
            'year'        => $year,
            'report'      => $report,
            'generatedAt' => Carbon::now('Asia/Makassar')->format('d/m/Y H:i') . ' WITA',
        ])->setPaper('a4', 'portrait');

        return $pdf->download($this->exportFilename($type, $year, 'pdf'));
    }

    /* ============================================================
     | DATA LAPORAN
     | Digunakan halaman, Excel, dan PDF.
     | Semua angka berasal dari historyQuery() sehingga ikut filter
     | (termasuk filter jumlah keikutsertaan) dan selalu sinkron.
     * ============================================================ */

    private function summaryData(array $filters): array
    {
        $year = $filters['year'];

        // Total kegiatan: bila filter jumlah keikutsertaan aktif,
        // hitung hanya kegiatan yang diikuti peserta yang lolos filter.
        $totalTrainings = $filters['minimum'] !== null
            ? (int) $this->historyQuery($filters)->distinct()->count('trainings.id')
            : Training::query()->whereYear('start_date', $year)->count();

        $totalTrainedParticipants = (int) $this->historyQuery($filters)
            ->distinct()
            ->count('training_histories.participant_id');

        $totalParticipations = $this->historyQuery($filters)
            ->distinct()
            ->count('training_histories.id');

        /*
         * JENIS KELAMIN
         */

        $genderBase = $this->historyQuery($filters)
            ->whereNotNull('participants.gender')
            ->selectRaw("
                training_histories.participant_id AS participant_id,
                CASE
                    WHEN LOWER(TRIM(participants.gender))
                        IN ('l', 'laki-laki', 'laki laki', 'laki')
                        THEN 'L'

                    WHEN LOWER(TRIM(participants.gender))
                        IN ('p', 'perempuan')
                        THEN 'P'

                    ELSE NULL
                END AS gender_code
            ");

        $genderCounts = DB::query()
            ->fromSub($genderBase, 'gender_data')
            ->selectRaw("
                gender_code,
                COUNT(DISTINCT participant_id) AS total
            ")
            ->whereNotNull('gender_code')
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
         * PROFESI
         */

        $professionBase = $this->historyQuery($filters)
            ->whereNotNull('participants.profession')
            ->whereRaw("TRIM(participants.profession) != ''")
            ->selectRaw("
                training_histories.participant_id AS participant_id,
                TRIM(participants.profession) AS profession_name
            ");

        $nakesCounts = DB::query()
            ->fromSub($professionBase, 'profession_data')
            ->selectRaw("
                profession_name AS name,
                COUNT(DISTINCT participant_id) AS total
            ")
            ->groupBy('profession_name')
            ->orderByDesc('total')
            ->get()
            ->keyBy(
                fn ($item) => mb_strtolower(trim((string) $item->name))
            );

        $professionNames = collect();

        foreach (
            HealthWorkerType::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get() as $type
        ) {
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
                    'name'  => $name,
                    'total' => $count ? (int) $count->total : 0,
                ];
            })
            ->sortByDesc('total')
            ->values();

        /*
         * SUMBER DANA
         */

        $fundingBase = $this->historyQuery($filters, true)
            ->selectRaw("
                trainings.id AS training_id,
                training_histories.id AS history_id,
                training_histories.participant_id AS participant_id,
                " . self::FUNDING_EXPR . " AS funding_source
            ");

        $fundingRecap = DB::query()
            ->fromSub($fundingBase, 'funding_data')
            ->selectRaw("
                funding_source,
                COUNT(DISTINCT training_id) AS total_trainings,
                COUNT(DISTINCT history_id) AS total_participations,
                COUNT(DISTINCT participant_id) AS total_participants
            ")
            ->groupBy('funding_source')
            ->orderByDesc('total_trainings')
            ->orderBy('funding_source')
            ->get();

        /*
         * KATEGORI
         */

        $categoryBase = $this->historyQuery($filters, true)
            ->leftJoin(
                'training_categories',
                'training_categories.id',
                '=',
                'trainings.training_category_id'
            )
            ->selectRaw("
                trainings.training_category_id AS category_id,
                " . self::CATEGORY_EXPR . " AS category_name,
                trainings.id AS training_id,
                training_histories.id AS history_id,
                training_histories.participant_id AS participant_id
            ");

        $categoryRecap = DB::query()
            ->fromSub($categoryBase, 'category_data')
            ->selectRaw("
                category_id,
                category_name,
                COUNT(DISTINCT training_id) AS total_trainings,
                COUNT(DISTINCT history_id) AS total_participations,
                COUNT(DISTINCT participant_id) AS total_participants
            ")
            ->groupBy('category_id', 'category_name')
            ->orderByDesc('total_trainings')
            ->orderBy('category_name')
            ->get();

        /*
         * REKAP PER NAMA PELATIHAN
         */

        $nameExpr = $this->nameExpr();

        $trainingBase = $this->historyQuery($filters, true)
            ->selectRaw("
                trainings.id AS training_id,
                training_histories.id AS history_id,
                training_histories.participant_id AS participant_id,
                {$nameExpr} AS training_name
            ");

        $trainingTotals = DB::query()
            ->fromSub($trainingBase, 'training_data')
            ->selectRaw("
                training_name,
                COUNT(DISTINCT training_id) AS total_trainings,
                COUNT(DISTINCT history_id) AS total_participations,
                COUNT(DISTINCT participant_id) AS total_participants
            ")
            ->groupBy('training_name')
            ->orderByDesc('total_trainings')
            ->orderBy('training_name')
            ->get();

        /*
         * RINCIAN SUMBER DANA PER NAMA PELATIHAN
         */

        $trainingFundingBase = $this->historyQuery($filters, true)
            ->selectRaw("
                trainings.id AS training_id,
                training_histories.participant_id AS participant_id,
                {$nameExpr} AS training_name,
                " . self::FUNDING_EXPR . " AS funding_source
            ");

        $trainingFundingRows = DB::query()
            ->fromSub($trainingFundingBase, 'training_funding_data')
            ->selectRaw("
                training_name,
                funding_source,
                COUNT(DISTINCT training_id) AS total_trainings,
                COUNT(DISTINCT participant_id) AS total_participants
            ")
            ->groupBy('training_name', 'funding_source')
            ->orderBy('training_name')
            ->orderBy('funding_source')
            ->get()
            ->groupBy(
                fn ($row) => mb_strtolower((string) $row->training_name)
            );

        $trainingRecap = $trainingTotals
            ->map(function ($row) use ($trainingFundingRows) {
                $funding = (
                    $trainingFundingRows->get(
                        mb_strtolower((string) $row->training_name)
                    ) ?? collect()
                )
                    ->map(fn ($item) => (object) [
                        'funding_source'     => $item->funding_source,
                        'total_trainings'    => (int) $item->total_trainings,
                        'total_participants' => (int) $item->total_participants,
                    ])
                    ->sortByDesc('total_trainings')
                    ->values();

                return (object) [
                    'name'               => $row->training_name,
                    'total_trainings'    => (int) $row->total_trainings,
                    'total_participants' => (int) $row->total_participants,
                    'funding'            => $funding,
                ];
            })
            ->values();

        /*
         * KABUPATEN/KOTA X SUMBER DANA
         */

        $districtFundingBase = $this->historyQuery($filters)
            ->whereNotNull('participants.district')
            ->whereRaw("TRIM(participants.district) != ''")
            ->selectRaw("
                trainings.id AS training_id,
                training_histories.id AS history_id,
                training_histories.participant_id AS participant_id,
                TRIM(participants.district) AS district,
                " . self::FUNDING_EXPR . " AS funding_source
            ");

        $districtFundingRecap = DB::query()
            ->fromSub($districtFundingBase, 'district_funding_data')
            ->selectRaw("
                district,
                funding_source,
                COUNT(DISTINCT training_id) AS total_trainings,
                COUNT(DISTINCT history_id) AS total_participations,
                COUNT(DISTINCT participant_id) AS total_participants
            ")
            ->groupBy('district', 'funding_source')
            ->orderBy('district')
            ->orderBy('funding_source')
            ->get();

        /*
         * TOTAL PER KABUPATEN/KOTA
         */

        $districtTotalBase = $this->historyQuery($filters)
            ->whereNotNull('participants.district')
            ->whereRaw("TRIM(participants.district) != ''")
            ->selectRaw("
                trainings.id AS training_id,
                training_histories.id AS history_id,
                training_histories.participant_id AS participant_id,
                TRIM(participants.district) AS district
            ");

        $districtFundingTotals = DB::query()
            ->fromSub($districtTotalBase, 'district_total_data')
            ->selectRaw("
                district,
                COUNT(DISTINCT training_id) AS total_trainings,
                COUNT(DISTINCT history_id) AS total_participations,
                COUNT(DISTINCT participant_id) AS total_participants
            ")
            ->groupBy('district')
            ->orderBy('district')
            ->get();

        return compact(
            'totalTrainings',
            'totalTrainedParticipants',
            'totalParticipations',
            'genderStats',
            'maleCount',
            'femaleCount',
            'genderTotal',
            'malePercent',
            'femalePercent',
            'nakesByType',
            'fundingRecap',
            'categoryRecap',
            'trainingRecap',
            'districtFundingRecap',
            'districtFundingTotals'
        );
    }

    /* ============================================================
     | SEBARAN JUMLAH KEIKUTSERTAAN (1 kali, 2 kali, 3 kali, ...)
     | Dihitung dari subquery yang sama dengan daftar peserta dan
     | panel rincian, sehingga angkanya pasti cocok.
     * ============================================================ */

    private function frequencyRecap(array $filters)
    {
        return DB::query()
            ->fromSub($this->timesSubquery($filters), 'pt')
            ->selectRaw('pt.times AS times, COUNT(*) AS total_participants')
            ->groupBy('pt.times')
            ->orderBy('pt.times')
            ->get()
            ->map(fn ($row) => (object) [
                'times'              => (int) $row->times,
                'total_participants' => (int) $row->total_participants,
            ]);
    }

    /**
     * Sebaran keikutsertaan untuk EKSPOR.
     * Bila filter "tepat N kali" aktif, hanya baris N yang dimuat sehingga
     * total peserta di ekspor sama dengan total peserta di halaman laporan.
     * Bila "Semua keikutsertaan", seluruh kategori dimuat secara dinamis.
     */
    private function frequencyRows(array $filters)
    {
        $rows = $this->frequencyRecap($filters);

        if ($filters['minimum'] !== null) {
            $rows = $rows
                ->filter(fn ($row) => (int) $row->times === (int) $filters['minimum'])
                ->values();
        }

        return $rows;
    }

    /* ============================================================
     | DATA EKSPOR
     * ============================================================ */

    private function exportData(array $filters): array
    {
        $data = $this->summaryData($filters);

        $data['trainingList'] = $this->selectTrainingRows(
            $this->historyQuery($filters, true)
        )
            ->get()
            ->map(fn ($row) => $this->mapTrainingRow($row))
            ->values();

        $data['participantsAll'] = $this->participantQuery($filters)->get();

        return $data;
    }

    /* ============================================================
     | RIWAYAT LENGKAP
     * ============================================================ */

    private function historyRows(array $filters)
    {
        $cols = $this->trainingColumns();

        $endSelect = $cols['end']
            ? 'trainings.' . $cols['end']
            : 'NULL';

        $locationSelect = $cols['location']
            ? 'trainings.' . $cols['location']
            : 'NULL';

        return $this->historyQuery($filters)
            ->leftJoin(
                'training_categories',
                'training_categories.id',
                '=',
                'trainings.training_category_id'
            )
            ->select([
                'trainings.id AS training_id',
                'training_histories.participant_id',
                'trainings.start_date',
                'participants.name AS participant_name',
                'participants.nik',
                'participants.nip',
                'participants.profession',
                'participants.institution',
                'participants.district',
            ])
            ->selectRaw(
                $this->nameExpr() . ' AS training_name, '
                . self::FUNDING_EXPR . ' AS funding_source, '
                . self::CATEGORY_EXPR . ' AS category_name, '
                . $endSelect . ' AS end_date, '
                . $locationSelect . ' AS location'
            )
            ->orderBy('trainings.start_date')
            ->orderByRaw($this->nameExpr())
            ->orderBy('participants.name')
            ->get();
    }

    /* ============================================================
     | ISI EXCEL (satu sheet "Laporan")
     | $type = summary : statistik + rekap frekuensi, TANPA identitas peserta
     | $type = full    : semua isi ringkasan + daftar peserta + rincian
     |
     | Catatan penyesuaian:
     |  - KPI "Total Keikutsertaan" dihapus.
     |  - Kolom Keikutsertaan dihapus dari: Rekap Per Nama Pelatihan,
     |    Rekap Kategori Pelatihan, Rekap Wilayah Berdasarkan Sumber Dana.
     |  - Daftar Kegiatan Pelatihan: kolom Lokasi dan Keikutsertaan dihapus.
     |  - Detail Peserta Per Kegiatan: kolom Lokasi dihapus.
     |  Data di database dan query tidak diubah.
     * ============================================================ */

    private function workbookPayload(array $data, array $filters, string $type = self::EXPORT_FULL): array
    {
        $isSummary = $type === self::EXPORT_SUMMARY;

        // Kelas ekspor lama mengharapkan angka. null (semua) = minimal 1 kali.
        $minimum = $filters['minimum'] ?? 1;

        $fmt = fn ($date) => $date
            ? Carbon::parse($date)->format('d/m/Y')
            : '';

        $dash = fn ($v) => ($v === null || trim((string) $v) === '')
            ? '-'
            : (string) $v;

        $col = fn (string $label, int $w, string $align = 'c', ?string $format = null) => [
            'label'  => $label,
            'w'      => $w,
            'align'  => $align,
            'format' => $format,
        ];

        $row = fn (array $cells, bool $bold = false) => [
            'cells' => $cells,
            'bold'  => $bold,
        ];

        $num = fn ($v) => number_format((int) $v, 0, ',', '.');
        $pc = fn ($v) => number_format($v * 100, 1, ',', '.') . '%';

        $int = '#,##0';
        $pct = '0.0%';
        $orang = '#,##0" Orang"';

        $totalTr = (int) $data['totalTrainings'];
        $totalPe = (int) $data['totalTrainedParticipants'];

        $tables = [];

        /* ---------- A. Jenis kelamin ---------- */

        $genderTotal = (int) $data['genderTotal'];
        $male = (int) $data['maleCount'];
        $female = (int) $data['femaleCount'];

        $tables[] = [
            'title'   => 'REKAPITULASI JENIS KELAMIN PESERTA',
            'note'    => 'Persentase dihitung dari peserta yang jenis kelaminnya tercatat.',
            'columns' => [
                $col('No', 1),
                $col('Jenis Kelamin', 5, 'l'),
                $col('Peserta', 2, 'c', $int),
                $col('Persentase', 2, 'c', $pct),
            ],
            'rows'    => $genderTotal > 0
                ? [
                    $row([1, 'Laki-laki', $male, $male / $genderTotal]),
                    $row([2, 'Perempuan', $female, $female / $genderTotal]),
                ]
                : [],
            'total'   => $genderTotal > 0
                ? ['cells' => ['', 'TOTAL', $genderTotal, 1]]
                : null,
            'empty'   => 'Belum ada data jenis kelamin.',
            'pie'     => [
                'name'  => 'chartGender',
                'label' => 1,
                'value' => 2,
                'title' => 'Komposisi Peserta Berdasarkan Jenis Kelamin',
            ],
        ];

        /* ---------- B. Profesi ---------- */

        $tables[] = [
            'title'   => 'TOTAL PESERTA BERDASARKAN PROFESI',
            'columns' => [
                $col('No', 1),
                $col('Jenis Profesi', 6, 'l'),
                $col('Peserta Terlatih', 2, 'c', $int),
            ],
            'rows'    => $data['nakesByType']
                ->filter(fn ($r) => (int) $r->total > 0)
                ->values()
                ->map(fn ($r, $i) => $row([
                    $i + 1,
                    (string) $r->name,
                    (int) $r->total,
                ]))
                ->all(),
            'empty'   => 'Belum ada data profesi.',
        ];

        /* ---------- C. Sumber dana ---------- */

        $fundingSum = max(1, (int) $data['fundingRecap']->sum('total_trainings'));

        $tables[] = [
            'title'   => 'RINCIAN SUMBER DANA',
            'note'    => 'Porsi kegiatan dihitung dari seluruh kegiatan pada tahun terpilih.',
            'columns' => [
                $col('No', 1),
                $col('Sumber Dana', 3, 'l'),
                $col('Kegiatan', 1, 'c', $int),
                $col('Peserta', 1, 'c', $int),
                $col('Keikutsertaan', 1, 'c', $int),
                $col('Porsi Kegiatan', 1, 'c', $pct),
            ],
            'rows'    => $data['fundingRecap']
                ->values()
                ->map(fn ($r, $i) => $row([
                    $i + 1,
                    (string) $r->funding_source,
                    (int) $r->total_trainings,
                    (int) $r->total_participants,
                    (int) $r->total_participations,
                    ((int) $r->total_trainings) / $fundingSum,
                ]))
                ->all(),
            'empty'   => 'Belum ada data sumber dana.',
            'pie'     => [
                'name'  => 'chartFunding',
                'label' => 1,
                'value' => 4,
                'title' => 'Keikutsertaan Berdasarkan Sumber Dana',
            ],
        ];

        /* ---------- D. Rekap per nama pelatihan (tanpa Keikutsertaan) ---------- */

        $tables[] = [
            'title'   => 'REKAP PER NAMA PELATIHAN',
            'columns' => [
                $col('No', 1),
                $col('Nama Pelatihan', 5, 'l'),
                $col('Kegiatan', 1, 'c', $int),
                $col('Sumber Dana (jumlah kegiatan)', 4, 'l'),
                $col('Peserta', 1, 'c', $int),
            ],
            'rows'    => $data['trainingRecap']
                ->values()
                ->map(fn ($r, $i) => $row([
                    $i + 1,
                    (string) $r->name,
                    (int) $r->total_trainings,
                    $r->funding
                        ->map(fn ($f) => $f->funding_source . ': ' . $f->total_trainings)
                        ->implode('; '),
                    (int) $r->total_participants,
                ]))
                ->all(),
            'empty'   => 'Belum ada data pelatihan.',
        ];

        /* ---------- E. Kategori (tanpa Keikutsertaan) ---------- */

        $tables[] = [
            'title'   => 'REKAP KATEGORI PELATIHAN',
            'columns' => [
                $col('No', 1),
                $col('Kategori Pelatihan', 6, 'l'),
                $col('Jumlah Kegiatan', 2, 'c', $int),
                $col('Peserta', 2, 'c', $int),
            ],
            'rows'    => $data['categoryRecap']
                ->values()
                ->map(fn ($r, $i) => $row([
                    $i + 1,
                    (string) $r->category_name,
                    (int) $r->total_trainings,
                    (int) $r->total_participants,
                ]))
                ->all(),
            'empty'   => 'Belum ada data kategori pelatihan.',
        ];

        /* ---------- F. Wilayah x sumber dana (tanpa Keikutsertaan) ---------- */

        $districtLines = $data['districtFundingRecap']->groupBy('district');
        $districtSorted = $data['districtFundingTotals']
            ->sortByDesc(fn ($r) => (int) $r->total_participants)
            ->values();
        $districtRows = [];

        foreach ($districtSorted as $total) {
            $districtRows[] = $row([
                (string) $total->district,
                'SEMUA SUMBER DANA',
                (int) $total->total_trainings,
                (int) $total->total_participants,
            ], true);

            foreach ($districtLines->get($total->district, collect()) as $line) {
                $districtRows[] = $row([
                    (string) $total->district,
                    (string) $line->funding_source,
                    (int) $line->total_trainings,
                    (int) $line->total_participants,
                ]);
            }
        }

        $tables[] = [
            'title'   => 'REKAP WILAYAH BERDASARKAN SUMBER DANA',
            'columns' => [
                $col('Kabupaten/Kota', 3, 'l'),
                $col('Sumber Dana', 3, 'l'),
                $col('Kegiatan', 2, 'c', $int),
                $col('Peserta', 2, 'c', $int),
            ],
            'rows'    => $districtRows,
            'empty'   => 'Belum ada data wilayah berdasarkan sumber dana.',
        ];

        /* ---------- G. Rekap peserta berdasarkan frekuensi keikutsertaan ---------- */
        /* Ada di KEDUA jenis ekspor. Hanya angka, tanpa identitas peserta. */

        $frequencyRows = $data['frequencyRows'] ?? collect();
        $frequencyTotal = (int) $frequencyRows->sum('total_participants');

        $tables[] = [
            'title'   => 'REKAPITULASI PESERTA BERDASARKAN JUMLAH KEIKUTSERTAAN',
            'note'    => $filters['minimum'] === null
                ? 'Keikutsertaan = jumlah kegiatan berbeda yang diikuti peserta pada tahun ' . $filters['year'] . '. Seluruh kategori ditampilkan.'
                : 'Keikutsertaan = jumlah kegiatan berbeda yang diikuti peserta pada tahun ' . $filters['year'] . '. Filter aktif: tepat ' . $filters['minimum'] . ' kali.',
            'columns' => [
                $col('Keikutsertaan', 4, 'c'),
                $col('Jumlah Peserta', 3, 'c', $orang),
            ],
            'rows'    => $frequencyRows
                ->values()
                ->map(fn ($r) => $row([
                    (int) $r->times . ' Kali',
                    (int) $r->total_participants,
                ]))
                ->all(),
            'total'   => $frequencyRows->isNotEmpty()
                ? ['cells' => ['Total Peserta', $frequencyTotal]]
                : null,
            'empty'   => 'Tidak ada peserta yang memenuhi filter.',
        ];

        /* ---------- H. Daftar peserta (HANYA ekspor lengkap) ---------- */

        if (!$isSummary) {
            $tables[] = [
                'title'   => 'JUMLAH PELATIHAN PER PESERTA',
                'note'    => $filters['minimum'] === null
                    ? 'Seluruh peserta sesuai filter (semua jumlah keikutsertaan).'
                    : 'Peserta sesuai filter dengan tepat ' . $filters['minimum'] . ' kali keikutsertaan.',
                'columns' => [
                    $col('No', 1),
                    $col('Nama Peserta', 3, 'l'),
                    $col('NIK', 2),
                    $col('NIP', 2),
                    $col('Profesi', 2, 'l'),
                    $col('Instansi', 3, 'l'),
                    $col('Kabupaten/Kota', 2, 'l'),
                    $col('Keikutsertaan', 1, 'c', $int),
                ],
                'rows'    => $data['participantsAll']
                    ->values()
                    ->map(fn ($p, $i) => $row([
                        $i + 1,
                        $dash($p->name),
                        $dash($p->nik),
                        $dash($p->nip),
                        $dash($p->profession),
                        $dash($p->institution),
                        $dash($p->district),
                        (int) $p->participations_count,
                    ]))
                    ->all(),
                'empty'   => 'Tidak ada peserta yang memenuhi filter.',
            ];
        }

        /* ---------- Analisis singkat ---------- */

        $insights = [];

        $insights[] = sprintf(
            'Pada tahun %s terselenggara %s kegiatan pelatihan dengan %s peserta.',
            $filters['year'],
            $num($totalTr),
            $num($totalPe)
        );

        if ($top = $data['fundingRecap']->first()) {
            $insights[] = sprintf(
                'Sumber dana dengan kegiatan terbanyak adalah %s dengan %s kegiatan, %s peserta, dan %s keikutsertaan (%s dari seluruh kegiatan). Pelatihan dibiayai dari %s sumber dana.',
                $top->funding_source,
                $num($top->total_trainings),
                $num($top->total_participants),
                $num($top->total_participations),
                $pc(((int) $top->total_trainings) / $fundingSum),
                $num($data['fundingRecap']->count())
            );
        }

        $topCategory = $data['categoryRecap']->first(
            fn ($r) => $r->category_name !== 'Tidak berkategori'
        );

        if ($topCategory) {
            $insights[] = sprintf(
                'Kategori pelatihan yang paling banyak diselenggarakan adalah %s dengan %s kegiatan dan %s peserta.',
                $topCategory->category_name,
                $num($topCategory->total_trainings),
                $num($topCategory->total_participants)
            );
        }

        if ($genderTotal > 0) {
            $insights[] = sprintf(
                'Komposisi peserta berdasarkan jenis kelamin: Laki-laki %s orang (%s), Perempuan %s orang (%s).',
                $num($male),
                $pc($male / $genderTotal),
                $num($female),
                $pc($female / $genderTotal)
            );
        }

        $topProfession = $data['nakesByType']->sortByDesc('total')->first();

        if ($topProfession && (int) $topProfession->total > 0) {
            $insights[] = sprintf(
                'Profesi dengan peserta terbanyak adalah %s sebanyak %s orang (%s dari seluruh peserta).',
                $topProfession->name,
                $num($topProfession->total),
                $pc(((int) $topProfession->total) / max(1, $totalPe))
            );
        }

        $topTraining = $data['trainingRecap']->sortByDesc('total_participants')->first();

        if ($topTraining && (int) $topTraining->total_participants > 0) {
            $insights[] = sprintf(
                'Pelatihan dengan peserta terbanyak adalah "%s" dengan %s peserta dari %s kegiatan.',
                $topTraining->name,
                $num($topTraining->total_participants),
                $num($topTraining->total_trainings)
            );
        }

        $topDistrict = $districtSorted->first();

        if ($topDistrict) {
            $insights[] = sprintf(
                'Wilayah dengan peserta terbanyak adalah %s sebanyak %s peserta dari %s kegiatan.',
                $topDistrict->district,
                $num($topDistrict->total_participants),
                $num($topDistrict->total_trainings)
            );
        }

        if ($frequencyRows->isNotEmpty()) {
            $topFrequency = $frequencyRows->sortByDesc('total_participants')->first();

            $insights[] = sprintf(
                'Peserta terbanyak mengikuti pelatihan sebanyak %s kali, yaitu %s orang dari total %s peserta.',
                $num($topFrequency->times),
                $num($topFrequency->total_participants),
                $num($frequencyTotal)
            );
        }

        /* ---------- Bagian II: rincian (HANYA ekspor lengkap) ---------- */

        $details = [];

        if (!$isSummary) {
            // Daftar Kegiatan Pelatihan: tanpa Lokasi dan Keikutsertaan.
            $details[] = [
                'title'   => 'DAFTAR KEGIATAN PELATIHAN',
                'columns' => [
                    $col('No', 1),
                    $col('Nama Pelatihan', 4, 'l'),
                    $col('Mulai', 1),
                    $col('Selesai', 1),
                    $col('Sumber Dana', 2, 'l'),
                    $col('Kategori', 2, 'l'),
                    $col('Peserta', 1, 'c', $int),
                ],
                'rows'    => $data['trainingList']
                    ->values()
                    ->map(fn ($r, $i) => $row([
                        $i + 1,
                        $dash($r['name']),
                        $fmt($r['start_date']),
                        $fmt($r['end_date']),
                        $dash($r['funding_source']),
                        $dash($r['category']),
                        (int) $r['participants'],
                    ]))
                    ->all(),
                'empty'   => 'Belum ada kegiatan pelatihan.',
            ];

            // Detail Peserta Per Kegiatan: tanpa Lokasi.
            // Bobot kolom berjumlah 12 agar seluruh kolom A..L terisi (tanpa kolom kosong).
            $details[] = [
                'title'   => 'DETAIL PESERTA PER KEGIATAN',
                'note'    => 'Satu baris untuk setiap keikutsertaan peserta pada satu kegiatan. Gunakan filter pada judul kolom untuk menyaring.',
                'filter'  => true,
                'columns' => [
                    $col('No', 1),
                    $col('Nama Peserta', 1, 'l'),
                    $col('NIK', 1),
                    $col('Profesi', 1, 'l'),
                    $col('Instansi', 1, 'l'),
                    $col('Kabupaten/ Kota', 1, 'l'),
                    $col('Nama Pelatihan', 2, 'l'),
                    $col('Mulai', 1),
                    $col('Selesai', 1),
                    $col('Sumber Dana', 1, 'l'),
                    $col('Kategori', 1, 'l'),
                ],
                'rows'    => $data['historyRows']
                    ->values()
                    ->map(fn ($r, $i) => $row([
                        $i + 1,
                        $dash($r->participant_name ?? ''),
                        $dash($r->nik ?? ''),
                        $dash($r->profession ?? ''),
                        $dash($r->institution ?? ''),
                        $dash($r->district ?? ''),
                        $dash($r->training_name ?? ''),
                        $fmt($r->start_date),
                        $fmt($r->end_date ?? null),
                        $dash($r->funding_source ?? ''),
                        $dash($r->category_name ?? ''),
                    ]))
                    ->all(),
                'empty'   => 'Tidak terdapat data yang sesuai dengan filter yang dipilih.',
            ];
        }

        return [
            'meta' => [
                'year'          => $filters['year'],
                'minimum'       => $minimum,
                'minimum_label' => $this->minimumLabel($filters),
                'district'      => $filters['district'],
                'institution'   => $filters['institution'],
                'search'        => $filters['search'],
                'type'          => $type,
                'type_label'    => $isSummary ? 'Ekspor Ringkasan' : 'Ekspor Lengkap',
                'title'         => $isSummary
                    ? 'LAPORAN RINGKASAN RIWAYAT PELATIHAN'
                    : 'LAPORAN LENGKAP RIWAYAT PELATIHAN',
            ],

            // KPI "Total Keikutsertaan" dihapus.
            'kpis' => [
                ['Peserta Sudah Dilatih', $totalPe],
                ['Total Kegiatan Pelatihan', $totalTr],
            ],

            'tables'   => $tables,
            'insights' => $insights,
            'details'  => $details,
        ];
    }

    /* ============================================================
     | RINCIAN PANEL
     * ============================================================ */

    public function detail(Request $request): JsonResponse
    {
        $years = $this->getAvailableTrainingYears();
        $year = $this->resolveYear($request, $years);

        $filters = $this->filtersFrom($request, $year);

        $label = trim(
            mb_substr((string) $request->input('label', ''), 0, 160)
        );

        return match ((string) $request->input('view')) {
            'trainings'    => $this->detailTrainings($request, $filters, $label),
            'participants' => $this->detailParticipants($request, $filters, $label),
            'training'     => $this->detailTraining($request, $filters),
            'participant'  => $this->detailParticipant($request, $filters),

            default => response()->json([
                'message' => 'Jenis rincian tidak dikenal.',
            ], 422),
        };
    }

    private function detailTrainings(
        Request $request,
        array $filters,
        string $label
    ): JsonResponse {
        $rows = $this->selectTrainingRows(
            $this->scopedQuery($request, $filters, true)
        )->get();

        $uniqueParticipants = $this->scopedQuery($request, $filters)
            ->distinct()
            ->count('training_histories.participant_id');

        $total = $rows->count();

        return response()->json([
            'view' => 'trainings',
            'title' => 'Daftar kegiatan pelatihan',
            'subtitle' => $label ?: 'Tahun ' . $filters['year'],

            'summary' => [
                ['label' => 'Kegiatan', 'value' => $total],
                ['label' => 'Peserta', 'value' => $uniqueParticipants],
            ],

            'list_title' => 'Kegiatan (urut tanggal)',
            'total' => $total,
            'truncated' => $total > self::MAX_DETAIL_ROWS,

            'rows' => $rows
                ->take(self::MAX_DETAIL_ROWS)
                ->map(fn ($row) => $this->mapTrainingRow($row))
                ->values(),
        ]);
    }

    private function detailParticipants(
        Request $request,
        array $filters,
        string $label
    ): JsonResponse {
        $rows = $this->selectParticipantRows(
            $this->scopedQuery($request, $filters)
        )->get();

        $total = $rows->count();

        return response()->json([
            'view' => 'participants',
            'title' => $label !== '' ? $label : 'Daftar peserta',
            'subtitle' => 'Tahun ' . $filters['year'],

            'summary' => [
                ['label' => 'Peserta', 'value' => $total],
                ['label' => 'Keikutsertaan', 'value' => (int) $rows->sum('participations')],
            ],

            'list_title' => 'Nama peserta',
            'total' => $total,
            'truncated' => $total > self::MAX_DETAIL_ROWS,

            'rows' => $rows
                ->take(self::MAX_DETAIL_ROWS)
                ->map(fn ($row) => $this->mapParticipantRow($row))
                ->values(),
        ]);
    }

    private function detailTraining(
        Request $request,
        array $filters
    ): JsonResponse {
        $trainingId = (int) $request->input('training_id');

        if ($trainingId < 1) {
            return response()->json([
                'message' => 'Pelatihan tidak ditemukan pada filter ini.',
            ], 404);
        }

        $row = $this->selectTrainingRows(
            $this->scopedQuery($request, $filters, true)
        )->first();

        if (!$row || (int) $row->id !== $trainingId) {
            return response()->json([
                'message' => 'Pelatihan tidak ditemukan pada filter ini.',
            ], 404);
        }

        $participantRows = $this->selectParticipantRows(
            $this->scopedQuery($request, $filters)
        )->get();

        $mapped = $this->mapTrainingRow($row);

        $info = [
            ['label' => 'Nama pelatihan', 'value' => $mapped['name']],
            [
                'label' => 'Tanggal',
                'value' => $this->formatDateRange($mapped['start_date'], $mapped['end_date']),
            ],
            ['label' => 'Sumber dana', 'value' => $mapped['funding_source']],
            ['label' => 'Kategori', 'value' => $mapped['category']],
        ];

        $rawTraining = DB::table('trainings')
            ->where('id', $trainingId)
            ->first();

        $raw = $rawTraining ? (array) $rawTraining : [];

        $usedLabels = [];

        foreach (self::TRAINING_EXTRA_FIELDS as $column => $fieldLabel) {
            $value = $raw[$column] ?? null;

            if (
                $value === null
                || trim((string) $value) === ''
                || in_array($fieldLabel, $usedLabels, true)
            ) {
                continue;
            }

            $usedLabels[] = $fieldLabel;

            $info[] = ['label' => $fieldLabel, 'value' => (string) $value];
        }

        $total = $participantRows->count();

        $info[] = ['label' => 'Peserta', 'value' => (string) $total];

        return response()->json([
            'view' => 'training',
            'title' => $mapped['name'],
            'subtitle' => $this->formatDateRange($mapped['start_date'], $mapped['end_date']),

            'summary' => [
                ['label' => 'Peserta', 'value' => $total],
            ],

            'info' => $info,
            'list_title' => 'Peserta kegiatan ini',
            'total' => $total,
            'truncated' => $total > self::MAX_DETAIL_ROWS,

            'rows' => $participantRows
                ->take(self::MAX_DETAIL_ROWS)
                ->map(fn ($row) => $this->mapParticipantRow($row))
                ->values(),
        ]);
    }

    private function detailParticipant(
        Request $request,
        array $filters
    ): JsonResponse {
        $participant = Participant::query()
            ->find((int) $request->input('participant_id'));

        if (!$participant) {
            return response()->json([
                'message' => 'Peserta tidak ditemukan.',
            ], 404);
        }

        $rows = $this->selectTrainingRows(
            $this->scopedQuery($request, $filters)
        )->get();

        $total = $rows->count();

        return response()->json([
            'view' => 'participant',
            'title' => $participant->name ?: 'Peserta',
            'subtitle' => 'Riwayat pelatihan tahun ' . $filters['year'],

            'summary' => [
                ['label' => 'Keikutsertaan', 'value' => (int) $rows->sum('total_participations')],
                ['label' => 'Kegiatan', 'value' => $total],
            ],

            'info' => [
                ['label' => 'Nama', 'value' => $participant->name ?: '-'],
                ['label' => 'NIK', 'value' => $participant->nik ?: '-'],
                ['label' => 'NIP', 'value' => $participant->nip ?: '-'],
                ['label' => 'Profesi', 'value' => $participant->profession ?: '-'],
                ['label' => 'Instansi', 'value' => $participant->institution ?: '-'],
                ['label' => 'Kabupaten/Kota', 'value' => $participant->district ?: '-'],
            ],

            'list_title' => 'Pelatihan yang diikuti',
            'total' => $total,
            'truncated' => $total > self::MAX_DETAIL_ROWS,

            'rows' => $rows
                ->take(self::MAX_DETAIL_ROWS)
                ->map(fn ($row) => $this->mapTrainingRow($row))
                ->values(),
        ]);
    }

    /* ============================================================
     | FILTER DAN QUERY BERSAMA
     * ============================================================ */

    /**
     * Jenis ekspor. Nilai selain "summary" dianggap "full"
     * agar tautan ekspor lama tetap berperilaku seperti sebelumnya.
     */
    private function resolveType(Request $request): string
    {
        return strtolower(trim((string) $request->input('type'))) === self::EXPORT_SUMMARY
            ? self::EXPORT_SUMMARY
            : self::EXPORT_FULL;
    }

    private function exportFilename(string $type, int $year, string $extension): string
    {
        $label = $type === self::EXPORT_SUMMARY ? 'ringkasan' : 'lengkap';

        return 'laporan-' . $label . '-riwayat-pelatihan-' . $year . '.' . $extension;
    }

    private function minimumLabel(array $filters): string
    {
        return $filters['minimum'] === null
            ? 'Semua keikutsertaan'
            : $filters['minimum'] . ' kali keikutsertaan';
    }

    /**
     * Membaca filter jumlah keikutsertaan.
     * - kosong / "all" / bukan angka  => null (SEMUA keikutsertaan)
     * - angka >= 1                    => tepat sejumlah itu
     * TIDAK ADA default 1 lagi.
     */
    private function resolveMinimum(Request $request): ?int
    {
        $value = $request->input('minimum');

        if (!is_numeric($value) || (int) $value < 1) {
            return null;
        }

        return min(100, (int) $value);
    }

    private function filtersFrom(
        Request $request,
        int $year
    ): array {
        return [
            'year'        => $year,
            'search'      => trim((string) $request->input('search', '')),
            'district'    => trim((string) $request->input('district', '')),
            'institution' => trim((string) $request->input('institution', '')),
            'minimum'     => $this->resolveMinimum($request),
        ];
    }

    /**
     * Subquery dasar: tiap peserta + berapa KEGIATAN berbeda yang ia ikuti
     * pada tahun terpilih (sesuai filter wilayah/instansi/pencarian).
     * COUNT(DISTINCT trainings.id) mencegah hitung ganda pada kegiatan sama.
     * Filter jumlah keikutsertaan sengaja DIABAIKAN di sini agar tidak melingkar.
     */
    private function timesSubquery(array $filters)
    {
        $base = $filters;
        $base['minimum'] = null;

        return $this->historyQuery($base)
            ->groupBy('training_histories.participant_id')
            ->select('training_histories.participant_id')
            ->selectRaw('COUNT(DISTINCT trainings.id) AS times');
    }

    /** ID peserta yang jumlah keikutsertaannya tepat $times. */
    private function participantIdsWithTimes(array $filters, int $times)
    {
        return DB::query()
            ->fromSub($this->timesSubquery($filters), 'pt')
            ->where('pt.times', '=', $times)
            ->select('pt.participant_id');
    }

    private function participantQuery(array $filters)
    {
        $query = Participant::query()
            ->joinSub(
                $this->timesSubquery($filters),
                'pt',
                'pt.participant_id',
                '=',
                'participants.id'
            )
            ->select('participants.*')
            ->addSelect('pt.times AS participations_count');

        if ($filters['minimum'] !== null) {
            $query->where('pt.times', '=', $filters['minimum']);   // tepat, bukan >=
        }

        return $query
            ->orderByDesc('pt.times')
            ->orderBy('participants.name');
    }

    private function historyQuery(
        array $filters,
        bool $includeEmpty = false
    ) {
        $minimum = $filters['minimum'] ?? null;

        $hasParticipantFilter =
            $filters['search'] !== ''
            || $filters['district'] !== ''
            || $filters['institution'] !== ''
            || $minimum !== null;

        $query = DB::table('trainings')
            ->whereYear('trainings.start_date', $filters['year']);

        if ($includeEmpty && !$hasParticipantFilter) {
            $query
                ->leftJoin(
                    'training_histories',
                    'training_histories.training_id',
                    '=',
                    'trainings.id'
                )
                ->leftJoin(
                    'participants',
                    'participants.id',
                    '=',
                    'training_histories.participant_id'
                );
        } else {
            $query
                ->join(
                    'training_histories',
                    'training_histories.training_id',
                    '=',
                    'trainings.id'
                )
                ->join(
                    'participants',
                    'participants.id',
                    '=',
                    'training_histories.participant_id'
                );
        }

        if ($filters['district'] !== '') {
            $query->whereRaw(
                'TRIM(participants.district) = ?',
                [$filters['district']]
            );
        }

        if ($filters['institution'] !== '') {
            $query->whereRaw(
                'TRIM(participants.institution) = ?',
                [$filters['institution']]
            );
        }

        if ($filters['search'] !== '') {
            $search = $filters['search'];

            $query->where(function ($sub) use ($search) {
                $sub->where('participants.name', 'like', "%{$search}%")
                    ->orWhere('participants.nik', 'like', "%{$search}%")
                    ->orWhere('participants.nip', 'like', "%{$search}%");
            });
        }

        // Filter jumlah keikutsertaan: memengaruhi SEMUA komponen laporan.
        if ($minimum !== null) {
            $query->whereIn(
                'training_histories.participant_id',
                $this->participantIdsWithTimes($filters, $minimum)
            );
        }

        return $query;
    }

    private function scopedQuery(
        Request $request,
        array $filters,
        bool $allowEmpty = false
    ) {
        $participantScoped =
            $request->filled('scope_district')
            || $request->filled('participant_id')
            || $request->filled('times');

        $query = $this->historyQuery(
            $filters,
            $allowEmpty && !$participantScoped
        );

        if ($request->filled('funding_source')) {
            $query->whereRaw(
                self::FUNDING_EXPR . ' = ?',
                [(string) $request->input('funding_source')]
            );
        }

        if ($request->filled('category_id')) {
            $categoryId = (string) $request->input('category_id');

            if ($categoryId === 'none') {
                $query->whereNull('trainings.training_category_id');
            } else {
                $query->where(
                    'trainings.training_category_id',
                    (int) $categoryId
                );
            }
        }

        if ($request->filled('scope_district')) {
            $query->whereRaw(
                'TRIM(participants.district) = ?',
                [(string) $request->input('scope_district')]
            );
        }

        if ($request->filled('training_name')) {
            $query->whereRaw(
                $this->nameExpr() . ' = ?',
                [(string) $request->input('training_name')]
            );
        }

        if ($request->filled('training_id')) {
            $query->where('trainings.id', (int) $request->input('training_id'));
        }

        if ($request->filled('participant_id')) {
            $query->where(
                'training_histories.participant_id',
                (int) $request->input('participant_id')
            );
        }

        // Klik kartu "N kali": hanya peserta yang tepat N kali ikut.
        if ($request->filled('times')) {
            $query->whereIn(
                'training_histories.participant_id',
                $this->participantIdsWithTimes(
                    $filters,
                    max(1, (int) $request->input('times'))
                )
            );
        }

        return $query;
    }

    /* ============================================================
     | QUERY BARIS PELATIHAN
     * ============================================================ */

    private function selectTrainingRows($query)
    {
        $cols = $this->trainingColumns();

        $nameExpr = $this->nameExpr();

        $endSelect = $cols['end']
            ? 'trainings.' . $cols['end'] . ' AS end_date,'
            : 'NULL AS end_date,';

        $locationSelect = $cols['location']
            ? 'trainings.' . $cols['location'] . ' AS location,'
            : 'NULL AS location,';

        $baseQuery = $query
            ->leftJoin(
                'training_categories',
                'training_categories.id',
                '=',
                'trainings.training_category_id'
            )
            ->selectRaw("
                trainings.id AS training_id,
                trainings.start_date AS start_date,
                {$endSelect}
                {$locationSelect}
                {$nameExpr} AS training_name,
                " . self::FUNDING_EXPR . " AS funding_source,
                " . self::CATEGORY_EXPR . " AS category_name,
                training_histories.id AS history_id,
                training_histories.participant_id AS participant_id
            ");

        return DB::query()
            ->fromSub($baseQuery, 'training_rows')
            ->selectRaw("
                training_id AS id,
                start_date,
                end_date,
                location,
                training_name,
                funding_source,
                category_name,
                COUNT(DISTINCT participant_id) AS total_participants,
                COUNT(DISTINCT history_id) AS total_participations
            ")
            ->groupBy(
                'training_id',
                'start_date',
                'end_date',
                'location',
                'training_name',
                'funding_source',
                'category_name'
            )
            ->orderBy('start_date')
            ->orderBy('training_id');
    }

    /* ============================================================
     | QUERY BARIS PESERTA
     | "participations" = jumlah kegiatan berbeda (sama dengan
     | definisi pada daftar peserta dan kartu sebaran).
     * ============================================================ */

    private function selectParticipantRows($query)
    {
        $plain = [
            'participants.id',
            'participants.name',
            'participants.nik',
            'participants.nip',
            'participants.institution',
            'participants.district',
            'participants.profession',
        ];

        return $query
            ->select($plain)
            ->selectRaw('COUNT(DISTINCT trainings.id) AS participations')
            ->groupBy($plain)
            ->orderByRaw('COUNT(DISTINCT trainings.id) DESC')
            ->orderBy('participants.name');
    }

    /* ============================================================
     | PEMETAAN DATA
     * ============================================================ */

    private function mapTrainingRow(object $row): array
    {
        return [
            'id' => (int) $row->id,
            'name' => $row->training_name,
            'start_date' => $this->dateOnly($row->start_date ?? null),
            'end_date' => $this->dateOnly($row->end_date ?? null),
            'location' => $row->location ?? null,
            'funding_source' => $row->funding_source,
            'category' => $row->category_name,
            'participants' => (int) $row->total_participants,
            'participations' => (int) $row->total_participations,
            'total_participations' => (int) $row->total_participations,
        ];
    }

    private function mapParticipantRow(object $row): array
    {
        return [
            'id' => (int) $row->id,
            'name' => $row->name ?: '-',
            'nik' => $row->nik,
            'nip' => $row->nip,
            'institution' => $row->institution,
            'district' => $row->district,
            'profession' => $row->profession,
            'participations' => (int) $row->participations,
        ];
    }

    /* ============================================================
     | HELPER KOLOM TABEL TRAININGS
     * ============================================================ */

    private function trainingColumns(): array
    {
        if ($this->trainingColumnCache !== null) {
            return $this->trainingColumnCache;
        }

        $pick = function (array $candidates) {
            foreach ($candidates as $column) {
                if (Schema::hasColumn('trainings', $column)) {
                    return $column;
                }
            }

            return null;
        };

        return $this->trainingColumnCache = [
            'name' => $pick([
                'name',
                'title',
                'training_name',
                'nama',
                'nama_pelatihan',
            ]),

            'end' => $pick(['end_date']),

            'location' => $pick([
                'location',
                'venue',
                'place',
                'lokasi',
                'tempat',
            ]),
        ];
    }

    private function nameExpr(): string
    {
        $column = $this->trainingColumns()['name'];

        return $column
            ? "COALESCE(NULLIF(TRIM(trainings.{$column}), ''), 'Tanpa nama')"
            : "'Pelatihan'";
    }

    /* ============================================================
     | FORMAT TANGGAL
     * ============================================================ */

    private function dateOnly($value): ?string
    {
        return $value
            ? substr((string) $value, 0, 10)
            : null;
    }

    private function formatDateRange(
        ?string $start,
        ?string $end
    ): string {
        if (!$start) {
            return '-';
        }

        $format = fn (string $date) => Carbon::parse($date)
            ->locale('id')
            ->translatedFormat('d F Y');

        if (!$end || $end === $start) {
            return $format($start);
        }

        return $format($start) . ' – ' . $format($end);
    }

    /* ============================================================
     | TAHUN PELATIHAN YANG TERSEDIA
     * ============================================================ */

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

    private function resolveYear(
        Request $request,
        $years
    ): int {
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