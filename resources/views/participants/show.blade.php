
@extends('layouts.app')

@section('title', 'Profil Peserta')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">
            {{ auth()->user()->role === 'admin' ? 'DATA MASTER' : 'PROFIL SAYA' }}
        </div>

        <h1>{{ $participant->name }}</h1>
        <p>NIP {{ $participant->nip ?: '-' }}</p>
    </div>

    @if (auth()->user()->role === 'admin')
        <a class="btn btn-light"
           href="{{ route('participants.edit', $participant) }}">
            Edit Profil
        </a>
    @endif
</div>

{{-- Statistik peserta --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Riwayat</div>
        <div class="stat-number">
            {{ $participant->histories->count() }}
        </div>
        <div class="stat-note">Keikutsertaan tercatat</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Hari Pelatihan</div>
        <div class="stat-number">
            {{ $participant->histories->sum(
                fn ($history) => $history->training?->duration_days ?? 0
            ) }}
        </div>
        <div class="stat-note">
            Total hari berdasarkan durasi pelatihan yang tersedia
        </div>
    </div>
</div>

{{-- Informasi peserta --}}
<div class="panel">
    <div class="section-heading">
        <div>
            <h2>Informasi Peserta</h2>
            <p>Data identitas dan asal instansi sesuai data peserta.</p>
        </div>
    </div>

    <div class="detail-grid">
        <div>
            <span>Nama Lengkap</span>
            <strong>{{ $participant->name ?: '-' }}</strong>
        </div>

        <div>
            <span>NIK</span>
            <strong>{{ $participant->nik ?: '-' }}</strong>
        </div>

        <div>
            <span>NIP</span>
            <strong>{{ $participant->nip ?: '-' }}</strong>
        </div>

        <div>
            <span>Jenis Kelamin</span>
            <strong>
                @if ($participant->gender === 'L')
                    Laki-laki
                @elseif ($participant->gender === 'P')
                    Perempuan
                @else
                    {{ $participant->gender ?: '-' }}
                @endif
            </strong>
        </div>

        <div>
            <span>Pangkat/Golongan</span>
            <strong>{{ $participant->rank ?: '-' }}</strong>
        </div>

        <div>
            <span>Profesi Nakes</span>
            <strong>{{ $participant->profession ?: '-' }}</strong>
        </div>

        <div>
            <span>Instansi</span>
            <strong>{{ $participant->institution ?: '-' }}</strong>
        </div>

        <div>
            <span>Unit Kerja</span>
            <strong>{{ $participant->unit ?: '-' }}</strong>
        </div>

        <div>
            <span>Kabupaten/Kota</span>
            <strong>{{ $participant->district ?: '-' }}</strong>
        </div>

        <div>
            <span>Provinsi</span>
            <strong>{{ $participant->province ?: '-' }}</strong>
        </div>

        <div>
            <span>Jabatan</span>
            <strong>{{ $participant->position ?: '-' }}</strong>
        </div>

        <div>
            <span>Email</span>
            <strong>{{ $participant->email ?: '-' }}</strong>
        </div>

        <div>
            <span>Nomor Telepon</span>
            <strong>{{ $participant->phone ?: '-' }}</strong>
        </div>
    </div>
</div>

{{-- Riwayat pelatihan --}}
<div class="panel">
    <div class="section-heading">
        <div>
            <h2>Riwayat Pelatihan</h2>
            <p>
                Daftar pelatihan yang pernah diikuti
                {{ auth()->user()->role === 'admin'
                    ? 'peserta ini'
                    : 'oleh Anda' }}.
            </p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelatihan</th>
                    <th>Angkatan</th>
                    <th>Sumber Dana</th>
                    <th>Bidang</th>
                    <th>Penyelenggara</th>
                    <th>Tanggal Pelatihan</th>
                    <th>Durasi</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($participant->histories as $history)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <strong>
                                {{ $history->training?->name
                                    ?? 'Pelatihan tidak tersedia' }}
                            </strong>
                        </td>

                        <td>
                            {{ $history->training?->batch_number ?? '-' }}
                        </td>

                        <td>
                            {{ $history->training?->funding_source ?? '-' }}
                        </td>

                        <td>
                            {{ $history->training?->field ?: '-' }}
                        </td>

                        <td>
                            {{ $history->training?->organizer ?: '-' }}
                        </td>

                        <td>
                            @if ($history->training?->start_date)
                                {{ $history->training->start_date->format('d/m/Y') }}

                                @if ($history->training?->end_date)
                                    – {{ $history->training->end_date->format('d/m/Y') }}
                                @endif
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ $history->training?->duration_days ?? 0 }} hari
                        </td>

                        <td>
                            <span class="status-pill">
                                {{ ucfirst($history->status ?? '-') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty-state">
                            Belum ada riwayat pelatihan untuk peserta ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection