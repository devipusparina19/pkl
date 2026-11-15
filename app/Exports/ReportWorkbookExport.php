<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Laporan Eksekutif Riwayat Pelatihan: SATU worksheet.
 *
 * Bagian I  : kop, informasi filter, KPI, diagram pie, tabel rekap, analisis pimpinan.
 * Bagian II : detail peserta (lampiran).
 *
 * Semua angka (KPI, tabel, diagram, analisis) dihitung dari SATU sumber yang sama,
 * yaitu $participations, sehingga ringkasan selalu sama dengan daftar peserta.
 *
 * $participations = daftar BARIS KEIKUTSERTAAN (satu baris = satu peserta pada satu kegiatan).
 * Kunci yang dibaca (semua opsional, yang kosong dianggap "tidak tercatat"):
 *   participant_id, name, nik, gender, profession, institution, district,
 *   training_id, training_name, category, funding_source, start_date, end_date
 *
 * $meta (opsional):
 *   year, minimum, search, district, institution, funding_sources (array),
 *   exported_at (string), logo_path (path file png/jpg)
 */
class ReportWorkbookExport extends DefaultValueBinder implements
    FromArray,
    WithTitle,
    WithEvents,
    WithCharts,
    WithDrawings,
    WithCustomValueBinder
{
    private const SHEET = 'Laporan';
    private const COLS = 12;
    private const CHART_ROWS = 18;
    private const EMPTY = 'Tidak tercatat';

    private const NAVY = '1F3864';
    private const GREY = '595959';
    private const LINE = 'BFBFBF';
    private const TONES = ['1F3864', '2F5597', '1F6B75', '44546A', '6B5B45', '3B4A8F'];

    /** @var array<int, array<int, mixed>> */
    private array $grid = [];
    private array $merges = [];
    private array $styles = [];
    private array $formats = [];
    private array $heights = [];
    private array $chartSpecs = [];
    private array $out = [];

    private array $data = [];
    private int $cursor = 1;
    private int $chartTop = 0;
    private int $detailBand = 0;
    private int $detailHeader = 0;
    private int $detailLast = 0;
    private int $lastRow = 1;

    public function __construct(private array $participations, private array $meta = [])
    {
        $this->data = $this->normalize($participations);
        $this->build();
    }

    /* ------------------------------------------------------------------ */
    /* Kontrak Laravel Excel                                               */
    /* ------------------------------------------------------------------ */

    public function title(): string
    {
        return self::SHEET;
    }

    public function array(): array
    {
        return $this->out;
    }

    /** NIK 16 digit harus tetap teks (jangan jadi angka ilmiah). */
    public function bindValue(Cell $cell, mixed $value = null): bool
    {
        if (is_string($value) && ($value !== '') && (ctype_digit($value) && strlen($value) >= 10 || $value[0] === '=')) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function drawings(): array
    {
        $path = $this->meta['logo_path'] ?? public_path('images/logo-kalsel.png');

        if (!$path || !is_file($path)) {
            return [];
        }

        $logo = new Drawing();
        $logo->setName('Logo');
        $logo->setPath($path);
        $logo->setHeight(74);
        $logo->setCoordinates('A1');
        $logo->setOffsetX(10);
        $logo->setOffsetY(4);

        return [$logo];
    }

    public function charts(): array
    {
        $specs = $this->chartSpecs;
        if (!$specs) {
            return [];
        }

        $top = $this->chartTop;
        $bottom = $top + self::CHART_ROWS - 2;
        $frames = count($specs) === 1 ? [['C', 'J']] : [['A', 'F'], ['G', 'L']];
        $charts = [];

        foreach ($specs as $i => $s) {
            $chart = $this->pie($s);
            $chart->setTopLeftPosition($frames[$i][0] . $top);
            $chart->setBottomRightPosition($frames[$i][1] . $bottom);
            $charts[] = $chart;
        }

        return $charts;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => fn (AfterSheet $event) => $this->decorate($event->sheet->getDelegate()),
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Normalisasi & agregasi                                              */
    /* ------------------------------------------------------------------ */

    private function normalize(array $items): array
    {
        $out = [];

        foreach ($items as $item) {
            if (is_object($item) && method_exists($item, 'toArray')) {
                $item = $item->toArray();
            }
            $item = (array) $item;
            $s = fn (string $key): string => trim((string) ($item[$key] ?? ''));

            $nik = $s('nik');
            $name = $s('name');
            $trainingName = $s('training_name');
            $start = $s('start_date');
            $funding = $s('funding_source');

            $participantKey = $s('participant_id') !== '' ? $s('participant_id') : ($nik !== '' ? $nik : $name);
            $trainingKey = $s('training_id') !== '' ? $s('training_id') : ($trainingName . '|' . $start . '|' . $funding);

            $out[] = [
                'name' => $name,
                'nik' => $nik,
                'gender' => $this->gender($s('gender')),
                'profession' => $s('profession'),
                'institution' => $s('institution'),
                'district' => $s('district'),
                'training_name' => $trainingName,
                'category' => $s('category'),
                'funding_source' => $funding,
                'start_date' => $start,
                'end_date' => $s('end_date'),
                'participant_key' => $participantKey,
                'training_key' => $trainingKey,
            ];
        }

        return $out;
    }

    private function gender(string $value): string
    {
        $v = mb_strtolower($value);

        return match (true) {
            in_array($v, ['l', 'lk', 'laki-laki', 'laki laki', 'pria', 'male', 'm'], true) => 'Laki-laki',
            in_array($v, ['p', 'pr', 'perempuan', 'wanita', 'female', 'f'], true) => 'Perempuan',
            default => '',
        };
    }

    private function recap(Collection $rows, string $field, string $sort = 'participations'): array
    {
        return $rows
            ->groupBy(fn ($r) => $r[$field] !== '' ? $r[$field] : self::EMPTY)
            ->map(fn (Collection $g, $label) => [
                'label' => (string) $label,
                'trainings' => $g->pluck('training_key')->unique()->count(),
                'participants' => $g->pluck('participant_key')->unique()->count(),
                'participations' => $g->count(),
            ])
            ->filter(fn ($x) => $x['participations'] > 0)
            ->sort(fn ($a, $b) => [$b[$sort], $a['label']] <=> [$a[$sort], $b['label']])
            ->values()
            ->all();
    }

    private function hasReal(array $recap): bool
    {
        foreach ($recap as $row) {
            if ($row['label'] !== self::EMPTY) {
                return true;
            }
        }

        return false;
    }

    private function firstReal(array $recap): ?array
    {
        foreach ($recap as $row) {
            if ($row['label'] !== self::EMPTY) {
                return $row;
            }
        }

        return null;
    }

    private function n(int|float $v): string
    {
        return number_format($v, 0, ',', '.');
    }

    private function p(float $v): string
    {
        return number_format($v * 100, 1, ',', '.') . '%';
    }

    private function date(string $value): string
    {
        if ($value === '') {
            return '';
        }

        try {
            return Carbon::parse($value)->format('d/m/Y');
        } catch (\Throwable) {
            return $value;
        }
    }

    /* ------------------------------------------------------------------ */
    /* Helper grid                                                         */
    /* ------------------------------------------------------------------ */

    private function col(int $c): string
    {
        return Coordinate::stringFromColumnIndex($c);
    }

    private function cell(int $r, int $c, mixed $v): void
    {
        $this->grid[$r][$c] = $v;
    }

    private function merge(int $r, int $c1, int $c2): void
    {
        if ($c2 > $c1) {
            $this->merges[] = $this->col($c1) . $r . ':' . $this->col($c2) . $r;
        }
    }

    private function style(int $r1, int $c1, int $r2, int $c2, array $style): void
    {
        $this->styles[] = [$this->col($c1) . $r1 . ':' . $this->col($c2) . $r2, $style];
    }

    private function format(int $r1, int $c1, int $r2, int $c2, string $code): void
    {
        $this->formats[] = [$this->col($c1) . $r1 . ':' . $this->col($c2) . $r2, $code];
    }

    private function font(int $size = 10, bool $bold = false, string $color = '000000', bool $italic = false): array
    {
        return ['name' => 'Arial', 'size' => $size, 'bold' => $bold, 'italic' => $italic, 'color' => ['rgb' => $color]];
    }

    private function fill(string $rgb): array
    {
        return ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $rgb]];
    }

    private function box(string $color = self::LINE, string $style = Border::BORDER_THIN): array
    {
        return ['allBorders' => ['borderStyle' => $style, 'color' => ['rgb' => $color]]];
    }

    private function align(string $h, bool $wrap = true, int $indent = 0): array
    {
        return ['horizontal' => $h, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => $wrap, 'indent' => $indent];
    }

    private function band(string $text): void
    {
        $r = $this->cursor;
        $this->cell($r, 1, $text);
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(12, true, 'FFFFFF'),
            'fill' => $this->fill(self::NAVY),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false, 1),
        ]);
        $this->heights[$r] = 24;
        $this->cursor++;
    }

    /* ------------------------------------------------------------------ */
    /* Penyusunan laporan                                                  */
    /* ------------------------------------------------------------------ */

    private function build(): void
    {
        $this->kop();
        $this->info();

        $rows = collect($this->data);

        if ($rows->isEmpty()) {
            $this->cursor++;
            $r = $this->cursor;
            $this->cell($r, 1, 'Tidak terdapat data pelatihan yang sesuai dengan filter yang dipilih.');
            $this->merge($r, 1, self::COLS);
            $this->style($r, 1, $r, self::COLS, [
                'font' => $this->font(11, true, self::GREY),
                'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
                'borders' => $this->box(),
            ]);
            $this->heights[$r] = 40;
            $this->cursor++;
            $this->finish();

            return;
        }

        $year = (string) ($this->meta['year'] ?? '');

        $total = [
            'trainings' => $rows->pluck('training_key')->unique()->count(),
            'participants' => $rows->pluck('participant_key')->unique()->count(),
            'participations' => $rows->count(),
        ];

        $fund = $this->recap($rows, 'funding_source');
        $cat = $this->recap($rows, 'category');
        $prof = $this->recap($rows, 'profession', 'participants');
        $gen = $this->recap($rows, 'gender', 'participants');
        $dist = $this->recap($rows, 'district');
        $train = $this->recap($rows, 'training_name', 'participants');

        $realCount = fn (string $f): int => $rows->pluck($f)->filter(fn ($v) => $v !== '')->unique()->count();

        $this->cursor++;
        $this->band('BAGIAN I — LAPORAN EKSEKUTIF');
        $this->cursor++;

        /* KPI */
        $this->kpis([
            ['Total Pelatihan', $total['trainings']],
            ['Peserta Unik', $total['participants']],
            ['Total Keikutsertaan', $total['participations']],
            ['Kategori Pelatihan', $realCount('category')],
            ['Sumber Dana', $realCount('funding_source')],
            ['Kabupaten/Kota', $realCount('district')],
        ]);
        $this->cursor++;

        /* Area diagram */
        $wantGender = $this->hasReal($gen);
        $wantFund = $this->hasReal($fund);

        if ($wantGender || $wantFund) {
            $this->subhead('VISUALISASI DATA');
            $this->chartTop = $this->cursor;
            $this->cursor += self::CHART_ROWS;
        }

        /* Tabel rekap */
        $this->subhead('REKAPITULASI EKSEKUTIF');
        $this->cursor++;

        $tables = [];
        $k = 0;
        $tone = fn () => self::TONES[$k++ % count(self::TONES)];
        $all = ['trainings', 'participants', 'participations'];

        if ($wantFund) {
            $tables['fund'] = $this->table('A. REKAPITULASI SUMBER DANA', 'Persentase dihitung dari total keikutsertaan.', 'Sumber Dana', $all, $fund, $total, 'participations', $tone());
        }
        if ($this->hasReal($cat)) {
            $this->table('B. REKAPITULASI KATEGORI PELATIHAN', 'Persentase dihitung dari total keikutsertaan.', 'Kategori Pelatihan', $all, $cat, $total, 'participations', $tone());
        }
        if ($this->hasReal($prof)) {
            $this->table('C. REKAPITULASI JENIS PROFESI PESERTA', 'Persentase dihitung dari total peserta unik.', 'Jenis Profesi', ['participants'], $prof, $total, 'participants', $tone());
        }
        if ($wantGender) {
            $tables['gender'] = $this->table('D. REKAPITULASI JENIS KELAMIN PESERTA', 'Persentase dihitung dari total peserta unik.', 'Jenis Kelamin', ['participants'], $gen, $total, 'participants', $tone());
        }

        $trainShown = array_slice($train, 0, 15);
        $trainNote = 'Persentase dihitung dari total keikutsertaan.'
            . (count($train) > 15 ? ' Menampilkan 15 dari ' . $this->n(count($train)) . ' pelatihan dengan peserta terbanyak.' : '');
        $this->table('E. REKAPITULASI PELATIHAN BERDASARKAN JUMLAH PESERTA', $trainNote, 'Nama Pelatihan', $all, $trainShown, $total, 'participations', $tone());

        if ($this->hasReal($dist)) {
            $this->table('F. REKAPITULASI WILAYAH (KABUPATEN/KOTA)', 'Persentase dihitung dari total keikutsertaan.', 'Kabupaten/Kota', $all, $dist, $total, 'participations', $tone());
        }

        /* Spesifikasi diagram (sesudah posisi tabel diketahui) */
        if ($wantGender) {
            $t = $tables['gender'];
            $this->chartSpecs[] = [
                'name' => 'chartGender', 'title' => 'Komposisi Peserta Berdasarkan Jenis Kelamin',
                'labelCol' => 'B', 'valueCol' => $t['cols']['participants'],
                'first' => $t['first'], 'last' => $t['last'], 'header' => $t['header'],
            ];
        }
        if ($wantFund) {
            $t = $tables['fund'];
            $this->chartSpecs[] = [
                'name' => 'chartFunding', 'title' => 'Distribusi Keikutsertaan Berdasarkan Sumber Dana',
                'labelCol' => 'B', 'valueCol' => $t['cols']['participations'],
                'first' => $t['first'], 'last' => $t['last'], 'header' => $t['header'],
            ];
        }

        /* Analisis */
        $this->insights($year, $total, $fund, $cat, $prof, $gen, $train, $dist);

        /* Bagian II */
        $this->cursor++;
        $this->detailBand = $this->cursor;
        $this->band('BAGIAN II — DETAIL DATA PESERTA');
        $this->detail($rows);

        $this->finish();
    }

    private function kop(): void
    {
        $lines = [
            ['PEMERINTAH PROVINSI KALIMANTAN SELATAN', 12, 20],
            ['DINAS KESEHATAN', 14, 22],
            ['UPTD BALAI PELATIHAN KESEHATAN', 16, 26],
        ];

        foreach ($lines as [$text, $size, $height]) {
            $r = $this->cursor;
            $this->cell($r, 1, $text);
            $this->merge($r, 1, self::COLS);
            $this->style($r, 1, $r, self::COLS, [
                'font' => $this->font($size, true, self::NAVY),
                'alignment' => $this->align(Alignment::HORIZONTAL_CENTER, false),
            ]);
            $this->heights[$r] = $height;
            $this->cursor++;
        }

        // garis kop
        $this->style($this->cursor - 1, 1, $this->cursor - 1, self::COLS, [
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => self::NAVY]]],
        ]);
        $this->heights[$this->cursor] = 6;
        $this->cursor++;

        $year = (string) ($this->meta['year'] ?? '');

        $r = $this->cursor;
        $this->cell($r, 1, 'LAPORAN EKSEKUTIF RIWAYAT PELATIHAN');
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(16, true, self::NAVY),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER, false),
        ]);
        $this->heights[$r] = 30;
        $this->cursor++;

        $r = $this->cursor;
        $this->cell($r, 1, $year !== '' ? 'TAHUN ' . $year : '');
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(12, true, self::GREY),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER, false),
        ]);
        $this->heights[$r] = 20;
        $this->cursor++;
        $this->cursor++;
    }

    private function info(): void
    {
        $sources = array_values(array_filter((array) ($this->meta['funding_sources'] ?? [])));
        $exported = $this->meta['exported_at'] ?? Carbon::now('Asia/Makassar')->format('d/m/Y H:i') . ' WITA';

        $items = [
            ['Tahun Pelatihan', (string) ($this->meta['year'] ?? '-')],
            ['Tanggal dan Waktu Ekspor', $exported],
            ['Sumber Dana', $sources ? implode(', ', $sources) : 'Semua sumber dana'],
            ['Kabupaten/Kota', ($this->meta['district'] ?? '') !== '' ? $this->meta['district'] : 'Semua kabupaten/kota'],
            ['Instansi', ($this->meta['institution'] ?? '') !== '' ? $this->meta['institution'] : 'Semua instansi'],
            ['Kata Kunci Peserta', ($this->meta['search'] ?? '') !== '' ? $this->meta['search'] : '-'],
            ['Minimal Keikutsertaan', ($this->meta['minimum'] ?? 1) . ' kali'],
        ];

        $first = $this->cursor;

        foreach ($items as [$label, $value]) {
            $r = $this->cursor;
            $this->cell($r, 1, $label);
            $this->merge($r, 1, 3);
            $this->cell($r, 4, ': ' . $value);
            $this->merge($r, 4, self::COLS);
            $this->cursor++;
        }

        $last = $this->cursor - 1;
        $this->style($first, 1, $last, self::COLS, [
            'font' => $this->font(10),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false, 1),
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_HAIR, 'color' => ['rgb' => self::LINE]]],
        ]);
        $this->style($first, 1, $last, 3, ['font' => $this->font(10, true, self::NAVY)]);
        $this->style($first - 1, 1, $first - 1, 1, []);
    }

    private function subhead(string $text): void
    {
        $r = $this->cursor;
        $this->cell($r, 1, $text);
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(11, true, self::NAVY),
            'fill' => $this->fill('E7ECF5'),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false, 1),
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => self::NAVY]]],
        ]);
        $this->heights[$r] = 22;
        $this->cursor++;
    }

    private function kpis(array $items): void
    {
        $label = $this->cursor;
        $value = $label + 1;

        foreach ($items as $i => [$text, $number]) {
            $c1 = 1 + $i * 2;
            $c2 = $c1 + 1;

            $this->cell($label, $c1, $text);
            $this->merge($label, $c1, $c2);
            $this->cell($value, $c1, (int) $number);
            $this->merge($value, $c1, $c2);

            $this->style($label, $c1, $label, $c2, [
                'font' => $this->font(9, true, 'FFFFFF'),
                'fill' => $this->fill(self::NAVY),
                'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
                'borders' => $this->box('FFFFFF', Border::BORDER_MEDIUM),
            ]);
            $this->style($value, $c1, $value, $c2, [
                'font' => $this->font(20, true, self::NAVY),
                'fill' => $this->fill('F2F5FA'),
                'alignment' => $this->align(Alignment::HORIZONTAL_CENTER, false),
                'borders' => $this->box('FFFFFF', Border::BORDER_MEDIUM),
            ]);
            $this->format($value, $c1, $value, $c2, '#,##0');
        }

        $this->heights[$label] = 26;
        $this->heights[$value] = 40;
        $this->cursor += 2;
    }

    /**
     * Tabel rekap. Label di kolom B..label-end, setiap metrik memakai 2 kolom,
     * persentase selalu di dua kolom terakhir (K:L).
     */
    private function table(string $title, string $note, string $labelHeader, array $metrics, array $recap, array $total, string $basis, string $tone): array
    {
        $heads = ['trainings' => 'Jumlah Kegiatan', 'participants' => 'Peserta Unik', 'participations' => 'Keikutsertaan'];
        $labelEnd = self::COLS - 2 * (count($metrics) + 1);
        $pc = self::COLS - 1;
        $cols = [];

        $r = $this->cursor;

        $this->cell($r, 1, $title);
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(11, true, $tone),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false, 1),
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => $tone]]],
        ]);
        $this->heights[$r] = 22;
        $r++;

        $this->cell($r, 1, $note);
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(9, false, self::GREY, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false, 1),
        ]);
        $r++;

        $header = $r;
        $this->cell($r, 1, 'No');
        $this->cell($r, 2, $labelHeader);
        $this->merge($r, 2, $labelEnd);
        foreach ($metrics as $i => $key) {
            $c = $labelEnd + 1 + 2 * $i;
            $cols[$key] = $this->col($c);
            $this->cell($r, $c, $heads[$key]);
            $this->merge($r, $c, $c + 1);
        }
        $this->cell($r, $pc, 'Persentase');
        $this->merge($r, $pc, $pc + 1);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(10, true, 'FFFFFF'),
            'fill' => $this->fill($tone),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
            'borders' => $this->box('FFFFFF'),
        ]);
        $this->heights[$r] = 26;
        $r++;

        $first = $r;
        $denominator = max(1, (int) $total[$basis]);

        foreach ($recap as $i => $row) {
            $this->cell($r, 1, $i + 1);
            $this->cell($r, 2, $row['label']);
            $this->merge($r, 2, $labelEnd);
            foreach ($metrics as $j => $key) {
                $c = $labelEnd + 1 + 2 * $j;
                $this->cell($r, $c, (int) $row[$key]);
                $this->merge($r, $c, $c + 1);
            }
            $this->cell($r, $pc, $row[$basis] / $denominator);
            $this->merge($r, $pc, $pc + 1);
            $r++;
        }

        $last = $r - 1;

        $this->style($first, 1, $last, self::COLS, [
            'font' => $this->font(10),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
            'borders' => $this->box(),
        ]);
        $this->style($first, 2, $last, $labelEnd, ['alignment' => $this->align(Alignment::HORIZONTAL_LEFT, true, 1)]);
        $this->format($first, $labelEnd + 1, $last + 1, $pc - 1, '#,##0');
        $this->format($first, $pc, $last + 1, $pc, '0.0%');

        // baris total
        $this->cell($r, 2, 'TOTAL');
        $this->merge($r, 2, $labelEnd);
        foreach ($metrics as $j => $key) {
            $c = $labelEnd + 1 + 2 * $j;
            $this->cell($r, $c, (int) $total[$key]);
            $this->merge($r, $c, $c + 1);
        }
        $this->cell($r, $pc, 1);
        $this->merge($r, $pc, $pc + 1);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(10, true, self::NAVY),
            'fill' => $this->fill('E7ECF5'),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::LINE]],
                'top' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => $tone]],
            ],
        ]);
        $this->style($r, 2, $r, $labelEnd, ['alignment' => $this->align(Alignment::HORIZONTAL_LEFT, true, 1)]);

        $this->cursor = $r + 2;

        return ['first' => $first, 'last' => $last, 'header' => $header, 'cols' => $cols];
    }

    private function insights(string $year, array $total, array $fund, array $cat, array $prof, array $gen, array $train, array $dist): void
    {
        $T = max(1, $total['trainings']);
        $lines = [];

        $lines[] = sprintf(
            'Pada %s terselenggara %s kegiatan pelatihan dengan %s peserta unik dan total %s keikutsertaan, rata-rata %s keikutsertaan per kegiatan.',
            $year !== '' ? 'tahun ' . $year : 'periode laporan',
            $this->n($total['trainings']),
            $this->n($total['participants']),
            $this->n($total['participations']),
            number_format($total['participations'] / $T, 1, ',', '.')
        );

        if ($top = $this->firstReal($fund)) {
            $lines[] = sprintf(
                'Sumber dana terbesar adalah %s dengan %s kegiatan dan %s keikutsertaan (%s dari seluruh keikutsertaan). Pelatihan dibiayai dari %s sumber dana.',
                $top['label'], $this->n($top['trainings']), $this->n($top['participations']),
                $this->p($top['participations'] / max(1, $total['participations'])),
                $this->n(count(array_filter($fund, fn ($f) => $f['label'] !== self::EMPTY)))
            );
        }

        if ($top = $this->firstReal($cat)) {
            $lines[] = sprintf(
                'Kategori pelatihan yang paling dominan adalah %s dengan %s kegiatan dan %s keikutsertaan (%s).',
                $top['label'], $this->n($top['trainings']), $this->n($top['participations']),
                $this->p($top['participations'] / max(1, $total['participations']))
            );
        }

        if ($this->hasReal($gen)) {
            $parts = array_map(
                fn ($g) => sprintf('%s %s orang (%s)', $g['label'], $this->n($g['participants']), $this->p($g['participants'] / max(1, $total['participants']))),
                $gen
            );
            $lines[] = 'Komposisi peserta berdasarkan jenis kelamin: ' . implode(', ', $parts) . '.';
        }

        if ($top = $this->firstReal($prof)) {
            $lines[] = sprintf(
                'Profesi dengan jumlah peserta terbanyak adalah %s sebanyak %s orang (%s dari peserta unik).',
                $top['label'], $this->n($top['participants']), $this->p($top['participants'] / max(1, $total['participants']))
            );
        }

        if ($train && $train[0]['label'] !== self::EMPTY) {
            $lines[] = sprintf(
                'Pelatihan dengan peserta terbanyak adalah "%s" dengan %s peserta unik dari %s kegiatan.',
                $train[0]['label'], $this->n($train[0]['participants']), $this->n($train[0]['trainings'])
            );
        }

        if ($top = $this->firstReal($dist)) {
            $lines[] = sprintf(
                'Wilayah dengan keikutsertaan tertinggi adalah %s sebanyak %s keikutsertaan dari %s peserta unik.',
                $top['label'], $this->n($top['participations']), $this->n($top['participants'])
            );
        }

        $this->subhead('ANALISIS SINGKAT UNTUK PIMPINAN');
        $first = $this->cursor;

        foreach ($lines as $i => $text) {
            $r = $this->cursor;
            $this->cell($r, 1, $i + 1);
            $this->cell($r, 2, $text);
            $this->merge($r, 2, self::COLS);
            $this->heights[$r] = max(22, (int) ceil(mb_strlen($text) / 140) * 15 + 8);
            $this->cursor++;
        }

        $last = $this->cursor - 1;
        $this->style($first, 1, $last, self::COLS, [
            'font' => $this->font(10),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, true, 1),
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_HAIR, 'color' => ['rgb' => self::LINE]]],
        ]);
        $this->style($first, 1, $last, 1, ['font' => $this->font(10, true, self::NAVY), 'alignment' => $this->align(Alignment::HORIZONTAL_CENTER)]);
    }

    private function detail(Collection $rows): void
    {
        $r = $this->cursor;
        $this->cell($r, 1, 'Data pendukung: ' . $this->n($rows->count()) . ' baris keikutsertaan peserta, sesuai filter yang sama dengan ringkasan di atas.');
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(9, false, self::GREY, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false, 1),
        ]);
        $this->cursor++;

        $heads = ['No', 'Nama Peserta', 'NIK', 'Jenis Kelamin', 'Profesi', 'Instansi', 'Kabupaten/Kota', 'Nama Pelatihan', 'Kategori', 'Sumber Dana', 'Tanggal Mulai', 'Tanggal Selesai'];
        $this->detailHeader = $this->cursor;

        foreach ($heads as $i => $h) {
            $this->cell($this->cursor, $i + 1, $h);
        }
        $this->style($this->cursor, 1, $this->cursor, self::COLS, [
            'font' => $this->font(10, true, 'FFFFFF'),
            'fill' => $this->fill(self::NAVY),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
            'borders' => $this->box('FFFFFF'),
        ]);
        $this->heights[$this->cursor] = 28;
        $this->cursor++;

        $first = $this->cursor;
        $sorted = $rows->sort(fn ($a, $b) => [mb_strtolower($a['name']), $a['start_date']] <=> [mb_strtolower($b['name']), $b['start_date']])->values();

        foreach ($sorted as $i => $p) {
            $r = $this->cursor;
            $this->grid[$r] = [
                1 => $i + 1,
                2 => $p['name'] ?: '-',
                3 => $p['nik'] ?: '-',
                4 => $p['gender'] ?: '-',
                5 => $p['profession'] ?: '-',
                6 => $p['institution'] ?: '-',
                7 => $p['district'] ?: '-',
                8 => $p['training_name'] ?: '-',
                9 => $p['category'] ?: '-',
                10 => $p['funding_source'] ?: '-',
                11 => $this->date($p['start_date']) ?: '-',
                12 => $this->date($p['end_date']) ?: '-',
            ];
            $this->cursor++;
        }

        $this->detailLast = $this->cursor - 1;

        $this->style($first, 1, $this->detailLast, self::COLS, [
            'font' => $this->font(9),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, true, 0),
            'borders' => $this->box(),
        ]);
        foreach ([1, 3, 4, 11, 12] as $c) {
            $this->style($first, $c, $this->detailLast, $c, ['alignment' => $this->align(Alignment::HORIZONTAL_CENTER)]);
        }
        $this->format($first, 3, $this->detailLast, 3, '@');
    }

    private function finish(): void
    {
        $this->lastRow = max(1, $this->cursor - 1);

        for ($r = 1; $r <= $this->lastRow; $r++) {
            $line = [];
            for ($c = 1; $c <= self::COLS; $c++) {
                $line[] = $this->grid[$r][$c] ?? null;
            }
            $this->out[] = $line;
        }
    }

    /* ------------------------------------------------------------------ */
    /* Diagram pie asli (bisa diedit di Excel)                             */
    /* ------------------------------------------------------------------ */

    private function pie(array $s): Chart
    {
        $n = $s['last'] - $s['first'] + 1;
        $ref = fn (string $col, int $a, int $b): string => sprintf("'%s'!$%s$%d:$%s$%d", self::SHEET, $col, $a, $col, $b);

        $label = [new DataSeriesValues(
            DataSeriesValues::DATASERIES_TYPE_STRING,
            sprintf("'%s'!$%s$%d", self::SHEET, $s['valueCol'], $s['header']),
            null,
            1
        )];
        $categories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $ref($s['labelCol'], $s['first'], $s['last']), null, $n)];
        $values = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $ref($s['valueCol'], $s['first'], $s['last']), null, $n)];

        $series = new DataSeries(DataSeries::TYPE_PIECHART, null, range(0, count($values) - 1), $label, $categories, $values);

        $layout = new Layout();
        $layout->setShowPercent(true);
        $layout->setShowVal(false);
        $layout->setShowCatName(false);
        $layout->setShowSerName(false);
        $layout->setShowLegendKey(false);
        $layout->setShowLeaderLines(true);

        return new Chart(
            $s['name'],
            new Title($s['title']),
            new Legend(Legend::POSITION_RIGHT, null, false),
            new PlotArea($layout, [$series]),
            true,
            'gap'
        );
    }

    /* ------------------------------------------------------------------ */
    /* Format, tata letak, dan pengaturan cetak                            */
    /* ------------------------------------------------------------------ */

    private function decorate(Worksheet $ws): void
    {
        $widths = [1 => 8, 2 => 26, 3 => 20, 4 => 16, 5 => 20, 6 => 24, 7 => 20, 8 => 28, 9 => 20, 10 => 18, 11 => 14, 12 => 14];
        foreach ($widths as $c => $w) {
            $ws->getColumnDimension($this->col($c))->setWidth($w);
        }

        $ws->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        foreach ($this->merges as $range) {
            $ws->mergeCells($range);
        }
        foreach ($this->styles as [$range, $style]) {
            if ($style) {
                $ws->getStyle($range)->applyFromArray($style);
            }
        }
        foreach ($this->formats as [$range, $code]) {
            $ws->getStyle($range)->getNumberFormat()->setFormatCode($code);
        }
        foreach ($this->heights as $row => $h) {
            $ws->getRowDimension($row)->setRowHeight($h);
        }

        if ($this->detailHeader > 0 && $this->detailLast > $this->detailHeader) {
            $ws->setAutoFilter('A' . $this->detailHeader . ':' . $this->col(self::COLS) . $this->detailLast);
            $ws->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd($this->detailHeader, $this->detailHeader);
        }
        if ($this->detailBand > 1) {
            $ws->setBreak('A' . ($this->detailBand - 1), Worksheet::BREAK_ROW);
        }

        $ws->setShowGridlines(false);
        $ws->getSheetView()->setZoomScale(90);

        $page = $ws->getPageSetup();
        $page->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $page->setPaperSize(PageSetup::PAPERSIZE_A4);
        $page->setFitToPage(true);
        $page->setFitToWidth(1);
        $page->setFitToHeight(0);
        $page->setHorizontalCentered(true);
        $page->setPrintArea('A1:' . $this->col(self::COLS) . $this->lastRow);

        $ws->getPageMargins()->setTop(0.6)->setBottom(0.7)->setLeft(0.4)->setRight(0.4)->setHeader(0.3)->setFooter(0.3);
        $ws->getHeaderFooter()->setOddFooter('&L&8UPTD Balai Pelatihan Kesehatan - Dinas Kesehatan Provinsi Kalimantan Selatan&C&8Halaman &P dari &N&R&8Dicetak &D &T');
    }
}