@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">DASHBOARD PIMPINAN</span>
        <h1>Ringkasan Pelatihan</h1>
        <p>Informasi agregat untuk pemantauan pelaksanaan pelatihan.</p>
    </div>

    <a class="btn btn-primary" href="{{ route('reports.index') }}">
        Buka Laporan
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Peserta</div>
        <div class="stat-number">{{ number_format($totalParticipants) }}</div>
        <div class="stat-note">Peserta terdaftar di sistem</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Pelatihan</div>
        <div class="stat-number">{{ number_format($totalTrainings) }}</div>
        <div class="stat-note">Pelatihan tercatat</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Keikutsertaan</div>
        <div class="stat-number">{{ number_format($totalParticipations) }}</div>
        <div class="stat-note">Riwayat keikutsertaan</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Keikutsertaan Tahun {{ $year }}</div>
        <div class="stat-number">{{ number_format($participationsThisYear) }}</div>
        <div class="stat-note">Berdasarkan tanggal mulai pelatihan</div>
    </div>
</div>

<div class="panel">
    <h2>Distribusi bidang pelatihan</h2>
    <p>Jumlah pelatihan per bidang untuk pemantauan program.</p>

    @php
        $maxFieldTotal = max(1, (int) $fieldStats->max('total'));
    @endphp

    @forelse ($fieldStats as $stat)
        <div class="field-stat">
            <div class="field-stat-line">
                <span>{{ $stat->field }}</span>
                <strong>{{ $stat->total }}</strong>
            </div>

            <div class="progress-track">
                <div
                    class="progress-fill"
                    style="width: {{ ($stat->total / $maxFieldTotal) * 100 }}%"
                ></div>
            </div>
        </div>
    @empty
        <div class="empty-state">Belum ada data bidang pelatihan.</div>
    @endforelse
</div>

<div class="panel">
    <h2>Akses laporan</h2>
    <p>
        Pimpinan dapat meninjau laporan untuk memantau pelaksanaan
        pelatihan dan mendukung pengambilan keputusan.
    </p>

    <div style="margin-top:18px">
        <a class="btn btn-primary" href="{{ route('reports.index') }}">
            Lihat Laporan
        </a>
    </div>
</div>
@endsection