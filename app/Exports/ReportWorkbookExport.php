<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
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
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Laporan Eksekutif Riwayat Pelatihan (Excel, portrait, satu worksheet "Laporan").
 * Isi = semua yang tampil di halaman laporan + diagram pie + analisis pimpinan.
 * Tanpa kop surat dan tanpa logo.
 *
 * Payload (disiapkan ReportController::workbookPayload):
 *  meta     : year, minimum, district, institution, search
 *  kpis     : [[label, value], ...]
 *  tables   : tabel Bagian I
 *  insights : [kalimat analisis, ...]
 *  details  : tabel Bagian II
 *
 * Tiap tabel: title, note?, columns[{label,w,align,format?}], rows[{cells,bold?}],
 *             empty, total?{cells}, pie?{label,value,title,name}, filter?
 */
class ReportWorkbookExport extends DefaultValueBinder implements
    FromArray,
    WithTitle,
    WithEvents,
    WithCharts,
    WithCustomValueBinder
{
    private const SHEET = 'Laporan';
    private const COLS = 12;
    private const CHART_ROWS = 19;
    private const BLACK = '000000';
    private const SYSTEM = 'ARTA - Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan';

    /** Lebar kolom A..L */
    private const WIDTHS = [5, 20, 18, 13, 17, 13, 24, 11, 11, 11, 14, 13];

    /** Warna irisan pie */
    private const PALETTE = ['2457D6', '0E8FB3', '38C6E8', '173D86', '3478F6', '64748B', '081B33', '9DB7E8'];

    private array $grid = [];
    private array $merges = [];
    private array $styles = [];
    private array $formats = [];
    private array $heights = [];
    private array $chartSpecs = [];
    private array $out = [];

    private int $chartTop = 0;
    private ?array $filterRange = null;
    private int $detailBand = 0;

    private int $cursor = 1;
    private int $lastRow = 1;
    private int $letter = 0;

    public function __construct(private array $report)
    {
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

    /** NIK/NIP (angka panjang) dan angka berawalan 0 tetap teks. */
    public function bindValue(Cell $cell, mixed $value = null): bool
    {
        if (
            is_string($value)
            && $value !== ''
            && (
                (ctype_digit($value) && (strlen($value) >= 10 || $value[0] === '0'))
                || $value[0] === '='
            )
        ) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
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

        foreach (array_slice($specs, 0, 2) as $i => $s) {
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

    /** Semua font selalu hitam. */
    private function font(int $size = 10, bool $bold = false, bool $italic = false, bool $underline = false): array
    {
        $font = [
            'name' => 'Arial',
            'size' => $size,
            'bold' => $bold,
            'italic' => $italic,
            'color' => ['rgb' => self::BLACK],
        ];

        if ($underline) {
            $font['underline'] = 'single';
        }

        return $font;
    }

    private function box(string $style = Border::BORDER_THIN): array
    {
        return ['allBorders' => ['borderStyle' => $style, 'color' => ['rgb' => self::BLACK]]];
    }

    private function align(string $h, bool $wrap = true, int $indent = 0): array
    {
        return ['horizontal' => $h, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => $wrap, 'indent' => $indent];
    }

    private function fill(string $rgb): array
    {
        return ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $rgb]];
    }

    /** Bagi COLS kolom ke beberapa sel menurut bobot (jumlah selalu = COLS). */
    private function spans(array $weights): array
    {
        $weights = array_values($weights);
        $total = max(1, array_sum($weights));
        $ideal = array_map(fn ($w) => $w / $total * self::COLS, $weights);
        $spans = array_map(fn ($v) => max(1, (int) floor($v)), $ideal);
        $diff = self::COLS - array_sum($spans);

        while ($diff > 0) {
            $best = 0;
            $bestR = -INF;
            foreach ($spans as $i => $s) {
                $r = $ideal[$i] - $s;
                if ($r > $bestR) {
                    $bestR = $r;
                    $best = $i;
                }
            }
            $spans[$best]++;
            $diff--;
        }

        while ($diff < 0) {
            $best = array_search(max($spans), $spans, true);
            if ($spans[$best] <= 1) {
                break;
            }
            $spans[$best]--;
            $diff++;
        }

        return $spans;
    }

    private function band(string $text): void
    {
        $r = $this->cursor;
        $this->cell($r, 1, $text);
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(12, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false),
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => self::BLACK]]],
        ]);
        $this->heights[$r] = 24;
        $this->cursor++;
    }

    private function subhead(string $text): void
    {
        $r = $this->cursor;
        $this->cell($r, 1, $text);
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(11, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false),
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::BLACK]]],
        ]);
        $this->heights[$r] = 22;
        $this->cursor++;
    }

    /* ------------------------------------------------------------------ */
    /* Penyusunan laporan                                                  */
    /* ------------------------------------------------------------------ */

    private function build(): void
    {
        $this->heading();
        $this->info();

        $tables = $this->report['tables'] ?? [];
        $details = $this->report['details'] ?? [];

        $this->cursor++;
        $this->band('BAGIAN I — LAPORAN EKSEKUTIF');
        $this->cursor++;

        $this->kpis($this->report['kpis'] ?? []);
        $this->cursor++;

        $pieCount = 0;
        foreach ($tables as $t) {
            if (!empty($t['pie']) && !empty($t['rows'])) {
                $pieCount++;
            }
        }

        if ($pieCount > 0) {
            $this->subhead('VISUALISASI DATA');
            $this->chartTop = $this->cursor;
            $this->cursor += self::CHART_ROWS;
        }

        $this->subhead('REKAPITULASI');
        $this->cursor++;

        $this->letter = 0;
        foreach ($tables as $t) {
            $this->table($t, false);
        }

        $this->insights($this->report['insights'] ?? []);

        if ($details) {
            $this->cursor++;
            $this->detailBand = $this->cursor;
            $this->band('BAGIAN II — DATA RINCIAN');
            $this->cursor++;

            $this->letter = 0;
            foreach ($details as $t) {
                $this->table($t, true);
            }
        }

        $this->closing();
        $this->finish();
    }

    /** Judul laporan saja (tanpa kop surat, logo, dan tanggal surat). */
    private function heading(): void
    {
        $year = (string) ($this->report['meta']['year'] ?? '');

        $r = $this->cursor;
        $this->cell($r, 1, 'LAPORAN EKSEKUTIF RIWAYAT PELATIHAN');
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(13, true, false, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER, false),
        ]);
        $this->heights[$r] = 26;
        $this->cursor++;

        $r = $this->cursor;
        $this->cell($r, 1, $year !== '' ? 'TAHUN ANGGARAN ' . $year : '');
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(11, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER, false),
        ]);
        $this->heights[$r] = 19;
        $this->cursor += 2;
    }

    private function info(): void
    {
        $m = $this->report['meta'] ?? [];
        $exported = $m['exported_at'] ?? Carbon::now('Asia/Makassar')->format('d/m/Y H:i') . ' WITA';

        $items = [
            ['Sumber Data', self::SYSTEM],
            ['Tahun Pelatihan', (string) ($m['year'] ?? '-')],
            ['Tanggal dan Waktu Ekspor', $exported],
            ['Kabupaten/Kota', ($m['district'] ?? '') !== '' ? $m['district'] : 'Semua kabupaten/kota'],
            ['Instansi', ($m['institution'] ?? '') !== '' ? $m['institution'] : 'Semua instansi'],
            ['Keikutsertaan', ($m['minimum'] ?? 1) . ' kali'],
        ];

        $first = $this->cursor;

        foreach ($items as [$label, $value]) {
            $r = $this->cursor;
            $this->cell($r, 1, $label);
            $this->merge($r, 1, 3);
            $this->cell($r, 4, ': ' . $value);
            $this->merge($r, 4, self::COLS);
            $this->heights[$r] = 17;
            $this->cursor++;
        }

        $this->style($first, 1, $this->cursor - 1, self::COLS, [
            'font' => $this->font(10),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false, 1),
        ]);
    }

    private function kpis(array $items): void
    {
        if (!$items) {
            return;
        }

        $spans = $this->spans(array_fill(0, count($items), 1));
        $label = $this->cursor;
        $value = $label + 1;
        $c = 1;

        foreach ($items as $i => [$text, $number]) {
            $c1 = $c;
            $c2 = $c + $spans[$i] - 1;
            $c = $c2 + 1;

            $this->cell($label, $c1, $text);
            $this->merge($label, $c1, $c2);
            $this->cell($value, $c1, (int) $number);
            $this->merge($value, $c1, $c2);

            $this->style($label, $c1, $label, $c2, [
                'font' => $this->font(9, true),
                'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
                'borders' => $this->box(),
                'fill' => $this->fill('D9D9D9'),
            ]);
            $this->style($value, $c1, $value, $c2, [
                'font' => $this->font(18, true),
                'alignment' => $this->align(Alignment::HORIZONTAL_CENTER, false),
                'borders' => $this->box(),
            ]);
            $this->format($value, $c1, $value, $c1, '#,##0');
        }

        $this->heights[$label] = 28;
        $this->heights[$value] = 38;
        $this->cursor += 2;
    }

    /** Satu tabel generik. */
    private function table(array $t, bool $small): void
    {
        $cols = $t['columns'];
        $rows = $t['rows'] ?? [];
        $spans = $this->spans(array_column($cols, 'w'));
        $fs = $small ? 8 : 10;

        $starts = [];
        $c = 1;
        foreach ($spans as $i => $s) {
            $starts[$i] = $c;
            $c += $s;
        }

        $width = fn (int $i): float => array_sum(array_slice(self::WIDTHS, $starts[$i] - 1, $spans[$i]));

        $r = $this->cursor;

        // judul tabel
        $this->cell($r, 1, chr(65 + $this->letter++) . '. ' . $t['title']);
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(10, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false),
        ]);
        $this->heights[$r] = 20;
        $r++;

        if (!empty($t['note'])) {
            $this->cell($r, 1, $t['note']);
            $this->merge($r, 1, self::COLS);
            $this->style($r, 1, $r, self::COLS, [
                'font' => $this->font(9, false, true),
                'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, false),
            ]);
            $r++;
        }

        // header
        $header = $r;
        $headLines = 1;
        foreach ($cols as $i => $col) {
            $this->cell($r, $starts[$i], $col['label']);
            $this->merge($r, $starts[$i], $starts[$i] + $spans[$i] - 1);
            $headLines = max($headLines, (int) ceil(mb_strlen($col['label']) / max(1, $width($i) * 0.85)));
        }
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font($fs, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
            'borders' => $this->box(),
            'fill' => $this->fill('D9D9D9'),
        ]);
        $this->heights[$r] = max($small ? 24 : 22, $headLines * ($small ? 11 : 13) + 8);
        $r++;

        // tidak ada data
        if (!$rows) {
            $this->cell($r, 1, $t['empty'] ?? 'Tidak ada data.');
            $this->merge($r, 1, self::COLS);
            $this->style($r, 1, $r, self::COLS, [
                'font' => $this->font($fs, false, true),
                'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
                'borders' => $this->box(),
            ]);
            $this->heights[$r] = 22;
            $this->cursor = $r + 2;

            return;
        }

        $first = $r;
        $boldRows = [];

        foreach ($rows as $row) {
            $lines = 1;

            foreach ($cols as $i => $col) {
                $v = $row['cells'][$i] ?? '';
                $v = ($v === null || $v === '') ? '-' : $v;

                $this->cell($r, $starts[$i], $v);
                $this->merge($r, $starts[$i], $starts[$i] + $spans[$i] - 1);

                $len = is_float($v) ? 6 : mb_strlen((string) $v);
                $lines = max($lines, (int) ceil($len / max(1, $width($i) * ($small ? 1.15 : 0.95))));
            }

            $this->heights[$r] = max($small ? 15 : 18, $lines * ($small ? 11 : 13) + 5);

            if (!empty($row['bold'])) {
                $boldRows[] = $r;
            }

            $r++;
        }

        $last = $r - 1;

        $this->style($first, 1, $last, self::COLS, [
            'font' => $this->font($fs),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
            'borders' => $this->box(),
        ]);

        foreach ($cols as $i => $col) {
            if (($col['align'] ?? 'c') === 'l') {
                $this->style($first, $starts[$i], $last, $starts[$i] + $spans[$i] - 1, [
                    'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, true, 1),
                ]);
            }

            if (!empty($col['format'])) {
                $this->format($first, $starts[$i], $last, $starts[$i], $col['format']);
            }
        }

        foreach ($boldRows as $br) {
            $this->style($br, 1, $br, self::COLS, [
                'font' => $this->font($fs, true),
                'fill' => $this->fill('F2F2F2'),
            ]);
        }

        // baris total (tidak ikut dihitung di diagram)
        $next = $last + 1;
        if (!empty($t['total'])) {
            foreach ($cols as $i => $col) {
                $v = $t['total']['cells'][$i] ?? '';
                if ($v !== null && $v !== '') {
                    $this->cell($next, $starts[$i], $v);
                }
                $this->merge($next, $starts[$i], $starts[$i] + $spans[$i] - 1);

                if (!empty($col['format'])) {
                    $this->format($next, $starts[$i], $next, $starts[$i], $col['format']);
                }
            }
            $this->style($next, 1, $next, self::COLS, [
                'font' => $this->font($fs, true),
                'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
                'borders' => $this->box(),
                'fill' => $this->fill('F2F2F2'),
            ]);
            $this->heights[$next] = 19;
            $next++;
        }

        if (!empty($t['filter'])) {
            $this->filterRange = [$header, $last];
        }

        if (!empty($t['pie'])) {
            $this->chartSpecs[] = [
                'name' => $t['pie']['name'] ?? ('chart' . (count($this->chartSpecs) + 1)),
                'title' => $t['pie']['title'] ?? 'Diagram',
                'labelCol' => $this->col($starts[$t['pie']['label']]),
                'valueCol' => $this->col($starts[$t['pie']['value']]),
                'first' => $first,
                'last' => $last,
                'header' => $header,
            ];
        }

        $this->cursor = $next + 1;
    }

    /** Analisis singkat untuk pimpinan. */
    private function insights(array $lines): void
    {
        if (!$lines) {
            return;
        }

        $this->subhead('ANALISIS SINGKAT UNTUK PIMPINAN');
        $first = $this->cursor;

        foreach (array_values($lines) as $i => $text) {
            $r = $this->cursor;
            $this->cell($r, 1, $i + 1);
            $this->cell($r, 2, $text);
            $this->merge($r, 2, self::COLS);
            $this->heights[$r] = max(22, (int) ceil(mb_strlen($text) / 120) * 14 + 8);
            $this->cursor++;
        }

        $last = $this->cursor - 1;

        $this->style($first, 1, $last, self::COLS, [
            'font' => $this->font(10),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, true, 1),
            'borders' => $this->box(),
        ]);
        $this->style($first, 1, $last, 1, [
            'font' => $this->font(10, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_CENTER),
        ]);

        $this->cursor++;
    }

    private function closing(): void
    {
        $this->cursor++;
        $r = $this->cursor;
        $this->cell($r, 1, 'Dokumen ini dihasilkan oleh ' . self::SYSTEM . ', UPTD Balai Pelatihan Kesehatan Provinsi Kalimantan Selatan, berdasarkan data yang tersimpan pada saat dokumen diekspor.');
        $this->merge($r, 1, self::COLS);
        $this->style($r, 1, $r, self::COLS, [
            'font' => $this->font(9, false, true),
            'alignment' => $this->align(Alignment::HORIZONTAL_LEFT, true),
            'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::BLACK]]],
        ]);
        $this->heights[$r] = 28;
        $this->cursor++;
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
    /* Diagram pie penuh (bisa diedit di Excel)                            */
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

        $colors = [];
        for ($i = 0; $i < $n; $i++) {
            $colors[] = self::PALETTE[$i % count(self::PALETTE)];
        }
        $values[0]->setFillColor($colors);

        // TYPE_PIECHART = pie penuh (bukan donat)
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
            new Legend(Legend::POSITION_BOTTOM, null, false),
            new PlotArea($layout, [$series]),
            true,
            'gap'
        );
    }

    /* ------------------------------------------------------------------ */
    /* Format, tata letak, dan pengaturan cetak (PORTRAIT)                 */
    /* ------------------------------------------------------------------ */

    private function decorate(Worksheet $ws): void
    {
        foreach (self::WIDTHS as $i => $w) {
            $ws->getColumnDimension($this->col($i + 1))->setWidth($w);
        }

        $ws->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        foreach ($this->merges as $range) {
            $ws->mergeCells($range);
        }
        foreach ($this->styles as [$range, $style]) {
            $ws->getStyle($range)->applyFromArray($style);
        }
        foreach ($this->formats as [$range, $code]) {
            $ws->getStyle($range)->getNumberFormat()->setFormatCode($code);
        }
        foreach ($this->heights as $row => $h) {
            $ws->getRowDimension($row)->setRowHeight($h);
        }

        if ($this->filterRange && $this->filterRange[1] > $this->filterRange[0]) {
            $ws->setAutoFilter('A' . $this->filterRange[0] . ':' . $this->col(self::COLS) . $this->filterRange[1]);
        }

        if ($this->detailBand > 1) {
            $ws->setBreak('A' . ($this->detailBand - 1), Worksheet::BREAK_ROW);
        }

        $ws->setShowGridlines(false);
        $ws->getSheetView()->setZoomScale(100);

        $page = $ws->getPageSetup();
        $page->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $page->setPaperSize(PageSetup::PAPERSIZE_A4);
        $page->setFitToPage(true);
        $page->setFitToWidth(1);
        $page->setFitToHeight(0);
        $page->setHorizontalCentered(true);
        $page->setPrintArea('A1:' . $this->col(self::COLS) . $this->lastRow);

        $ws->getPageMargins()->setTop(0.5)->setBottom(0.7)->setLeft(0.3)->setRight(0.3)->setHeader(0.3)->setFooter(0.3);
        $ws->getHeaderFooter()->setOddFooter('&L&"Arial,Regular"&8ARTA - UPTD Balai Pelatihan Kesehatan Prov. Kalimantan Selatan&C&"Arial,Regular"&8Halaman &P dari &N&R&"Arial,Regular"&8Dicetak &D &T');
    }
}