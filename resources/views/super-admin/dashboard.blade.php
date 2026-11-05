@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')

<style>
    /* =====================================================
       SIRAPIK — SUPER ADMIN DASHBOARD
       Premium Blue Interface
       ===================================================== */

    .sa-dashboard {
        --sa-navy: #142d4e;
        --sa-blue: #2463a8;
        --sa-blue-dark: #194d86;
        --sa-sky: #eaf3fc;
        --sa-teal: #168b91;
        --sa-green: #21836b;
        --sa-purple: #7861a8;
        --sa-red: #c45d68;
        --sa-text: #233b58;
        --sa-muted: #718198;
        --sa-border: #e3eaf2;
        --sa-surface: #ffffff;
        --sa-background: #f5f8fc;

        width: 100%;
        color: var(--sa-text);
        font-family: inherit;
        padding-bottom: 35px;
    }

    .sa-dashboard *,
    .sa-dashboard *::before,
    .sa-dashboard *::after {
        box-sizing: border-box;
    }

    .sa-dashboard a {
        -webkit-tap-highlight-color: transparent;
    }

    /* =========================
       HEADER
       ========================= */

    .sa-header {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 28px;
        min-height: 235px;
        padding: 38px 42px;
        margin-bottom: 36px;
        overflow: hidden;
        isolation: isolate;
        color: white;
        background: var(--sa-navy);
        border-radius: 22px;
        box-shadow: 0 16px 38px rgba(20, 45, 78, .13);
    }

    .sa-header::before {
        content: "";
        position: absolute;
        z-index: -1;
        top: -170px;
        right: 8%;
        width: 420px;
        height: 420px;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 50%;
        box-shadow:
            0 0 0 35px rgba(255,255,255,.035),
            0 0 0 75px rgba(255,255,255,.025);
    }

    .sa-header::after {
        content: "";
        position: absolute;
        z-index: -1;
        right: 0;
        bottom: 0;
        width: 44%;
        height: 100%;
        background: linear-gradient(
            135deg,
            transparent,
            rgba(36, 99, 168, .30)
        );
        pointer-events: none;
    }

    .sa-header-content {
        max-width: 680px;
    }

    .sa-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: #91d9d9;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .sa-eyebrow::before {
        content: "";
        width: 28px;
        height: 3px;
        background: #57c5c2;
        border-radius: 10px;
    }

    .sa-header h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(32px, 4vw, 43px);
        font-weight: 850;
        line-height: 1.15;
        letter-spacing: -1.4px;
    }

    .sa-header p {
        max-width: 570px;
        margin: 17px 0 0;
        color: rgba(255,255,255,.78);
        font-size: 16px;
        line-height: 1.85;
    }

    .sa-header-actions {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-shrink: 0;
    }

    .sa-header-symbol {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        color: #a9eeee;
        background: rgba(255,255,255,.09);
        border: 1px solid rgba(255,255,255,.19);
        border-radius: 19px;
    }

    .sa-header-symbol svg {
        width: 32px;
        height: 32px;
    }

    /* =========================
       BUTTONS
       ========================= */

    .sa-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 49px;
        padding: 12px 19px;
        border: 1px solid transparent;
        border-radius: 12px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 800;
        line-height: 1.4;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition:
            transform .22s ease,
            background .22s ease,
            color .22s ease,
            box-shadow .22s ease,
            border-color .22s ease;
    }

    .sa-btn svg {
        width: 19px;
        height: 19px;
        flex-shrink: 0;
    }

    .sa-btn:hover {
        transform: translateY(-3px);
    }

    .sa-btn:focus-visible {
        outline: 3px solid rgba(22,139,145,.45);
        outline-offset: 4px;
    }

    .sa-btn-primary {
        color: #173b5c;
        background: #d9f5f0;
        border-color: rgba(255,255,255,.35);
        box-shadow: 0 6px 18px rgba(0,0,0,.10);
    }

    .sa-btn-primary:hover {
        color: #123b47;
        background: #fff;
        box-shadow: 0 10px 22px rgba(0,0,0,.15);
    }

    .sa-btn-outline {
        color: var(--sa-blue);
        background: white;
        border-color: #d9e5f1;
    }

    .sa-btn-outline:hover {
        color: var(--sa-blue-dark);
        background: #f0f6fd;
        border-color: #b8cee4;
        box-shadow: 0 7px 18px rgba(36,99,168,.10);
    }

    /* =========================
       SECTION HEADING
       ========================= */

    .sa-section {
        margin-bottom: 36px;
    }

    .sa-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 19px;
    }

    .sa-section-kicker {
        display: block;
        margin-bottom: 8px;
        color: var(--sa-teal);
        font-size: 12px;
        font-weight: 850;
        letter-spacing: 1.7px;
        text-transform: uppercase;
    }

    .sa-section-heading h2 {
        margin: 0;
        color: var(--sa-navy);
        font-size: 25px;
        font-weight: 850;
        line-height: 1.3;
        letter-spacing: -.65px;
    }

    .sa-section-heading p {
        margin: 8px 0 0;
        color: var(--sa-muted);
        font-size: 14px;
        line-height: 1.8;
    }

    .sa-section-number {
        color: #a8b8c9;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 1px;
        white-space: nowrap;
    }

    /* =========================
       STATISTICS
       ========================= */

    .sa-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 17px;
    }

    .sa-stat-card {
        --stat-color: var(--sa-blue);
        --stat-soft: #eaf2fc;

        position: relative;
        min-width: 0;
        min-height: 200px;
        padding: 23px;
        overflow: hidden;
        background: var(--sa-surface);
        border: 1px solid var(--sa-border);
        border-radius: 17px;
        box-shadow: 0 5px 18px rgba(20,45,78,.035);
        transition:
            transform .24s ease,
            border-color .24s ease,
            box-shadow .24s ease;
    }

    .sa-stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--stat-color);
    }

    .sa-stat-card::after {
        content: "";
        position: absolute;
        right: -38px;
        bottom: -65px;
        width: 130px;
        height: 130px;
        background: var(--stat-soft);
        border-radius: 50%;
        opacity: .65;
        pointer-events: none;
    }

    .sa-stat-card:hover {
        transform: translateY(-5px);
        border-color: #c7d9eb;
        box-shadow: 0 14px 28px rgba(20,45,78,.085);
    }

    .sa-stat-top {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 22px;
    }

    .sa-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 49px;
        height: 49px;
        color: var(--stat-color);
        background: var(--stat-soft);
        border-radius: 15px;
    }

    .sa-stat-icon svg {
        width: 24px;
        height: 24px;
    }

    .sa-stat-index {
        color: #a1afc0;
        font-size: 11px;
        font-weight: 850;
        letter-spacing: 1px;
    }

    .sa-stat-label {
        position: relative;
        z-index: 1;
        color: #667991;
        font-size: 14px;
        font-weight: 750;
        line-height: 1.6;
    }

    .sa-stat-number {
        position: relative;
        z-index: 1;
        margin-top: 7px;
        color: var(--sa-navy);
        font-size: clamp(34px, 3vw, 43px);
        font-weight: 900;
        line-height: 1.2;
        letter-spacing: -1.5px;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
    }

    .sa-stat-note {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
        padding-top: 13px;
        color: #8795a7;
        border-top: 1px solid #edf1f6;
        font-size: 12px;
        line-height: 1.6;
    }

    .sa-stat-note::before {
        content: "";
        width: 7px;
        height: 7px;
        flex-shrink: 0;
        background: var(--stat-color);
        border-radius: 50%;
    }

    /* =========================
       MANAGEMENT CARDS
       ========================= */

    .sa-management-grid {
        display: grid;
        grid-template-columns: 1fr 1.15fr;
        gap: 19px;
    }

    .sa-management-card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        min-height: 310px;
        padding: 27px;
        overflow: hidden;
        background: white;
        border: 1px solid var(--sa-border);
        border-radius: 18px;
        box-shadow: 0 5px 18px rgba(20,45,78,.035);
        transition:
            transform .24s ease,
            box-shadow .24s ease,
            border-color .24s ease;
    }

    .sa-management-card:hover {
        border-color: #cbdbea;
        box-shadow: 0 13px 27px rgba(20,45,78,.07);
        transform: translateY(-3px);
    }

    .sa-management-card.master {
        background: linear-gradient(145deg,#fff 0%,#f1f8fd 100%);
    }

    .sa-card-top {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 21px;
    }

    .sa-card-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 53px;
        height: 53px;
        flex-shrink: 0;
        color: var(--sa-blue);
        background: var(--sa-sky);
        border-radius: 16px;
    }

    .sa-card-icon.teal {
        color: var(--sa-teal);
        background: #e4f5f3;
    }

    .sa-card-icon svg {
        width: 26px;
        height: 26px;
    }

    .sa-card-kicker {
        display: block;
        margin-bottom: 4px;
        color: var(--sa-teal);
        font-size: 11px;
        font-weight: 850;
        letter-spacing: 1.3px;
        text-transform: uppercase;
    }

    .sa-management-card h3 {
        margin: 0;
        color: var(--sa-navy);
        font-size: 21px;
        font-weight: 850;
        line-height: 1.4;
        letter-spacing: -.4px;
    }

    .sa-card-description {
        margin: 0;
        color: #687b91;
        font-size: 15px;
        line-height: 1.9;
    }

    .sa-card-divider {
        height: 1px;
        margin: 21px 0;
        background: #eaf0f5;
        border: 0;
    }

    .sa-card-detail {
        margin: 0;
        color: #78899d;
        font-size: 14px;
        line-height: 1.9;
    }

    .sa-access-note {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-top: auto;
        padding: 15px;
        color: #4f6884;
        background: #f1f6fb;
        border: 1px solid #e2ebf4;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.8;
    }

    .sa-access-note svg {
        width: 19px;
        height: 19px;
        flex-shrink: 0;
        margin-top: 1px;
        color: var(--sa-blue);
    }

    .sa-card-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 11px;
        margin-top: auto;
        padding-top: 24px;
    }

    .sa-master-link {
        min-height: 50px;
        padding: 13px 18px;
    }

    .sa-master-link .sa-arrow {
        transition: transform .2s ease;
    }

    .sa-master-link:hover .sa-arrow {
        transform: translateX(4px);
    }

    /* =========================
       RESPONSIVE TABLET
       ========================= */

    @media (max-width: 1100px) {
        .sa-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .sa-management-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .sa-header {
            padding: 32px;
        }
    }

    /* =========================
       RESPONSIVE MOBILE
       ========================= */

    @media (max-width: 680px) {
        .sa-dashboard {
            padding-bottom: 20px;
        }

        .sa-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 23px;
            min-height: auto;
            padding: 27px 22px;
            margin-bottom: 29px;
            border-radius: 17px;
        }

        .sa-header::before {
            right: -190px;
            top: -150px;
        }

        .sa-header h1 {
            font-size: 30px;
            letter-spacing: -.8px;
        }

        .sa-header p {
            margin-top: 13px;
            font-size: 14px;
        }

        .sa-eyebrow {
            margin-bottom: 13px;
            font-size: 10px;
            letter-spacing: 1.3px;
        }

        .sa-header-actions {
            width: 100%;
        }

        .sa-header-actions .sa-btn {
            width: 100%;
        }

        .sa-header-symbol {
            display: none;
        }

        .sa-section {
            margin-bottom: 30px;
        }

        .sa-section-heading {
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .sa-section-heading h2 {
            font-size: 22px;
        }

        .sa-section-heading p {
            font-size: 13px;
        }

        .sa-section-number {
            display: none;
        }

        .sa-stats-grid {
            gap: 12px;
        }

        .sa-stat-card {
            min-height: 180px;
            padding: 17px 15px;
            border-radius: 14px;
        }

        .sa-stat-top {
            margin-bottom: 17px;
        }

        .sa-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 13px;
        }

        .sa-stat-icon svg {
            width: 21px;
            height: 21px;
        }

        .sa-stat-index {
            font-size: 9px;
        }

        .sa-stat-label {
            font-size: 12px;
        }

        .sa-stat-number {
            font-size: 32px;
        }

        .sa-stat-note {
            align-items: flex-start;
            font-size: 11px;
        }

        .sa-management-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .sa-management-card {
            min-height: 275px;
            padding: 21px 18px;
            border-radius: 15px;
        }

        .sa-card-icon {
            width: 47px;
            height: 47px;
        }

        .sa-management-card h3 {
            font-size: 19px;
        }

        .sa-card-description,
        .sa-card-detail {
            font-size: 13px;
        }

        .sa-card-actions .sa-btn {
            width: 100%;
        }
    }

    @media (max-width: 390px) {
        .sa-stats-grid {
            grid-template-columns: 1fr;
        }

        .sa-stat-card {
            min-height: 160px;
        }

        .sa-stat-label {
            font-size: 14px;
        }

        .sa-stat-number {
            font-size: 36px;
        }
    }

    /* =========================
       ACCESSIBILITY
       ========================= */

    @media (prefers-reduced-motion: reduce) {
        .sa-dashboard *,
        .sa-dashboard *::before,
        .sa-dashboard *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="sa-dashboard">

    {{-- =========================
         HEADER
         ========================= --}}

    <header class="sa-header">
        <div class="sa-header-content">
            <span class="sa-eyebrow">
                PANEL PENGELOLAAN SISTEM
            </span>

            <h1>Dashboard Super Admin</h1>

            <p>
                Pusat kendali akun pengguna dan konfigurasi data master.
                Kelola akses serta referensi sistem SIRAPIK dalam satu
                ruang pengelolaan yang terintegrasi.
            </p>
        </div>

        <div class="sa-header-actions">
            <div class="sa-header-symbol" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.6"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3 20 7v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V7l8-4Z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>

            <a href="{{ route('super-admin.users.create') }}"
               class="sa-btn sa-btn-primary">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Tambah Akun
            </a>
        </div>
    </header>


    {{-- =========================
         ACCOUNT STATISTICS
         ========================= --}}

    <section class="sa-section"
             aria-labelledby="sa-account-title">

        <div class="sa-section-heading">
            <div>
                <span class="sa-section-kicker">
                    IKHTISAR PENGGUNA
                </span>

                <h2 id="sa-account-title">
                    Ringkasan Akun Sistem
                </h2>

                <p>
                    Gambaran jumlah akun berdasarkan peran
                    dan status pengguna.
                </p>
            </div>

            <span class="sa-section-number">
                OVERVIEW / 01
            </span>
        </div>

        <div class="sa-stats-grid">

            {{-- TOTAL ADMIN --}}

            <article class="sa-stat-card"
                     style="--stat-color:#2463a8;--stat-soft:#eaf2fc;">

                <div class="sa-stat-top">
                    <div class="sa-stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>

                    <span class="sa-stat-index">01 / ADMIN</span>
                </div>

                <div class="sa-stat-label">
                    Total Akun Admin
                </div>

                <div class="sa-stat-number">
                    {{ number_format($totalAdmin, 0, ',', '.') }}
                </div>

                <div class="sa-stat-note">
                    Pengelola data operasional.
                </div>
            </article>


            {{-- TOTAL PIMPINAN --}}

            <article class="sa-stat-card"
                     style="--stat-color:#7861a8;--stat-soft:#f0ebf8;">

                <div class="sa-stat-top">
                    <div class="sa-stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                            <path d="M8 13.5 12 16l4-2.5"/>
                        </svg>
                    </div>

                    <span class="sa-stat-index">02 / PIMPINAN</span>
                </div>

                <div class="sa-stat-label">
                    Total Akun Pimpinan
                </div>

                <div class="sa-stat-number">
                    {{ number_format($totalPimpinan, 0, ',', '.') }}
                </div>

                <div class="sa-stat-note">
                    Akun untuk pemantauan sistem.
                </div>
            </article>


            {{-- AKUN AKTIF --}}

            <article class="sa-stat-card"
                     style="--stat-color:#21836b;--stat-soft:#e6f5ef;">

                <div class="sa-stat-top">
                    <div class="sa-stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m8 12 2.5 2.5L16 9"/>
                        </svg>
                    </div>

                    <span class="sa-stat-index">03 / STATUS</span>
                </div>

                <div class="sa-stat-label">
                    Akun Aktif
                </div>

                <div class="sa-stat-number">
                    {{ number_format($totalAktif, 0, ',', '.') }}
                </div>

                <div class="sa-stat-note">
                    Akun Admin dan Pimpinan aktif.
                </div>
            </article>


            {{-- AKUN NONAKTIF --}}

            <article class="sa-stat-card"
                     style="--stat-color:#c45d68;--stat-soft:#fbecef;">

                <div class="sa-stat-top">
                    <div class="sa-stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m9 9 6 6M15 9l-6 6"/>
                        </svg>
                    </div>

                    <span class="sa-stat-index">04 / STATUS</span>
                </div>

                <div class="sa-stat-label">
                    Akun Nonaktif
                </div>

                <div class="sa-stat-number">
                    {{ number_format($totalNonaktif, 0, ',', '.') }}
                </div>

                <div class="sa-stat-note">
                    Akun Admin dan Pimpinan nonaktif.
                </div>
            </article>

        </div>
    </section>


    {{-- =========================
         MANAGEMENT CENTER
         ========================= --}}

    <section class="sa-section"
             aria-labelledby="sa-management-title">

        <div class="sa-section-heading">
            <div>
                <span class="sa-section-kicker">
                    PENGELOLAAN SISTEM
                </span>

                <h2 id="sa-management-title">
                    Pusat Pengelolaan
                </h2>

                <p>
                    Akses pengaturan utama berdasarkan kewenangan
                    Super Admin.
                </p>
            </div>

            <span class="sa-section-number">
                MANAGEMENT / 02
            </span>
        </div>


        <div class="sa-management-grid">

            {{-- MANAJEMEN PENGGUNA --}}

            <article class="sa-management-card">

                <div class="sa-card-top">
                    <div class="sa-card-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                            <path d="M18 8h4M20 6v4"/>
                        </svg>
                    </div>

                    <div>
                        <span class="sa-card-kicker">
                            AKSES PENGGUNA
                        </span>

                        <h3>Manajemen Pengguna</h3>
                    </div>
                </div>

                <p class="sa-card-description">
                    Kelola akun Admin dan Pimpinan, mulai dari
                    penambahan akun hingga pengaturan akses pengguna.
                </p>

                <hr class="sa-card-divider">

                <p class="sa-card-detail">
                    Gunakan menu Kelola Akun pada sidebar untuk
                    melihat dan mengelola akun yang terdaftar
                    di dalam sistem.
                </p>

                <div class="sa-access-note">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 11v5M12 8h.01"/>
                    </svg>

                    <span>
                        Pastikan setiap pengguna memiliki akses
                        yang sesuai dengan perannya.
                    </span>
                </div>
            </article>


            {{-- DATA MASTER --}}

            <article class="sa-management-card master">

                <div class="sa-card-top">
                    <div class="sa-card-icon teal" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 3 8l9 5 9-5-9-5Z"/>
                            <path d="m3 12 9 5 9-5"/>
                            <path d="m3 16 9 5 9-5"/>
                        </svg>
                    </div>

                    <div>
                        <span class="sa-card-kicker">
                            KONFIGURASI REFERENSI
                        </span>

                        <h3>Data Master</h3>
                    </div>
                </div>

                <p class="sa-card-description">
                    Kelola data referensi yang digunakan sebagai
                    pilihan dan acuan pada berbagai fitur SIRAPIK.
                </p>

                <hr class="sa-card-divider">

                <p class="sa-card-detail">
                    Pengelolaan mencakup provinsi, kabupaten/kota,
                    mitra, sumber dana, kategori pelatihan,
                    tahun pelatihan, dan jenis tenaga kesehatan.
                </p>

                <div class="sa-card-actions">
                    <a href="{{ route('super-admin.master-data.index', [
                        'category' => 'provinces'
                    ]) }}"
                       class="sa-btn sa-btn-outline sa-master-link">

                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M4 5h16M4 12h16M4 19h16"/>
                            <circle cx="8" cy="5" r="1.5"
                                    fill="currentColor" stroke="none"/>
                            <circle cx="15" cy="12" r="1.5"
                                    fill="currentColor" stroke="none"/>
                            <circle cx="10" cy="19" r="1.5"
                                    fill="currentColor" stroke="none"/>
                        </svg>

                        Buka Data Master

                        <svg class="sa-arrow"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </article>

        </div>
    </section>

</div>

@endsection