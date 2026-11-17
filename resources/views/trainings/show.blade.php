@extends('layouts.app')

@section('title', 'Detail Pelatihan')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       PREMIUM BLUE EDITORIAL — DETAIL PELATIHAN
       ===================================================== */

    .td {
        --navy: #081B33;
        --midnight: #0B2545;
        --royal: #2457D6;
        --electric: #3478F6;
        --soft: #EAF2FF;
        --ice: #F3F7FC;
        --slate: #64748B;
        --text: #14243A;
        --border: #E1E9F2;
        --cyan: #38C6E8;
        --ok: #0F6B4A;
        --ok-bg: #E8F6EF;
        --warning: #946200;
        --warning-bg: #FFF7E6;
        --font-display: "Sora", "Segoe UI", Arial, sans-serif;
        --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
        --ease: cubic-bezier(.2, .7, .2, 1);

        width: 100%;
        color: var(--text);
        font-family: var(--font-body);
        font-size: 14px;
        line-height: 1.7;
    }

    .td *,
    .td *::before,
    .td *::after {
        box-sizing: border-box;
    }

    .td svg.i {
        width: 1em;
        height: 1em;
        flex: none;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .td a {
        text-decoration: none;
    }

    .td :focus-visible {
        outline: 3px solid var(--cyan);
        outline-offset: 3px;
    }

    /* HEADER */

    .td .hero {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 24px;
        padding: clamp(26px, 4vw, 44px);
        margin-bottom: 22px;
        border-radius: 24px;
        color: white;
        background: linear-gradient(
            120deg,
            var(--navy),
            var(--midnight) 60%,
            #173d86
        );
        animation: tdRise .7s var(--ease) both;
    }

    .td .hero::after {
        content: "";
        position: absolute;
        z-index: -1;
        width: 300px;
        height: 300px;
        right: -80px;
        top: -130px;
        border-radius: 50%;
        background: var(--royal);
        opacity: .45;
        filter: blur(65px);
    }

    .td .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        color: var(--cyan);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .td .hero h1 {
        margin: 0 0 12px;
        color: white;
        font-family: var(--font-display);
        font-size: clamp(1.7rem, 4vw, 2.8rem);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.035em;
    }

    .td .hero p {
        max-width: 680px;
        margin: 0;
        color: rgba(255,255,255,.78);
        font-size: 14px;
    }

    .td .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    /* BUTTONS */

    .td .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 9px 15px;
        border: 1px solid transparent;
        border-radius: 11px;
        font-family: var(--font-display);
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
        transition: .2s var(--ease);
    }

    .td .btn:hover {
        transform: translateY(-2px);
    }

    .td .btn-light {
        color: var(--navy);
        background: white;
    }

    .td .btn-light:hover {
        color: var(--royal);
        background: var(--soft);
    }

    .td .btn-outline {
        color: white;
        border-color: rgba(255,255,255,.35);
        background: rgba(255,255,255,.08);
    }

    .td .btn-outline:hover {
        background: rgba(255,255,255,.17);
    }

    .td .btn-primary {
        color: white;
        background: linear-gradient(110deg, var(--royal), var(--electric));
        box-shadow: 0 8px 20px rgba(36,87,214,.2);
    }

    .td .btn-primary:hover {
        color: white;
        box-shadow: 0 12px 25px rgba(36,87,214,.3);
    }

    .td .btn-secondary {
        color: var(--royal);
        border-color: #C5D6F5;
        background: white;
    }

    .td .btn-secondary:hover {
        background: var(--soft);
    }

    .td .btn-sm {
        min-height: 38px;
        padding: 7px 12px;
        font-size: 12px;
    }

    /* STATISTICS */

    .td .stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .td .stat {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
        padding: 19px;
        border: 1px solid var(--border);
        border-radius: 17px;
        background: white;
        box-shadow: 0 8px 25px rgba(8,27,51,.035);
    }

    .td .stat-icon {
        display: grid;
        place-items: center;
        width: 48px;
        height: 48px;
        flex: none;
        border-radius: 14px;
        color: var(--royal);
        background: var(--soft);
        font-size: 22px;
    }

    .td .stat small {
        display: block;
        color: var(--slate);
        font-size: 12px;
        font-weight: 600;
    }

    .td .stat strong {
        display: block;
        margin-top: 3px;
        color: var(--navy);
        font-family: var(--font-display);
        font-size: 23px;
        font-weight: 800;
        line-height: 1.3;
    }

    /* PANEL */

    .td .panel {
        overflow: hidden;
        margin-bottom: 22px;
        border: 1px solid var(--border);
        border-radius: 19px;
        background: white;
        box-shadow: 0 12px 35px rgba(8,27,51,.045);
        animation: tdRise .7s var(--ease) both;
    }

    .td .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 19px 23px;
        border-bottom: 1px solid var(--border);
    }

    .td .panel-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .td .panel-heading .icon {
        display: grid;
        place-items: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        color: var(--royal);
        background: var(--soft);
        font-size: 19px;
    }

    .td .panel-heading h2 {
        margin: 0;
        color: var(--navy);
        font-family: var(--font-display);
        font-size: 16px;
        font-weight: 700;
    }

    .td .panel-heading p {
        margin: 2px 0 0;
        color: var(--slate);
        font-size: 12px;
    }

    .td .panel-body {
        padding: 23px;
    }

    /* DETAIL GRID */

    .td .detail-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0 24px;
    }

    .td .detail-item {
        min-width: 0;
        padding: 15px 0;
        border-bottom: 1px solid #EDF1F6;
    }

    .td .detail-item:nth-last-child(-n+3) {
        border-bottom: 0;
    }

    .td .detail-label {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 6px;
        color: var(--slate);
        font-size: 12px;
        font-weight: 600;
    }

    .td .detail-value {
        overflow-wrap: anywhere;
        color: var(--navy);
        font-size: 14px;
        font-weight: 700;
    }

    .td .description {
        margin-top: 20px;
        padding: 18px;
        border: 1px solid #DCE8FA;
        border-radius: 14px;
        background: #F7FAFF;
    }

    .td .description h3 {
        margin: 0 0 8px;
        color: var(--navy);
        font-family: var(--font-display);
        font-size: 13px;
    }

    .td .description p {
        margin: 0;
        color: #45566B;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .td .tag {
        display: inline-flex;
        align-items: center;
        padding: 5px 11px;
        border-radius: 999px;
        color: var(--royal);
        background: var(--soft);
        font-size: 12px;
        font-weight: 700;
    }

    /* SEARCH PARTICIPANTS */

    .td .participant-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        padding: 18px 23px;
        border-bottom: 1px solid var(--border);
    }

    .td .search-form {
        display: flex;
        align-items: center;
        flex: 1;
        gap: 9px;
        min-width: 250px;
    }

    .td .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .td .search-input-wrap svg {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--slate);
        font-size: 17px;
    }

    .td .search-input {
        width: 100%;
        height: 43px;
        padding: 0 13px 0 40px;
        border: 1px solid var(--border);
        border-radius: 11px;
        outline: none;
        color: var(--text);
        background: var(--ice);
        font-family: var(--font-body);
        font-size: 13px;
    }

    .td .search-input:focus {
        border-color: var(--electric);
        background: white;
        box-shadow: 0 0 0 3px rgba(52,120,246,.12);
    }

    .td .participant-count {
        color: var(--slate);
        font-size: 12px;
        font-weight: 600;
    }

    /* TABLE */

    .td .table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .td table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .td thead th {
        padding: 14px 15px;
        color: white;
        background: var(--navy);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-align: left;
        white-space: nowrap;
    }

    .td tbody td {
        padding: 14px 15px;
        border-bottom: 1px solid #EDF1F6;
        color: #33465C;
        font-size: 12px;
        vertical-align: middle;
    }

    .td tbody tr:hover {
        background: #F5F9FF;
    }

    .td tbody tr:last-child td {
        border-bottom: 0;
    }

    .td .nowrap {
        white-space: nowrap;
    }

    .td .number {
        width: 55px;
        color: var(--slate);
        font-weight: 700;
    }

    .td .participant-name {
        min-width: 170px;
        color: var(--navy);
        font-weight: 700;
    }

    /* kolom Pangkat/Golongan: badge ringan agar mudah dibaca */
    .td .rank {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 8px;
        color: var(--royal);
        background: var(--soft);
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .td .status {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 999px;
        color: var(--ok);
        background: var(--ok-bg);
        font-size: 11px;
        font-weight: 700;
    }

    .td .status-other {
        color: var(--warning);
        background: var(--warning-bg);
    }

    /* EMPTY STATE */

    .td .empty {
        padding: 48px 20px;
        text-align: center;
        color: var(--slate);
    }

    .td .empty-icon {
        display: grid;
        place-items: center;
        width: 58px;
        height: 58px;
        margin: 0 auto 13px;
        border-radius: 17px;
        color: var(--royal);
        background: var(--soft);
        font-size: 25px;
    }

    .td .empty strong {
        display: block;
        margin-bottom: 5px;
        color: var(--navy);
        font-family: var(--font-display);
        font-size: 16px;
    }

    /* PAGINATION */

    .td .pager {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 22px;
        border-top: 1px solid var(--border);
    }

    .td .pager p {
        margin: 0;
        color: var(--slate);
        font-size: 12px;
    }

    .td .pager nav {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .td .pager nav > div:first-child {
        display: none;
    }

    .td .pager nav a,
    .td .pager nav span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 35px;
        min-height: 35px;
        padding: 5px 9px;
        border: 1px solid var(--border);
        border-radius: 9px;
        color: var(--royal);
        background: var(--ice);
        font-size: 12px;
    }

    .td .pager nav a:hover {
        color: white;
        background: var(--royal);
    }

    .td .pager nav span[aria-current="page"] span {
        color: white;
        background: var(--royal);
    }

    .td .pager svg {
        width: 16px;
        height: 16px;
    }

    /* RESPONSIVE */

    @keyframes tdRise {
        from {
            opacity: 0;
            transform: translateY(14px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 950px) {
        .td .hero {
            grid-template-columns: 1fr;
        }

        .td .detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .td .detail-item:nth-last-child(-n+3) {
            border-bottom: 1px solid #EDF1F6;
        }

        .td .detail-item:nth-last-child(-n+1) {
            border-bottom: 0;
        }
    }

    @media (max-width: 650px) {
        .td .stats {
            grid-template-columns: 1fr;
        }

        .td .detail-grid {
            grid-template-columns: 1fr;
        }

        .td .detail-item,
        .td .detail-item:nth-last-child(-n+3) {
            border-bottom: 1px solid #EDF1F6;
        }

        .td .detail-item:last-child {
            border-bottom: 0;
        }

        .td .panel-body {
            padding: 16px;
        }

        .td .panel-header,
        .td .participant-toolbar {
            padding: 15px;
        }

        .td .hero-actions {
            width: 100%;
        }

        .td .hero-actions .btn {
            flex: 1;
        }

        .td .search-form {
            width: 100%;
            min-width: 0;
            flex-wrap: wrap;
        }

        .td .search-input-wrap {
            flex-basis: 100%;
        }

        .td .search-form .btn {
            flex: 1;
        }

        .td .pager {
            padding: 14px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .td *,
        .td *::before,
        .td *::after {
            animation: none !important;
            transition: none !important;
        }
    }
</style>

@php
    $search = $search ?? request('search', '');

    $formatDate = function ($date) {
        if (!$date) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($date)->translatedFormat('d F Y');
        } catch (\Throwable $e) {
            return $date;
        }
    };

    /*
     | Informasi pelatihan: item yang kosong TIDAK ditampilkan
     | (tidak ada lagi tanda "-").
     */
    $details = collect([
        ['Nama Pelatihan',     $training->name],
        ['Kategori Pelatihan', $training->category?->name],
        ['Penyelenggara',      $training->organizer],
        ['Angkatan',           $training->batch_number ?: $training->angkatan],
        ['Sumber Dana',        $training->funding_source ?: $training->sumber_dana],
        ['Jumlah JPL',         $training->jpl !== null ? $training->jpl . ' JPL' : null],
        ['Durasi',             $training->duration_days !== null ? $training->duration_days . ' Hari' : null],
        ['Tanggal Mulai',      $formatDate($training->start_date)],
        ['Tanggal Selesai',    $formatDate($training->end_date)],
        ['Kabupaten/Kota',     $training->district],
    ])->filter(fn ($row) => filled($row[1]))->values();

    /*
     | Kolom tabel peserta. Judul kolom sekarang SAMA PERSIS dengan isi sel
     | (sebelumnya judul "Pangkat" tertukar dengan isi Jabatan).
     | Kolom yang seluruh barisnya kosong di halaman ini disembunyikan.
     */
    $columns = [
        ['key' => 'nip',                  'label' => 'NIP',            'class' => 'nowrap'],
        ['key' => 'nik',                  'label' => 'NIK',            'class' => 'nowrap'],
        ['key' => 'gender',               'label' => 'Jenis Kelamin',  'class' => 'nowrap'],
        ['key' => 'profession',           'label' => 'Profesi',        'class' => ''],
        ['key' => 'position',             'label' => 'Jabatan',        'class' => ''],
        ['key' => 'rank',                 'label' => 'Pangkat',        'class' => 'rank'],
        ['key' => 'institution',          'label' => 'Instansi',       'class' => ''],
        ['key' => 'unit',                 'label' => 'Unit Kerja',     'class' => ''],
        ['key' => 'participant_district', 'label' => 'Kabupaten/Kota', 'class' => ''],
        ['key' => 'province',             'label' => 'Provinsi',       'class' => ''],
        ['key' => 'certificate_number',   'label' => 'No. Sertifikat', 'class' => 'nowrap'],
    ];

    $pageRows = $participants->getCollection();

    $visibleColumns = collect($columns)
        ->filter(fn ($col) => $pageRows->contains(fn ($p) => filled($p->{$col['key']})))
        ->values();

    $showStatus = $pageRows->contains(fn ($p) => filled($p->participation_status ?? null));

    $colspan = 2 + $visibleColumns->count() + ($showStatus ? 1 : 0);
@endphp

<div class="td">

    {{-- HEADER --}}
    <header class="hero">
        <div>
            <div class="eyebrow">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4"/>
                    <path d="M12 8h.01"/>
                </svg>
                INFORMASI PROGRAM
            </div>

            <h1>Detail <span>Pelatihan</span></h1>

            <p>
                Informasi lengkap program pelatihan beserta daftar peserta
                yang terdaftar dalam kegiatan ini.
            </p>
        </div>

        <div class="hero-actions">
            <a href="{{ route('trainings.index') }}" class="btn btn-outline">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/>
                    <path d="M9 12h12"/>
                </svg>
                Kembali
            </a>

            <a href="{{ route('trainings.edit', $training) }}" class="btn btn-light">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 20h9"/>
                    <path d="M16.4 3.6a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                </svg>
                Edit Pelatihan
            </a>
        </div>
    </header>

    {{-- STATISTIK --}}
    <section class="stats">

        <div class="stat">
            <div class="stat-icon">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                    <path d="M8 2v4m8-4v4M3 10h18"/>
                </svg>
            </div>

            <div>
                <small>Durasi Pelatihan</small>
                <strong>{{ $training->duration_days ?? 0 }}</strong>
                <small>Hari</small>
            </div>
        </div>

        <div class="stat">
            <div class="stat-icon">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="9" cy="8" r="4"/>
                    <path d="M3 21v-2a6 6 0 0 1 12 0v2"/>
                    <path d="M16 4.5a4 4 0 0 1 0 7.5"/>
                    <path d="M18 15a5 5 0 0 1 3 4.5V21"/>
                </svg>
            </div>

            <div>
                <small>Total Peserta</small>
                <strong>{{ number_format($totalParticipants ?? 0, 0, ',', '.') }}</strong>
                <small>Peserta terdaftar</small>
            </div>
        </div>

        <div class="stat">
            <div class="stat-icon">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                    <path d="M8 7h8M8 11h8"/>
                </svg>
            </div>

            <div>
                <small>Jumlah JPL</small>
                <strong>{{ $training->jpl ?? 0 }}</strong>
                <small>Jam Pelajaran</small>
            </div>
        </div>

    </section>

    {{-- INFORMASI PELATIHAN --}}
    <section class="panel">

        <div class="panel-header">
            <div class="panel-heading">
                <div class="icon">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                    </svg>
                </div>

                <div>
                    <h2>Informasi Pelatihan</h2>
                    <p>Rincian lengkap program pelatihan</p>
                </div>
            </div>

            <span class="tag">
                {{ $training->code ?: 'Data Pelatihan' }}
            </span>
        </div>

        <div class="panel-body">

            <div class="detail-grid">

                @foreach ($details as $row)
                    <div class="detail-item">
                        <div class="detail-label">{{ $row[0] }}</div>
                        <div class="detail-value">{{ $row[1] }}</div>
                    </div>
                @endforeach

            </div>

        </div>

    </section>

    {{-- DAFTAR PESERTA --}}
    <section class="panel">

        <div class="panel-header">
            <div class="panel-heading">
                <div class="icon">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="9" cy="8" r="4"/>
                        <path d="M3 21v-2a6 6 0 0 1 12 0v2"/>
                        <path d="M16 4.5a4 4 0 0 1 0 7.5"/>
                        <path d="M18 15a5 5 0 0 1 3 4.5V21"/>
                    </svg>
                </div>

                <div>
                    <h2>Daftar Peserta Pelatihan</h2>
                    <p>Peserta yang terhubung dengan program pelatihan ini</p>
                </div>
            </div>

            <span class="tag">
                {{ number_format($totalParticipants ?? 0, 0, ',', '.') }} Peserta
            </span>
        </div>

        {{-- PENCARIAN PESERTA --}}
        <div class="participant-toolbar">

            <form
                action="{{ route('trainings.show', $training) }}"
                method="GET"
                class="search-form"
            >
                <div class="search-input-wrap">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>

                    <input
                        type="search"
                        name="search"
                        class="search-input"
                        value="{{ $search }}"
                        placeholder="Cari nama, NIP, NIK, instansi, profesi..."
                        aria-label="Cari peserta pelatihan"
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                    Cari
                </button>

                @if ($search !== '')
                    <a
                        href="{{ route('trainings.show', $training) }}"
                        class="btn btn-secondary"
                    >
                        Reset
                    </a>
                @endif
            </form>

            <div class="participant-count">
                @if ($participants->total() > 0)
                    Menampilkan {{ $participants->firstItem() }}
                    - {{ $participants->lastItem() }}
                    dari {{ $participants->total() }} peserta
                @else
                    Tidak ada peserta ditemukan
                @endif
            </div>

        </div>

        {{-- TABEL PESERTA --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Peserta</th>

                        @foreach ($visibleColumns as $col)
                            <th>{{ $col['label'] }}</th>
                        @endforeach

                        @if ($showStatus)
                            <th>Status</th>
                        @endif
                    </tr>
                </thead>

                <tbody>

                    @forelse ($participants as $index => $participant)

                        <tr>
                            <td class="number">
                                {{ $participants->firstItem() + $index }}
                            </td>

                            <td class="participant-name">
                                {{ $participant->participant_name }}
                            </td>

                            @foreach ($visibleColumns as $col)
                                @php $cell = $participant->{$col['key']}; @endphp

                                <td class="{{ $col['class'] === 'rank' ? '' : $col['class'] }}">
                                    @if (filled($cell))
                                        @if ($col['class'] === 'rank')
                                            <span class="rank">{{ $cell }}</span>
                                        @else
                                            {{ $cell }}
                                        @endif
                                    @endif
                                </td>
                            @endforeach

                            @if ($showStatus)
                                <td>
                                    @php
                                        $participantStatus = $participant->participation_status ?? '';
                                    @endphp

                                    @if (strtolower($participantStatus) === 'selesai')
                                        <span class="status">{{ $participantStatus }}</span>
                                    @elseif ($participantStatus !== '')
                                        <span class="status status-other">{{ $participantStatus }}</span>
                                    @endif
                                </td>
                            @endif
                        </tr>

                    @empty

                        <tr>
                            <td colspan="{{ $colspan }}">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                                            <circle cx="9" cy="8" r="4"/>
                                            <path d="M3 21v-2a6 6 0 0 1 12 0v2"/>
                                            <path d="M17 8h5M19.5 5.5v5"/>
                                        </svg>
                                    </div>

                                    @if ($search !== '')
                                        <strong>Peserta tidak ditemukan</strong>
                                        Tidak ada peserta yang cocok dengan kata kunci
                                        "{{ $search }}".
                                        <br>
                                        Silakan coba kata kunci lainnya.
                                    @else
                                        <strong>Belum ada peserta</strong>
                                        Belum terdapat peserta yang terhubung dengan
                                        pelatihan ini.
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($participants->hasPages())
            <div class="pager">
                <p>
                    Halaman {{ $participants->currentPage() }}
                    dari {{ $participants->lastPage() }}
                </p>

                {{ $participants->appends(request()->query())->links() }}
            </div>
        @endif

    </section>

    {{-- TOMBOL KEMBALI --}}
    <div style="display:flex;justify-content:flex-end;margin-bottom:25px;">
        <a href="{{ route('trainings.index') }}" class="btn btn-secondary">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                <path d="m15 18-6-6 6-6"/>
                <path d="M9 12h12"/>
            </svg>
            Kembali ke Data Pelatihan
        </a>
    </div>

</div>

@endsection