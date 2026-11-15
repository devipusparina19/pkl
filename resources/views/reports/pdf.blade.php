<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Eksekutif Riwayat Pelatihan {{ $year }}</title>

<style>
    @page {
        size: A4 landscape;
        margin: 22px 30px 25px 30px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: "Times New Roman", Times, serif;
        font-size: 10px;
        color: #172033;
        line-height: 1.35;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    /* =========================
       KOP SURAT
    ========================= */

    .kop {
        margin-bottom: 5px;
    }

    .kop td {
        border: none;
        padding: 0;
        vertical-align: middle;
        background: transparent !important;
    }

    .kop-logo {
        width: 100px;
        text-align: center;
    }

    .kop-logo img {
        width: 76px;
        height: auto;
    }

    .kop-text {
        text-align: center;
        padding-right: 100px !important;
    }

    .kop-text .pemerintah {
        font-size: 15px;
        font-weight: bold;
        text-transform: uppercase;
        color: #000;
    }

    .kop-text .dinas {
        font-size: 17px;
        font-weight: bold;
        text-transform: uppercase;
        color: #000;
    }

    .kop-text .balai {
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        color: #000;
    }

    .kop-text .alamat {
        font-size: 10px;
        color: #000;
        line-height: 1.35;
    }

    .kop-garis {
        border-top: 3px solid #000;
        border-bottom: 1px solid #000;
        height: 5px;
        margin-top: 7px;
        margin-bottom: 15px;
    }

    /* =========================
       JUDUL LAPORAN
    ========================= */

    .judul {
        text-align: center;
        margin-bottom: 12px;
    }

    .judul h1 {
        font-size: 16px;
        text-transform: uppercase;
        margin: 0 0 3px;
        color: #000;
        font-weight: bold;
    }

    .judul .subjudul {
        font-size: 11px;
        font-weight: bold;
    }

    .judul .nomor {
        font-size: 9px;
        margin-top: 3px;
        color: #444;
    }

    /* =========================
       INFORMASI LAPORAN
    ========================= */

    .info {
        margin-bottom: 10px;
        font-size: 10px;
    }

    .info td {
        border: none;
        padding: 2px 4px;
        vertical-align: top;
    }

    .info-label {
        width: 105px;
        font-weight: bold;
    }

    .info-separator {
        width: 10px;
    }

    /* =========================
       JUDUL BAGIAN
    ========================= */

    h2 {
        font-size: 11px;
        text-transform: uppercase;
        margin: 12px 0 5px;
        padding-bottom: 3px;
        border-bottom: 1px solid #263B59;
        color: #172B4D;
    }

    /* =========================
       RINGKASAN STATISTIK
    ========================= */

    .stats {
        margin-bottom: 8px;
    }

    .stats td {
        width: 25%;
        border: 1px solid #B8C4D3;
        padding: 8px 5px;
        text-align: center;
        background: #F7F9FC;
    }

    .stats .angka {
        display: block;
        font-size: 20px;
        font-weight: bold;
        color: #142D4E;
        margin-bottom: 2px;
    }

    .stats .label {
        font-size: 10px;
        color: #333;
    }

    /* =========================
       TABEL DATA
    ========================= */

    .data-table {
        margin-bottom: 8px;
    }

    .data-table thead {
        display: table-header-group;
    }

    .data-table th {
        background: #DCE5F0;
        color: #172033;
        font-size: 10px;
        font-weight: bold;
        text-align: center;
        padding: 5px 6px;
        border: 1px solid #AAB8C9;
    }

    .data-table td {
        padding: 4px 6px;
        border: 1px solid #D4DCE6;
        font-size: 10px;
        vertical-align: top;
    }

    .data-table tbody tr:nth-child(even) td {
        background: #F8FAFC;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .bold {
        font-weight: bold;
    }

    .total-row td {
        background: #EAF0F7 !important;
        font-weight: bold;
    }

    /* =========================
       LAYOUT DUA KOLOM
    ========================= */

    .two-column {
        width: 100%;
        margin-top: 2px;
    }

    .two-column td {
        width: 50%;
        vertical-align: top;
        border: none;
        padding: 0 8px 0 0;
    }

    .two-column td:last-child {
        padding: 0 0 0 8px;
    }

    /* =========================
       CATATAN
    ========================= */

    .catatan {
        margin-top: 9px;
        padding: 7px 9px;
        border: 1px solid #CBD5E1;
        background: #F8FAFC;
        font-size: 9px;
    }

    .catatan strong {
        font-size: 10px;
    }

    /* =========================
       FOOTER
    ========================= */

    .footer {
        margin-top: 12px;
        padding-top: 5px;
        border-top: 1px solid #AAB8C9;
        text-align: center;
        font-size: 8px;
        color: #555;
    }

    .muted {
        color: #666;
    }

    tr {
        page-break-inside: avoid;
    }
</style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | Helper Format
    |--------------------------------------------------------------------------
    */

    $n = fn ($v) => number_format((int) $v, 0, ',', '.');

    $d = fn ($v) => $v
        ? \Illuminate\Support\Carbon::parse($v)->format('d/m/Y')
        : '-';

    /*
    |--------------------------------------------------------------------------
    | Pemeriksaan Data
    |--------------------------------------------------------------------------
    */

    $hasValue = fn ($v) => (float) $v > 0;

    $hasGender = $hasValue($maleCount) || $hasValue($femaleCount);

    $hasProfession = collect($nakesByType ?? [])
        ->contains(fn ($row) => (int) ($row->total ?? 0) > 0);

    $hasFunding = collect($fundingRecap ?? [])
        ->contains(fn ($row) => (int) ($row->total_trainings ?? 0) > 0);

    $hasTraining = collect($trainingRecap ?? [])
        ->contains(fn ($row) => (int) ($row->total_trainings ?? 0) > 0);

    $hasCategory = collect($categoryRecap ?? [])
        ->contains(fn ($row) => (int) ($row->total_trainings ?? 0) > 0);

    $hasTrainingList = collect($trainingList ?? [])
        ->contains(fn ($row) => (int) ($row['participants'] ?? 0) > 0);

    /*
    |--------------------------------------------------------------------------
    | Filter Data Nol
    |--------------------------------------------------------------------------
    */

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

    $totalTrainingCount = (int) $totalTrainings;
    $totalParticipantCount = (int) $totalTrainedParticipants;
    $totalParticipationCount = (int) $totalParticipations;

    $hasSummary = $totalTrainingCount > 0
        || $totalParticipantCount > 0
        || $totalParticipationCount > 0;

@endphp

<!-- =====================================================
     KOP SURAT RESMI
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
            <div class="pemerintah">
                PEMERINTAH PROVINSI KALIMANTAN SELATAN
            </div>

            <div class="dinas">
                DINAS KESEHATAN
            </div>

            <div class="balai">
                UPTD BALAI PELATIHAN KESEHATAN
            </div>

            <div class="alamat">
                Jalan H. Mistar Tjokrokusumo No. 5A Banjarbaru Kode Pos 70714<br>
                Telepon: (0511) 477201
            </div>
        </td>
    </tr>
</table>

<div class="kop-garis"></div>

<!-- =====================================================
     JUDUL
===================================================== -->

<div class="judul">
    <h1>LAPORAN EKSEKUTIF RIWAYAT PELATIHAN</h1>

    <div class="subjudul">
        TAHUN ANGGARAN {{ $year }}
    </div>

    <div class="nomor">
        UPTD Balai Pelatihan Kesehatan Provinsi Kalimantan Selatan
    </div>
</div>

<!-- =====================================================
     IDENTITAS LAPORAN
===================================================== -->

<table class="info">
    <tr>
        <td class="info-label">Periode Laporan</td>
        <td class="info-separator">:</td>
        <td>Tahun {{ $year }}</td>

        <td class="info-label">Tanggal Cetak</td>
        <td class="info-separator">:</td>
        <td>{{ now()->format('d F Y') }}</td>
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
     RINGKASAN EKSEKUTIF
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
                    <span class="angka">{{ $n($maleCount + $femaleCount) }}</span>
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
     KOMPOSISI PESERTA
===================================================== -->

@if ($hasGender || $hasProfession)
    <h2>II. Komposisi Peserta Terlatih</h2>

    <table class="two-column">
        <tr>
            @if ($hasGender)
                <td>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th colspan="3">Berdasarkan Jenis Kelamin</th>
                            </tr>
                            <tr>
                                <th style="width: 10%">No.</th>
                                <th>Jenis Kelamin</th>
                                <th style="width: 25%">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($hasValue($maleCount))
                                <tr>
                                    <td class="center">1</td>
                                    <td>Laki-laki</td>
                                    <td class="right">{{ $n($maleCount) }}</td>
                                </tr>
                            @endif

                            @if ($hasValue($femaleCount))
                                <tr>
                                    <td class="center">{{ $hasValue($maleCount) ? 2 : 1 }}</td>
                                    <td>Perempuan</td>
                                    <td class="right">{{ $n($femaleCount) }}</td>
                                </tr>
                            @endif

                            <tr class="total-row">
                                <td colspan="2">Total</td>
                                <td class="right">{{ $n($maleCount + $femaleCount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            @endif

            @if ($hasProfession)
                <td>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th colspan="3">Berdasarkan Profesi</th>
                            </tr>
                            <tr>
                                <th style="width: 10%">No.</th>
                                <th>Jenis Profesi</th>
                                <th style="width: 25%">Jumlah</th>
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
                </td>
            @endif
        </tr>
    </table>
@endif

<!-- =====================================================
     SUMBER DANA
===================================================== -->

@if ($hasFunding)
    <h2>III. Rekapitulasi Sumber Dana</h2>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 7%">No.</th>
                <th>Sumber Dana</th>
                <th style="width: 18%">Jumlah Kegiatan</th>
                <th style="width: 18%">Peserta Unik</th>
                <th style="width: 18%">Keikutsertaan</th>
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
     KATEGORI PELATIHAN
===================================================== -->

@if ($hasCategory)
    <h2>IV. Rekapitulasi Kategori Pelatihan</h2>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 7%">No.</th>
                <th>Kategori Pelatihan</th>
                <th style="width: 18%">Jumlah Kegiatan</th>
                <th style="width: 18%">Peserta Unik</th>
                <th style="width: 18%">Keikutsertaan</th>
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
     KEGIATAN UTAMA
===================================================== -->

@if ($hasTrainingList)
    <h2>V. Daftar Kegiatan Pelatihan Utama</h2>

    <div class="muted" style="font-size: 9px; margin-bottom: 5px;">
        Menampilkan paling banyak 10 kegiatan berdasarkan jumlah peserta.
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No.</th>
                <th style="width: 31%">Nama Pelatihan</th>
                <th style="width: 19%">Waktu Pelaksanaan</th>
                <th style="width: 19%">Sumber Dana</th>
                <th style="width: 16%">Kategori</th>
                <th style="width: 10%">Peserta</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($trainingRows as $i => $row)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>

                    <td>{{ $row['name'] }}</td>

                    <td>
                        {{ $d($row['start_date']) }}

                        @if (
                            !empty($row['end_date']) &&
                            $row['end_date'] !== $row['start_date']
                        )
                            s.d. {{ $d($row['end_date']) }}
                        @endif
                    </td>

                    <td>{{ $row['funding_source'] ?: '-' }}</td>

                    <td>{{ $row['category'] ?: '-' }}</td>

                    <td class="right">
                        {{ $n($row['participants']) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<!-- =====================================================
     KETERANGAN
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
    UPTD Balai Pelatihan Kesehatan Provinsi Kalimantan Selatan
    &nbsp; | &nbsp;
    Laporan Eksekutif Riwayat Pelatihan Tahun {{ $year }}
</div>

</body>
</html>