<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SIRAPIK - Bapelkes Kalimantan Selatan</title>

    <link rel="stylesheet" href="{{ asset('css/sirapik.css') }}">

    <style>
        :root {
            --navy: #17365d;
            --navy-dark: #102846;
            --blue: #245ba7;
            --blue-hover: #1b477f;
            --pale: #eef4fb;
            --ink: #243247;
            --muted: #637187;
            --border: #dce4ed;
            --surface: #ffffff;
            --page: #f5f7fa;
            --green: #237653;
            --green-bg: #eaf5ef;
            --red: #a63c3c;
            --red-bg: #fbefef;
            --radius: 10px;
            --shadow: 0 2px 10px rgba(24, 45, 75, .045);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 24px;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            background: var(--page);
            font-size: 15px;
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        select {
            font: inherit;
        }

        button,
        a,
        select {
            -webkit-tap-highlight-color: transparent;
        }

        a:focus-visible,
        button:focus-visible,
        select:focus-visible {
            outline: 3px solid rgba(36, 91, 167, .3);
            outline-offset: 3px;
        }

        .public-container {
            width: min(1180px, calc(100% - 48px));
            margin-inline: auto;
        }

        /* =========================
           HEADER / IDENTITAS
        ========================= */

        .public-nav {
            position: relative;
            z-index: 2;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .nav-inner {
            min-height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: var(--navy);
            color: #fff;
            font-size: 24px;
            font-weight: 800;
            line-height: 1;
        }

        .brand-title {
            color: var(--navy);
            font-size: 21px;
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: .2px;
        }

        .brand-subtitle {
            margin-top: 3px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        /* =========================
           TOMBOL
        ========================= */

        .btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 17px;
            border: 1px solid var(--blue);
            border-radius: 7px;
            background: var(--blue);
            color: #fff;
            font-size: 14px;
            font-weight: 650;
            line-height: 1.35;
            cursor: pointer;
            transition:
                background-color .18s ease,
                border-color .18s ease;
        }

        .btn:hover {
            background: var(--blue-hover);
            border-color: var(--blue-hover);
        }

        .btn-outline {
            background: #fff;
            color: var(--navy);
            border-color: #cbd6e4;
        }

        .btn-outline:hover {
            background: var(--pale);
            border-color: #b8c8dc;
        }

        /* =========================
           BANNER UTAMA
        ========================= */

        .hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background-color: var(--navy-dark);
            background-image:
                linear-gradient(
                    105deg,
                    rgba(13, 36, 65, .78) 0%,
                    rgba(20, 55, 96, .67) 52%,
                    rgba(28, 76, 128, .54) 100%
                ),
                url("{{ asset('images/background-sirapik.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #fff;
            padding: 58px 0 62px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(270px, .75fr);
            align-items: center;
            gap: 54px;
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 5px;
            color: #d9e8fa;
            font-size: 12px;
            font-weight: 750;
            letter-spacing: 1.25px;
            line-height: 1.5;
            text-transform: uppercase;
        }

        .hero h1 {
            max-width: 690px;
            margin: 10px 0 16px;
            font-size: clamp(32px, 3.4vw, 42px);
            font-weight: 750;
            line-height: 1.2;
            letter-spacing: -.65px;
            text-wrap: balance;
        }

        .hero-description {
            max-width: 660px;
            margin: 0 0 24px;
            color: #edf3fa;
            font-size: 16px;
            line-height: 1.8;
        }

        .hero .btn {
            min-height: 44px;
            padding-inline: 19px;
            background: #fff;
            border-color: #fff;
            color: var(--navy);
        }

        .hero .btn:hover {
            background: #eaf1fa;
            border-color: #eaf1fa;
        }

        .hero-note {
            padding: 23px 24px;
            border: 1px solid rgba(255, 255, 255, .28);
            border-radius: 9px;
            background: rgba(12, 35, 63, .36);
        }

        .hero-note strong {
            display: block;
            margin-bottom: 8px;
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            line-height: 1.5;
        }

        .hero-note p {
            margin: 0;
            color: #edf3fa;
            font-size: 14px;
            line-height: 1.8;
        }

        /* =========================
           BAGIAN DASHBOARD
        ========================= */

        .section {
            padding: 36px 0 42px;
        }

        .section-heading {
            margin-bottom: 19px;
        }

        .section-heading h2 {
            margin: 0 0 5px;
            color: var(--navy);
            font-size: 25px;
            font-weight: 750;
            line-height: 1.4;
            letter-spacing: -.25px;
        }

        .section-heading p {
            max-width: 760px;
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        /* =========================
           KARTU DAN FILTER
        ========================= */

        .filter-card,
        .stat-card,
        .content-card {
            min-width: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .filter-card {
            padding: 20px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns:
                minmax(130px, .8fr)
                minmax(210px, 1.35fr)
                minmax(160px, 1fr)
                auto;
            align-items: end;
            gap: 14px;
        }

        .form-field {
            min-width: 0;
        }

        .form-field label {
            display: block;
            margin-bottom: 7px;
            color: var(--ink);
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
        }

        .form-field select {
            width: 100%;
            min-height: 43px;
            padding: 9px 34px 9px 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background-color: #fff;
            color: var(--ink);
            font-size: 14px;
            line-height: 1.5;
        }

        .form-field select:hover {
            border-color: #9aacc2;
        }

        .form-field select:focus {
            outline: 3px solid rgba(36, 91, 167, .13);
            border-color: var(--blue);
        }

        .filter-grid > .btn {
            min-height: 43px;
            white-space: nowrap;
        }

        /* =========================
           KARTU STATISTIK
        ========================= */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 18px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            padding: 21px 22px 20px 24px;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 4px;
            background: var(--blue);
        }

        .stat-label {
            color: var(--muted);
            font-size: 13px;
            font-weight: 650;
            line-height: 1.5;
        }

        .stat-value {
            margin-top: 8px;
            color: var(--navy);
            font-size: clamp(29px, 3vw, 34px);
            font-weight: 780;
            line-height: 1.25;
            letter-spacing: -.5px;
            overflow-wrap: anywhere;
        }

        .stat-caption {
            margin-top: 7px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
        }

        /* =========================
           TABEL DAN KARTU DATA
        ========================= */

        .data-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            align-items: start;
            gap: 18px;
            margin-top: 18px;
        }

        .content-card {
            padding: 22px;
        }

        .content-card h3 {
            margin: 0;
            color: var(--navy);
            font-size: 18px;
            font-weight: 750;
            line-height: 1.5;
        }

        .card-description {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            overscroll-behavior-x: contain;
        }

        table {
            width: 100%;
            margin-top: 16px;
            border-collapse: collapse;
            font-size: 13px;
        }

        th,
        td {
            padding: 12px 11px;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid #e6ebf1;
        }

        th {
            background: #f3f6fa;
            color: #40516a;
            font-size: 12px;
            font-weight: 750;
            line-height: 1.55;
        }

        td {
            color: #334155;
            line-height: 1.65;
            overflow-wrap: anywhere;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .empty {
            padding: 22px 12px;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        /* =========================
           PERINGKAT JENIS NAKES
        ========================= */

        .ranking-card {
            margin-top: 18px;
        }

        .ranking-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            align-items: start;
            gap: 14px;
            margin-top: 18px;
        }

        .ranking-panel {
            min-width: 0;
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
        }

        .ranking-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .ranking-icon {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            display: grid;
            place-items: center;
            border-radius: 7px;
            font-size: 19px;
            font-weight: 750;
        }

        .ranking-high .ranking-icon {
            background: var(--green-bg);
            color: var(--green);
        }

        .ranking-low .ranking-icon {
            background: var(--red-bg);
            color: var(--red);
        }

        .ranking-heading h4 {
            margin: 0;
            color: var(--navy);
            font-size: 14px;
            font-weight: 750;
            line-height: 1.5;
        }

        .ranking-heading p {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .ranking-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .ranking-item {
            display: grid;
            grid-template-columns: 27px minmax(0, 1fr) auto;
            align-items: center;
            gap: 9px;
            padding: 11px 0;
            border-bottom: 1px solid #edf0f4;
        }

        .ranking-item:last-child {
            border-bottom: none;
        }

        .ranking-number {
            width: 26px;
            height: 26px;
            display: grid;
            place-items: center;
            border-radius: 5px;
            background: #edf3fa;
            color: var(--navy);
            font-size: 12px;
            font-weight: 750;
        }

        .ranking-name {
            min-width: 0;
            color: #344256;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .ranking-total {
            color: var(--navy);
            font-size: 12px;
            font-weight: 750;
            white-space: nowrap;
        }

        .ranking-empty {
            padding: 15px;
            border: 1px solid #e8edf3;
            border-radius: 7px;
            background: #f7f9fc;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.75;
        }

        /* =========================
           FOOTER
        ========================= */

        .public-footer {
            margin-top: 0;
            padding: 24px 0;
            background: var(--navy-dark);
            color: #e2eaf4;
            font-size: 12px;
            line-height: 1.8;
        }

        .footer-inner {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px 28px;
        }

        .footer-inner strong {
            color: #fff;
            font-size: 14px;
            font-weight: 750;
        }

        /* =========================
           TABLET
        ========================= */

        @media (max-width: 1000px) {
            .hero-grid {
                grid-template-columns: minmax(0, 1.2fr) minmax(240px, .8fr);
                gap: 30px;
            }

            .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-grid > .btn {
                width: 100%;
            }

            .data-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .public-container {
                width: calc(100% - 36px);
            }

            .hero {
                padding: 43px 0 46px;
                background-position: center;
            }

            .hero-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .hero h1 {
                max-width: 620px;
                font-size: clamp(30px, 6vw, 38px);
            }

            .hero-description {
                font-size: 15px;
                line-height: 1.8;
            }

            .hero-note {
                max-width: 620px;
                padding: 18px 20px;
            }

            .section {
                padding: 30px 0 34px;
            }

            .section-heading h2 {
                font-size: 23px;
            }

            .stats-grid {
                gap: 12px;
            }

            .stat-card {
                padding: 18px 18px 18px 21px;
            }

            .content-card {
                padding: 19px;
            }

            .ranking-grid {
                gap: 12px;
            }
        }

        /* =========================
           TELEPON
        ========================= */

        @media (max-width: 540px) {
            body {
                font-size: 14px;
            }

            .public-container {
                width: calc(100% - 28px);
            }

            .nav-inner {
                min-height: 70px;
                gap: 10px;
            }

            .brand {
                gap: 9px;
            }

            .brand-mark {
                width: 39px;
                height: 39px;
                flex-basis: 39px;
                border-radius: 7px;
                font-size: 21px;
            }

            .brand-title {
                font-size: 18px;
            }

            .brand-subtitle {
                max-width: 180px;
                margin-top: 2px;
                font-size: 11px;
                line-height: 1.4;
            }

            .nav-inner > .btn {
                min-height: 37px;
                gap: 4px;
                padding: 8px 10px;
                font-size: 12px;
                white-space: nowrap;
            }

            .hero {
                padding: 37px 0 39px;
            }

            .eyebrow {
                font-size: 11px;
                letter-spacing: 1px;
            }

            .hero h1 {
                margin: 10px 0 13px;
                font-size: 30px;
                line-height: 1.25;
                letter-spacing: -.35px;
            }

            .hero-description {
                margin-bottom: 20px;
                font-size: 14px;
                line-height: 1.8;
            }

            .hero .btn {
                min-height: 42px;
                padding-inline: 15px;
                font-size: 13px;
            }

            .hero-note {
                padding: 16px;
                border-radius: 8px;
            }

            .hero-note strong {
                font-size: 15px;
            }

            .hero-note p {
                font-size: 13px;
                line-height: 1.75;
            }

            .section {
                padding: 27px 0 30px;
            }

            .section-heading {
                margin-bottom: 16px;
            }

            .section-heading h2 {
                font-size: 22px;
            }

            .section-heading p {
                font-size: 13px;
            }

            .filter-card {
                padding: 16px;
            }

            .filter-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 13px;
            }

            .form-field label {
                font-size: 13px;
            }

            .form-field select {
                min-height: 43px;
                font-size: 13px;
            }

            .filter-grid > .btn {
                min-height: 42px;
                font-size: 13px;
            }

            .stats-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 11px;
                margin-top: 14px;
            }

            .stat-card {
                padding: 16px 17px 16px 20px;
            }

            .stat-label {
                font-size: 13px;
            }

            .stat-value {
                margin-top: 5px;
                font-size: 29px;
            }

            .stat-caption {
                margin-top: 4px;
                font-size: 12px;
            }

            .data-grid {
                gap: 14px;
                margin-top: 14px;
            }

            .content-card {
                padding: 16px;
            }

            .content-card h3 {
                font-size: 17px;
            }

            .card-description {
                font-size: 12px;
            }

            table {
                min-width: 290px;
                font-size: 12px;
            }

            th,
            td {
                padding: 10px 8px;
            }

            th {
                font-size: 11px;
            }

            .ranking-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 11px;
                margin-top: 14px;
            }

            .ranking-panel {
                padding: 14px;
            }

            .ranking-name,
            .ranking-total {
                font-size: 12px;
            }

            .footer-inner {
                flex-direction: column;
                gap: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
    {{-- NAVIGASI --}}
    <header class="public-nav">
        <div class="public-container nav-inner">
            <div class="brand">
                <div class="brand-mark">S</div>

                <div>
                    <div class="brand-title">SIRAPIK</div>
                    <div class="brand-subtitle">
                        Bapelkes Provinsi Kalimantan Selatan
                    </div>
                </div>
            </div>

            <a class="btn btn-outline" href="{{ route('login') }}">
                Login Internal <span aria-hidden="true">→</span>
            </a>
        </div>
    </header>

    <main>
        {{-- HERO --}}
        <section class="hero">
            <div class="public-container hero-grid">
                <div>
                    <div class="eyebrow">Sistem Informasi Pelatihan</div>

                    <h1>
                        Informasi pelatihan kesehatan dalam satu sistem.
                    </h1>

                    <p class="hero-description">
                        SIRAPIK menyajikan ringkasan data pelatihan Bapelkes
                        Kalimantan Selatan berdasarkan tahun, kabupaten/kota
                        atau mitra, dan sumber dana untuk membantu pemantauan
                        pelaksanaan pelatihan tenaga kesehatan.
                    </p>

                    <a class="btn" href="#dashboard">
                        Lihat Dashboard <span aria-hidden="true">↓</span>
                    </a>
                </div>

                <aside class="hero-note">
                    <strong>Informasi untuk publik</strong>

                    <p>
                        Dashboard ini dapat dibuka tanpa akun. Pengelolaan
                        data dan fitur administrasi hanya tersedia bagi
                        petugas yang memiliki akun internal sesuai
                        kewenangannya.
                    </p>
                </aside>
            </div>
        </section>

        {{-- DASHBOARD PUBLIK --}}
        <section class="section public-container" id="dashboard">
            <div class="section-heading">
                <h2>Dashboard Pelatihan</h2>

                <p>
                    Pilih kriteria untuk melihat ringkasan pelatihan
                    yang telah tercatat.
                </p>
            </div>

            {{-- FILTER --}}
            <form
                method="GET"
                action="{{ route('public.dashboard') }}"
                class="filter-card"
            >
                <div class="filter-grid">
                    <div class="form-field">
                        <label for="year">Tahun pelatihan</label>

                        <select name="year" id="year">
                            @forelse ($years as $availableYear)
                                <option
                                    value="{{ $availableYear }}"
                                    @selected((int) $year === (int) $availableYear)
                                >
                                    {{ $availableYear }}
                                </option>
                            @empty
                                <option value="{{ $year }}">
                                    {{ $year }}
                                </option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="district">Kabupaten/Kota/Mitra</label>

                        <select name="district" id="district">
                            <option value="">Semua Kabupaten/Kota/Mitra</option>

                            @foreach ($districts as $item)
                                <option
                                    value="{{ $item }}"
                                    @selected($district === $item)
                                >
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="funding_source">Sumber dana</label>

                        <select name="funding_source" id="funding_source">
                            <option value="">Semua sumber dana</option>

                            @foreach ($fundingSources as $source)
                                <option
                                    value="{{ $source }}"
                                    @selected($fundingSource === $source)
                                >
                                    {{ $source }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn" type="submit">
                        Terapkan Filter
                    </button>
                </div>
            </form>

            {{-- STATISTIK --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Jumlah pelatihan</div>

                    <div class="stat-value">
                        {{ number_format($totalTrainings, 0, ',', '.') }}
                    </div>

                    <div class="stat-caption">
                        Sesuai filter yang dipilih
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Peserta terdaftar</div>

                    <div class="stat-value">
                        {{ number_format($totalParticipants, 0, ',', '.') }}
                    </div>

                    <div class="stat-caption">
                        Peserta unik dari riwayat pelatihan yang cocok
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Tahun laporan</div>

                    <div class="stat-value">{{ $year }}</div>

                    <div class="stat-caption">
                        Tahun pelaksanaan pelatihan
                    </div>
                </div>
            </div>

            {{-- REKAP KABUPATEN/KOTA --}}
            <div class="data-grid">
                <section class="content-card">
                    <h3>Rekap Kabupaten/Kota/Mitra</h3>

                    <p class="card-description">
                        Jumlah pelatihan berdasarkan wilayah atau mitra
                        yang tercatat.
                    </p>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Kabupaten/Kota/Mitra</th>
                                    <th>Jumlah pelatihan</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($trainingByDistrict as $row)
                                    <tr>
                                        <td>{{ $row->district }}</td>

                                        <td>
                                            {{ number_format($row->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="empty">
                                            Belum ada data untuk filter ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- PERINGKAT JENIS NAKES --}}
                <section class="content-card">
                    <h3>Tenaga Kesehatan (Jenis Nakes)</h3>

                    <p class="card-description">
                        Perbandingan jenis tenaga kesehatan berdasarkan
                        keikutsertaan dalam pelatihan pada filter yang dipilih.
                    </p>

                    <div class="ranking-grid">
                        {{-- LIMA TERTINGGI --}}
                        <div class="ranking-panel ranking-high">
                            <div class="ranking-heading">
                                <div class="ranking-icon">↑</div>

                                <div>
                                    <h4>5 Tertinggi</h4>
                                    <p>Jenis nakes paling aktif</p>
                                </div>
                            </div>

                            @php
                                $topNakes = $topNakes ?? collect();
                            @endphp

                            @if ($topNakes->isNotEmpty())
                                <ol class="ranking-list">
                                    @foreach ($topNakes->take(5) as $nakes)
                                        <li class="ranking-item">
                                            <span class="ranking-number">
                                                {{ $loop->iteration }}
                                            </span>

                                            <span class="ranking-name">
                                                {{ $nakes->name ?? $nakes->profession ?? $nakes->jenis_nakes ?? 'Tidak diketahui' }}
                                            </span>

                                            <span class="ranking-total">
                                                {{ number_format((int) ($nakes->total ?? $nakes->total_participations ?? 0), 0, ',', '.') }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ol>
                            @else
                                <div class="ranking-empty">
                                    Data peringkat jenis nakes belum tersedia.
                                    Setelah data jenis nakes terhubung,
                                    lima jenis dengan keikutsertaan tertinggi
                                    akan ditampilkan di sini.
                                </div>
                            @endif
                        </div>

                        {{-- LIMA TERENDAH --}}
                        <div class="ranking-panel ranking-low">
                            <div class="ranking-heading">
                                <div class="ranking-icon">↓</div>

                                <div>
                                    <h4>5 Terendah</h4>
                                    <p>Jenis nakes yang jarang aktif</p>
                                </div>
                            </div>

                            @php
                                $bottomNakes = $bottomNakes ?? collect();
                            @endphp

                            @if ($bottomNakes->isNotEmpty())
                                <ol class="ranking-list">
                                    @foreach ($bottomNakes->take(5) as $nakes)
                                        <li class="ranking-item">
                                            <span class="ranking-number">
                                                {{ $loop->iteration }}
                                            </span>

                                            <span class="ranking-name">
                                                {{ $nakes->name ?? $nakes->profession ?? $nakes->jenis_nakes ?? 'Tidak diketahui' }}
                                            </span>

                                            <span class="ranking-total">
                                                {{ number_format((int) ($nakes->total ?? $nakes->total_participations ?? 0), 0, ',', '.') }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ol>
                            @else
                                <div class="ranking-empty">
                                    Data peringkat jenis nakes belum tersedia.
                                    Setelah data jenis nakes terhubung,
                                    lima jenis dengan keikutsertaan terendah
                                    akan ditampilkan di sini.
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="public-footer">
        <div class="public-container footer-inner">
            <div>
                <strong>SIRAPIK</strong><br>
                Bapelkes Provinsi Kalimantan Selatan
            </div>

            <div>
                © {{ date('Y') }} SIRAPIK.
                Informasi ditampilkan berdasarkan data yang tersedia.
            </div>
        </div>
    </footer>
</body>
</html>