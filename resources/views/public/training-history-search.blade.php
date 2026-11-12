<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#081b33">

    <title>Cari Riwayat Pelatihan — ARTA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    {{-- sirapik.css sengaja tidak dimuat: halaman ini self-contained (sama seperti halaman login) --}}

    <style>
        /* Reset defensif agar header/footer tidak terpengaruh CSS global */
        :where(.lookup-layout) :is(header, footer) {
            position: static; width: auto; max-width: none; margin: 0; padding: 0;
            border: 0; background: none; box-shadow: none; backdrop-filter: none; transform: none;
        }

        :root {
            --navy: #081b33;
            --midnight: #0b2545;
            --royal: #2457d6;
            --electric: #3478f6;
            --soft: #eaf2ff;
            --ice: #f3f7fc;
            --slate: #64748b;
            --ink: #14243a;
            --border: #e1e9f2;
            --cyan: #38c6e8;
            --red: #991b1b;
            --red-soft: #fef2f2;
            --red-line: #fecaca;

            --f-display: 'Sora', system-ui, sans-serif;
            --f-body: 'Plus Jakarta Sans', system-ui, sans-serif;
            --r: 14px;
            --ease: cubic-bezier(.2, .7, .3, 1);
        }

        *, *::before, *::after { box-sizing: border-box; }
        [hidden] { display: none !important; }
        html { min-height: 100%; -webkit-text-size-adjust: 100%; }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            margin: 0;
            overflow-x: hidden;
            color: var(--ink);
            background: #fff;
            font-family: var(--f-body);
            font-size: 16px;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        ::selection { color: #fff; background: var(--royal); }
        :focus-visible { outline: 3px solid rgba(52,120,246,.55); outline-offset: 3px; border-radius: 8px; }

        svg.i {
            width: 1em; height: 1em; flex: none;
            fill: none; stroke: currentColor;
            stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        }

        .skip {
            position: absolute; left: 12px; top: -60px; z-index: 100;
            padding: 10px 16px; border-radius: 8px;
            background: #fff; color: var(--navy); font-weight: 700; transition: top .2s;
        }
        .skip:focus { top: 12px; }

        .top-progress {
            position: fixed; inset: 0 0 auto 0; z-index: 50; height: 3px;
            background: linear-gradient(90deg, var(--cyan), var(--electric));
            transform: scaleX(0); transform-origin: left; opacity: 0;
        }
        .top-progress.run { opacity: 1; transform: scaleX(.85); transition: transform 6s cubic-bezier(.1,.8,.2,1), opacity .2s; }

        /* Layout penuh layar */
        .lookup-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
        }

        /* =====================================================
           BRAND PANEL
        ===================================================== */
        .brand {
            position: relative; isolation: isolate;
            display: flex; flex-direction: column; justify-content: space-between;
            min-width: 0; padding: 40px clamp(28px, 4.5vw, 72px);
            overflow: hidden; color: #fff; background: var(--navy);
        }
        .brand-bg {
            position: absolute; inset: 0; z-index: -4;
            background:
                radial-gradient(900px 500px at 85% 10%, rgba(52,120,246,.28), transparent 60%),
                linear-gradient(160deg, rgba(5,22,42,.97) 0%, rgba(8,34,63,.92) 55%, rgba(9,39,70,.85) 100%),
                url("{{ asset('images/login-bapelkes.jpg') }}") center / cover no-repeat;
        }
        .brand-grid {
            position: absolute; inset: 0; z-index: -3; opacity: .12;
            background-image:
                linear-gradient(rgba(255,255,255,.5) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.5) 1px, transparent 1px);
            background-size: 64px 64px;
            mask-image: linear-gradient(160deg, #000 10%, transparent 80%);
            -webkit-mask-image: linear-gradient(160deg, #000 10%, transparent 80%);
            animation: grid-drift 40s linear infinite;
        }
        @keyframes grid-drift { to { background-position: 64px 64px; } }

        .orb { position: absolute; z-index: -2; border-radius: 50%; filter: blur(60px); will-change: transform; }
        .orb-a { top: -120px; right: -100px; width: 420px; height: 420px; background: rgba(52,120,246,.35); animation: float-a 16s ease-in-out infinite; }
        .orb-b { bottom: -140px; left: -80px; width: 380px; height: 380px; background: rgba(56,198,232,.2); animation: float-b 20s ease-in-out infinite; }
        @keyframes float-a { 50% { transform: translate3d(-50px, 60px, 0); } }
        @keyframes float-b { 50% { transform: translate3d(70px, -40px, 0); } }

        .brand-orbit {
            position: absolute; z-index: -1; right: -180px; bottom: -180px;
            width: 560px; height: 560px; border: 1px solid rgba(130,183,255,.22); border-radius: 50%;
            box-shadow: 0 0 0 60px rgba(130,183,255,.04), 0 0 0 120px rgba(130,183,255,.025);
            animation: spin 90s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .brand-top { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .brand-id { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .brand-logo { flex: 0 0 52px; width: 52px; height: 52px; padding: 6px; background: #fff; border-radius: 15px; }
        .brand-logo img { display: block; width: 100%; height: 100%; object-fit: contain; }
        .brand-name strong { display: block; font-family: var(--f-display); font-size: 20px; font-weight: 800; letter-spacing: -.03em; line-height: 1.2; }
        .brand-name span { display: block; margin-top: 2px; color: rgba(255,255,255,.7); font-size: 13px; }

        .nav-links { display: flex; align-items: center; gap: 6px; }
        .nav-link {
            position: relative; display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 15px; border-radius: 999px;
            color: rgba(255,255,255,.82); font-size: 14px; font-weight: 600;
            transition: color .2s, background .2s;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,.1); }
        .nav-link[aria-current="page"] { color: #fff; background: rgba(52,120,246,.4); }

        .brand-content { margin: 44px 0; }
        .brand-label {
            display: inline-flex; align-items: center; gap: 10px; margin-bottom: 22px;
            padding: 9px 16px; border: 1px solid rgba(255,255,255,.2); border-radius: 999px;
            background: rgba(255,255,255,.07); color: #bcd8ff; font-size: 14px; font-weight: 600;
        }
        .brand-label i { width: 9px; height: 9px; border-radius: 50%; background: #54d69b; box-shadow: 0 0 0 4px rgba(84,214,155,.18); animation: pulse 2.6s ease-in-out infinite; }
        @keyframes pulse { 50% { box-shadow: 0 0 0 8px rgba(84,214,155,0); } }

        .brand-content h1 {
            margin: 0;
            font-family: var(--f-display);
            font-size: clamp(40px, 6.2vw, 92px);
            font-weight: 800; letter-spacing: -.05em; line-height: 1;
        }
        .brand-content h1 span { display: block; margin-top: 4px; color: #82b7ff; }

        .brand-content > p {
            max-width: 50ch; margin: 24px 0 0;
            color: rgba(255,255,255,.78);
            font-size: clamp(15px, 1.25vw, 17px); line-height: 1.8;
        }
        .brand-content > p b { color: #fff; }

        /* ECG */
        .ecg {
            width: 100%; max-width: 520px; height: 60px; margin-top: 26px; overflow: hidden;
            mask-image: linear-gradient(90deg, transparent, #000 15%, #000 85%, transparent);
            -webkit-mask-image: linear-gradient(90deg, transparent, #000 15%, #000 85%, transparent);
        }
        .ecg svg { display: block; width: 200%; height: 100%; animation: ecg-run 7s linear infinite; }
        @keyframes ecg-run { to { transform: translateX(-50%); } }

        /* Timeline langkah */
        .steps { max-width: 520px; margin: 30px 0 0; padding: 0; list-style: none; }
        .steps li { position: relative; display: flex; gap: 16px; align-items: flex-start; padding-bottom: 20px; }
        .steps li:last-child { padding-bottom: 0; }
        .steps li:not(:last-child)::before {
            content: ""; position: absolute; left: 19px; top: 42px; bottom: 2px; width: 2px;
            background: linear-gradient(var(--cyan), rgba(56,198,232,.08));
        }
        .step-dot {
            display: grid; place-items: center; flex: none; width: 40px; height: 40px; border-radius: 12px;
            background: rgba(56,198,232,.14); border: 1px solid rgba(56,198,232,.4);
            color: var(--cyan); font-size: 18px;
        }
        .steps strong { display: block; font-family: var(--f-display); font-size: 15px; font-weight: 600; }
        .steps span.d { font-size: 14px; color: rgba(255,255,255,.72); line-height: 1.6; }

        .brand-foot { display: flex; align-items: flex-start; gap: 14px; padding-top: 22px; border-top: 1px solid rgba(255,255,255,.18); }
        .brand-foot .ic {
            flex: 0 0 44px; width: 44px; height: 44px; display: grid; place-items: center;
            border: 1px solid rgba(255,255,255,.16); border-radius: 14px;
            background: rgba(255,255,255,.08); color: var(--cyan); font-size: 22px;
        }
        .brand-foot p { margin: 0; color: rgba(255,255,255,.78); font-size: 14px; line-height: 1.75; }

        /* =====================================================
           FORM PANEL
        ===================================================== */
        .panel {
            display: flex; flex-direction: column; justify-content: center;
            min-width: 0; padding: 48px clamp(28px, 5vw, 80px);
            background: radial-gradient(600px 300px at 100% 0%, var(--soft), transparent 70%), #fff;
        }
        .panel-inner { width: 100%; max-width: 560px; margin-inline: auto; }

        .reveal { opacity: 0; transform: translateY(18px); animation: rise .7s var(--ease) forwards; animation-delay: calc(var(--i, 0) * 90ms + 80ms); }
        @keyframes rise { to { opacity: 1; transform: none; } }

        .panel-head { margin-bottom: 30px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 12px; margin-bottom: 14px; color: var(--royal); font-size: 15px; font-weight: 700; }
        .eyebrow::before { content: ""; width: 32px; height: 2px; background: var(--cyan); }
        .panel-head h2 {
            margin: 0 0 12px; color: var(--navy); font-family: var(--f-display);
            font-size: clamp(32px, 3.8vw, 48px); font-weight: 800; letter-spacing: -.045em; line-height: 1.1;
        }
        .panel-head h2 em { font-style: normal; color: var(--royal); }
        .panel-head p { margin: 0; color: var(--slate); font-size: 16px; line-height: 1.75; }

        .alert {
            display: flex; gap: 12px; margin-bottom: 22px; padding: 15px 18px;
            border: 1px solid var(--red-line); border-radius: var(--r);
            background: var(--red-soft); color: var(--red);
            font-size: 14.5px; line-height: 1.7; overflow-wrap: anywhere;
            animation: rise .4s var(--ease) both;
        }
        .alert > .i { margin-top: 3px; font-size: 18px; }
        .alert ul { margin: 4px 0 0; padding-left: 20px; }

        .field { margin-bottom: 22px; }
        .field label { display: block; margin-bottom: 9px; color: #40536a; font-size: 14px; font-weight: 700; }
        .control { position: relative; display: flex; align-items: center; }
        .control > .i {
            position: absolute; left: 16px; z-index: 2; font-size: 20px;
            color: #8394aa; pointer-events: none; transition: color .25s;
        }
        .control:focus-within > .i { color: var(--royal); }
        .form-control {
            display: block; width: 100%; height: 58px; padding: 0 16px 0 48px;
            border: 1.5px solid var(--border); border-radius: var(--r); outline: none;
            background: #fff; color: var(--ink); font-size: 16px;
            transition: border-color .25s, box-shadow .25s;
        }
        .form-control::placeholder { color: #8998ab; }
        .form-control:hover { border-color: #a6bddb; }
        .form-control:focus { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.12); }
        .help { display: block; margin-top: 8px; color: var(--slate); font-size: 13px; line-height: 1.6; }

        .btn-submit {
            position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: center; gap: 12px;
            width: 100%; min-height: 60px; margin-top: 6px; padding: 12px 22px;
            border: 0; border-radius: 999px; background: var(--royal); color: #fff;
            font-size: 16px; font-weight: 700; cursor: pointer;
            transition: background .25s, transform .25s var(--ease), box-shadow .25s;
        }
        .btn-submit::after {
            content: ""; position: absolute; inset: 0 auto 0 0; width: 40%;
            background: linear-gradient(100deg, transparent, rgba(255,255,255,.28), transparent);
            transform: translateX(-160%) skewX(-20deg); animation: shine 4.5s ease-in-out infinite;
        }
        @keyframes shine { 60%, 100% { transform: translateX(380%) skewX(-20deg); } }
        .btn-submit .i { font-size: 20px; transition: transform .25s var(--ease); }
        .btn-submit:hover { background: var(--navy); transform: translateY(-2px); box-shadow: 0 14px 30px rgba(36,87,214,.28); }
        .btn-submit:hover .i { transform: translateX(4px); }
        .btn-submit:active { transform: scale(.98); }
        .btn-submit[aria-busy="true"] { background: #7f9bd8; box-shadow: none; transform: none; cursor: wait; pointer-events: none; }
        .btn-submit[aria-busy="true"]::after { animation: none; opacity: 0; }

        .spinner { width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .8s linear infinite; }

        .info {
            display: flex; gap: 14px; align-items: flex-start; margin-top: 28px; padding: 16px 18px;
            border: 1px solid var(--border); border-radius: var(--r); background: var(--ice);
        }
        .info .ic { display: grid; place-items: center; flex: none; width: 38px; height: 38px; border-radius: 12px; background: var(--soft); color: var(--royal); font-size: 20px; }
        .info h3 { margin: 0 0 2px; color: var(--navy); font-family: var(--f-display); font-size: 14.5px; font-weight: 600; }
        .info p { margin: 0; color: var(--slate); font-size: 13.5px; line-height: 1.7; }

        .panel-bottom { margin-top: 28px; padding-top: 22px; border-top: 1px solid var(--border); text-align: center; }
        .back {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 4px;
            color: var(--royal); font-size: 15px; font-weight: 700; transition: color .25s;
        }
        .back .i { font-size: 18px; transition: transform .25s var(--ease); }
        .back:hover { color: var(--navy); }
        .back:hover .i { transform: translateX(-4px); }
        .panel-bottom p { margin: 10px 0 0; color: var(--slate); font-size: 13px; line-height: 1.6; }

        /* =====================================================
           RESPONSIVE
        ===================================================== */
        @media (max-width: 1000px) {
            .lookup-layout { grid-template-columns: 1fr; }
            .brand { padding: 28px clamp(20px, 5vw, 40px) 32px; }
            .brand-content { margin: 30px 0 4px; }
            .brand-content h1 { font-size: clamp(38px, 9vw, 72px); }
            .steps, .brand-foot { display: none; }
            .panel { padding: 36px clamp(20px, 5vw, 40px) 48px; }
            .panel-inner { max-width: none; }
        }
        @media (max-width: 560px) {
            .brand-logo { flex-basis: 46px; width: 46px; height: 46px; border-radius: 13px; }
            .nav-link .t { display: none; }
            .nav-link { padding: 10px 12px; }
            .brand-label { margin-bottom: 16px; }
            .ecg { height: 48px; margin-top: 20px; }
            .brand-orbit { right: -260px; bottom: -260px; }
            .panel-head { margin-bottom: 24px; }
            .panel-head p { font-size: 15px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; scroll-behavior: auto !important; transition-duration: .01ms !important; }
            .reveal { opacity: 1; transform: none; }
        }
    </style>
</head>

<body>
<div class="top-progress" id="topProgress" aria-hidden="true"></div>
<a class="skip" href="#form">Lewati ke formulir</a>

<main class="lookup-layout">

    {{-- BRAND --}}
    <section class="brand" id="brandPanel">
        <div class="brand-bg" aria-hidden="true"></div>
        <div class="brand-grid" aria-hidden="true"></div>
        <div class="orb orb-a" aria-hidden="true" data-depth="30"></div>
        <div class="orb orb-b" aria-hidden="true" data-depth="-24"></div>
        <div class="brand-orbit" aria-hidden="true"></div>

        <div class="brand-top reveal" style="--i:0">
            <a class="brand-id" href="{{ route('public.dashboard') }}" aria-label="ARTA, kembali ke dashboard publik">
                <span class="brand-logo">
                    <img src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                         alt="Logo Provinsi Kalimantan Selatan" width="52" height="52">
                </span>
                <span class="brand-name">
                    <strong>ARTA</strong>
                    <span>Bapelkes Provinsi Kalimantan Selatan</span>
                </span>
            </a>

            <nav class="nav-links" aria-label="Navigasi utama">
                <a class="nav-link" href="{{ route('public.dashboard') }}">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                    <span class="t">Dashboard</span>
                </a>
                <a class="nav-link" href="{{ route('public.training-history.lookup') }}" aria-current="page">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <span class="t">Riwayat pelatihan</span>
                </a>
            </nav>
        </div>

        <div class="brand-content">
            <span class="brand-label reveal" style="--i:1">
                <i></i> Layanan Informasi Publik
            </span>

            <h1 class="reveal" style="--i:2">
                Riwayat pelatihan Anda,
                <span>dalam satu pencarian.</span>
            </h1>

            <p class="reveal" style="--i:3">
                Periksa pelatihan yang tercatat atas nama Anda di <b>ARTA</b>
                (Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan).
                Masukkan NIK dan nama lengkap sesuai KTP, lalu lihat hasilnya.
            </p>

            <div class="ecg reveal" style="--i:4" aria-hidden="true">
                <svg viewBox="0 0 1200 70" preserveAspectRatio="none" fill="none"
                     stroke="#38c6e8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M0 35 H80 L100 35 L115 12 L135 58 L150 35 H260 L275 35 L290 22 L305 35 H600
                             M600 35 H680 L700 35 L715 12 L735 58 L750 35 H860 L875 35 L890 22 L905 35 H1200"/>
                </svg>
            </div>

            <ol class="steps reveal" style="--i:5">
                <li>
                    <span class="step-dot">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                    </span>
                    <div>
                        <strong>Siapkan KTP</strong>
                        <span class="d">NIK dan nama harus sama persis dengan yang tertera di KTP.</span>
                    </div>
                </li>
                <li>
                    <span class="step-dot">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.4 3.6a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                    </span>
                    <div>
                        <strong>Isi dua kolom</strong>
                        <span class="d">Tidak perlu membuat akun atau masuk terlebih dahulu.</span>
                    </div>
                </li>
                <li>
                    <span class="step-dot">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                    </span>
                    <div>
                        <strong>Lihat riwayat</strong>
                        <span class="d">Daftar pelatihan yang pernah Anda ikuti akan ditampilkan.</span>
                    </div>
                </li>
            </ol>
        </div>

        <div class="brand-foot reveal" style="--i:6">
            <span class="ic" aria-hidden="true">
                <svg class="i" viewBox="0 0 24 24"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/><path d="m9 12 2 2 4-4"/></svg>
            </span>
            <p>
                Data hanya ditampilkan jika NIK dan nama cocok dengan data peserta
                yang terdaftar. Jaga kerahasiaan identitas Anda.
            </p>
        </div>
    </section>

    {{-- FORM --}}
    <section class="panel" id="form" aria-labelledby="form-title">
        <div class="panel-inner">

            <div class="panel-head reveal" style="--i:0">
                <span class="eyebrow">Untuk Peserta Pelatihan</span>
                <h2 id="form-title">Cari riwayat <em>pelatihan</em></h2>
                <p>
                    Isi kedua kolom sesuai identitas pada KTP. NIK dan nama harus
                    cocok dengan data peserta yang terdaftar.
                </p>
            </div>

            @if (session('lookup_error'))
                <div class="alert" role="alert">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                    <div>
                        <strong>Data tidak ditemukan.</strong>
                        <div>{{ session('lookup_error') }}</div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert" role="alert">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    <div>
                        <strong>Periksa kembali data yang diisi.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form id="lookup-form" method="POST" action="{{ route('public.training-history.lookup') }}">
                @csrf

                <div class="field reveal" style="--i:1">
                    <label for="nik">NIK sesuai KTP</label>
                    <div class="control">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M6 10h4"/><path d="M6 14h8"/><circle cx="17" cy="10" r="1.5"/></svg>
                        <input
                            class="form-control"
                            type="text"
                            id="nik"
                            name="nik"
                            value="{{ old('nik') }}"
                            placeholder="Masukkan NIK sesuai KTP"
                            maxlength="30"
                            autocomplete="off"
                            inputmode="numeric"
                            aria-describedby="nik-help"
                            required
                        >
                    </div>
                    <span class="help" id="nik-help">
                        Masukkan NIK yang terdaftar pada data peserta ARTA.
                    </span>
                </div>

                <div class="field reveal" style="--i:2">
                    <label for="name">Nama lengkap sesuai KTP</label>
                    <div class="control">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input
                            class="form-control"
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap sesuai KTP"
                            maxlength="255"
                            autocomplete="name"
                            aria-describedby="name-help"
                            required
                        >
                    </div>
                    <span class="help" id="name-help">
                        Penulisan nama harus sesuai dengan nama yang tercatat pada data peserta.
                    </span>
                </div>

                <div class="reveal" style="--i:3">
                    <button class="btn-submit" type="submit" id="submit-btn">
                        <span class="spinner" id="submit-spinner" hidden aria-hidden="true"></span>
                        <span id="submit-label">Cari Riwayat Pelatihan</span>
                        <svg class="i" id="submit-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </div>
            </form>

            <div class="info reveal" style="--i:4">
                <span class="ic">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                </span>
                <div>
                    <h3>Dari mana data ini berasal?</h3>
                    <p>
                        Informasi ditampilkan berdasarkan data peserta yang tercatat di ARTA.
                        Pastikan NIK dan nama yang dimasukkan benar agar data dapat ditemukan.
                    </p>
                </div>
            </div>

            <div class="panel-bottom reveal" style="--i:5">
                <a class="back" href="{{ route('public.dashboard') }}">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Kembali ke Dashboard Publik
                </a>
                <p>&copy; {{ date('Y') }} ARTA — Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan · Bapelkes Provinsi Kalimantan Selatan</p>
            </div>

        </div>
    </section>
</main>

<script>
    (() => {
        const SCROLL_KEY = 'arta_lookup_scroll';

        /* Tetap di posisi scroll semula setelah submit / reload */
        try {
            const saved = sessionStorage.getItem(SCROLL_KEY);
            if (saved !== null) {
                sessionStorage.removeItem(SCROLL_KEY);
                window.scrollTo(0, parseInt(saved, 10) || 0);
            }
        } catch (e) {}

        /* Fokus awal tanpa melompat (hanya layar lebar) */
        if (window.matchMedia('(min-width: 1001px)').matches) {
            document.getElementById('nik').focus({ preventScroll: true });
        }

        const form = document.getElementById('lookup-form');
        const btn = document.getElementById('submit-btn');
        const label = document.getElementById('submit-label');
        const spinner = document.getElementById('submit-spinner');
        const arrow = document.getElementById('submit-arrow');
        const progress = document.getElementById('topProgress');

        function resetSubmit() {
            btn.removeAttribute('aria-busy');
            label.textContent = 'Cari Riwayat Pelatihan';
            spinner.hidden = true;
            arrow.hidden = false;
            progress.classList.remove('run');
        }

        form.addEventListener('submit', () => {
            if (!form.checkValidity()) return;

            try { sessionStorage.setItem(SCROLL_KEY, String(window.scrollY)); } catch (e) {}

            btn.setAttribute('aria-busy', 'true');
            label.textContent = 'Mencari data…';
            spinner.hidden = false;
            arrow.hidden = true;
            progress.classList.add('run');
        });

        /* Pulihkan tombol jika pengguna kembali lewat riwayat browser */
        window.addEventListener('pageshow', resetSubmit);

        /* Parallax halus pada orb (transform saja) */
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const brand = document.getElementById('brandPanel');
        const orbs = brand.querySelectorAll('.orb');

        if (!reduce && window.matchMedia('(hover: hover)').matches) {
            brand.addEventListener('pointermove', (e) => {
                const r = brand.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - .5;
                const y = (e.clientY - r.top) / r.height - .5;
                orbs.forEach((orb) => {
                    const d = parseFloat(orb.dataset.depth || 20);
                    orb.style.translate = (x * d) + 'px ' + (y * d) + 'px';
                });
            }, { passive: true });
        }
    })();
</script>
</body>
</html>