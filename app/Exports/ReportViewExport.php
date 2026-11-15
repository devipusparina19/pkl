<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
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
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

/**
 * Pemakaian:
 *   new ReportViewExport('Laporan', $headings, $rows, $meta);
 *
 * $headings/$rows = data lengkap riwayat (bagian "DATA LENGKAP").
 * $meta berisi: title, year, district, institution, search, minimum,
 *               boxes[], gender[], tables[]  (disiapkan ReportController).
 *
 * Susunan baris (INFO_ROWS = 7):
 *   1-3 judul | 4-10 info | 11 kosong | 12 "I. RINGKASAN" | 13-14 kotak
 *   15 kosong | 16 judul diagram | 17-32 ruang diagram
 *   33 "REKAPITULASI" | 34 "A. JENIS KELAMIN" | 35 header | 36+ data
 */
class ReportViewExport extends DefaultValueBinder implements
    FromView,
    WithTitle,
    WithCharts,
    WithColumnWidths,
    WithCustomValueBinder,
    WithEvents
{
    private const SYSTEM = 'ARTA - Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan';
    private const INFO_ROWS = 7;

    private string $sheet;
    private array $headings;
    private array $rows;
    private int $cols;
    private array $genderRecap = [];
    private int $genderTotal = 0;
    private array $boxes = [];
    private array $tables = [];

    public function __construct(string $sheetTitle, array $headings, array $rows, private array $meta = [])
    {
        $t = mb_substr(preg_replace('/[\\\\\/\?\*\[\]:\']/u', '', $sheetTitle), 0, 31);
        $this->sheet = $t !== '' ? $t : 'Laporan';
        $this->headings = array_values($headings);
        $this->rows = array_map(fn ($r) => array_values((array) $r), array_values($rows));
        $this->cols = max(8, count($this->headings) + 1);
        $this->prepare();
    }

    /** Bagi $this->cols kolom ke beberapa sel sesuai bobot (jumlah selalu = cols). */
    private function spans(array $weights): array
    {
        $weights = array_values($weights);
        $total = max(1, array_sum($weights));
        $ideal = array_map(fn ($w) => $w / $total * $this->cols, $weights);
        $spans = array_map(fn ($v) => max(1, (int) floor($v)), $ideal);
        $diff = $this->cols - array_sum($spans);

        while ($diff !== 0) {
            if ($diff > 0) {
                $best = 0;
                $bestR = -1;
                foreach ($spans as $i => $s) {
                    $r = $ideal[$i] - $s;
                    if ($r > $bestR) {
                        $bestR = $r;
                        $best = $i;
                    }
                }
                $spans[$best]++;
                $diff--;
            } else {
                $best = array_search(max($spans), $spans, true);
                if ($spans[$best] <= 1) {
                    break;
                }
                $spans[$best]--;
                $diff++;
            }
        }

        return $spans;
    }

    private function prepare(): void
    {
        // Kotak ringkasan (3 angka hero)
        $boxes = $this->meta['boxes'] ?? [];
        if ($boxes) {
            $spans = $this->spans(array_fill(0, count($boxes), 1));
            foreach ($boxes as $i => $b) {
                $this->boxes[] = ['label' => $b['label'], 'value' => $b['value'], 'span' => $spans[$i]];
            }
        }

        // Jenis kelamin
        foreach (($this->meta['gender'] ?? []) as $g) {
            $this->genderRecap[] = ['label' => $g['label'], 'value' => (int) $g['value']];
        }
        $this->genderTotal = array_sum(array_column($this->genderRecap, 'value'));

        // Tabel rekap: hitung colspan tiap kolom
        foreach (($this->meta['tables'] ?? []) as $t) {
            $spans = $this->spans(array_column($t['columns'], 'w'));
            foreach ($t['columns'] as $i => $c) {
                $t['columns'][$i]['span'] = $spans[$i];
            }
            $this->tables[] = $t;
        }
    }

    private function chartTop(): int
    {
        return self::INFO_ROWS + 10;      // 17
    }

    private function genderHeaderRow(): int
    {
        return self::INFO_ROWS + 28;      // 35
    }

    public function view(): View
    {
        $exportedAt = $this->meta['exported_at']
            ?? Carbon::now('Asia/Makassar')->format('d/m/Y H:i') . ' WITA';

        $dash = fn ($v, $fallback) => (isset($v) && trim((string) $v) !== '') ? $v : $fallback;

        $info = [
            ['Sumber Data', self::SYSTEM],
            ['Tanggal dan Waktu Ekspor', $exportedAt],
            ['Tahun', $this->meta['year'] ?? '-'],
            ['Minimal Keikutsertaan', ($this->meta['minimum'] ?? 1) . ' kali (daftar peserta)'],
            ['Kabupaten/Kota', $dash($this->meta['district'] ?? '', 'Semua kabupaten/kota')],
            ['Instansi', $dash($this->meta['institution'] ?? '', 'Semua instansi')],
            ['Pencarian Peserta', $dash($this->meta['search'] ?? '', '-')],
        ];

        return view('reports.excel', [
            'headings'    => $this->headings,
            'rows'        => $this->rows,
            'meta'        => $this->meta,
            'cols'        => $this->cols,
            'info'        => $info,
            'boxes'       => $this->boxes,
            'genderRecap' => $this->genderRecap,
            'genderTotal' => $this->genderTotal,
            'tables'      => $this->tables,
            'sistem'      => self::SYSTEM,
        ]);
    }

    public function title(): string
    {
        return $this->sheet;
    }

    public function columnWidths(): array
    {
        $w = [1 => 6];
        foreach ($this->headings as $c => $h) {
            $max = mb_strlen((string) $h);
            foreach ($this->rows as $row) {
                $max = max($max, mb_strlen((string) ($row[$c] ?? '')));
            }
            $w[$c + 2] = max(12, min(30, (int) ceil($max * 0.95) + 2));
        }

        $out = [];
        for ($c = 1; $c <= $this->cols; $c++) {
            $out[Coordinate::stringFromColumnIndex($c)] = $w[$c] ?? 14;
        }

        return $out;
    }

    /** NIK/NIP (angka panjang) dan angka berawalan 0 tetap teks. */
    public function bindValue(Cell $cell, mixed $value = null): bool
    {
        if (
            is_string($value)
            && $value !== ''
            && ctype_digit($value)
            && (strlen($value) >= 10 || $value[0] === '0')
        ) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function charts(): array
    {
        $n = count($this->genderRecap);
        if ($n === 0 || $this->genderTotal <= 0) {
            return [];
        }

        $h = $this->genderHeaderRow();
        $first = $h + 1;
        $last = $h + $n;
        $vc = Coordinate::stringFromColumnIndex($this->cols - 1);
        $q = "'" . $this->sheet . "'!";

        $name = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "{$q}\${$vc}\${$h}", null, 1)];
        $cat = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "{$q}\$B\${$first}:\$B\${$last}", null, $n)];
        $val = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "{$q}\${$vc}\${$first}:\${$vc}\${$last}", null, $n)];
        $val[0]->setFillColor(['2457D6', '0E8FB3']);

        // TYPE_PIECHART = pie penuh (bukan donat)
        $series = new DataSeries(DataSeries::TYPE_PIECHART, null, [0], $name, $cat, $val);

        $layout = new Layout();
        $layout->setShowPercent(true);
        $layout->setShowVal(false);
        $layout->setShowCatName(false);
        $layout->setShowSerName(false);
        $layout->setShowLegendKey(false);
        $layout->setShowLeaderLines(true);

        $chart = new Chart(
            'chartGender',
            new Title('Komposisi Peserta Berdasarkan Jenis Kelamin'),
            new Legend(Legend::POSITION_BOTTOM, null, false),
            new PlotArea($layout, [$series]),
            true,
            'gap'
        );

        $top = $this->chartTop();
        $chart->setTopLeftPosition('B' . $top);
        $chart->setBottomRightPosition(Coordinate::stringFromColumnIndex(min($this->cols, 8)) . ($top + 14));

        return [$chart];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $ws = $event->sheet->getDelegate();
                $lastRow = $ws->getHighestRow();
                $lastCol = Coordinate::stringFromColumnIndex($this->cols);

                $ws->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
                    'font' => ['name' => 'Arial', 'size' => 10, 'color' => ['rgb' => '000000']],
                    'alignment' => ['wrapText' => true, 'vertical' => 'center'],
                ]);

                $ws->setShowGridlines(false);

                $page = $ws->getPageSetup();
                $page->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $page->setPaperSize(PageSetup::PAPERSIZE_A4);
                $page->setFitToPage(true);
                $page->setFitToWidth(1);
                $page->setFitToHeight(0);
                $page->setHorizontalCentered(true);
                $page->setPrintArea("A1:{$lastCol}{$lastRow}");

                $ws->getPageMargins()->setTop(0.5)->setBottom(0.7)->setLeft(0.3)->setRight(0.3);
                $ws->getHeaderFooter()->setOddFooter('&L&8ARTA - UPTD Balai Pelatihan Kesehatan Prov. Kalimantan Selatan&C&8Halaman &P dari &N&R&8Dicetak &D &T');
            },
        ];
    }
}