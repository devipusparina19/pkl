@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">DASHBOARD PIMPINAN</span>
        <h1>Ringkasan Pelatihan</h1>
        <p>
            Informasi agregat untuk pemantauan pelaksanaan pelatihan
            dan evaluasi program.
        </p>
    </div>
</div>

{{-- Ringkasan statistik --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Peserta</div>
        <div class="stat-number">
            {{ number_format($totalParticipants) }}
        </div>
        <div class="stat-note">Peserta terdaftar di sistem</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Pelatihan</div>
        <div class="stat-number">
            {{ number_format($totalTrainings) }}
        </div>
        <div class="stat-note">Pelatihan tercatat di sistem</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Keikutsertaan</div>
        <div class="stat-number">
            {{ number_format($totalParticipations) }}
        </div>
        <div class="stat-note">Riwayat keikutsertaan peserta</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            Keikutsertaan Tahun {{ $year }}
        </div>
        <div class="stat-number">
            {{ number_format($participationsThisYear) }}
        </div>
        <div class="stat-note">
            Berdasarkan tanggal mulai pelatihan
        </div>
    </div>
</div>

{{-- Distribusi bidang pelatihan --}}
<div class="panel">
    <h2>Distribusi Bidang Pelatihan</h2>
    <p>
        Jumlah pelatihan per bidang untuk membantu pemantauan
        pelaksanaan program.
    </p>

    @php
        $maxFieldTotal = max(
            1,
            (int) $fieldStats->max('total')
        );
    @endphp

    @forelse ($fieldStats as $stat)
        <div class="field-stat">
            <div class="field-stat-line">
                <span>{{ $stat->field }}</span>
                <strong>{{ number_format($stat->total) }}</strong>
            </div>

            <div class="progress-track">
                <div
                    class="progress-fill"
                    style="width: {{ ($stat->total / $maxFieldTotal) * 100 }}%"
                ></div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            Belum ada data bidang pelatihan yang dapat ditampilkan.
        </div>
    @endforelse
</div>

{{-- Catatan pemantauan --}}
<div class="panel">
    <h2>Pemantauan Pelaksanaan Pelatihan</h2>
    <p>
        Gunakan menu <strong>Laporan Pelatihan</strong> pada sidebar
        untuk melihat rincian laporan dan memantau pelaksanaan
        pelatihan berdasarkan data yang tercatat di SIRAPIK.
    </p>
</div>
@endsection