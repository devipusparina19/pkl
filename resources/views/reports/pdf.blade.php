<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Riwayat Pelatihan {{ $year }}</title>
<style>
    @page { margin: 28px 30px; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #14243A; }
    h1 { margin: 0 0 2px; font-size: 18px; color: #081B33; }
    h2 { margin: 18px 0 6px; padding-bottom: 3px; font-size: 12px; color: #081B33; border-bottom: 2px solid #2457D6; }
    .brand { font-size: 8px; font-weight: bold; letter-spacing: 1px; color: #2457D6; text-transform: uppercase; }
    .sub { color: #64748B; font-size: 9px; }
    table { width: 100%; margin-bottom: 6px; border-collapse: collapse; }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
    th { padding: 5px 6px; background: #081B33; color: #fff; text-align: left; font-size: 8px; }
    td { padding: 4px 6px; border-bottom: 1px solid #E1E9F2; vertical-align: top; }
    tbody tr:nth-child(even) td { background: #F7FAFF; }
    .num { text-align: right; white-space: nowrap; }
    .stats td { padding: 10px; border: 1px solid #E1E9F2; background: #fff !important; text-align: center; }
    .stats b { display: block; font-size: 18px; color: #2457D6; }
    .group td { background: #EAF2FF !important; color: #081B33; font-weight: bold; }
    .muted { color: #64748B; }
</style>
</head>
<body>
@php
    $n = fn ($v) => number_format((int) $v, 0, ',', '.');
    $d = fn ($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('d/m/Y') : '-';
    $districtLines = $districtFundingRecap->groupBy('district');
@endphp

<div class="brand">ARTA · Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan</div>
<h1>Laporan Riwayat Pelatihan Tahun {{ $year }}</h1>
<div class="sub">
    Bapelkes Kalimantan Selatan · Dicetak {{ now()->format('d/m/Y H:i') }}<br>
    Filter: kabupaten/kota {{ $districtFilter ?: 'semua' }}, instansi {{ $institutionFilter ?: 'semua' }},
    pencarian {{ $search ?: '-' }}, minimal keikutsertaan {{ $minimum }} kali (daftar peserta).
</div>

<h2>Ringkasan</h2>
<table class="stats">
    <tr>
        <td><b>{{ $n($totalTrainedParticipants) }}</b>Peserta unik</td>
        <td><b>{{ $n($totalTrainings) }}</b>Total kegiatan pelatihan</td>
        <td><b>{{ $n($totalParticipations) }}</b>Total keikutsertaan</td>
        <td><b>{{ $n($maleCount) }}</b>Laki-laki ({{ number_format($malePercent, 1, ',', '.') }}%)</td>
        <td><b>{{ $n($femaleCount) }}</b>Perempuan ({{ number_format($femalePercent, 1, ',', '.') }}%)</td>
    </tr>
</table>

<h2>Peserta berdasarkan profesi</h2>
<table>
    <thead><tr><th style="width:30px">No.</th><th>Jenis Profesi</th><th class="num">Peserta Terlatih</th></tr></thead>
    <tbody>
    @forelse ($nakesByType as $i => $row)
        <tr><td>{{ $i + 1 }}</td><td>{{ $row->name }}</td><td class="num">{{ $n($row->total) }}</td></tr>
    @empty
        <tr><td colspan="3" class="muted">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>Rincian sumber dana</h2>
<table>
    <thead><tr><th style="width:30px">No.</th><th>Sumber Dana</th><th class="num">Kegiatan</th><th class="num">Peserta Unik</th><th class="num">Keikutsertaan</th></tr></thead>
    <tbody>
    @forelse ($fundingRecap as $i => $row)
        <tr><td>{{ $i + 1 }}</td><td>{{ $row->funding_source }}</td><td class="num">{{ $n($row->total_trainings) }}</td><td class="num">{{ $n($row->total_participants) }}</td><td class="num">{{ $n($row->total_participations) }}</td></tr>
    @empty
        <tr><td colspan="5" class="muted">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>Rekap per pelatihan</h2>
<table>
    <thead><tr><th style="width:30px">No.</th><th>Nama Pelatihan</th><th class="num">Kegiatan</th><th class="num">Peserta Unik</th><th class="num">Keikutsertaan</th><th>Sumber Dana (kegiatan)</th></tr></thead>
    <tbody>
    @forelse ($trainingRecap as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->name }}</td>
            <td class="num">{{ $n($row->total_trainings) }}</td>
            <td class="num">{{ $n($row->total_participants) }}</td>
            <td class="num">{{ $n($row->total_participations) }}</td>
            <td>{{ $row->funding->map(fn ($f) => $f->funding_source . ': ' . $f->total_trainings)->implode('; ') }}</td>
        </tr>
    @empty
        <tr><td colspan="6" class="muted">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>Rekap kategori pelatihan</h2>
<table>
    <thead><tr><th style="width:30px">No.</th><th>Kategori</th><th class="num">Kegiatan</th><th class="num">Peserta Unik</th><th class="num">Keikutsertaan</th></tr></thead>
    <tbody>
    @forelse ($categoryRecap as $i => $row)
        <tr><td>{{ $i + 1 }}</td><td>{{ $row->category_name }}</td><td class="num">{{ $n($row->total_trainings) }}</td><td class="num">{{ $n($row->total_participants) }}</td><td class="num">{{ $n($row->total_participations) }}</td></tr>
    @empty
        <tr><td colspan="5" class="muted">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>Rekap wilayah berdasarkan sumber dana</h2>
<table>
    <thead><tr><th>Kabupaten/Kota</th><th>Sumber Dana</th><th class="num">Kegiatan</th><th class="num">Peserta Unik</th><th class="num">Keikutsertaan</th></tr></thead>
    <tbody>
    @forelse ($districtFundingTotals as $total)
        <tr class="group">
            <td>{{ $total->district }}</td><td>Semua sumber dana</td>
            <td class="num">{{ $n($total->total_trainings) }}</td><td class="num">{{ $n($total->total_participants) }}</td><td class="num">{{ $n($total->total_participations) }}</td>
        </tr>
        @foreach ($districtLines->get($total->district, collect()) as $line)
            <tr>
                <td></td><td>{{ $line->funding_source }}</td>
                <td class="num">{{ $n($line->total_trainings) }}</td><td class="num">{{ $n($line->total_participants) }}</td><td class="num">{{ $n($line->total_participations) }}</td>
            </tr>
        @endforeach
    @empty
        <tr><td colspan="5" class="muted">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>Daftar kegiatan pelatihan</h2>
<table>
    <thead><tr><th style="width:30px">No.</th><th>Nama Pelatihan</th><th>Tanggal</th><th>Sumber Dana</th><th>Kategori</th><th>Lokasi</th><th class="num">Peserta</th></tr></thead>
    <tbody>
    @forelse ($trainingList as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $d($row['start_date']) }}@if ($row['end_date'] && $row['end_date'] !== $row['start_date']) – {{ $d($row['end_date']) }}@endif</td>
            <td>{{ $row['funding_source'] }}</td>
            <td>{{ $row['category'] }}</td>
            <td>{{ $row['location'] ?? '-' }}</td>
            <td class="num">{{ $n($row['participants']) }}</td>
        </tr>
    @empty
        <tr><td colspan="7" class="muted">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>

<h2>Daftar peserta dan jumlah keikutsertaan</h2>
<table>
    <thead><tr><th style="width:30px">No.</th><th>Nama Peserta</th><th>NIK</th><th>Profesi</th><th>Instansi</th><th>Kabupaten/Kota</th><th class="num">Ikut</th></tr></thead>
    <tbody>
    @forelse ($participantsAll as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->name ?: '-' }}</td>
            <td>{{ $p->nik ?: '-' }}</td>
            <td>{{ $p->profession ?: '-' }}</td>
            <td>{{ $p->institution ?: '-' }}</td>
            <td>{{ $p->district ?: '-' }}</td>
            <td class="num">{{ $n($p->participations_count) }}</td>
        </tr>
    @empty
        <tr><td colspan="7" class="muted">Belum ada data.</td></tr>
    @endforelse
    </tbody>
</table>
</body>
</html>