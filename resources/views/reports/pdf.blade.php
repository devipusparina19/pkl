<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Eksekutif Riwayat Pelatihan {{ $year }}</title>

<style>
    @page {
        size: A4 portrait;
        margin: 28px 40px 40px 40px;
    }

    * {
        box-sizing: border-box;
    }

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

    /* =========================
       KOP SURAT
    ========================= */

    .kop td {
        border: none;
        padding: 0;
        vertical-align: middle;
    }

    .kop-logo {
        width: 80px;
        text-align: left;
    }

    .kop-logo img {
        width: 66px;
        height: auto;
    }

    .kop-text {
        text-align: center;
        padding-right: 80px !important;
    }

    .kop-text .pemerintah,
    .kop-text .dinas {
        font-size: 13px;
        font-weight: bold;
        text-transform: uppercase;
        line-height: 1.25;
    }

    .kop-text .balai {
        font-size: 15px;
        font-weight: bold;
        text-transform: uppercase;
        line-height: 1.25;
    }

    .kop-text .alamat {
        margin-top: 2px;
        font-size: 8px;
        line-height: 1.35;
    }

    .kop-garis {
        margin-top: 6px;
        border-top: 1.5px solid #000;
        height: 0;
    }

    /* =========================
       TANGGAL & JUDUL
    ========================= */

    .tanggal {
        margin: 5px 0 8px;
        text-align: right;
        font-size: 10px;
    }

    .judul {
        text-align: center;
        margin-bottom: 10px;
    }

    .judul h1 {
        margin: 0 0 3px;
        font-size: 13px;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: underline;
    }

    .judul .subjudul {
        font-size: 10.5px;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* =========================
       INFORMASI LAPORAN
    ========================= */

    .info {
        margin-bottom: 6px;
        font-size: 10px;
    }

    .info td {
        border: none;
        padding: 1.5px 3px;
        vertical-align: top;
    }

    .info-label {
        width: 95px;
        white-space: nowrap;
    }

    .info-separator {
        width: 8px;
    }

    /* =========================
       JUDUL BAGIAN
    ========================= */

    h2 {
        margin: 12px 0 5px;
        padding-bottom: 2px;
        border-bottom: 1px solid #000;
        font-size: 10.5px;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* =========================
       RINGKASAN STATISTIK
    ========================= */

    .stats {
        margin-bottom: 6px;
    }

    .stats td {
        width: 25%;
        border: 1px solid #000;
        padding: 7px 4px;
        text-align: center;
    }

    .stats .angka {
        display: block;
        margin-bottom: 2px;
        font-size: 17px;
        font-weight: bold;
    }

    .stats .label {
        font-size: 9px;
    }

    /* =========================
       TABEL DATA
    ========================= */

    .data-table {
        margin-bottom: 6px;
    }

    .data-table thead {
        display: table-header-group;
    }

    .data-table th {
        padding: 4px 5px;
        border: 1px solid #000;
        font-size: 9.5px;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
    }

    .data-table td {
        padding: 3.5px 5px;
        border: 1px solid #000;
        font-size: 9.5px;
        vertical-align: middle;
    }

    .data-table.small th,
    .data-table.small td {
        padding: 3px 3px;
        font-size: 7.5px;
    }

    .center { text-align: center; }
    .right  { text-align: right; }
    .bold   { font-weight: bold; }

    .total-row td {
        font-weight: bold;
    }

    /* =========================
       PIE + TABEL JENIS KELAMIN
    ========================= */

    .layout {
        width: 100%;
    }

    .layout > tbody > tr > td {
        border: none;
        vertical-align: top;
        padding: 0;
    }

    .pie-cell {
        width: 46%;
        text-align: center;
    }

    .pie-cell img {
        width: 165px;
        height: 165px;
    }

    .legend {
        margin-top: 4px;
        font-size: 9px;
    }

    .legend td {
        border: none;
        padding: 1px 3px;
        text-align: left;
    }

    .swatch {
        width: 10px;
        height: 10px;
        border: 1px solid #000;
    }

    .chart-title {
        margin-bottom: 3px;
        font-size: 9.5px;
        font-weight: bold;
        text-align: center;
    }

    /* =========================
       CATATAN & FOOTER
    ========================= */

    .catatan {
        margin-top: 10px;
        font-size: 9px;
        text-align: justify;
    }

    .footer {
        margin-top: 14px;
        padding-top: 5px;
        border-top: 1px solid #000;
        text-align: center;
        font-size: 7.5px;
        line-height: 1.4;
    }

    .muted {
        margin-bottom: 4px;
        font-size: 8.5px;
    }

    tr {
        page-break-inside: avoid;
    }
</style>
</head>

<body>

@php
    $sistem = 'ARTA - Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan';

    /* Helper format */
    $n = fn ($v) => number_format((int) $v, 0, ',', '.');

    $d = fn ($v) => $v
        ? \Illuminate\Support\Carbon::parse($v)->format('d/m/Y')
        : '-';

    $tanggalCetak = \Illuminate\Support\Carbon::now('Asia/Makassar')
        ->locale('id')
        ->translatedFormat('d F Y');

    /* Pemeriksaan data */
    $hasValue = fn ($v) => (float) $v > 0;

    $hasGender = $hasValue($maleCount) || $hasValue($femaleCount);

    $hasProfession = collect($nakesByType ?? [])
        ->contains(fn ($row) => (int) ($row->total ?? 0) > 0);

    $hasFunding = collect($fundingRecap ?? [])
        ->contains(fn ($row) => (int) ($row->total_trainings ?? 0) > 0);

    $hasCategory = collect($categoryRecap ?? [])
        ->contains(fn ($row) => (int) ($row->total_trainings ?? 0) > 0);

    $hasTrainingList = collect($trainingList ?? [])
        ->contains(fn ($row) => (int) ($row['participants'] ?? 0) > 0);

    /* Filter data nol */
    $professionRows = collect($nakesByType ?? [])
        ->filter(fn ($row) => (int) ($row->total ?? 0) > 0)
        ->values();

    $fundingRows = collect($fundingRecap ?? [])
        ->filter(fn ($row) => (int) ($row->total_trainings ?? 0) > 0)
        ->values();

    $categoryRows = collect($categoryRecap ?? [])
        ->filter(fn ($row) => (int) ($row->total_trainings ?? 0) > 0)
        ->values();

    $trainingRows = collect($trainingList ?? [])
        ->filter(fn ($row) => (int) ($row['participants'] ?? 0) > 0)
        ->sortByDesc('participants')
        ->take(10)
        ->values();

    /* Data peserta (opsional, kirim dari controller sebagai $participantList) */
    $participantRows = collect($participantList ?? [])
        ->map(fn ($p) => (array) (is_object($p) && method_exists($p, 'toArray') ? $p->toArray() : $p))
        ->values();

    $hasParticipants = $participantRows->isNotEmpty();

    $totalTrainingCount = (int) $totalTrainings;
    $totalParticipantCount = (int) $totalTrainedParticipants;
    $totalParticipationCount = (int) $totalParticipations;

    $hasSummary = $totalTrainingCount > 0
        || $totalParticipantCount > 0
        || $totalParticipationCount > 0;

    /* Pie chart jenis kelamin (pie penuh, SVG) */
    $genderTotal = (int) $maleCount + (int) $femaleCount;
    $pieImage = null;
    $pieSlices = [];

    if ($genderTotal > 0) {
        $slices = [];
        if ($hasValue($maleCount)) {
            $slices[] = ['label' => 'Laki-laki', 'value' => (int) $maleCount, 'fill' => '#3A3A3A'];
        }
        if ($hasValue($femaleCount)) {
            $slices[] = ['label' => 'Perempuan', 'value' => (int) $femaleCount, 'fill' => '#C8C8C8'];
        }

        $cx = 100; $cy = 100; $r = 96;
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">';

        if (count($slices) === 1) {
            $svg .= '<circle cx="'.$cx.'" cy="'.$cy.'" r="'.$r.'" fill="'.$slices[0]['fill'].'" stroke="#000000" stroke-width="1.5"/>';
        } else {
            $angle = -M_PI / 2;
            foreach ($slices as $s) {
                $sweep = ($s['value'] / $genderTotal) * 2 * M_PI;
                $end = $angle + $sweep;
                $x1 = $cx + $r * cos($angle);
                $y1 = $cy + $r * sin($angle);
                $x2 = $cx + $r * cos($end);
                $y2 = $cy + $r * sin($end);
                $large = $sweep > M_PI ? 1 : 0;

                $svg .= sprintf(
                    '<path d="M%s,%s L%.3f,%.3f A%s,%s 0 %d,1 %.3f,%.3f Z" fill="%s" stroke="#000000" stroke-width="1.5"/>',
                    $cx, $cy, $x1, $y1, $r, $r, $large, $x2, $y2, $s['fill']
                );
                $angle = $end;
            }
        }

        $svg .= '</svg>';
        $pieImage = 'data:image/svg+xml;base64,' . base64_encode($svg);

        foreach ($slices as $s) {
            $s['percent'] = number_format($s['value'] / $genderTotal * 100, 1, ',', '.') . '%';
            $pieSlices[] = $s;
        }
    }
@endphp

<!-- =====================================================
     KOP SURAT
===================================================== -->

<table class="kop">
    <tr>
        <td class="kop-logo">
            <img
                src="{{ public_path('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                alt="Logo Provinsi Kalimantan Selatan"
            >
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

<!-- =====================================================
     JUDUL
===================================================== -->

<div class="judul">
    <h1>Laporan Eksekutif Riwayat Pelatihan</h1>
    <div class="subjudul">Tahun Anggaran {{ $year }}</div>
</div>

<!-- =====================================================
     IDENTITAS LAPORAN
===================================================== -->

<table class="info">
    <tr>
        <td class="info-label">Sumber Data</td>
        <td class="info-separator">:</td>
        <td colspan="4">{{ $sistem }}</td>
    </tr>
    <tr>
        <td class="info-label">Periode Laporan</td>
        <td class="info-separator">:</td>
        <td>Tahun {{ $year }}</td>

        <td class="info-label">Tanggal Cetak</td>
        <td class="info-separator">:</td>
        <td>{{ $tanggalCetak }}</td>
    </tr>
    <tr>
        <td class="info-label">Kabupaten/Kota</td>
        <td class="info-separator">:</td>
        <td>{{ $districtFilter ?: 'Seluruh wilayah' }}</td>

        <td class="info-label">Instansi</td>
        <td class="info-separator">:</td>
        <td>{{ $institutionFilter ?: 'Seluruh instansi' }}</td>
    </tr>
</table>

<!-- =====================================================
     I. RINGKASAN CAPAIAN
===================================================== -->

@if ($hasSummary)
    <h2>I. Ringkasan Capaian Pelatihan</h2>

    <table class="stats">
        <tr>
            <td>
                <span class="angka">{{ $n($totalTrainingCount) }}</span>
                <span class="label">Jumlah Kegiatan Pelatihan</span>
            </td>

            <td>
                <span class="angka">{{ $n($totalParticipantCount) }}</span>
                <span class="label">Peserta Unik Terlatih</span>
            </td>

            <td>
                <span class="angka">{{ $n($totalParticipationCount) }}</span>
                <span class="label">Total Keikutsertaan</span>
            </td>

            @if ($hasGender)
                <td>
                    <span class="angka">{{ $n($genderTotal) }}</span>
                    <span class="label">Peserta dengan Data Gender</span>
                </td>
            @else
                <td>
                    <span class="angka">{{ $n($totalParticipantCount) }}</span>
                    <span class="label">Total Peserta Terlatih</span>
                </td>
            @endif
        </tr>
    </table>
@endif

<!-- =====================================================
     II. KOMPOSISI PESERTA
===================================================== -->

@if ($hasGender || $hasProfession)
    <h2>II. Komposisi Peserta Terlatih</h2>

    @if ($hasGender)
        <table class="layout">
            <tr>
                <td class="pie-cell">
                    <div class="chart-title">Diagram Jenis Kelamin Peserta</div>

                    <img src="{{ $pieImage }}" alt="Diagram pie jenis kelamin">

                    <table class="legend" style="width: 150px; margin: 4px auto 0;">
                        @foreach ($pieSlices as $s)
                            <tr>
                                <td style="width: 14px;">
                                    <div class="swatch" style="background: {{ $s['fill'] }};"></div>
                                </td>
                                <td>{{ $s['label'] }} : {{ $n($s['value']) }} ({{ $s['percent'] }})</td>
                            </tr>
                        @endforeach
                    </table>
                </td>

                <td style="padding-left: 10px;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th colspan="4">Berdasarkan Jenis Kelamin</th>
                            </tr>
                            <tr>
                                <th style="width: 12%">No.</th>
                                <th>Jenis Kelamin</th>
                                <th style="width: 22%">Jumlah</th>
                                <th style="width: 24%">Persentase</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pieSlices as $i => $s)
                                <tr>
                                    <td class="center">{{ $i + 1 }}</td>
                                    <td>{{ $s['label'] }}</td>
                                    <td class="right">{{ $n($s['value']) }}</td>
                                    <td class="right">{{ $s['percent'] }}</td>
                                </tr>
                            @endforeach

                            <tr class="total-row">
                                <td colspan="2" class="center">Total</td>
                                <td class="right">{{ $n($genderTotal) }}</td>
                                <td class="right">100,0%</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    @endif

    @if ($hasProfession)
        <div style="margin-top: 8px;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th colspan="3">Berdasarkan Profesi</th>
                    </tr>
                    <tr>
                        <th style="width: 8%">No.</th>
                        <th>Jenis Profesi</th>
                        <th style="width: 18%">Jumlah</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($professionRows as $i => $row)
                        <tr>
                            <td class="center">{{ $i + 1 }}</td>
                            <td>{{ $row->name }}</td>
                            <td class="right">{{ $n($row->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endif

<!-- =====================================================
     III. SUMBER DANA
===================================================== -->

@if ($hasFunding)
    <h2>III. Rekapitulasi Sumber Dana</h2>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 7%">No.</th>
                <th>Sumber Dana</th>
                <th style="width: 17%">Jumlah Kegiatan</th>
                <th style="width: 17%">Peserta Unik</th>
                <th style="width: 17%">Keikutsertaan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($fundingRows as $i => $row)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $row->funding_source }}</td>
                    <td class="right">{{ $n($row->total_trainings) }}</td>
                    <td class="right">{{ $n($row->total_participants) }}</td>
                    <td class="right">{{ $n($row->total_participations) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<!-- =====================================================
     IV. KATEGORI PELATIHAN
===================================================== -->

@if ($hasCategory)
    <h2>IV. Rekapitulasi Kategori Pelatihan</h2>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 7%">No.</th>
                <th>Kategori Pelatihan</th>
                <th style="width: 17%">Jumlah Kegiatan</th>
                <th style="width: 17%">Peserta Unik</th>
                <th style="width: 17%">Keikutsertaan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($categoryRows as $i => $row)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $row->category_name }}</td>
                    <td class="right">{{ $n($row->total_trainings) }}</td>
                    <td class="right">{{ $n($row->total_participants) }}</td>
                    <td class="right">{{ $n($row->total_participations) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<!-- =====================================================
     V. KEGIATAN UTAMA
===================================================== -->

@if ($hasTrainingList)
    <h2>V. Daftar Kegiatan Pelatihan Utama</h2>

    <div class="muted">
        Menampilkan paling banyak 10 kegiatan berdasarkan jumlah peserta.
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No.</th>
                <th style="width: 30%">Nama Pelatihan</th>
                <th style="width: 20%">Waktu Pelaksanaan</th>
                <th style="width: 18%">Sumber Dana</th>
                <th style="width: 17%">Kategori</th>
                <th style="width: 10%">Peserta</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($trainingRows as $i => $row)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $row['name'] }}</td>

                    <td class="center">
                        {{ $d($row['start_date']) }}
                        @if (!empty($row['end_date']) && $row['end_date'] !== $row['start_date'])
                            <br>s.d. {{ $d($row['end_date']) }}
                        @endif
                    </td>

                    <td>{{ $row['funding_source'] ?: '-' }}</td>
                    <td>{{ $row['category'] ?: '-' }}</td>
                    <td class="right">{{ $n($row['participants']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<!-- =====================================================
     VI. DATA PESERTA (opsional)
===================================================== -->

@if ($hasParticipants)
    <h2>VI. Data Peserta Pelatihan</h2>

    <div class="muted">
        Total {{ $n($participantRows->count()) }} data keikutsertaan peserta sesuai filter laporan.
    </div>

    <table class="data-table small">
        <thead>
            <tr>
                <th style="width: 4%">No.</th>
                <th style="width: 17%">Nama Peserta</th>
                <th style="width: 15%">NIK</th>
                <th style="width: 8%">Jenis Kelamin</th>
                <th style="width: 12%">Profesi</th>
                <th style="width: 14%">Instansi</th>
                <th style="width: 10%">Kab./Kota</th>
                <th style="width: 20%">Nama Pelatihan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($participantRows as $i => $p)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $p['name'] ?? '-' }}</td>
                    <td class="center">{{ $p['nik'] ?? '-' }}</td>
                    <td class="center">{{ $p['gender'] ?? '-' }}</td>
                    <td>{{ $p['profession'] ?? '-' }}</td>
                    <td>{{ $p['institution'] ?? '-' }}</td>
                    <td>{{ $p['district'] ?? '-' }}</td>
                    <td>{{ $p['training_name'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<!-- =====================================================
     CATATAN
===================================================== -->

@if (!$hasSummary && !$hasGender && !$hasProfession && !$hasFunding && !$hasCategory && !$hasTrainingList)
    <div class="catatan">
        <strong>Keterangan:</strong><br>
        Belum terdapat data pelatihan yang memenuhi filter laporan pada periode {{ $year }}.
    </div>
@else
    <div class="catatan">
        <strong>Catatan:</strong><br>
        Laporan ini merupakan ringkasan eksekutif riwayat pelatihan tenaga kesehatan
        pada periode {{ $year }}. Jumlah peserta unik menunjukkan individu yang
        mengikuti pelatihan, sedangkan jumlah keikutsertaan menunjukkan total
        partisipasi peserta dalam seluruh kegiatan. Daftar kegiatan utama
        ditampilkan berdasarkan jumlah peserta terbanyak.
    </div>
@endif

<!-- =====================================================
     FOOTER
===================================================== -->

<div class="footer">
    Dokumen ini dihasilkan oleh {{ $sistem }}
    <br>
    UPTD Balai Pelatihan Kesehatan Provinsi Kalimantan Selatan
    &nbsp;|&nbsp;
    Laporan Eksekutif Riwayat Pelatihan Tahun {{ $year }}
</div>

</body>
</html>