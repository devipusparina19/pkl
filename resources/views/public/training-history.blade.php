<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#081B33">
    <meta name="description" content="Informasi riwayat pelatihan peserta SIRAPIK.">

    <title>Riwayat Pelatihan — SIRAPIK</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
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
            --ok-line: #B7E2CC;
            --font-display: "Sora", "Segoe UI", Arial, sans-serif;
            --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
            --ease: cubic-bezier(.2, .7, .2, 1);
            --pad: clamp(20px, 5vw, 80px);
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font-body);
            font-size: 16px;
            line-height: 1.65;
            color: var(--text);
            background: var(--ice);
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; }

        svg.i {
            width: 1em;
            height: 1em;
            flex: none;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        :focus-visible {
            outline: 3px solid var(--cyan);
            outline-offset: 3px;
            border-radius: 6px;
        }

        .skip {
            position: absolute;
            left: 12px;
            top: -60px;
            z-index: 100;
            padding: 10px 16px;
            border-radius: 8px;
            background: #fff;
            color: var(--navy);
            font-weight: 700;
            text-decoration: none;
            transition: top .2s;
        }

        .skip:focus { top: 12px; }

        /* Navbar */
        .nav {
            position: sticky;
            top: 0;
            z-index: 50;
            transition: background .3s, box-shadow .3s;
        }

        .nav.is-scrolled {
            background: rgba(8, 27, 51, .92);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 30px rgba(8, 27, 51, .25);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px var(--pad);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            color: #fff;
            text-decoration: none;
        }

        .nav-logo {
            display: grid;
            place-items: center;
            flex: none;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }

        .nav-logo img {
            width: 100%;
            height: 100%;
            padding: 4px;
            object-fit: contain;
        }

        .nav-name {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 19px;
            letter-spacing: .02em;
            line-height: 1.1;
        }

        .nav-sub {
            display: block;
            font-family: var(--font-body);
            font-weight: 500;
            font-size: 12px;
            color: rgba(255,255,255,.75);
            letter-spacing: 0;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 999px;
            color: rgba(255,255,255,.82);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: color .2s, background .2s;
        }

        .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.1);
        }

        .nav-link[aria-current="page"] {
            color: #fff;
            background: rgba(52,120,246,.35);
        }

        .nav-link[aria-current="page"]::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 2px;
            width: 18px;
            height: 3px;
            border-radius: 3px;
            background: var(--cyan);
            transform: translateX(-50%);
        }

        .progress {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 60;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--cyan), var(--electric));
            transform: scaleX(0);
            transform-origin: left;
        }

        /* Hero */
        .hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            margin-top: -78px;
            padding: calc(78px + clamp(40px, 7vw, 88px)) var(--pad) clamp(48px, 7vw, 88px);
            color: #fff;
            background: var(--navy);
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(120deg, rgba(8,27,51,.96) 0%, rgba(11,37,69,.9) 55%, rgba(36,87,214,.55) 100%),
                url("{{ asset('images/login-bapelkes.jpg') }}") center / cover no-repeat;
        }

        .orb {
            position: absolute;
            z-index: -1;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(60px);
            animation: drift 24s ease-in-out infinite alternate;
        }

        .orb.a {
            width: 380px;
            height: 380px;
            left: -100px;
            top: 40px;
            background: var(--royal);
            opacity: .5;
        }

        .orb.b {
            width: 300px;
            height: 300px;
            right: 6%;
            bottom: -60px;
            background: var(--cyan);
            opacity: .2;
            animation-duration: 30s;
        }

        @keyframes drift {
            to { transform: translate3d(50px, -36px, 0); }
        }

        .hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, .8fr);
            align-items: end;
            gap: clamp(28px, 5vw, 72px);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px 8px 10px;
            margin-bottom: 22px;
            border: 1px solid rgba(105,214,162,.4);
            border-radius: 999px;
            background: rgba(105,214,162,.12);
            color: #C9F2DD;
            font-size: 13px;
            font-weight: 600;
        }

        .badge .i {
            font-size: 16px;
            color: #69D6A2;
        }

        .hero h1 {
            margin: 0 0 20px;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(2.4rem, 6.4vw, 4.8rem);
            line-height: 1.04;
            letter-spacing: -.035em;
        }

        .hero h1 span {
            display: block;
            color: var(--cyan);
        }

        .hero p {
            max-width: 560px;
            margin: 0;
            font-size: clamp(1rem, 1.5vw, 1.125rem);
            line-height: 1.8;
            color: rgba(255,255,255,.84);
        }

        .stat {
            position: relative;
            padding: 26px 28px;
            border-radius: 22px;
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(8px);
        }

        .stat-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            color: rgba(255,255,255,.8);
        }

        .stat-label .i {
            font-size: 20px;
            color: var(--cyan);
        }

        .stat-number {
            display: block;
            margin: 10px 0 4px;
            font-family: var(--font-display);
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            font-size: clamp(3.4rem, 8vw, 5.6rem);
            line-height: 1;
            letter-spacing: -.04em;
        }

        .stat-note {
            font-size: 13px;
            color: rgba(255,255,255,.72);
        }

        /* Main */
        main {
            padding: 0 var(--pad) clamp(48px, 6vw, 72px);
        }

        .section {
            padding-top: clamp(36px, 5vw, 60px);
        }

        .section-head { margin-bottom: 22px; }

        .section-head h2 {
            margin: 0 0 6px;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: clamp(1.5rem, 3.2vw, 2.1rem);
            letter-spacing: -.025em;
            color: var(--navy);
        }

        .section-head p {
            margin: 0;
            font-size: 15px;
            color: var(--slate);
        }

        .success {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-top: 28px;
            padding: 16px 20px;
            border: 1px solid var(--ok-line);
            border-left: 5px solid #1E9C6B;
            border-radius: 14px;
            background: var(--ok-bg);
            color: var(--ok);
        }

        .success .i {
            margin-top: 3px;
            font-size: 22px;
            color: #1E9C6B;
        }

        .success strong {
            display: block;
            font-size: 15px;
        }

        .success p {
            margin: 2px 0 0;
            font-size: 14px;
            color: #36765A;
        }

        /* Profile */
        .profile {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            column-gap: clamp(24px, 4vw, 56px);
            margin: 0;
            border-top: 2px solid var(--navy);
        }

        .profile > div {
            min-width: 0;
            padding: 18px 0;
            border-bottom: 1px solid var(--border);
        }

        .profile dt {
            margin: 0 0 4px;
            font-size: 13px;
            font-weight: 600;
            color: var(--slate);
        }

        .profile dd {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: var(--navy);
            overflow-wrap: anywhere;
        }

        /* Table */
        .panel {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 18px 44px rgba(8, 27, 51, .07);
        }

        .panel-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
        }

        .panel-top strong {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 600;
            color: var(--navy);
        }

        .count {
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--soft);
            color: var(--royal);
            font-size: 13px;
            font-weight: 700;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 720px;
            border-collapse: collapse;
        }

        th, td {
            padding: 17px 22px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #EBF0F6;
        }

        th {
            background: var(--ice);
            color: #55677F;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        td {
            font-size: 15px;
            color: #33465C;
        }

        tbody tr { transition: background .2s; }
        tbody tr:hover { background: #F7FAFF; }
        tbody tr:last-child td { border-bottom: 0; }

        .num {
            width: 64px;
            font-weight: 700;
            color: #7C8DA3;
            font-variant-numeric: tabular-nums;
        }

        .t-name {
            font-weight: 700;
            color: var(--navy);
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 12px;
            border-radius: 8px;
            background: var(--ok-bg);
            color: var(--ok);
            font-size: 13px;
            font-weight: 700;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .status::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #1E9C6B;
        }

        .empty {
            padding: 48px 24px;
            text-align: center;
            color: var(--slate);
        }

        .empty .i {
            display: block;
            margin: 0 auto 12px;
            font-size: 34px;
            color: var(--royal);
        }

        .empty strong {
            display: block;
            margin-bottom: 4px;
            font-family: var(--font-display);
            font-size: 17px;
            color: var(--navy);
        }

        /* Actions */
        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 40px;
            padding-top: 26px;
            border-top: 1px solid var(--border);
        }

        .actions p {
            margin: 0;
            max-width: 520px;
            font-size: 14px;
            color: var(--slate);
        }

        .btn-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 52px;
            padding: 12px 22px;
            border-radius: 14px;
            border: 1.5px solid transparent;
            font-family: var(--font-display);
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: transform .2s var(--ease), box-shadow .2s, background .2s;
        }

        .btn .i {
            font-size: 18px;
            transition: transform .2s var(--ease);
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(110deg, var(--royal), var(--electric));
            box-shadow: 0 12px 26px rgba(36,87,214,.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(36,87,214,.4);
        }

        .btn-primary:hover .i { transform: translateX(-4px); }
        .btn-primary:active { transform: translateY(0); }

        .btn-secondary {
            color: var(--royal);
            background: #fff;
            border-color: #C5D6F5;
        }

        .btn-secondary:hover {
            background: var(--soft);
            transform: translateY(-2px);
        }

        .btn-print {
            color: #fff;
            background: #0F6B4A;
            border-color: #0F6B4A;
            box-shadow: 0 10px 22px rgba(15,107,74,.2);
        }

        .btn-print:hover {
            background: #0B563B;
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 24px var(--pad);
            background: var(--navy);
            color: rgba(255,255,255,.8);
        }

        .footer b {
            display: block;
            font-family: var(--font-display);
            color: #fff;
            font-size: 16px;
        }

        .footer span { font-size: 13px; }

        /* Entrance */
        .rise {
            opacity: 0;
            transform: translate3d(0, 22px, 0);
            animation: rise .8s var(--ease) forwards;
        }

        .rise.d1 { animation-delay: .1s; }
        .rise.d2 { animation-delay: .2s; }
        .rise.d3 { animation-delay: .32s; }

        @keyframes rise {
            to { opacity: 1; transform: none; }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .hero-grid {
                grid-template-columns: 1fr;
                align-items: start;
            }

            .profile {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .nav-sub { display: none; }
            .nav-link .t { display: none; }
            .profile { grid-template-columns: 1fr; }
            .panel-top { padding: 16px; }
            .table-wrap { overflow: visible; }

            table {
                display: block;
                min-width: 0;
            }

            thead { display: none; }

            tbody {
                display: grid;
                gap: 12px;
                padding: 12px;
            }

            tbody tr {
                display: block;
                overflow: hidden;
                border: 1px solid var(--border);
                border-radius: 14px;
            }

            tbody td {
                display: grid;
                grid-template-columns: 112px minmax(0, 1fr);
                gap: 10px;
                padding: 12px 14px;
                font-size: 14px;
                border-bottom: 1px solid #EDF1F5;
            }

            tbody td::before {
                content: attr(data-label);
                font-size: 13px;
                font-weight: 600;
                color: var(--slate);
            }

            tbody tr:last-child td { border-bottom: 1px solid #EDF1F5; }
            tbody tr td:last-child { border-bottom: 0; }
            .num { width: auto; }

            .actions {
                align-items: stretch;
                flex-direction: column;
            }

            .btn-row, .btn { width: 100%; }
            .footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }

            *, *::before, *::after {
                animation: none !important;
                transition-duration: .01ms !important;
            }

            .rise {
                opacity: 1;
                transform: none;
            }
        }

        /* Cetak / Ekspor PDF */
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        @media print {
            html, body {
                width: 100% !important;
                min-height: 0 !important;
                background: #fff !important;
                color: #14243A !important;
                font-size: 10pt !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .progress,
            .skip,
            .nav,
            .orb,
            .actions,
            .footer {
                display: none !important;
            }

            .hero {
                margin: 0 !important;
                padding: 22px 25px !important;
                color: #fff !important;
                background: #081B33 !important;
                break-inside: avoid;
            }

            .hero::before {
                display: none !important;
            }

            .hero-grid {
                grid-template-columns: 1.2fr .8fr !important;
                gap: 20px !important;
                align-items: center !important;
            }

            .hero h1 {
                font-size: 25pt !important;
                margin-bottom: 10px !important;
            }

            .hero p {
                font-size: 9pt !important;
            }

            .badge {
                margin-bottom: 10px !important;
            }

            .stat {
                padding: 18px !important;
                background: #0B2545 !important;
                border-color: rgba(255,255,255,.3) !important;
            }

            .stat-number {
                font-size: 38pt !important;
            }

            .rise {
                opacity: 1 !important;
                transform: none !important;
                animation: none !important;
            }

            main {
                padding: 0 !important;
            }

            .success {
                margin-top: 15px !important;
                padding: 10px 14px !important;
                break-inside: avoid;
            }

            .section {
                padding-top: 20px !important;
            }

            .section-head {
                margin-bottom: 12px !important;
            }

            .section-head h2 {
                font-size: 16pt !important;
            }

            .section-head p {
                font-size: 9pt !important;
            }

            .profile {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                column-gap: 25px !important;
            }

            .profile > div {
                padding: 9px 0 !important;
                break-inside: avoid;
            }

            .profile dt {
                font-size: 9pt !important;
            }

            .profile dd {
                font-size: 10pt !important;
            }

            .panel {
                overflow: visible !important;
                box-shadow: none !important;
                border: 1px solid #D5DDE7 !important;
                border-radius: 8px !important;
            }

            .panel-top {
                padding: 10px 14px !important;
            }

            .table-wrap {
                overflow: visible !important;
            }

            table {
                display: table !important;
                width: 100% !important;
                min-width: 0 !important;
                border-collapse: collapse !important;
            }

            thead {
                display: table-header-group !important;
            }

            tbody {
                display: table-row-group !important;
                padding: 0 !important;
            }

            tbody tr {
                display: table-row !important;
                border: 0 !important;
                border-radius: 0 !important;
                break-inside: avoid;
            }

            th, td {
                display: table-cell !important;
                padding: 9px 10px !important;
                font-size: 9pt !important;
                border: 1px solid #D5DDE7 !important;
            }

            th {
                background: #EAF2FF !important;
                color: #14243A !important;
            }

            tbody td::before {
                display: none !important;
                content: none !important;
            }

            .status {
                background: #E8F6EF !important;
                color: #0F6B4A !important;
            }

            .section-head,
            .panel-top {
                break-after: avoid;
            }

            .empty {
                padding: 25px !important;
            }

            a {
                text-decoration: none !important;
                color: inherit !important;
            }
        }
    </style>
</head>

<body>
<div class="progress" id="progress" aria-hidden="true"></div>

<a class="skip" href="#riwayat">Lewati ke riwayat pelatihan</a>

<header class="nav" id="nav">
    <div class="nav-inner">
        <a class="nav-brand" href="{{ route('public.dashboard') }}" aria-label="SIRAPIK, kembali ke dashboard publik">
            <span class="nav-logo">
                <img src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                     alt="Logo Provinsi Kalimantan Selatan" width="46" height="46">
            </span>

            <span class="nav-name">
                SIRAPIK
                <span class="nav-sub">Bapelkes Provinsi Kalimantan Selatan</span>
            </span>
        </a>

        <nav class="nav-links" aria-label="Navigasi utama">
            <a class="nav-link" href="{{ route('public.dashboard') }}">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="3" y="3" width="7" height="9"/>
                    <rect x="14" y="3" width="7" height="5"/>
                    <rect x="14" y="12" width="7" height="9"/>
                    <rect x="3" y="16" width="7" height="5"/>
                </svg>
                <span class="t">Dashboard</span>
            </a>

            <a class="nav-link" href="{{ route('public.training-history') }}" aria-current="page">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                <span class="t">Riwayat pelatihan</span>
            </a>
        </nav>
    </div>
</header>

<section class="hero" aria-labelledby="hero-title">
    <span class="orb a" aria-hidden="true"></span>
    <span class="orb b" aria-hidden="true"></span>

    <div class="hero-grid">
        <div>
            <span class="badge rise">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
                Data peserta ditemukan
            </span>

            <h1 id="hero-title" class="rise d1">
                Riwayat pelatihan Anda
                <span>tercatat di SIRAPIK.</span>
            </h1>

            <p class="rise d2">
                Identitas peserta dan seluruh pelatihan yang pernah diikuti
                ditampilkan berdasarkan data yang tersimpan di sistem.
            </p>
        </div>

        <div class="stat rise d3">
            <span class="stat-label">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21z"/>
                    <path d="M4 5.5v15"/>
                    <path d="M8 7h8M8 11h8"/>
                </svg>
                Total riwayat pelatihan
            </span>

            <strong class="stat-number" id="counter"
                    data-target="{{ $participant->histories->count() }}">
                {{ $participant->histories->count() }}
            </strong>

            <span class="stat-note">Jumlah keikutsertaan yang tercatat</span>
        </div>
    </div>
</section>

<main>
    <div class="success" role="status">
        <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/>
            <path d="m9 12 2 2 4-4"/>
        </svg>

        <div>
            <strong>Data peserta berhasil ditemukan.</strong>
            <p>NIK yang dimasukkan cocok dengan data peserta yang terdaftar di SIRAPIK.</p>
        </div>
    </div>

    <section class="section" aria-labelledby="profil-title">
        <div class="section-head">
            <h2 id="profil-title">Informasi peserta</h2>
            <p>Identitas peserta yang terdaftar dalam sistem.</p>
        </div>

        <dl class="profile">
            <div>
                <dt>Nama lengkap</dt>
                <dd>{{ $participant->name ?: '-' }}</dd>
            </div>

            <div>
                <dt>NIK</dt>
                <dd>{{ $participant->nik ?: '-' }}</dd>
            </div>

            <div>
                <dt>NIP</dt>
                <dd>{{ $participant->nip ?: '-' }}</dd>
            </div>

            <div>
                <dt>Instansi</dt>
                <dd>{{ $participant->institution ?: '-' }}</dd>
            </div>

            <div>
                <dt>Kabupaten/Kota/Mitra</dt>
                <dd>{{ $participant->district ?: '-' }}</dd>
            </div>

            <div>
                <dt>Profesi</dt>
                <dd>{{ $participant->profession ?: '-' }}</dd>
            </div>
        </dl>
    </section>

    <section class="section" id="riwayat" aria-labelledby="riwayat-title">
        <div class="section-head">
            <h2 id="riwayat-title">Daftar riwayat pelatihan</h2>
            <p>Seluruh pelatihan yang pernah diikuti peserta.</p>
        </div>

        <div class="panel">
            <div class="panel-top">
                <strong>Riwayat keikutsertaan</strong>
                <span class="count">{{ $participant->histories->count() }} data</span>
            </div>

            @if ($participant->histories->isEmpty())
                <div class="empty">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M22 12h-6l-2 3h-4l-2-3H2"/>
                        <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                    </svg>

                    <strong>Belum ada riwayat pelatihan</strong>
                    Data keikutsertaan pelatihan peserta ini belum tercatat di sistem.
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <caption style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">
                            Daftar riwayat pelatihan peserta
                        </caption>

                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama Pelatihan</th>
                                <th scope="col">Angkatan</th>
                                <th scope="col">Sumber Dana</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($participant->histories as $history)
                                <tr>
                                    <td class="num" data-label="No.">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td data-label="Nama Pelatihan">
                                        <span class="t-name">
                                            {{ $history->training?->name ?? 'Nama pelatihan tidak tersedia' }}
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
                                            <span class="status">{{ $history->status }}</span>
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

    <div class="actions">
        <p>
            Informasi ini ditampilkan berdasarkan data yang tersimpan di sistem SIRAPIK.
        </p>

        <div class="btn-row">
            <button type="button" class="btn btn-print" onclick="window.print()">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 9V2h12v7"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <path d="M6 14h12v8H6z"/>
                </svg>
                Ekspor PDF
            </button>

            <a class="btn btn-secondary" href="{{ route('public.training-history') }}">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                Cari Data Lain
            </a>

            <a class="btn btn-primary" href="{{ route('public.dashboard') }}">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
                Kembali ke Dashboard Publik
            </a>
        </div>
    </div>
</main>

<footer class="footer">
    <div>
        <b>SIRAPIK</b>
        <span>Bapelkes Provinsi Kalimantan Selatan</span>
    </div>

    <span>&copy; {{ date('Y') }} SIRAPIK. Seluruh hak cipta dilindungi.</span>
</footer>

<script>
    (function () {
        var nav = document.getElementById('nav');
        var bar = document.getElementById('progress');
        var ticking = false;

        function update() {
            var y = window.scrollY || document.documentElement.scrollTop;
            var max = document.documentElement.scrollHeight - window.innerHeight;

            nav.classList.toggle('is-scrolled', y > 12);

            bar.style.transform = 'scaleX(' +
                (max > 0 ? Math.min(y / max, 1) : 0) + ')';

            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(update);
            }
        }, { passive: true });

        update();

        // Counter total riwayat
        var el = document.getElementById('counter');
        var target = parseInt(el.getAttribute('data-target'), 10) || 0;
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!reduce && target > 0) {
            var start = null;
            var dur = 900;

            el.textContent = '0';

            (function step(t) {
                if (start === null) {
                    start = t;
                }

                var p = Math.min((t - start) / dur, 1);

                el.textContent = Math.round(
                    target * (1 - Math.pow(1 - p, 3))
                );

                if (p < 1) {
                    requestAnimationFrame(step);
                }
            })(performance.now());
        }
    })();
</script>
</body>
</html>