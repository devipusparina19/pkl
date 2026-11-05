@extends('layouts.app')

@section('title', 'Profil Peserta')

@section('content')

<style>
    /* =====================================================
       PROFIL PESERTA - SIRAPIK
    ===================================================== */

    .profile-page {
        width: 100%;
        color: #243b53;
        font-size: 14px;
    }

    .profile-page *,
    .profile-page *::before,
    .profile-page *::after {
        box-sizing: border-box;
    }

    /* =====================================================
       HEADER HALAMAN
    ===================================================== */

    .profile-page .page-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 22px;
    }

    .profile-page .eyebrow {
        margin-bottom: 7px;
        color: #2865a8;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.3px;
        text-transform: uppercase;
    }

    .profile-page .page-heading h1 {
        margin: 0;
        color: #17365f;
        font-size: 27px;
        font-weight: 800;
        line-height: 1.35;
    }

    .profile-page .page-heading p {
        margin: 7px 0 0;
        color: #718096;
        font-size: 13px;
        line-height: 1.6;
    }

    /* =====================================================
       TOMBOL
    ===================================================== */

    .profile-page .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 10px 16px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition: all .2s ease;
    }

    .profile-page .btn-primary {
        color: #fff;
        background: #2865a8;
        border-color: #2865a8;
    }

    .profile-page .btn-primary:hover {
        color: #fff;
        background: #1d4f89;
        border-color: #1d4f89;
        transform: translateY(-1px);
    }

    .profile-page .btn-light {
        color: #2457a7;
        background: #edf4fd;
        border-color: #dce9fa;
    }

    .profile-page .btn-light:hover {
        color: #173f78;
        background: #dceafd;
    }

    /* =====================================================
       KARTU IDENTITAS
    ===================================================== */

    .profile-page .profile-hero {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
        padding: 25px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 13px;
        box-shadow: 0 4px 16px rgba(23, 43, 77, .035);
    }

    .profile-page .profile-hero::before {
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: #2865a8;
        content: "";
    }

    .profile-page .profile-identity {
        display: flex;
        align-items: center;
        gap: 17px;
        min-width: 0;
    }

    .profile-page .profile-avatar {
        display: flex;
        flex: 0 0 65px;
        align-items: center;
        justify-content: center;
        width: 65px;
        height: 65px;
        color: #2457a7;
        background: #eaf2fc;
        border: 1px solid #d7e6f8;
        border-radius: 13px;
        font-size: 25px;
        font-weight: 800;
    }

    .profile-page .profile-identity-info {
        min-width: 0;
    }

    .profile-page .profile-identity-info h2 {
        margin: 0;
        color: #17365f;
        font-size: 21px;
        font-weight: 800;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .profile-page .profile-identity-info p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    .profile-page .profile-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        padding: 6px 10px;
        color: #2457a7;
        background: #edf4fd;
        border: 1px solid #dce9fa;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    /* =====================================================
       STATISTIK
    ===================================================== */

    .profile-page .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 21px;
    }

    .profile-page .stat-card {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        min-width: 0;
        padding: 21px;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, .035);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .profile-page .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(23, 43, 77, .07);
    }

    .profile-page .stat-icon {
        display: flex;
        flex: 0 0 46px;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        color: #2865a8;
        background: #edf4fd;
        border-radius: 10px;
    }

    .profile-page .stat-content {
        min-width: 0;
    }

    .profile-page .stat-label {
        margin-bottom: 5px;
        color: #718096;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-page .stat-number {
        color: #17365f;
        font-size: 29px;
        font-weight: 800;
        line-height: 1.3;
    }

    .profile-page .stat-note {
        margin-top: 5px;
        color: #8494aa;
        font-size: 11px;
        line-height: 1.5;
    }

    /* =====================================================
       PANEL UMUM
    ===================================================== */

    .profile-page .panel {
        width: 100%;
        min-width: 0;
        margin-bottom: 21px;
        padding: 23px;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, .035);
    }

    .profile-page .section-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e7edf5;
    }

    .profile-page .section-heading h2 {
        margin: 0;
        color: #17365f;
        font-size: 17px;
        font-weight: 800;
    }

    .profile-page .section-heading p {
        margin: 6px 0 0;
        color: #8494aa;
        font-size: 12px;
        line-height: 1.6;
    }

    /* =====================================================
       INFORMASI IDENTITAS
    ===================================================== */

    .profile-page .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 30px;
    }

    .profile-page .detail-item {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
        padding: 14px 0;
        border-bottom: 1px solid #edf1f6;
    }

    .profile-page .detail-item:nth-last-child(-n+2) {
        border-bottom: none;
    }

    .profile-page .detail-item span {
        color: #718096;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-page .detail-item strong {
        color: #263f5e;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    /* =====================================================
       INFORMASI RIWAYAT
    ===================================================== */

    .profile-page .history-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 29px;
        height: 27px;
        padding: 0 9px;
        color: #2457a7;
        background: #edf4fd;
        border: 1px solid #dce9fa;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 800;
    }

    /* =====================================================
       TABEL
    ===================================================== */

    .profile-page .table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #dce5ef;
        border-radius: 9px;
        -webkit-overflow-scrolling: touch;
    }

    .profile-page table {
        width: 100%;
        min-width: 1250px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
    }

    .profile-page thead th {
        padding: 15px 12px;
        color: #173f78;
        background: #eaf1fa;
        border-bottom: 1px solid #d5e0ed;
        font-size: 11px;
        font-weight: 800;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
        letter-spacing: .25px;
    }

    .profile-page tbody td {
        padding: 15px 12px;
        color: #425873;
        border-bottom: 1px solid #e5ebf2;
        font-size: 12px;
        line-height: 1.7;
        vertical-align: middle;
    }

    .profile-page tbody tr:nth-child(odd) {
        background: #f5f7fa;
    }

    .profile-page tbody tr:nth-child(even) {
        background: #fff;
    }

    .profile-page tbody tr:hover {
        background: #edf4fd;
    }

    .profile-page tbody tr:last-child td {
        border-bottom: none;
    }

    .profile-page tbody td:first-child {
        color: #17365f;
        font-weight: 700;
        text-align: center;
    }

    .profile-page .training-name {
        display: block;
        min-width: 190px;
        color: #1c3659;
        font-size: 12px;
        font-weight: 700;
    }

    .profile-page .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        color: #2457a7;
        background: #edf4fd;
        border: 1px solid #dce9fa;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* =====================================================
       DATA KOSONG
    ===================================================== */

    .profile-page .empty-state {
        padding: 42px 18px;
        color: #8494aa;
        font-size: 13px;
        line-height: 1.8;
        text-align: center;
    }

    .profile-page .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        color: #7890ad;
        background: #f0f4f9;
        border-radius: 50%;
    }

    /* =====================================================
       RESPONSIVE TABLET
    ===================================================== */

    @media (max-width: 992px) {
        .profile-page .panel {
            padding: 19px;
        }

        .profile-page .profile-hero {
            padding: 21px;
        }
    }

    /* =====================================================
       RESPONSIVE MOBILE
    ===================================================== */

    @media (max-width: 768px) {
        .profile-page .page-heading {
            align-items: flex-start;
            margin-bottom: 17px;
        }

        .profile-page .page-heading h1 {
            font-size: 23px;
        }

        .profile-page .profile-hero {
            align-items: flex-start;
            padding: 18px;
        }

        .profile-page .profile-identity {
            gap: 12px;
        }

        .profile-page .profile-avatar {
            flex-basis: 53px;
            width: 53px;
            height: 53px;
            font-size: 21px;
        }

        .profile-page .profile-identity-info h2 {
            font-size: 17px;
        }

        .profile-page .profile-hero > div:last-child {
            width: 100%;
        }

        .profile-page .profile-hero .action-button {
            width: 100%;
        }

        .profile-page .stats-grid {
            grid-template-columns: 1fr;
            gap: 11px;
            margin-bottom: 15px;
        }

        .profile-page .stat-card {
            padding: 17px;
        }

        .profile-page .stat-number {
            font-size: 25px;
        }

        .profile-page .panel {
            padding: 16px;
            margin-bottom: 15px;
        }

        .profile-page .section-heading {
            margin-bottom: 13px;
        }

        .profile-page .section-heading h2 {
            font-size: 16px;
        }

        .profile-page .detail-grid {
            grid-template-columns: 1fr;
        }

        .profile-page .detail-item {
            padding: 12px 0;
            border-bottom: 1px solid #edf1f6 !important;
        }

        .profile-page .detail-item:last-child {
            border-bottom: none !important;
        }

        .profile-page .detail-item strong {
            font-size: 13px;
        }

        .profile-page .table-wrap {
            border-radius: 7px;
        }

        .profile-page .pagination-wrap {
            justify-content: flex-start;
        }
    }

    @media (max-width: 380px) {
        .profile-page .page-heading h1 {
            font-size: 21px;
        }

        .profile-page .profile-identity-info h2 {
            font-size: 15px;
        }

        .profile-page .panel {
            padding: 13px;
        }
    }
</style>

@php
    $totalHistories = $participant->histories->count();

    $totalTrainingDays = $participant->histories->sum(
        fn ($history) => $history->training?->duration_days ?? 0
    );

    $initials = collect(
        preg_split('/\s+/', trim($participant->name ?? ''))
    )
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');

    $initials = $initials ?: 'P';
@endphp

<div class="profile-page">

    {{-- =================================================
         HEADER HALAMAN
    ================================================== --}}

    <div class="page-heading">
        <div>
            <div class="eyebrow">
                {{ auth()->user()->role === 'admin' ? 'DATA MASTER' : 'PROFIL SAYA' }}
            </div>

            <h1>Profil Peserta</h1>

            <p>
                Informasi identitas dan riwayat keikutsertaan pelatihan.
            </p>
        </div>
    </div>

    {{-- =================================================
         IDENTITAS PESERTA
    ================================================== --}}

    <div class="profile-hero">

        <div class="profile-identity">

            <div class="profile-avatar">
                {{ $initials }}
            </div>

            <div class="profile-identity-info">

                <h2>{{ $participant->name ?: '-' }}</h2>

                <p>
                    NIP: {{ $participant->nip ?: '-' }}
                </p>

                <span class="profile-role">
                    <svg width="13" height="13" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Data Peserta
                </span>

            </div>

        </div>

        @if (auth()->user()->role === 'admin')
            <div>
                <a
                    class="action-button btn-primary"
                    href="{{ route('participants.edit', $participant) }}"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                    </svg>
                    Edit Profil
                </a>
            </div>
        @endif

    </div>

    {{-- =================================================
         STATISTIK PESERTA
    ================================================== --}}

    <div class="stats-grid">

        {{-- TOTAL RIWAYAT --}}

        <div class="stat-card">

            <div class="stat-icon">
                <svg width="23" height="23" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 6h13"/>
                    <path d="M8 12h13"/>
                    <path d="M8 18h13"/>
                    <path d="M3 6h.01"/>
                    <path d="M3 12h.01"/>
                    <path d="M3 18h.01"/>
                </svg>
            </div>

            <div class="stat-content">
                <div class="stat-label">Total Riwayat Pelatihan</div>

                <div class="stat-number">
                    {{ $totalHistories }}
                </div>

                <div class="stat-note">
                    Jumlah keikutsertaan pelatihan yang tercatat
                </div>
            </div>

        </div>

        {{-- TOTAL HARI PELATIHAN --}}

        <div class="stat-card">

            <div class="stat-icon">
                <svg width="23" height="23" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                    <path d="M16 2v4"/>
                    <path d="M8 2v4"/>
                    <path d="M3 10h18"/>
                </svg>
            </div>

            <div class="stat-content">
                <div class="stat-label">Total Hari Pelatihan</div>

                <div class="stat-number">
                    {{ $totalTrainingDays }}
                </div>

                <div class="stat-note">
                    Akumulasi durasi pelatihan yang tersedia (hari)
                </div>
            </div>

        </div>

    </div>

    {{-- =================================================
         INFORMASI PESERTA
    ================================================== --}}

    <div class="panel">

        <div class="section-heading">
            <div>
                <h2>Informasi Identitas Peserta</h2>
                <p>
                    Data pribadi dan informasi asal instansi peserta.
                </p>
            </div>
        </div>

        <div class="detail-grid">

            <div class="detail-item">
                <span>Nama Lengkap</span>
                <strong>{{ $participant->name ?: '-' }}</strong>
            </div>

            <div class="detail-item">
                <span>NIK</span>
                <strong>{{ $participant->nik ?: '-' }}</strong>
            </div>

            <div class="detail-item">
                <span>NIP</span>
                <strong>{{ $participant->nip ?: '-' }}</strong>
            </div>

            <div class="detail-item">
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

            <div class="detail-item">
                <span>Pangkat/Golongan</span>
                <strong>{{ $participant->rank ?: '-' }}</strong>
            </div>

            <div class="detail-item">
                <span>Profesi Nakes</span>
                <strong>{{ $participant->profession ?: '-' }}</strong>
            </div>

            <div class="detail-item">
                <span>Instansi/Unit Kerja</span>
                <strong>{{ $participant->institution ?: '-' }}</strong>
            </div>

            <div class="detail-item">
                <span>Kabupaten/Kota/Mitra/Mandiri</span>
                <strong>{{ $participant->district ?: '-' }}</strong>
            </div>

            <div class="detail-item">
                <span>Email</span>
                <strong>{{ $participant->email ?: '-' }}</strong>
            </div>

            <div class="detail-item">
                <span>Nomor Telepon</span>
                <strong>{{ $participant->phone ?: '-' }}</strong>
            </div>

        </div>

    </div>

    {{-- =================================================
         RIWAYAT PELATIHAN
    ================================================== --}}

    <div class="panel">

        <div class="section-heading">

            <div>
                <h2>Riwayat Pelatihan</h2>

                <p>
                    Daftar pelatihan yang pernah diikuti
                    {{ auth()->user()->role === 'admin' ? 'peserta ini' : 'oleh Anda' }}.
                </p>
            </div>

            <span class="history-count">
                {{ $totalHistories }} Data
            </span>

        </div>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th style="text-align: center;">NO.</th>
                        <th>NAMA PELATIHAN</th>
                        <th>ANGKATAN</th>
                        <th>SUMBER DANA</th>
                        <th>KATEGORI</th>
                        <th>JPL</th>
                        <th>PENYELENGGARA</th>
                        <th>TANGGAL PELATIHAN</th>
                        <th>DURASI</th>
                        <th>STATUS</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($participant->histories as $history)

                        @php
                            $training = $history->training;
                        @endphp

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <strong class="training-name">
                                    {{ $training?->name ?? 'Pelatihan tidak tersedia' }}
                                </strong>
                            </td>

                            <td>
                                {{ $training?->batch_number ?: '-' }}
                            </td>

                            <td>
                                {{ $training?->funding_source ?: '-' }}
                            </td>

                            <td>
                                {{ $training?->category?->name ?: '-' }}
                            </td>

                            <td>
                                {{ $training?->jpl ?? '-' }}
                            </td>

                            <td>
                                {{ $training?->organizer ?: '-' }}
                            </td>

                            <td>
                                @if ($training?->start_date)

                                    {{ $training->start_date->format('d/m/Y') }}

                                    @if ($training->end_date)
                                        – {{ $training->end_date->format('d/m/Y') }}
                                    @endif

                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ $training?->duration_days ?? 0 }} hari
                            </td>

                            <td>
                                <span class="status-pill">
                                    {{ ucfirst($history->status ?? '-') }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10" class="empty-state">

                                <div class="empty-icon">
                                    <svg width="22" height="22"
                                         viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="1.8"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M8 6h13"/>
                                        <path d="M8 12h13"/>
                                        <path d="M8 18h13"/>
                                        <path d="M3 6h.01"/>
                                        <path d="M3 12h.01"/>
                                        <path d="M3 18h.01"/>
                                    </svg>
                                </div>

                                <strong>Belum Ada Riwayat Pelatihan</strong>

                                <br>

                                Belum terdapat data keikutsertaan pelatihan
                                untuk peserta ini.

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection