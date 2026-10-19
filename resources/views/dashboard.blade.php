@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard Admin SIRAPIK</h1>
        <p>Pengelolaan data peserta, pelatihan, impor Excel, dan laporan.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Peserta</div>
        <div class="stat-value">{{ number_format($totalParticipants) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Pelatihan</div>
        <div class="stat-value">{{ number_format($totalTrainings) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Riwayat Keikutsertaan</div>
        <div class="stat-value">{{ number_format($totalParticipations) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Keikutsertaan Tahun {{ $year }}</div>
        <div class="stat-value">{{ number_format($participationsThisYear) }}</div>
    </div>
</div>

<div class="content-card">
    <h2>Peserta dengan Frekuensi Pelatihan Tertinggi</h2>
    <p>
        Daftar ini membantu staf meninjau distribusi peserta,
        bukan menetapkan bahwa seseorang pasti terbebani.
    </p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Jumlah Pelatihan {{ $year }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($frequentParticipants as $participant)
                    <tr>
                        <td>{{ $participant->name }}</td>
                        <td>{{ $participant->nip }}</td>
                        <td>{{ $participant->participations_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Belum ada data peserta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="content-card">
    <h2>Bidang Pelatihan</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Bidang</th>
                    <th>Jumlah Pelatihan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fieldStats as $field)
                    <tr>
                        <td>{{ $field->field }}</td>
                        <td>{{ $field->total }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Belum ada data bidang pelatihan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="content-card">
    <h2>Riwayat Pelatihan Terbaru</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Pelatihan</th>
                    <th>Tanggal Mulai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentHistories as $history)
                    <tr>
                        <td>{{ $history->participant?->name ?? '-' }}</td>
                        <td>{{ $history->training?->name ?? '-' }}</td>
                        <td>
                            {{ $history->training?->start_date?->format('d-m-Y') ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Belum ada riwayat pelatihan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection