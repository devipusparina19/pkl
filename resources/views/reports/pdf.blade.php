<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>{{ $report['meta']['title'] ?? 'Laporan Lengkap Riwayat Pelatihan' }} {{ $year }}</title>

<style>
    @page {
        size: A4 portrait;
        margin: 28px 40px 40px 40px;
    }

    * { box-sizing: border-box; }

    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 10px;
        color: #000;
        line-height: 1.35;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    /* KOP SURAT */
    .kop td { border: none; padding: 0; vertical-align: middle; }
    .kop-logo { width: 80px; text-align: left; }
    .kop-logo img { width: 66px; height: auto; }
    .kop-text { text-align: center; padding-right: 80px !important; }
    .kop-text .pemerintah,
    .kop-text .dinas {
        font-size: 13px; font-weight: bold;
        text-transform: uppercase; line-height: 1.25;
    }
    .kop-text .balai {
        font-size: 15px; font-weight: bold;
        text-transform: uppercase; line-height: 1.25;
    }
    .kop-text .alamat { margin-top: 2px; font-size: 8px; line-height: 1.35; }
    .kop-garis { margin-top: 6px; border-top: 1.5px solid #000; height: 0; }

    /* TANGGAL & JUDUL */
    .tanggal { margin: 5px 0 8px; text-align: right; font-size: 10px; }
    .judul { text-align: center; margin-bottom: 10px; }
    .judul h1 {
        margin: 0 0 3px; font-size: 13px; font-weight: bold;
        text-transform: uppercase; text-decoration: underline;
    }
    .judul .subjudul { font-size: 10.5px; font-weight: bold; text-transform: uppercase; }

    /* INFORMASI LAPORAN */
    .info { margin-bottom: 6px; font-size: 10px; }
    .info td { border: none; padding: 1.5px 3px; vertical-align: top; }
    .info-label { width: 110px; white-space: nowrap; }
    .info-separator { width: 8px; }

    /* JUDUL BAGIAN */
    .band {
        margin: 12px 0 6px;
        padding-bottom: 3px;
        border-bottom: 2px solid #000;
        font-size: 11.5px;
        font-weight: bold;
        text-transform: uppercase;
        page-break-after: avoid;
    }
    .band.new-page { page-break-before: always; margin-top: 0; }

    h2 {
        margin: 10px 0 5px;
        padding-bottom: 2px;
        border-bottom: 1px solid #000;
        font-size: 10.5px;
        font-weight: bold;
        text-transform: uppercase;
        page-break-after: avoid;
    }

    /* KPI */
    .stats { margin-bottom: 4px; }
    .stats td {
        border: 1px solid #000;
        padding: 6px 4px; text-align: center;
    }
    .stats .angka { display: block; margin-bottom: 2px; font-size: 16px; font-weight: bold; }
    .stats .label { font-size: 9px; font-weight: bold; }

    /* DIAGRAM PIE */
    .charts { margin-bottom: 4px; }
    .charts > tbody > tr > td {
        border: none; vertical-align: top; padding: 0 4px; text-align: center;
    }
    .charts img { width: 130px; height: 130px; }
    .chart-title { margin-bottom: 3px; font-size: 9.5px; font-weight: bold; text-align: center; }
    .legend { margin: 4px auto 0; font-size: 8.5px; width: auto; }
    .legend td { border: none; padding: 1px 3px; text-align: left; }
    .swatch { width: 10px; height: 10px; border: 1px solid #000; }

    /* TABEL DATA */
    .tbl-title { margin: 8px 0 3px; font-size: 10px; font-weight: bold; page-break-after: avoid; }
    .tbl-note { margin: 0 0 3px; font-size: 8.5px; font-style: italic; page-break-after: avoid; }
    .data-table { margin-bottom: 6px; table-layout: fixed; }
    .data-table thead { display: table-header-group; }
    .data-table th {
        padding: 3.5px 5px; border: 1px solid #000;
        background: #D9D9D9;
        font-size: 9.5px; font-weight: bold;
        text-align: center; vertical-align: middle;
    }
    .data-table td {
        padding: 3px 5px; border: 1px solid #000;
        font-size: 9.5px; vertical-align: middle;
        word-wrap: break-word;
    }
    .data-table.small th,
    .data-table.small td { padding: 3px 3px; font-size: 7.5px; }

    .center { text-align: center; }
    .left   { text-align: left; }
    .bold-row td { font-weight: bold; background: #F2F2F2; }
    .total-row td { font-weight: bold; background: #F2F2F2; text-align: center; }
    .empty-row td { text-align: center; font-style: italic; }

    /* ANALISIS */
    .insight td { border: 1px solid #000; padding: 4px 6px; font-size: 9.5px; vertical-align: top; }
    .insight .no { width: 28px; text-align: center; font-weight: bold; }

    /* FOOTER */
    .footer {
        margin-top: 14px; padding-top: 5px;
        border-top: 1px solid #000;
        text-align: left; font-size: 8.5px; font-style: italic; line-height: 1.4;
    }

    tr { page-break-inside: avoid; }
</style>
</head>

<body>

@php
    $sistem = 'ARTA - Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan';

    /* Hapus kolom Keikutsertaan (Rincian Sumber Dana) + judul pie baru (sama dengan Excel) */
    $report = \App\Exports\ReportExportSupport::normalize($report ?? []);

    $meta     = $report['meta'] ?? [];
    $kpis     = $report['kpis'] ?? [];
    $tables   = $report['tables'] ?? [];
    $insights = $report['insights'] ?? [];
    $details  = $report['details'] ?? [];

    $tanggalCetak = \Illuminate\Support\Carbon::now('Asia/Makassar')
        ->locale('id')
        ->translatedFormat('d F Y');

    $n = fn ($v) => number_format((int) $v, 0, ',', '.');

    /* Format sel: mengikuti format angka yang dipakai Excel */
    $cellText = function ($value, $format) use ($n) {
        if ($value === null || $value === '') {
            return '';
        }

        if (!is_numeric($value) || is_string($value)) {
            return (string) $value;
        }

        return match ($format) {
            '0.0%'             => number_format($value * 100, 1, ',', '.') . '%',
            '#,##0'            => $n($value),
            '#,##0" Orang"'    => $n($value) . ' Orang',
            default            => (string) $value,
        };
    };

    /* Warna irisan pie: sumber yang sama persis dengan Excel.
       Laki-laki biru dan perempuan merah (sama dengan dashboard). */
    $palette = array_map(fn ($c) => '#' . $c, \App\Exports\ReportExportSupport::PALETTE);

    /* Pie penuh sebagai SVG */
    $makePie = function (array $slices, float $sum) {
        $cx = 100; $cy = 100; $r = 96;
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">';

        $drawn = array_values(array_filter($slices, fn ($s) => $s['value'] > 0));

        if (count($drawn) === 1) {
            $svg .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . $r . '" fill="' . $drawn[0]['fill'] . '" stroke="#FFFFFF" stroke-width="1.5"/>';
        } else {
            $angle = -M_PI / 2;
            foreach ($drawn as $s) {
                $sweep = ($s['value'] / $sum) * 2 * M_PI;
                $end = $angle + $sweep;
                $x1 = $cx + $r * cos($angle);
                $y1 = $cy + $r * sin($angle);
                $x2 = $cx + $r * cos($end);
                $y2 = $cy + $r * sin($end);
                $large = $sweep > M_PI ? 1 : 0;

                $svg .= sprintf(
                    '<path d="M%s,%s L%.3f,%.3f A%s,%s 0 %d,1 %.3f,%.3f Z" fill="%s" stroke="#FFFFFF" stroke-width="1.5"/>',
                    $cx, $cy, $x1, $y1, $r, $r, $large, $x2, $y2, $s['fill']
                );
                $angle = $end;
            }
        }

        $svg .= '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    };

    /* Kumpulkan diagram dari tabel Bagian I (maksimal 2, seperti Excel) */
    $pies = [];
    foreach ($tables as $t) {
        if (empty($t['pie']) || empty($t['rows'])) {
            continue;
        }

        $slices = [];
        foreach (array_values($t['rows']) as $i => $row) {
            $sliceLabel = (string) ($row['cells'][$t['pie']['label']] ?? '-');

            $slices[] = [
                'label' => $sliceLabel,
                'value' => (float) ($row['cells'][$t['pie']['value']] ?? 0),
                'fill'  => '#' . \App\Exports\ReportExportSupport::colorFor($sliceLabel, $i),
            ];
        }

        $sum = array_sum(array_column($slices, 'value'));
        if ($sum <= 0) {
            continue;
        }

        foreach ($slices as $i => $s) {
            $slices[$i]['percent'] = number_format($s['value'] / $sum * 100, 1, ',', '.') . '%';
        }

        $pies[] = [
            'title'  => $t['pie']['title'] ?? 'Diagram',
            'image'  => $makePie($slices, $sum),
            'slices' => $slices,
        ];
    }
    $pies = array_slice($pies, 0, 2);

    /* Info laporan: sama dengan blok informasi di Excel */
    $info = [['Sumber Data', $sistem]];

    if (!empty($meta['type_label'])) {
        $info[] = ['Jenis Ekspor', $meta['type_label']];
    }

    $info[] = ['Tahun Pelatihan', (string) ($meta['year'] ?? $year)];
    $info[] = ['Tanggal dan Waktu Ekspor', $generatedAt];
    $info[] = ['Kabupaten/Kota', ($meta['district'] ?? '') !== '' ? $meta['district'] : 'Semua kabupaten/kota'];
    $info[] = ['Instansi', ($meta['institution'] ?? '') !== '' ? $meta['institution'] : 'Semua instansi'];
    $info[] = ['Keikutsertaan', $meta['minimum_label'] ?? 'Semua keikutsertaan'];

    if (($meta['search'] ?? '') !== '') {
        $info[] = ['Kata Kunci Pencarian', $meta['search']];
    }

    /* Render satu tabel generik (sama dengan Excel) */
    $renderTable = function (array $t, int $index, bool $small) use ($cellText) {
        $cols = $t['columns'];
        $rows = $t['rows'] ?? [];
        $sumW = max(1, array_sum(array_column($cols, 'w')));
        $letter = chr(65 + $index);

        $html = '<div class="tbl-title">' . e($letter . '. ' . $t['title']) . '</div>';

        if (!empty($t['note'])) {
            $html .= '<div class="tbl-note">' . e($t['note']) . '</div>';
        }

        $html .= '<table class="data-table' . ($small ? ' small' : '') . '"><thead><tr>';
        foreach ($cols as $c) {
            $html .= '<th style="width:' . number_format($c['w'] / $sumW * 100, 2, '.', '') . '%">' . e($c['label']) . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        if (!$rows) {
            $html .= '<tr class="empty-row"><td colspan="' . count($cols) . '">' . e($t['empty'] ?? 'Tidak ada data.') . '</td></tr>';
        } else {
            foreach ($rows as $row) {
                $html .= '<tr' . (!empty($row['bold']) ? ' class="bold-row"' : '') . '>';
                foreach ($cols as $i => $c) {
                    $v = $row['cells'][$i] ?? '';
                    $text = ($v === null || $v === '') ? '-' : $cellText($v, $c['format'] ?? null);
                    $cls = ($c['align'] ?? 'c') === 'l' ? 'left' : 'center';
                    $html .= '<td class="' . $cls . '">' . e($text) . '</td>';
                }
                $html .= '</tr>';
            }

            if (!empty($t['total'])) {
                $html .= '<tr class="total-row">';
                foreach ($cols as $i => $c) {
                    $v = $t['total']['cells'][$i] ?? '';
                    $html .= '<td>' . e($cellText($v, $c['format'] ?? null)) . '</td>';
                }
                $html .= '</tr>';
            }
        }

        $html .= '</tbody></table>';

        return $html;
    };
@endphp

<!-- KOP SURAT -->
<table class="kop">
    <tr>
        <td class="kop-logo">
            <img src="{{ public_path('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                 alt="Logo Provinsi Kalimantan Selatan">
        </td>

        <td class="kop-text">
            <div class="pemerintah">PEMERINTAH PROVINSI KALIMANTAN SELATAN</div>
            <div class="dinas">DINAS KESEHATAN</div>
            <div class="balai">UPTD BALAI PELATIHAN KESEHATAN</div>

            <div class="alamat">
                Jalan H. Mistar Tjokrokusumo No. 5A Banjarbaru Kode Pos 70714<br>
                Telepon : (0511) 477201<br>
                Email : info@bapelkes.kalselprov.go.id
                &nbsp; Website : www.bapelkes.kalselprov.go.id
            </div>
        </td>
    </tr>
</table>

<div class="kop-garis"></div>

<div class="tanggal">Banjarbaru, {{ $tanggalCetak }}</div>

<!-- JUDUL -->
<div class="judul">
    <h1>{{ $meta['title'] ?? 'Laporan Lengkap Riwayat Pelatihan' }}</h1>
    <div class="subjudul">Tahun Anggaran {{ $year }}</div>
</div>

<!-- IDENTITAS LAPORAN -->
<table class="info">
    @foreach ($info as [$label, $value])
        <tr>
            <td class="info-label">{{ $label }}</td>
            <td class="info-separator">:</td>
            <td>{{ $value }}</td>
        </tr>
    @endforeach
</table>

<!-- BAGIAN I -->
<div class="band">BAGIAN I — LAPORAN EKSEKUTIF</div>

@if ($kpis)
    <table class="stats">
        <tr>
            @foreach ($kpis as [$label, $value])
                <td style="width: {{ number_format(100 / count($kpis), 2, '.', '') }}%">
                    <span class="angka">{{ $n($value) }}</span>
                    <span class="label">{{ $label }}</span>
                </td>
            @endforeach
        </tr>
    </table>
@endif

@if ($pies)
    <h2>Visualisasi Data</h2>

    <table class="charts">
        <tr>
            @foreach ($pies as $pie)
                <td style="width: {{ 100 / count($pies) }}%">
                    <div class="chart-title">{{ $pie['title'] }}</div>
                    <img src="{{ $pie['image'] }}" alt="{{ $pie['title'] }}">

                    <table class="legend">
                        @foreach ($pie['slices'] as $s)
                            <tr>
                                <td style="width: 14px;">
                                    <div class="swatch" style="background: {{ $s['fill'] }};"></div>
                                </td>
                                <td>{{ $s['label'] }} : {{ $n($s['value']) }} ({{ $s['percent'] }})</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            @endforeach
        </tr>
    </table>
@endif

<h2>Rekapitulasi</h2>

@foreach ($tables as $i => $t)
    {!! $renderTable($t, $i, false) !!}
@endforeach

@if ($insights)
    <h2>Analisis Singkat untuk Pimpinan</h2>

    <table class="insight">
        @foreach ($insights as $i => $text)
            <tr>
                <td class="no">{{ $i + 1 }}</td>
                <td>{{ $text }}</td>
            </tr>
        @endforeach
    </table>
@endif

<!-- BAGIAN II -->
@if ($details)
    <div class="band new-page">BAGIAN II — DATA RINCIAN</div>

    @foreach ($details as $i => $t)
        {!! $renderTable($t, $i, true) !!}
    @endforeach
@endif

<!-- FOOTER -->
<div class="footer">
    Dokumen ini dihasilkan oleh {{ $sistem }}, UPTD Balai Pelatihan Kesehatan Provinsi Kalimantan Selatan,
    berdasarkan data yang tersimpan pada saat dokumen diekspor.
</div>

</body>
</html>