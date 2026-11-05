<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#102b4e">
    <meta name="description" content="Informasi riwayat pelatihan peserta SIRAPIK.">

    <title>Riwayat Pelatihan — SIRAPIK</title>

    <style>
        :root {
            --navy: #102b4e;
            --navy-deep: #0a1e36;
            --blue: #1e4f82;
            --blue-light: #eaf2fb;
            --blue-soft: #f3f7fc;
            --ink: #192b40;
            --muted: #65758a;
            --border: #dce5ef;
            --green: #16734d;
            --green-bg: #eaf7f0;
            --white: #ffffff;
            --page: #f4f7fb;
            --shadow: 0 12px 35px rgba(16, 43, 78, .07);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            width: 100%;
            margin: 0;
            color: var(--ink);
            background: var(--page);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            font-size: 16px;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
        }

        /* HEADER FULL WIDTH */

        .site-header {
            position: relative;
            width: 100%;
            overflow: hidden;
            color: var(--white);
            background: var(--navy-deep);
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            width: 100%;
            padding: 25px clamp(22px, 5vw, 80px);
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 17px;
            min-width: 0;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 58px;
            width: 58px;
            height: 58px;
            overflow: hidden;
            border-radius: 13px;
            background: white;
            box-shadow: 0 5px 18px rgba(0,0,0,.15);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            padding: 5px;
            object-fit: contain;
        }

        .brand-name {
            margin: 0;
            color: white;
            font-size: 25px;
            font-weight: 850;
            line-height: 1.25;
            letter-spacing: -.6px;
        }

        .brand-description {
            margin: 4px 0 0;
            color: rgba(255,255,255,.76);
            font-size: 14px;
        }

        .header-label {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 10px 16px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 30px;
            color: #e6effa;
            background: rgba(255,255,255,.07);
            font-size: 13px;
            font-weight: 700;
        }

        .header-label::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #69d6a2;
            box-shadow: 0 0 0 4px rgba(105,214,162,.13);
        }

        /* INTRODUCTION */

        .intro-section {
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 30px;
            width: 100%;
            padding: 48px clamp(22px, 5vw, 80px) 43px;
            overflow: hidden;
            color: white;
            background:
                radial-gradient(
                    circle at 88% 15%,
                    rgba(87,145,201,.22),
                    transparent 27%
                ),
                linear-gradient(115deg, #102b4e, #173e68);
        }

        .intro-section::after {
            content: "S";
            position: absolute;
            right: 8%;
            bottom: -115px;
            color: rgba(255,255,255,.035);
            font-size: 310px;
            font-weight: 900;
            line-height: 1;
            pointer-events: none;
        }

        .intro-content {
            position: relative;
            z-index: 1;
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 10px;
            color: #a9d0f8;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .intro-title {
            margin: 0;
            color: white;
            font-size: clamp(30px, 4vw, 48px);
            font-weight: 850;
            line-height: 1.2;
            letter-spacing: -1.3px;
        }

        .intro-description {
            max-width: 720px;
            margin: 13px 0 0;
            color: rgba(255,255,255,.78);
            font-size: 17px;
        }

        .intro-side {
            position: relative;
            z-index: 1;
            flex: 0 0 auto;
            padding: 13px 18px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 12px;
            color: white;
            background: rgba(255,255,255,.08);
            font-size: 14px;
            font-weight: 700;
        }

        /* MAIN CONTENT */

        .main-content {
            width: 100%;
            padding: 38px clamp(20px, 5vw, 80px) 65px;
        }

        .success-alert {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            width: 100%;
            margin-bottom: 36px;
            padding: 19px 22px;
            border: 1px solid #b9e4ce;
            border-left: 5px solid #269566;
            border-radius: 12px;
            color: var(--green);
            background: var(--green-bg);
        }

        .success-icon {
            display: flex;
            flex: 0 0 34px;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            color: white;
            background: #269566;
            font-size: 19px;
            font-weight: 800;
        }

        .success-alert strong {
            display: block;
            margin-bottom: 2px;
            font-size: 16px;
        }

        .success-alert p {
            margin: 0;
            color: #36765a;
            font-size: 14px;
        }

        /* SECTION HEADINGS */

        .section-block {
            margin-bottom: 39px;
        }

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }

        .section-title {
            margin: 0;
            color: var(--navy);
            font-size: 25px;
            font-weight: 850;
            letter-spacing: -.5px;
        }

        .section-subtitle {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 15px;
        }

        .section-index {
            color: #9aabc0;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        /* PARTICIPANT INFORMATION */

        .participant-panel {
            width: 100%;
            padding: 25px 28px;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
            box-shadow: var(--shadow);
        }

        .participant-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            column-gap: 35px;
            row-gap: 0;
        }

        .info-item {
            min-width: 0;
            padding: 17px 0;
            border-bottom: 1px solid #e9eef4;
        }

        .info-item:nth-last-child(-n+3) {
            border-bottom: none;
        }

        .info-label {
            display: block;
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 600;
        }

        .info-value {
            display: block;
            color: var(--ink);
            font-size: 17px;
            font-weight: 750;
            overflow-wrap: anywhere;
        }

        /* SUMMARY */

        .summary-layout {
            display: grid;
            grid-template-columns: 1.35fr 1fr;
            gap: 17px;
            margin-bottom: 21px;
        }

        .summary-card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            min-height: 145px;
            padding: 25px 29px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
            box-shadow: var(--shadow);
        }

        .summary-card.primary {
            color: white;
            border-color: var(--navy);
            background: var(--navy);
        }

        .summary-card.primary::after {
            content: "";
            position: absolute;
            right: -35px;
            bottom: -90px;
            width: 210px;
            height: 210px;
            border: 30px solid rgba(255,255,255,.07);
            border-radius: 50%;
        }

        .summary-info {
            position: relative;
            z-index: 1;
        }

        .summary-label {
            display: block;
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 15px;
            font-weight: 600;
        }

        .primary .summary-label {
            color: rgba(255,255,255,.72);
        }

        .summary-number {
            display: block;
            color: var(--navy);
            font-size: 42px;
            font-weight: 900;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .primary .summary-number {
            color: white;
        }

        .summary-note {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 13px;
        }

        .primary .summary-note {
            color: rgba(255,255,255,.7);
        }

        .summary-symbol {
            position: relative;
            z-index: 1;
            display: flex;
            flex: 0 0 55px;
            align-items: center;
            justify-content: center;
            width: 55px;
            height: 55px;
            border-radius: 15px;
            color: var(--blue);
            background: var(--blue-light);
        }

        .primary .summary-symbol {
            color: white;
            background: rgba(255,255,255,.13);
        }

        .summary-symbol svg {
            width: 27px;
            height: 27px;
        }

        /* TABLE */

        .table-panel {
            width: 100%;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
            box-shadow: var(--shadow);
        }

        .table-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 19px 24px;
            border-bottom: 1px solid var(--border);
        }

        .table-topbar strong {
            color: var(--navy);
            font-size: 17px;
            font-weight: 800;
        }

        .table-count {
            padding: 6px 12px;
            border-radius: 30px;
            color: var(--blue);
            background: var(--blue-light);
            font-size: 13px;
            font-weight: 800;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 18px 22px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #e9eef4;
        }

        th {
            color: #60728a;
            background: #f5f8fc;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        td {
            color: #35475d;
            font-size: 15px;
        }

        tbody tr {
            transition: background .2s ease;
        }

        tbody tr:hover {
            background: #f7faff;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .number-cell {
            width: 70px;
            color: #8b9bb0;
            font-weight: 700;
        }

        .training-name {
            display: block;
            color: var(--navy);
            font-size: 15px;
            font-weight: 800;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 12px;
            border-radius: 7px;
            color: #176b4a;
            background: #eaf7f0;
            font-size: 13px;
            font-weight: 800;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .status::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #269566;
        }

        .empty-state {
            padding: 42px 25px;
            color: var(--muted);
            text-align: center;
        }

        .empty-state strong {
            display: block;
            margin-bottom: 5px;
            color: var(--navy);
            font-size: 17px;
        }

        /* ACTIONS */

        .bottom-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            margin-top: 34px;
            padding-top: 26px;
            border-top: 1px solid var(--border);
        }

        .bottom-note {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            min-height: 50px;
            padding: 12px 21px;
            border: 1px solid var(--navy);
            border-radius: 9px;
            color: white;
            background: var(--navy);
            font-size: 15px;
            font-weight: 750;
            text-decoration: none;
            transition:
                background .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }

        .back-button:hover {
            transform: translateY(-2px);
            background: var(--blue);
            box-shadow: 0 8px 20px rgba(16,43,78,.18);
        }

        .back-button svg {
            width: 18px;
            height: 18px;
        }

        /* FOOTER */

        .site-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
            padding: 23px clamp(22px, 5vw, 80px);
            border-top: 1px solid var(--border);
            background: white;
        }

        .footer-brand {
            color: var(--navy);
            font-size: 15px;
            font-weight: 850;
        }

        .footer-text {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .footer-year {
            color: var(--muted);
            font-size: 13px;
        }

        /* ANIMATION */

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .intro-content,
        .section-block,
        .success-alert {
            animation: fadeUp .55s ease both;
        }

        .section-block:nth-of-type(2) {
            animation-delay: .08s;
        }

        .section-block:nth-of-type(3) {
            animation-delay: .14s;
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {
            .participant-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                column-gap: 25px;
            }

            .info-item:nth-last-child(-n+3) {
                border-bottom: 1px solid #e9eef4;
            }

            .info-item:nth-last-child(-n+2) {
                border-bottom: none;
            }

            .intro-section {
                align-items: flex-start;
                flex-direction: column;
                gap: 20px;
            }
        }

        @media (max-width: 600px) {
            .header-inner {
                padding: 18px 17px;
            }

            .brand {
                gap: 12px;
            }

            .brand-logo {
                flex-basis: 47px;
                width: 47px;
                height: 47px;
            }

            .brand-name {
                font-size: 21px;
            }

            .brand-description {
                font-size: 11px;
            }

            .header-label {
                display: none;
            }

            .intro-section {
                padding: 34px 20px;
            }

            .intro-title {
                font-size: 32px;
            }

            .intro-description {
                font-size: 15px;
            }

            .main-content {
                padding: 25px 15px 42px;
            }

            .success-alert {
                padding: 15px;
                gap: 11px;
            }

            .section-title {
                font-size: 22px;
            }

            .section-subtitle {
                font-size: 14px;
            }

            .participant-panel {
                padding: 12px 18px;
            }

            .participant-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .info-item,
            .info-item:nth-last-child(-n+2),
            .info-item:nth-last-child(-n+3) {
                border-bottom: 1px solid #e9eef4;
            }

            .info-item:last-child {
                border-bottom: none;
            }

            .info-value {
                font-size: 16px;
            }

            .summary-layout {
                grid-template-columns: minmax(0, 1fr);
                gap: 12px;
            }

            .summary-card {
                min-height: 120px;
                padding: 20px;
            }

            .summary-number {
                font-size: 36px;
            }

            .table-topbar {
                padding: 16px;
            }

            .table-wrapper {
                overflow: visible;
            }

            table {
                display: block;
                min-width: 0;
                width: 100%;
            }

            thead {
                display: none;
            }

            tbody {
                display: grid;
                gap: 12px;
                padding: 12px;
            }

            tbody tr {
                display: block;
                overflow: hidden;
                border: 1px solid var(--border);
                border-radius: 10px;
                background: white;
            }

            tbody td {
                display: grid;
                grid-template-columns: 115px minmax(0, 1fr);
                gap: 10px;
                padding: 12px;
                border-bottom: 1px solid #edf1f5;
                font-size: 14px;
            }

            tbody td::before {
                content: attr(data-label);
                color: var(--muted);
                font-size: 13px;
                font-weight: 700;
            }

            tbody tr:last-child td {
                border-bottom: 1px solid #edf1f5;
            }

            tbody tr td:last-child {
                border-bottom: none;
            }

            .bottom-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .back-button {
                width: 100%;
            }

            .site-footer {
                align-items: flex-start;
                flex-direction: column;
                padding: 20px;
            }
        }

        @media (max-width: 360px) {
            .intro-title {
                font-size: 28px;
            }

            tbody td {
                grid-template-columns: 90px minmax(0, 1fr);
                gap: 7px;
                padding: 10px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header class="site-header">
        <div class="header-inner">

            <div class="brand">
                <div class="brand-logo">
                    <img
                        src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                        alt="Logo Provinsi Kalimantan Selatan"
                    >
                </div>

                <div>
                    <h1 class="brand-name">SIRAPIK</h1>
                    <p class="brand-description">
                        Bapelkes Provinsi Kalimantan Selatan
                    </p>
                </div>
            </div>

            <div class="header-label">
                Sistem Informasi Riwayat Pelatihan
            </div>

        </div>
    </header>

    <!-- INTRO FULL WIDTH -->
    <section class="intro-section">
        <div class="intro-content">
            <span class="eyebrow">Hasil Pencarian</span>

            <h2 class="intro-title">
                Riwayat Pelatihan Anda
            </h2>

            <p class="intro-description">
                Informasi identitas peserta dan seluruh riwayat
                pelatihan yang telah tercatat di sistem SIRAPIK.
            </p>
        </div>

        <div class="intro-side">
            Data Peserta SIRAPIK
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- NOTIFIKASI -->
        <div class="success-alert" role="status">
            <div class="success-icon" aria-hidden="true">✓</div>

            <div>
                <strong>Data peserta berhasil ditemukan.</strong>
                <p>
                    NIK dan nama lengkap sesuai dengan data peserta
                    yang terdaftar di SIRAPIK.
                </p>
            </div>
        </div>

        <!-- INFORMASI PESERTA -->
        <section class="section-block">

            <div class="section-heading">
                <div>
                    <h3 class="section-title">Informasi Peserta</h3>
                    <p class="section-subtitle">
                        Identitas peserta yang terdaftar dalam sistem.
                    </p>
                </div>

                <span class="section-index">01 / PROFIL</span>
            </div>

            <div class="participant-panel">
                <div class="participant-grid">

                    <div class="info-item">
                        <span class="info-label">Nama Lengkap</span>
                        <strong class="info-value">
                            {{ $participant->name ?: '-' }}
                        </strong>
                    </div>

                    <div class="info-item">
                        <span class="info-label">NIK</span>
                        <strong class="info-value">
                            {{ $participant->nik ?: '-' }}
                        </strong>
                    </div>

                    <div class="info-item">
                        <span class="info-label">NIP</span>
                        <strong class="info-value">
                            {{ $participant->nip ?: '-' }}
                        </strong>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Instansi</span>
                        <strong class="info-value">
                            {{ $participant->institution ?: '-' }}
                        </strong>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Kabupaten/Kota/Mitra</span>
                        <strong class="info-value">
                            {{ $participant->district ?: '-' }}
                        </strong>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Profesi</span>
                        <strong class="info-value">
                            {{ $participant->profession ?: '-' }}
                        </strong>
                    </div>

                </div>
            </div>

        </section>

        <!-- RIWAYAT PELATIHAN -->
        <section class="section-block">

            <div class="section-heading">
                <div>
                    <h3 class="section-title">Daftar Riwayat Pelatihan</h3>
                    <p class="section-subtitle">
                        Seluruh pelatihan yang pernah diikuti peserta.
                    </p>
                </div>

                <span class="section-index">02 / RIWAYAT</span>
            </div>

            <!-- RINGKASAN -->
            <div class="summary-layout">

                <div class="summary-card primary">
                    <div class="summary-info">
                        <span class="summary-label">
                            Total Riwayat Pelatihan
                        </span>

                        <strong class="summary-number">
                            {{ $participant->histories->count() }}
                        </strong>

                        <span class="summary-note">
                            Jumlah keikutsertaan yang tercatat
                        </span>
                    </div>

                    <div class="summary-symbol" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21z"/>
                            <path d="M4 5.5v15"/>
                            <path d="M8 7h8M8 11h8"/>
                        </svg>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-info">
                        <span class="summary-label">
                            Status Data Peserta
                        </span>

                        <strong class="summary-number"
                                style="font-size:30px">
                            Terdaftar
                        </strong>

                        <span class="summary-note">
                            Identitas ditemukan dalam database
                        </span>
                    </div>

                    <div class="summary-symbol" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m8 12 2.5 2.5L16 9"/>
                        </svg>
                    </div>
                </div>

            </div>

            <!-- TABEL RIWAYAT -->
            <div class="table-panel">

                <div class="table-topbar">
                    <strong>Riwayat Keikutsertaan</strong>

                    <span class="table-count">
                        {{ $participant->histories->count() }} Data
                    </span>
                </div>

                @if ($participant->histories->isEmpty())

                    <div class="empty-state">
                        <strong>Belum Ada Riwayat Pelatihan</strong>
                        Data keikutsertaan pelatihan peserta ini
                        belum tercatat di sistem.
                    </div>

                @else

                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pelatihan</th>
                                    <th>Angkatan</th>
                                    <th>Sumber Dana</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($participant->histories as $history)
                                    <tr>
                                        <td class="number-cell"
                                            data-label="No.">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td data-label="Nama Pelatihan">
                                            <span class="training-name">
                                                {{ $history->training?->name
                                                    ?? 'Nama pelatihan tidak tersedia' }}
                                            </span>
                                        </td>

                                        <td data-label="Angkatan">
                                            {{ $history->training?->batch_number ?: '-' }}
                                        </td>

                                        <td data-label="Sumber Dana">
                                            {{ $history->training?->funding_source ?: '-' }}
                                        </td>

                                        <td data-label="Status">
                                            @if ($history->status)
                                                <span class="status">
                                                    {{ $history->status }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @endif

            </div>

        </section>

        <!-- NAVIGASI -->
        <div class="bottom-actions">

            <p class="bottom-note">
                Informasi ini ditampilkan berdasarkan data
                yang tersimpan di sistem SIRAPIK.
            </p>

            <a href="{{ route('public.dashboard') }}"
               class="back-button">

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/>
                    <path d="M9 12h12"/>
                </svg>

                Kembali ke Dashboard Publik
            </a>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div>
            <div class="footer-brand">SIRAPIK</div>
            <p class="footer-text">
                Bapelkes Provinsi Kalimantan Selatan
            </p>
        </div>

        <div class="footer-year">
            &copy; {{ date('Y') }} SIRAPIK. Seluruh hak cipta dilindungi.
        </div>
    </footer>

</body>
</html>