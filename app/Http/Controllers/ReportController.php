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

        $minimum = max(1, min(100, (int) $request->input('minimum', 1)));

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

        $participants = $this->participantQuery($filters, $minimum)
            ->paginate(15)
            ->withQueryString();

        return view('reports.index', array_merge(
            $this->summaryData($filters),
            [
                'year'              => $year,
                'years'             => $years,
                'minimum'           => $minimum,
                'search'            => $filters['search'],
                'districtFilter'    => $filters['district'],
                'institutionFilter' => $filters['institution'],
                'districts'         => $districts,
                'institutions'      => $institutions,
                'participants'      => $participants,
            ]
        ));
    }

    /* ============================================================
     | EKSPOR EXCEL
     * ============================================================ */

    public function export(Request $request)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(180);

        $years = $this->getAvailableTrainingYears();
        $year = $this->resolveYear($request, $years);

        $minimum = max(1, min(100, (int) $request->input('minimum', 1)));

        $filters = $this->filtersFrom($request, $year);

        $data = $this->exportData($filters, $minimum);
        $data['historyRows'] = $this->historyRows($filters);

        // PERBAIKAN: sebelumnya memanggil workbookSheets() yang tidak ada.
        // Method yang tersedia adalah workbookPayload().
        return Excel::download(
            new ReportWorkbookExport(
                $this->workbookPayload($data, $filters, $minimum)
            ),
            'laporan-riwayat-pelatihan-' . $year . '.xlsx'
        );
    }

    /* ============================================================
     | EKSPOR PDF
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

        $minimum = max(1, min(100, (int) $request->input('minimum', 1)));

        $filters = $this->filtersFrom($request, $year);

        $data = $this->exportData($filters, $minimum);

        // Peserta yang lolos filter + minimum keikutsertaan
        $participantMap = $data['participantsAll']->keyBy('id');

        // Peserta dikelompokkan per kegiatan pelatihan
        $participantGroups = $this->historyRows($filters)
            ->filter(fn ($r) => $participantMap->has($r->participant_id))
            ->groupBy('training_id')
            ->map(function ($rows) use ($participantMap) {
                $first = $rows->first();

                return [
                    'name'           => (string) $first->training_name,
                    'start_date'     => $first->start_date,
                    'end_date'       => $first->end_date,
                    'funding_source' => (string) $first->funding_source,
                    'category'       => (string) $first->category_name,
                    'participants'   => $rows
                        ->unique('participant_id')
                        ->map(function ($r) use ($participantMap) {
                            $p = $participantMap->get($r->participant_id);

                            return [
                                'name'           => (string) ($p->name ?? ''),
                                'nik'            => (string) ($p->nik ?? ''),
                                'profession'     => (string) ($p->profession ?? ''),
                                'institution'    => (string) ($p->institution ?? ''),
                                'district'       => (string) ($p->district ?? ''),
                                'participations' => (int) ($p->participations_count ?? 0),
                            ];
                        })
                        ->sortBy(fn ($x) => mb_strtolower($x['name']))
                        ->values()
                        ->all(),
                ];
            })
            ->sortBy(fn ($g) => ($g['start_date'] ?? '') . '|' . mb_strtolower($g['name']))
            ->values()
            ->all();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'reports.pdf',
            array_merge($data, [
                'year'              => $year,
                'minimum'           => $minimum,
                'search'            => $filters['search'],
                'districtFilter'    => $filters['district'],
                'institutionFilter' => $filters['institution'],
                'participantGroups' => $participantGroups,
                'participantCount'  => $participantMap->count(),
            ])
        )->setPaper('a4', 'portrait');

        return $pdf->download(
            'laporan-riwayat-pelatihan-' . $year . '.pdf'
        );
    }

    /* ============================================================
     | DATA LAPORAN
     | Digunakan halaman, Excel, dan PDF
     * ============================================================ */

    private function summaryData(array $filters): array
    {
        $year = $filters['year'];

        $totalTrainings = Training::query()
            ->whereYear('start_date', $year)
            ->count();

        $summaryQuery = Participant::query()
            ->whereHas(
                'histories',
                $this->historyInYear($year)
            );

        $this->applyParticipantFilters($summaryQuery, $filters);

        $totalTrainedParticipants = (clone $summaryQuery)->count();

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
                training_histories.id AS history_id,
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
                COUNT(DISTINCT history_id) AS total_participations,
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
                        'funding_source'       => $item->funding_source,
                        'total_trainings'      => (int) $item->total_trainings,
                        'total_participants'   => (int) $item->total_participants,
                        'total_participations' => (int) $item->total_participations,
                    ])
                    ->sortByDesc('total_trainings')
                    ->values();

                return (object) [
                    'name'                 => $row->training_name,
                    'total_trainings'      => (int) $row->total_trainings,
                    'total_participants'   => (int) $row->total_participants,
                    'total_participations' => (int) $row->total_participations,
                    'funding'              => $funding,
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
     | DATA EKSPOR
     * ============================================================ */

    private function exportData(array $filters, int $minimum): array
    {
        $data = $this->summaryData($filters);

        $data['trainingList'] = $this->selectTrainingRows(
            $this->historyQuery($filters, true)
        )
            ->get()
            ->map(fn ($row) => $this->mapTrainingRow($row))
            ->values();

        $data['participantsAll'] = $this->participantQuery(
            $filters,
            $minimum
        )->get();

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
     |
     | Bagian I  = semua isi halaman laporan + diagram pie + analisis.
     | Bagian II = daftar kegiatan & detail peserta per kegiatan.
     * ============================================================ */

    private function workbookPayload(
        array $data,
        array $filters,
        int $minimum
    ): array {
        $fmt = fn ($date) => $date
            ? Carbon::parse($date)->format('d/m/Y')
            : '';

        $dash = fn ($v) => ($v === null || trim((string) $v) === '')
            ? '-'
            : (string) $v;

        // w = bobot lebar kolom, format = format angka Excel
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

        $totalTr = (int) $data['totalTrainings'];
        $totalPe = (int) $data['totalTrainedParticipants'];

        $tables = [];

        /* ---------- A. Jenis kelamin (sumber diagram pie 1) ---------- */

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

        /* ---------- C. Sumber dana (sumber diagram pie 2) ---------- */

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

        /* ---------- D. Rekap per nama pelatihan ---------- */

        $tables[] = [
            'title'   => 'REKAP PER NAMA PELATIHAN',
            'columns' => [
                $col('No', 1),
                $col('Nama Pelatihan', 4, 'l'),
                $col('Kegiatan', 1, 'c', $int),
                $col('Sumber Dana (jumlah kegiatan)', 3, 'l'),
                $col('Peserta', 1, 'c', $int),
                $col('Keikutsertaan', 1, 'c', $int),
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
                    (int) $r->total_participations,
                ]))
                ->all(),
            'empty'   => 'Belum ada data pelatihan.',
        ];

        /* ---------- E. Kategori ---------- */

        $tables[] = [
            'title'   => 'REKAP KATEGORI PELATIHAN',
            'columns' => [
                $col('No', 1),
                $col('Kategori Pelatihan', 5, 'l'),
                $col('Jumlah Kegiatan', 1, 'c', $int),
                $col('Peserta', 1, 'c', $int),
                $col('Keikutsertaan', 1, 'c', $int),
            ],
            'rows'    => $data['categoryRecap']
                ->values()
                ->map(fn ($r, $i) => $row([
                    $i + 1,
                    (string) $r->category_name,
                    (int) $r->total_trainings,
                    (int) $r->total_participants,
                    (int) $r->total_participations,
                ]))
                ->all(),
            'empty'   => 'Belum ada data kategori pelatihan.',
        ];

        /* ---------- F. Wilayah x sumber dana ---------- */

        $districtLines = $data['districtFundingRecap']->groupBy('district');
        $districtSorted = $data['districtFundingTotals']
            ->sortByDesc(fn ($r) => (int) $r->total_participations)
            ->values();
        $districtRows = [];

        foreach ($districtSorted as $total) {
            $districtRows[] = $row([
                (string) $total->district,
                'SEMUA SUMBER DANA',
                (int) $total->total_trainings,
                (int) $total->total_participants,
                (int) $total->total_participations,
            ], true);

            foreach ($districtLines->get($total->district, collect()) as $line) {
                $districtRows[] = $row([
                    (string) $total->district,
                    (string) $line->funding_source,
                    (int) $line->total_trainings,
                    (int) $line->total_participants,
                    (int) $line->total_participations,
                ]);
            }
        }

        $tables[] = [
            'title'   => 'REKAP WILAYAH BERDASARKAN SUMBER DANA',
            'columns' => [
                $col('Kabupaten/Kota', 3, 'l'),
                $col('Sumber Dana', 3, 'l'),
                $col('Kegiatan', 1, 'c', $int),
                $col('Peserta', 1, 'c', $int),
                $col('Keikutsertaan', 1, 'c', $int),
            ],
            'rows'    => $districtRows,
            'empty'   => 'Belum ada data wilayah berdasarkan sumber dana.',
        ];

        /* ---------- G. Jumlah pelatihan per peserta ---------- */

        $tables[] = [
            'title'   => 'JUMLAH PELATIHAN PER PESERTA',
            'note'    => 'Seluruh peserta sesuai filter dan minimal keikutsertaan.',
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

        /* ---------- Analisis singkat untuk pimpinan ---------- */

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
                'Kategori pelatihan yang paling banyak diselenggarakan adalah %s dengan %s kegiatan dan %s keikutsertaan.',
                $topCategory->category_name,
                $num($topCategory->total_trainings),
                $num($topCategory->total_participations)
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
                'Wilayah dengan keikutsertaan tertinggi adalah %s sebanyak %s keikutsertaan dari %s peserta.',
                $topDistrict->district,
                $num($topDistrict->total_participations),
                $num($topDistrict->total_participants)
            );
        }

        /* ---------- Bagian II: rincian ---------- */

        $details = [];

        $details[] = [
            'title'   => 'DAFTAR KEGIATAN PELATIHAN',
            'columns' => [
                $col('No', 1),
                $col('Nama Pelatihan', 4, 'l'),
                $col('Mulai', 1),
                $col('Selesai', 1),
                $col('Sumber Dana', 2, 'l'),
                $col('Kategori', 2, 'l'),
                $col('Lokasi', 2, 'l'),
                $col('Peserta', 1, 'c', $int),
                $col('Keikutsertaan', 1, 'c', $int),
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
                    $dash($r['location'] ?? ''),
                    (int) $r['participants'],
                    (int) $r['participations'],
                ]))
                ->all(),
            'empty'   => 'Belum ada kegiatan pelatihan.',
        ];

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
                $col('Nama Pelatihan', 1, 'l'),
                $col('Mulai', 1),
                $col('Selesai', 1),
                $col('Sumber Dana', 1, 'l'),
                $col('Kategori', 1, 'l'),
                $col('Lokasi', 1, 'l'),
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
                    $dash($r->location ?? ''),
                ]))
                ->all(),
            'empty'   => 'Tidak terdapat data yang sesuai dengan filter yang dipilih.',
        ];

        return [
            'meta' => [
                'year'        => $filters['year'],
                'minimum'     => $minimum,
                'district'    => $filters['district'],
                'institution' => $filters['institution'],
                'search'      => $filters['search'],
            ],

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
            'trainings' => $this->detailTrainings(
                $request,
                $filters,
                $label
            ),

            'participants' => $this->detailParticipants(
                $request,
                $filters,
                $label
            ),

            'training' => $this->detailTraining(
                $request,
                $filters
            ),

            'participant' => $this->detailParticipant(
                $request,
                $filters
            ),

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

        $uniqueParticipants = $this->scopedQuery(
            $request,
            $filters
        )
            ->distinct()
            ->count('training_histories.participant_id');

        $total = $rows->count();

        return response()->json([
            'view' => 'trainings',
            'title' => 'Daftar kegiatan pelatihan',
            'subtitle' => $label ?: 'Tahun ' . $filters['year'],

            'summary' => [
                [
                    'label' => 'Kegiatan',
                    'value' => $total,
                ],
                [
                    'label' => 'Peserta unik',
                    'value' => $uniqueParticipants,
                ],
                [
                    'label' => 'Keikutsertaan',
                    'value' => (int) $rows->sum('total_participations'),
                ],
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
            'title' => 'Daftar peserta',
            'subtitle' => $label ?: 'Tahun ' . $filters['year'],

            'summary' => [
                [
                    'label' => 'Peserta unik',
                    'value' => $total,
                ],
                [
                    'label' => 'Keikutsertaan',
                    'value' => (int) $rows->sum('participations'),
                ],
            ],

            'list_title' => 'Peserta (terbanyak ikut di atas)',
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
            [
                'label' => 'Nama pelatihan',
                'value' => $mapped['name'],
            ],
            [
                'label' => 'Tanggal',
                'value' => $this->formatDateRange(
                    $mapped['start_date'],
                    $mapped['end_date']
                ),
            ],
            [
                'label' => 'Sumber dana',
                'value' => $mapped['funding_source'],
            ],
            [
                'label' => 'Kategori',
                'value' => $mapped['category'],
            ],
        ];

        if (!empty($mapped['location'])) {
            $info[] = [
                'label' => 'Lokasi',
                'value' => $mapped['location'],
            ];
        }

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

            $info[] = [
                'label' => $fieldLabel,
                'value' => (string) $value,
            ];
        }

        $total = $participantRows->count();

        $info[] = [
            'label' => 'Peserta unik',
            'value' => (string) $total,
        ];

        return response()->json([
            'view' => 'training',
            'title' => $mapped['name'],
            'subtitle' => $this->formatDateRange(
                $mapped['start_date'],
                $mapped['end_date']
            ),

            'summary' => [
                [
                    'label' => 'Peserta',
                    'value' => $total,
                ],
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
                [
                    'label' => 'Keikutsertaan',
                    'value' => (int) $rows->sum('total_participations'),
                ],
                [
                    'label' => 'Kegiatan',
                    'value' => $total,
                ],
            ],

            'info' => [
                [
                    'label' => 'Nama',
                    'value' => $participant->name ?: '-',
                ],
                [
                    'label' => 'NIK',
                    'value' => $participant->nik ?: '-',
                ],
                [
                    'label' => 'NIP',
                    'value' => $participant->nip ?: '-',
                ],
                [
                    'label' => 'Profesi',
                    'value' => $participant->profession ?: '-',
                ],
                [
                    'label' => 'Instansi',
                    'value' => $participant->institution ?: '-',
                ],
                [
                    'label' => 'Kabupaten/Kota',
                    'value' => $participant->district ?: '-',
                ],
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

    private function filtersFrom(
        Request $request,
        int $year
    ): array {
        return [
            'year' => $year,
            'search' => trim((string) $request->input('search', '')),
            'district' => trim((string) $request->input('district', '')),
            'institution' => trim((string) $request->input('institution', '')),
        ];
    }

    private function historyInYear(int $year): \Closure
    {
        return function ($query) use ($year) {
            $query->whereHas(
                'training',
                function ($trainingQuery) use ($year) {
                    $trainingQuery->whereYear('start_date', $year);
                }
            );
        };
    }

    private function participantQuery(
        array $filters,
        int $minimum
    ) {
        $scope = $this->historyInYear($filters['year']);

        $query = Participant::query()
            ->whereHas('histories', $scope)
            ->withCount([
                'histories as participations_count' => $scope,
            ]);

        $this->applyParticipantFilters($query, $filters);

        return $query
            ->having('participations_count', '>=', $minimum)
            ->orderByDesc('participations_count')
            ->orderBy('name');
    }

    private function historyQuery(
        array $filters,
        bool $includeEmpty = false
    ) {
        $hasParticipantFilter =
            $filters['search'] !== ''
            || $filters['district'] !== ''
            || $filters['institution'] !== '';

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
                $sub->where(
                    'participants.name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'participants.nik',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'participants.nip',
                        'like',
                        "%{$search}%"
                    );
            });
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
            || $request->filled('participant_id');

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
            $query->where(
                'trainings.id',
                (int) $request->input('training_id')
            );
        }

        if ($request->filled('participant_id')) {
            $query->where(
                'training_histories.participant_id',
                (int) $request->input('participant_id')
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
            ->selectRaw(
                'COUNT(DISTINCT training_histories.id) AS participations'
            )
            ->groupBy($plain)
            ->orderByRaw(
                'COUNT(DISTINCT training_histories.id) DESC'
            )
            ->orderBy('participants.name');
    }

    /* ============================================================
     | PEMETAAN DATA PELATIHAN
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

    /* ============================================================
     | PEMETAAN DATA PESERTA
     * ============================================================ */

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
     | FILTER PESERTA
     * ============================================================ */

    private function applyParticipantFilters(
        $query,
        array $filters
    ): void {
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
                $sub->where(
                    'participants.name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'participants.nik',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'participants.nip',
                        'like',
                        "%{$search}%"
                    );
            });
        }
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

            'end' => $pick([
                'end_date',
            ]),

            'location' => $pick([
                'location',
                'venue',
                'place',
                'lokasi',
                'tempat',
            ]),
        ];
    }

    /* ============================================================
     | EKSPRESI NAMA PELATIHAN
     * ============================================================ */

    private function nameExpr(): string
    {
        $column = $this->trainingColumns()['name'];

        return $column
            ? "COALESCE(NULLIF(TRIM(trainings.{$column}), ''), 'Tanpa nama')"
            : "'Pelatihan'";
    }

    private function groupByName($query)
    {
        if ($this->trainingColumns()['name']) {
            $query->groupByRaw($this->nameExpr());
        }

        return $query;
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
            $query->selectRaw(
                'YEAR(start_date) AS year'
            );
        }

        return $query
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter(fn ($year) => (int) $year > 0)
            ->map(fn ($year) => (int) $year)
            ->values();
    }

    /* ============================================================
     | RESOLUSI TAHUN
     * ============================================================ */

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

        if (
            $requestedYear > 0
            && $years->contains($requestedYear)
        ) {
            return $requestedYear;
        }

        return (int) $years->first();
    }
}