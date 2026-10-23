@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    /* =========================================
       SIRAPIK — ADMIN DASHBOARD
       Gaya: Institutional Modern / Editorial
       ========================================= */

    .dashboard-admin {
        --sirapik-ink: #172b4d;
        --sirapik-muted: #6c7b91;
        --sirapik-line: #e3eaf1;
        --sirapik-paper: #ffffff;
        --sirapik-bg: #f4f7fb;
        --sirapik-blue: #2457a7;
        --sirapik-teal: #087f8c;
        --sirapik-gold: #c58a32;

        color: var(--sirapik-ink);
        font-family: inherit;
        padding-bottom: 28px;
    }

    .dashboard-admin * {
        box-sizing: border-box;
    }

    /* Header utama */

    .dashboard-admin .dashboard-heading {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        overflow: hidden;
        padding: 30px 32px;
        margin-bottom: 25px;
        color: #fff;
        background:
            radial-gradient(
                circle at 92% 8%,
                rgba(86, 211, 205, .22),
                transparent 27%
            ),
            linear-gradient(118deg, #172b4d 0%, #204a7e 68%, #176b79 100%);
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(23, 43, 77, .12);
    }

    .dashboard-admin .dashboard-heading::after {
        content: "";
        position: absolute;
        right: -34px;
        bottom: -105px;
        width: 245px;
        height: 245px;
        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 50%;
        box-shadow:
            0 0 0 25px rgba(255, 255, 255, .035),
            0 0 0 52px rgba(255, 255, 255, .025);
        pointer-events: none;
    }

    .dashboard-admin .heading-copy {
        position: relative;
        z-index: 1;
        max-width: 650px;
    }

    .dashboard-admin .heading-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 12px;
        color: #bce9e8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .dashboard-admin .heading-eyebrow::before {
        content: "";
        width: 22px;
        height: 2px;
        background: #67d5cd;
        border-radius: 10px;
    }

    .dashboard-admin .dashboard-heading h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(25px, 3vw, 35px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.8px;
    }

    .dashboard-admin .dashboard-heading p {
        max-width: 560px;
        margin: 12px 0 0;
        color: rgba(255, 255, 255, .79);
        font-size: 14px;
        line-height: 1.8;
    }

    .dashboard-admin .heading-mark {
        position: relative;
        z-index: 1;
        display: flex;
        flex: 0 0 76px;
        align-items: center;
        justify-content: center;
        width: 76px;
        height: 76px;
        color: #d4fbf5;
        background: rgba(255, 255, 255, .10);
        border: 1px solid rgba(255, 255, 255, .19);
        border-radius: 22px;
        backdrop-filter: blur(5px);
    }

    .dashboard-admin .heading-mark svg {
        width: 38px;
        height: 38px;
    }

    /* Judul bagian */

    .dashboard-admin .section-intro {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        margin: 0 0 15px;
    }

    .dashboard-admin .section-kicker {
        display: block;
        margin-bottom: 5px;
        color: var(--sirapik-teal);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.8px;
        text-transform: uppercase;
    }

    .dashboard-admin .section-intro h2 {
        margin: 0;
        color: var(--sirapik-ink);
        font-size: 19px;
        font-weight: 800;
        letter-spacing: -.35px;
    }

    .dashboard-admin .section-intro p {
        margin: 6px 0 0;
        color: var(--sirapik-muted);
        font-size: 13px;
        line-height: 1.7;
    }

    /* Kartu ringkasan statistik */

    .dashboard-admin .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 30px;
    }

    .dashboard-admin .stat-card {
        position: relative;
        min-width: 0;
        overflow: hidden;
        padding: 22px 21px 20px;
        background: var(--sirapik-paper);
        border: 1px solid var(--sirapik-line);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, .035);
        transition:
            transform .22s ease,
            box-shadow .22s ease,
            border-color .22s ease;
    }

    .dashboard-admin .stat-card:hover {
        transform: translateY(-4px);
        border-color: #c6d8e9;
        box-shadow: 0 12px 24px rgba(23, 43, 77, .085);
    }

    .dashboard-admin .stat-card::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--stat-accent, var(--sirapik-blue));
    }

    .dashboard-admin .stat-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 19px;
    }

    .dashboard-admin .stat-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 43px;
        width: 43px;
        height: 43px;
        color: var(--stat-accent, var(--sirapik-blue));
        background: var(--stat-soft, #eaf1fb);
        border-radius: 13px;
    }

    .dashboard-admin .stat-icon svg {
        width: 22px;
        height: 22px;
    }

    .dashboard-admin .stat-index {
        color: #a4b0c0;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .dashboard-admin .stat-label {
        min-height: 38px;
        color: var(--sirapik-muted);
        font-size: 12px;
        font-weight: 700;
        line-height: 1.6;
    }

    .dashboard-admin .stat-value {
        margin-top: 5px;
        color: var(--sirapik-ink);
        font-size: clamp(27px, 2.5vw, 35px);
        font-weight: 850;
        line-height: 1.25;
        letter-spacing: -1.1px;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-admin .stat-caption {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 13px;
        padding-top: 12px;
        color: #8492a6;
        font-size: 11px;
        line-height: 1.5;
        border-top: 1px solid #edf1f6;
    }

    .dashboard-admin .stat-caption svg {
        flex: 0 0 14px;
        width: 14px;
        height: 14px;
        color: var(--stat-accent, var(--sirapik-blue));
    }

    /* Panel dan tabel */

    .dashboard-admin .content-card {
        min-width: 0;
        margin-bottom: 22px;
        padding: 25px;
        background: var(--sirapik-paper);
        border: 1px solid var(--sirapik-line);
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(23, 43, 77, .035);
    }

    .dashboard-admin .panel-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 19px;
    }

    .dashboard-admin .panel-heading h2 {
        margin: 0;
        color: var(--sirapik-ink);
        font-size: 17px;
        font-weight: 800;
        line-height: 1.5;
        letter-spacing: -.25px;
    }

    .dashboard-admin .panel-heading p {
        max-width: 700px;
        margin: 7px 0 0;
        color: var(--sirapik-muted);
        font-size: 12px;
        line-height: 1.8;
    }

    .dashboard-admin .panel-number {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        gap: 7px;
        padding: 8px 11px;
        color: #526984;
        background: #f2f6fb;
        border: 1px solid #e5edf5;
        border-radius: 9px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .7px;
        text-transform: uppercase;
    }

    .dashboard-admin .panel-number svg {
        width: 15px;
        height: 15px;
    }

    .dashboard-admin .table-responsive {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e7edf4;
        border-radius: 11px;
        -webkit-overflow-scrolling: touch;
    }

    .dashboard-admin .table-responsive table {
        width: 100%;
        min-width: 440px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        text-align: left;
    }

    .dashboard-admin .table-responsive thead th {
        padding: 14px 17px;
        color: #64758c;
        background: #f5f8fc;
        border-bottom: 1px solid #e2eaf2;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .9px;
        line-height: 1.5;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dashboard-admin .table-responsive tbody td {
        padding: 15px 17px;
        color: #43536b;
        border-bottom: 1px solid #edf1f6;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.7;
        vertical-align: middle;
    }

    .dashboard-admin .table-responsive tbody tr:last-child td {
        border-bottom: 0;
    }

    .dashboard-admin .table-responsive tbody tr {
        transition: background .18s ease;
    }

    .dashboard-admin .table-responsive tbody tr:hover {
        background: #f8fbff;
    }

    .dashboard-admin .table-responsive tbody td:first-child {
        color: #233a5b;
        font-weight: 700;
    }

    .dashboard-admin .table-responsive tbody td:nth-child(2) {
        font-variant-numeric: tabular-nums;
    }

    .dashboard-admin .table-responsive tbody td:last-child {
        font-variant-numeric: tabular-nums;
    }

    .dashboard-admin .table-responsive tbody tr:has(td[colspan]) td {
        padding: 26px 17px;
        color: #8391a5;
        font-size: 12px;
        text-align: center;
    }

    /* Label angka dalam tabel */

    .dashboard-admin .number-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 37px;
        min-height: 29px;
        padding: 5px 10px;
        color: #2457a7;
        background: #eaf1fc;
        border: 1px solid #dce8fa;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-admin .field-name {
        display: inline-flex;
        align-items: center;
        gap: 9px;
    }

    .dashboard-admin .field-dot {
        flex: 0 0 8px;
        width: 8px;
        height: 8px;
        background: #1696a3;
        border-radius: 3px;
        transform: rotate(45deg);
    }

    .dashboard-admin .history-name {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dashboard-admin .history-symbol {
        display: inline-flex;
        flex: 0 0 33px;
        align-items: center;
        justify-content: center;
        width: 33px;
        height: 33px;
        color: #087f8c;
        background: #e6f6f5;
        border-radius: 10px;
    }

    .dashboard-admin .history-symbol svg {
        width: 17px;
        height: 17px;
    }

    .dashboard-admin .date-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #526984;
        white-space: nowrap;
    }

    .dashboard-admin .date-label svg {
        width: 15px;
        height: 15px;
        color: #8496ac;
    }

    /* Catatan penjelas */

    .dashboard-admin .info-note {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-top: 16px;
        padding: 13px 15px;
        color: #526984;
        background: #f1f7fc;
        border: 1px solid #e0ebf5;
        border-left: 3px solid #4b8cc8;
        border-radius: 9px;
        font-size: 12px;
        line-height: 1.8;
    }

    .dashboard-admin .info-note svg {
        flex: 0 0 17px;
        width: 17px;
        height: 17px;
        margin-top: 2px;
        color: #357bb8;
    }

    .dashboard-admin .info-note strong {
        color: #294967;
    }

    /* Fokus aksesibilitas */

    .dashboard-admin :focus-visible {
        outline: 3px solid rgba(8, 127, 140, .55);
        outline-offset: 3px;
    }

    /* Tablet */

    @media (max-width: 1100px) {
        .dashboard-admin .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-admin .dashboard-heading {
            padding: 27px;
        }
    }

    /* Ponsel */

    @media (max-width: 640px) {
        .dashboard-admin {
            padding-bottom: 16px;
        }

        .dashboard-admin .dashboard-heading {
            align-items: flex-start;
            gap: 15px;
            padding: 23px 20px;
            margin-bottom: 22px;
            border-radius: 14px;
        }

        .dashboard-admin .dashboard-heading h1 {
            font-size: 25px;
        }

        .dashboard-admin .dashboard-heading p {
            font-size: 12px;
        }

        .dashboard-admin .heading-mark {
            flex-basis: 46px;
            width: 46px;
            height: 46px;
            border-radius: 13px;
        }

        .dashboard-admin .heading-mark svg {
            width: 25px;
            height: 25px;
        }

        .dashboard-admin .heading-eyebrow {
            font-size: 9px;
            letter-spacing: 1.3px;
        }

        .dashboard-admin .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
            margin-bottom: 24px;
        }

        .dashboard-admin .stat-card {
            padding: 16px 14px;
            border-radius: 12px;
        }

        .dashboard-admin .stat-topline {
            margin-bottom: 14px;
        }

        .dashboard-admin .stat-icon {
            flex-basis: 37px;
            width: 37px;
            height: 37px;
            border-radius: 11px;
        }

        .dashboard-admin .stat-icon svg {
            width: 19px;
            height: 19px;
        }

        .dashboard-admin .stat-index {
            font-size: 9px;
        }

        .dashboard-admin .stat-label {
            min-height: 39px;
            font-size: 11px;
        }

        .dashboard-admin .stat-value {
            font-size: 27px;
        }

        .dashboard-admin .stat-caption {
            align-items: flex-start;
            font-size: 10px;
        }

        .dashboard-admin .section-intro {
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .dashboard-admin .section-intro h2 {
            font-size: 17px;
        }

        .dashboard-admin .content-card {
            padding: 17px 14px;
            margin-bottom: 16px;
            border-radius: 13px;
        }

        .dashboard-admin .panel-heading {
            gap: 10px;
        }

        .dashboard-admin .panel-heading h2 {
            font-size: 15px;
        }

        .dashboard-admin .panel-heading p {
            font-size: 11px;
        }

        .dashboard-admin .panel-number {
            padding: 7px;
            font-size: 9px;
        }

        .dashboard-admin .table-responsive thead th {
            padding: 12px;
            font-size: 9px;
        }

        .dashboard-admin .table-responsive tbody td {
            padding: 13px 12px;
            font-size: 12px;
        }

        .dashboard-admin .info-note {
            padding: 12px;
            font-size: 11px;
        }
    }

    @media (max-width: 380px) {
        .dashboard-admin .stats-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-admin .stat-label {
            min-height: 0;
        }

        .dashboard-admin .dashboard-heading {
            padding: 19px 16px;
        }

        .dashboard-admin .heading-mark {
            display: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .dashboard-admin *,
        .dashboard-admin *::before,
        .dashboard-admin *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
        }
    }
</style>

<div class="dashboard-admin">

    {{-- HEADER DASHBOARD --}}
    <header class="dashboard-heading">
        <div class="heading-copy">
            <div class="heading-eyebrow">
                Sistem Informasi Pelatihan
            </div>

            <h1>Dashboard Admin SIRAPIK</h1>

            <p>
                Pusat informasi pengelolaan peserta, pelatihan, dan riwayat
                keikutsertaan. Pantau ringkasan data dengan lebih mudah
                dalam satu tampilan.
            </p>
        </div>

        <div class="heading-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.6"
                 stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="5" rx="1.5"/>
                <rect x="14" y="12" width="7" height="9" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            </svg>
        </div>
    </header>

    {{-- RINGKASAN DATA --}}
    <section aria-labelledby="summary-title">
        <div class="section-intro">
            <div>
                <span class="section-kicker">Ringkasan data</span>
                <h2 id="summary-title">Gambaran Umum SIRAPIK</h2>
                <p>Angka utama untuk membantu memahami kondisi data saat ini.</p>
            </div>
        </div>

        <div class="stats-grid">

            {{-- TOTAL PESERTA --}}
            <div class="stat-card"
                 style="--stat-accent:#2457a7;--stat-soft:#eaf1fc;">
                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span class="stat-index">01 / DATA</span>
                </div>

                <div class="stat-label">Total Peserta</div>
                <div class="stat-value">
                    {{ number_format($totalParticipants) }}
                </div>
                <div class="stat-caption">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v4l2.5 2"/>
                    </svg>
                    Jumlah peserta yang tercatat
                </div>
            </div>

            {{-- TOTAL PELATIHAN --}}
            <div class="stat-card"
                 style="--stat-accent:#087f8c;--stat-soft:#e5f6f4;">
                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                            <path d="M8 7h8M8 11h7"/>
                        </svg>
                    </div>
                    <span class="stat-index">02 / PROGRAM</span>
                </div>

                <div class="stat-label">Total Pelatihan</div>
                <div class="stat-value">
                    {{ number_format($totalTrainings) }}
                </div>
                <div class="stat-caption">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15Z"/>
                    </svg>
                    Jumlah program pelatihan
                </div>
            </div>

            {{-- TOTAL RIWAYAT --}}
            <div class="stat-card"
                 style="--stat-accent:#8b65bd;--stat-soft:#f1eafb;">
                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 2h8l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"/>
                            <path d="M16 2v5h5"/>
                            <path d="M8 12h8M8 16h8M8 8h3"/>
                        </svg>
                    </div>
                    <span class="stat-index">03 / RIWAYAT</span>
                </div>

                <div class="stat-label">Total Riwayat Keikutsertaan</div>
                <div class="stat-value">
                    {{ number_format($totalParticipations) }}
                </div>
                <div class="stat-caption">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2h8l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"/>
                        <path d="M8 12h8M8 16h5"/>
                    </svg>
                    Akumulasi keikutsertaan tercatat
                </div>
            </div>

            {{-- KEIKUTSERTAAN TAHUN BERJALAN --}}
            <div class="stat-card"
                 style="--stat-accent:#bd8530;--stat-soft:#fbf2e2;">
                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="16" rx="2"/>
                            <path d="M16 3v4M8 3v4M3 10h18"/>
                            <path d="m9 15 2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="stat-index">04 / TAHUN</span>
                </div>

                <div class="stat-label">
                    Keikutsertaan Tahun {{ $year }}
                </div>
                <div class="stat-value">
                    {{ number_format($participationsThisYear) }}
                </div>
                <div class="stat-caption">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path d="M16 3v4M8 3v4M3 10h18"/>
                    </svg>
                    Rekap keikutsertaan tahun {{ $year }}
                </div>
            </div>

        </div>
    </section>

    {{-- PESERTA DENGAN FREKUENSI PELATIHAN TERTINGGI --}}
    <section class="content-card" aria-labelledby="frequent-title">
        <div class="panel-heading">
            <div>
                <span class="section-kicker">Pemantauan peserta</span>
                <h2 id="frequent-title">
                    Peserta dengan Frekuensi Pelatihan Tertinggi
                </h2>
                <p>
                    Daftar peserta dengan jumlah keikutsertaan terbanyak
                    pada tahun {{ $year }}. Gunakan informasi ini sebagai
                    bahan peninjauan distribusi pelatihan.
                </p>
            </div>

            <div class="panel-number">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"/>
                    <path d="m18 9-5 5-4-4-4 4"/>
                </svg>
                Frekuensi
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>NIP</th>
                        <th>Jumlah Pelatihan {{ $year }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($frequentParticipants as $participant)
                        <tr>
                            <td>{{ $participant->name }}</td>
                            <td>{{ $participant->nip }}</td>
                            <td>
                                <span class="number-pill">
                                    {{ $participant->participations_count }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Belum ada data peserta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="info-note">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 11v5M12 8h.01"/>
            </svg>
            <div>
                <strong>Catatan:</strong>
                Frekuensi pelatihan yang tinggi tidak otomatis berarti
                seorang peserta terbebani. Data ini membantu staf meninjau
                distribusi kesempatan pelatihan secara lebih menyeluruh.
            </div>
        </div>
    </section>

    {{-- STATISTIK BIDANG PELATIHAN --}}
    <section class="content-card" aria-labelledby="field-title">
        <div class="panel-heading">
            <div>
                <span class="section-kicker">Komposisi program</span>
                <h2 id="field-title">Bidang Pelatihan</h2>
                <p>
                    Ringkasan jumlah program berdasarkan bidang pelatihan
                    yang tercatat di dalam sistem.
                </p>
            </div>

            <div class="panel-number">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                Bidang
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Bidang Pelatihan</th>
                        <th>Jumlah Pelatihan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($fieldStats as $field)
                        <tr>
                            <td>
                                <span class="field-name">
                                    <span class="field-dot"
                                          aria-hidden="true"></span>
                                    {{ $field->field }}
                                </span>
                            </td>
                            <td>
                                <span class="number-pill">
                                    {{ $field->total }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">
                                Belum ada data bidang pelatihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- RIWAYAT PELATIHAN TERBARU --}}
    <section class="content-card" aria-labelledby="history-title">
        <div class="panel-heading">
            <div>
                <span class="section-kicker">Aktivitas terbaru</span>
                <h2 id="history-title">Riwayat Pelatihan Terbaru</h2>
                <p>
                    Informasi peserta, nama pelatihan, dan tanggal mulai
                    dari riwayat keikutsertaan terbaru yang tersedia.
                </p>
            </div>

            <div class="panel-number">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3 2"/>
                </svg>
                Riwayat
            </div>
        </div>

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
                            <td>
                                <div class="history-name">
                                    <span class="history-symbol"
                                          aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.8"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <circle cx="12" cy="8" r="4"/>
                                            <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                                        </svg>
                                    </span>

                                    <span>
                                        {{ $history->participant?->name ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <td>{{ $history->training?->name ?? '-' }}</td>

                            <td>
                                <span class="date-label">
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.8"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                                        <path d="M16 3v4M8 3v4M3 10h18"/>
                                    </svg>

                                    {{ $history->training?->start_date?->format('d-m-Y') ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                Belum ada riwayat pelatihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection