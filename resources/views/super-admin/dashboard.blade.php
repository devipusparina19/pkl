@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')

<style>
    /* ==========================================
       SIRAPIK — SUPER ADMIN CONTROL PANEL
       Selaras dengan Dashboard Admin & Pimpinan
       ========================================== */

    .super-dashboard {
        --sd-navy: #172b4d;
        --sd-blue: #2457a7;
        --sd-teal: #087f8c;
        --sd-purple: #8061ad;
        --sd-green: #23856d;
        --sd-red: #c65c62;
        --sd-text: #263b59;
        --sd-muted: #718097;
        --sd-border: #e3eaf2;
        --sd-white: #ffffff;

        color: var(--sd-text);
        font-family: inherit;
        padding-bottom: 28px;
    }

    .super-dashboard * {
        box-sizing: border-box;
    }

    /* Header utama */

    .super-dashboard .page-heading {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        overflow: hidden;
        padding: 31px 32px;
        margin-bottom: 25px;
        color: #fff;
        background:
            radial-gradient(
                circle at 92% 10%,
                rgba(105, 220, 208, .19),
                transparent 28%
            ),
            linear-gradient(
                118deg,
                #172b4d 0%,
                #204a7e 66%,
                #176b79 100%
            );
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(23, 43, 77, .12);
    }

    .super-dashboard .page-heading::after {
        content: "";
        position: absolute;
        right: -38px;
        bottom: -115px;
        width: 250px;
        height: 250px;
        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 50%;
        box-shadow:
            0 0 0 26px rgba(255, 255, 255, .035),
            0 0 0 54px rgba(255, 255, 255, .025);
        pointer-events: none;
    }

    .super-dashboard .heading-copy {
        position: relative;
        z-index: 1;
        max-width: 650px;
    }

    .super-dashboard .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 12px;
        color: #bce9e8;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .super-dashboard .eyebrow::before {
        content: "";
        width: 23px;
        height: 2px;
        background: #67d5cd;
        border-radius: 10px;
    }

    .super-dashboard .page-heading h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(26px, 3vw, 35px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.7px;
    }

    .super-dashboard .page-heading p {
        max-width: 540px;
        margin: 12px 0 0;
        color: rgba(255, 255, 255, .80);
        font-size: 13px;
        line-height: 1.9;
    }

    .super-dashboard .heading-actions {
        position: relative;
        z-index: 2;
        display: flex;
        flex: 0 0 auto;
        align-items: center;
        gap: 12px;
    }

    .super-dashboard .heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 53px;
        height: 53px;
        color: #d4fbf5;
        background: rgba(255, 255, 255, .10);
        border: 1px solid rgba(255, 255, 255, .19);
        border-radius: 15px;
    }

    .super-dashboard .heading-icon svg {
        width: 27px;
        height: 27px;
    }

    /* Tombol tambah akun */

    .super-dashboard .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 43px;
        padding: 11px 17px;
        border: 1px solid transparent;
        border-radius: 10px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.4;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        transition:
            background .2s ease,
            border-color .2s ease,
            box-shadow .2s ease,
            transform .2s ease;
    }

    .super-dashboard .btn svg {
        flex: 0 0 17px;
        width: 17px;
        height: 17px;
    }

    .super-dashboard .btn-primary {
        color: #173653;
        background: #d7f5ef;
        border-color: rgba(255, 255, 255, .4);
        box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
    }

    .super-dashboard .btn-primary:hover {
        color: #123c45;
        background: #fff;
        border-color: #fff;
        box-shadow: 0 7px 17px rgba(0, 0, 0, .12);
        transform: translateY(-2px);
    }

    .super-dashboard .btn-light {
        color: #2457a7;
        background: #fff;
        border-color: #d8e4f1;
    }

    .super-dashboard .btn-light:hover {
        color: #173f80;
        background: #f0f6fd;
        border-color: #b8cde5;
        box-shadow: 0 5px 13px rgba(36, 87, 167, .08);
        transform: translateY(-2px);
    }

    .super-dashboard .btn:focus-visible {
        outline: 3px solid rgba(8, 127, 140, .5);
        outline-offset: 3px;
    }

    /* Judul bagian */

    .super-dashboard .section-intro {
        margin-bottom: 15px;
    }

    .super-dashboard .section-kicker {
        display: block;
        margin-bottom: 5px;
        color: var(--sd-teal);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.7px;
        text-transform: uppercase;
    }

    .super-dashboard .section-intro h2 {
        margin: 0;
        color: var(--sd-navy);
        font-size: 19px;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .super-dashboard .section-intro p {
        margin: 6px 0 0;
        color: var(--sd-muted);
        font-size: 12px;
        line-height: 1.8;
    }

    /* Kartu statistik */

    .super-dashboard .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 29px;
    }

    .super-dashboard .stat-card {
        position: relative;
        min-width: 0;
        overflow: hidden;
        padding: 21px 20px 18px;
        background: var(--sd-white);
        border: 1px solid var(--sd-border);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, .035);
        transition:
            transform .22s ease,
            box-shadow .22s ease,
            border-color .22s ease;
    }

    .super-dashboard .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--stat-accent, var(--sd-blue));
    }

    .super-dashboard .stat-card:hover {
        transform: translateY(-4px);
        border-color: #c8d8e8;
        box-shadow: 0 12px 24px rgba(23, 43, 77, .08);
    }

    .super-dashboard .stat-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 18px;
    }

    .super-dashboard .stat-icon {
        display: flex;
        flex: 0 0 43px;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        color: var(--stat-accent, var(--sd-blue));
        background: var(--stat-soft, #eaf1fc);
        border-radius: 13px;
    }

    .super-dashboard .stat-icon svg {
        width: 22px;
        height: 22px;
    }

    .super-dashboard .stat-index {
        color: #a0adbe;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .8px;
    }

    .super-dashboard .stat-label {
        min-height: 38px;
        color: var(--sd-muted);
        font-size: 12px;
        font-weight: 700;
        line-height: 1.7;
    }

    .super-dashboard .stat-number {
        margin-top: 5px;
        color: var(--sd-navy);
        font-size: clamp(27px, 2.5vw, 35px);
        font-weight: 850;
        line-height: 1.25;
        letter-spacing: -1px;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
    }

    .super-dashboard .stat-note {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        margin-top: 13px;
        padding-top: 12px;
        color: #8290a4;
        border-top: 1px solid #edf1f6;
        font-size: 10px;
        line-height: 1.7;
    }

    .super-dashboard .stat-note::before {
        content: "";
        flex: 0 0 6px;
        width: 6px;
        height: 6px;
        margin-top: 5px;
        background: var(--stat-accent, var(--sd-blue));
        border-radius: 50%;
    }

    /* Panel informasi */

    .super-dashboard .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        align-items: stretch;
    }

    .super-dashboard .content-card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        padding: 25px;
        background: #fff;
        border: 1px solid var(--sd-border);
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(23, 43, 77, .035);
        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .super-dashboard .content-card:hover {
        border-color: #d2dfec;
        box-shadow: 0 9px 22px rgba(23, 43, 77, .055);
    }

    .super-dashboard .card-topline {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-bottom: 18px;
    }

    .super-dashboard .card-icon {
        display: flex;
        flex: 0 0 45px;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        color: var(--card-accent, var(--sd-blue));
        background: var(--card-soft, #eaf1fc);
        border-radius: 13px;
    }

    .super-dashboard .card-icon svg {
        width: 22px;
        height: 22px;
    }

    .super-dashboard .card-title-wrap {
        min-width: 0;
    }

    .super-dashboard .card-title-wrap .section-kicker {
        margin-bottom: 4px;
    }

    .super-dashboard .content-card h2 {
        margin: 0;
        color: var(--sd-navy);
        font-size: 17px;
        font-weight: 800;
        line-height: 1.5;
        letter-spacing: -.25px;
    }

    .super-dashboard .content-card p {
        margin: 0 0 12px;
        color: var(--sd-muted);
        font-size: 12px;
        line-height: 1.9;
    }

    .super-dashboard .content-card p:last-of-type {
        margin-bottom: 0;
    }

    .super-dashboard .content-card strong {
        color: #405675;
        font-weight: 800;
    }

    .super-dashboard .card-divider {
        height: 1px;
        margin: 17px 0;
        background: #edf1f6;
        border: 0;
    }

    /* Keterangan akses */

    .super-dashboard .access-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: auto;
        padding: 12px;
        color: #526984;
        background: #f3f7fc;
        border: 1px solid #e4edf6;
        border-radius: 10px;
        font-size: 11px;
        line-height: 1.8;
    }

    .super-dashboard .access-note svg {
        flex: 0 0 16px;
        width: 16px;
        height: 16px;
        margin-top: 2px;
        color: #357bb8;
    }

    .super-dashboard .inline-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: auto;
        padding-top: 22px;
    }

    .super-dashboard .master-card {
        background:
            linear-gradient(135deg, #fff 0%, #f6fbfd 100%);
    }

    .super-dashboard .master-card .btn-light {
        min-height: 45px;
    }

    .super-dashboard .master-card .btn-light svg {
        margin-left: 2px;
        transition: transform .2s ease;
    }

    .super-dashboard .master-card .btn-light:hover svg {
        transform: translateX(3px);
    }

    /* Tablet */

    @media (max-width: 1100px) {
        .super-dashboard .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .super-dashboard .dashboard-grid {
            gap: 16px;
        }
    }

    /* Ponsel */

    @media (max-width: 640px) {
        .super-dashboard {
            padding-bottom: 16px;
        }

        .super-dashboard .page-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 20px;
            padding: 23px 20px;
            margin-bottom: 22px;
            border-radius: 14px;
        }

        .super-dashboard .page-heading h1 {
            font-size: 25px;
        }

        .super-dashboard .page-heading p {
            font-size: 12px;
        }

        .super-dashboard .eyebrow {
            font-size: 9px;
            letter-spacing: 1.3px;
        }

        .super-dashboard .heading-actions {
            width: 100%;
        }

        .super-dashboard .heading-actions .btn {
            width: 100%;
        }

        .super-dashboard .heading-icon {
            display: none;
        }

        .super-dashboard .stats-grid {
            gap: 11px;
            margin-bottom: 23px;
        }

        .super-dashboard .stat-card {
            padding: 16px 14px;
            border-radius: 12px;
        }

        .super-dashboard .stat-topline {
            margin-bottom: 14px;
        }

        .super-dashboard .stat-icon {
            flex-basis: 37px;
            width: 37px;
            height: 37px;
            border-radius: 11px;
        }

        .super-dashboard .stat-icon svg {
            width: 19px;
            height: 19px;
        }

        .super-dashboard .stat-index {
            font-size: 9px;
        }

        .super-dashboard .stat-label {
            min-height: 39px;
            font-size: 11px;
        }

        .super-dashboard .stat-number {
            font-size: 27px;
        }

        .super-dashboard .stat-note {
            font-size: 10px;
        }

        .super-dashboard .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .super-dashboard .content-card {
            padding: 19px 16px;
            border-radius: 13px;
        }

        .super-dashboard .content-card h2 {
            font-size: 16px;
        }

        .super-dashboard .content-card p {
            font-size: 11px;
        }

        .super-dashboard .inline-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 380px) {
        .super-dashboard .stats-grid {
            grid-template-columns: 1fr;
        }

        .super-dashboard .stat-label {
            min-height: 0;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .super-dashboard *,
        .super-dashboard *::before,
        .super-dashboard *::after {
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
        }
    }
</style>

<div class="super-dashboard">

    {{-- HEADER SUPER ADMIN --}}
    <header class="page-heading">
        <div class="heading-copy">
            <span class="eyebrow">PANEL PENGELOLAAN SISTEM</span>

            <h1>Dashboard Super Admin</h1>

            <p>
                Pusat kontrol akun pengguna dan data master.
                Pantau status akun serta akses pengelolaan konfigurasi
                sistem SIRAPIK dari satu halaman.
            </p>
        </div>

        <div class="heading-actions">
            <div class="heading-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.6"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3 20 7v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V7l8-4Z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>

            <a href="{{ route('super-admin.users.create') }}"
               class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Tambah Akun
            </a>
        </div>
    </header>

    {{-- RINGKASAN AKUN --}}
    <section aria-labelledby="account-summary-title">
        <div class="section-intro">
            <span class="section-kicker">Ikhtisar pengguna</span>
            <h2 id="account-summary-title">Ringkasan Akun Sistem</h2>
            <p>
                Pantau jumlah akun berdasarkan peran dan status
                untuk mendapatkan gambaran pengelolaan pengguna.
            </p>
        </div>

        <div class="stats-grid">

            {{-- TOTAL ADMIN --}}
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
                    <span class="stat-index">01 / ADMIN</span>
                </div>

                <div class="stat-label">Total Akun Admin</div>

                <div class="stat-number">
                    {{ number_format($totalAdmin, 0, ',', '.') }}
                </div>

                <div class="stat-note">
                    Pengelola data operasional.
                </div>
            </div>

            {{-- TOTAL PIMPINAN --}}
            <div class="stat-card"
                 style="--stat-accent:#8061ad;--stat-soft:#f1eafb;">
                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                            <path d="M8 13.5 12 16l4-2.5"/>
                        </svg>
                    </div>
                    <span class="stat-index">02 / PIMPINAN</span>
                </div>

                <div class="stat-label">Total Akun Pimpinan</div>

                <div class="stat-number">
                    {{ number_format($totalPimpinan, 0, ',', '.') }}
                </div>

                <div class="stat-note">
                    Akun untuk pemantauan.
                </div>
            </div>

            {{-- AKUN AKTIF --}}
            <div class="stat-card"
                 style="--stat-accent:#23856d;--stat-soft:#e4f5ef;">
                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m8 12 2.5 2.5L16 9"/>
                        </svg>
                    </div>
                    <span class="stat-index">03 / STATUS</span>
                </div>

                <div class="stat-label">Akun Aktif</div>

                <div class="stat-number">
                    {{ number_format($totalAktif, 0, ',', '.') }}
                </div>

                <div class="stat-note">
                    Akun Admin dan Pimpinan yang aktif.
                </div>
            </div>

            {{-- AKUN NONAKTIF --}}
            <div class="stat-card"
                 style="--stat-accent:#c65c62;--stat-soft:#fbecee;">
                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m9 9 6 6M15 9l-6 6"/>
                        </svg>
                    </div>
                    <span class="stat-index">04 / STATUS</span>
                </div>

                <div class="stat-label">Akun Nonaktif</div>

                <div class="stat-number">
                    {{ number_format($totalNonaktif, 0, ',', '.') }}
                </div>

                <div class="stat-note">
                    Akun Admin dan Pimpinan yang nonaktif.
                </div>
            </div>

        </div>
    </section>

    {{-- PANEL PENGELOLAAN --}}
    <section aria-labelledby="management-title">
        <div class="section-intro">
            <span class="section-kicker">Pengaturan sistem</span>
            <h2 id="management-title">Pusat Pengelolaan</h2>
            <p>
                Pilih bagian yang ingin dikelola sesuai kewenangan
                Super Admin.
            </p>
        </div>

        <div class="dashboard-grid">

            {{-- MANAJEMEN PENGGUNA --}}
            <section class="content-card">
                <div class="card-topline">
                    <div class="card-icon"
                         style="--card-accent:#2457a7;--card-soft:#eaf1fc;"
                         aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                            <path d="M18 8h4M20 6v4"/>
                        </svg>
                    </div>

                    <div class="card-title-wrap">
                        <span class="section-kicker">Akses pengguna</span>
                        <h2>Manajemen Pengguna</h2>
                    </div>
                </div>

                <p>
                    Kelola akun Admin dan Pimpinan. Super Admin tidak
                    mengelola data peserta maupun data pelatihan.
                </p>

                <hr class="card-divider">

                <p>
                    Gunakan menu Kelola Akun di sidebar untuk melihat
                    dan mengelola akun yang terdaftar.
                </p>

                <div class="access-note">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 11v5M12 8h.01"/>
                    </svg>
                    <span>
                        Gunakan pengelolaan akun untuk memastikan
                        akses pengguna sesuai perannya.
                    </span>
                </div>
            </section>

            {{-- DATA MASTER --}}
            <section class="content-card master-card">
                <div class="card-topline">
                    <div class="card-icon"
                         style="--card-accent:#087f8c;--card-soft:#e5f6f4;"
                         aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 3 8l9 5 9-5-9-5Z"/>
                            <path d="m3 12 9 5 9-5M3 16l9 5 9-5"/>
                        </svg>
                    </div>

                    <div class="card-title-wrap">
                        <span class="section-kicker">Konfigurasi referensi</span>
                        <h2>Data Master</h2>
                    </div>
                </div>

                <p>
                    Kelola provinsi, kabupaten/kota/mitra, sumber dana,
                    tahun pelatihan, dan jenis tenaga kesehatan.
                </p>

                <hr class="card-divider">

                <p>
                    Data master membantu menjaga konsistensi pilihan
                    dan referensi yang digunakan di dalam sistem.
                </p>

                <div class="inline-actions">
                    <a href="{{ route('super-admin.master-data.index', [
                        'category' => 'provinces'
                    ]) }}" class="btn btn-light">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M4 5h16M4 12h16M4 19h16"/>
                            <circle cx="8" cy="5" r="1.5" fill="currentColor" stroke="none"/>
                            <circle cx="15" cy="12" r="1.5" fill="currentColor" stroke="none"/>
                            <circle cx="10" cy="19" r="1.5" fill="currentColor" stroke="none"/>
                        </svg>

                        Buka Data Master

                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </section>

        </div>
    </section>

</div>
@endsection