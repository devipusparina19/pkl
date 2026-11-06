<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#081B33">

    <title>Cari Riwayat Pelatihan — SIRAPIK</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/sirapik.css') }}">

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
            --red: #991B1B;
            --red-bg: #FEF2F2;
            --red-line: #FECACA;
            --font-display: "Sora", "Segoe UI", Arial, sans-serif;
            --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
            --ease: cubic-bezier(.2, .7, .2, 1);
        }

        *, *::before, *::after { box-sizing: border-box; }
        [hidden] { display: none !important; }
        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font-body);
            font-size: 16px;
            line-height: 1.6;
            color: var(--text);
            background: var(--ice);
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; }
        svg.i {
            width: 1em; height: 1em; flex: none;
            fill: none; stroke: currentColor;
            stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        }

        :focus-visible {
            outline: 3px solid var(--cyan);
            outline-offset: 3px;
            border-radius: 6px;
        }

        .skip {
            position: absolute; left: 12px; top: -60px; z-index: 100;
            padding: 10px 16px; border-radius: 8px;
            background: #fff; color: var(--navy); font-weight: 700;
            text-decoration: none; transition: top .2s;
        }
        .skip:focus { top: 12px; }

        /* ---------- Navbar ---------- */
        .nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(8, 27, 51, .0);
            transition: background .3s, box-shadow .3s;
        }
        .nav.is-scrolled {
            background: rgba(8, 27, 51, .92);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 30px rgba(8, 27, 51, .25);
        }
        .nav-inner {
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            width: min(1240px, 100%); margin: 0 auto; padding: 14px 24px;
        }
        .nav-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; color: #fff; min-width: 0; }
        .nav-logo {
            display: grid; place-items: center; flex: none;
            width: 44px; height: 44px; border-radius: 12px; background: #fff; overflow: hidden;
        }
        .nav-logo img { width: 100%; height: 100%; padding: 4px; object-fit: contain; }
        .nav-name { font-family: var(--font-display); font-weight: 800; font-size: 19px; letter-spacing: .02em; line-height: 1.1; }
        .nav-sub { display: block; font-family: var(--font-body); font-weight: 500; font-size: 12px; color: rgba(255,255,255,.75); letter-spacing: 0; }
        .nav-links { display: flex; align-items: center; gap: 6px; }
        .nav-link {
            position: relative; display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 14px; border-radius: 999px;
            color: rgba(255,255,255,.82); font-size: 14px; font-weight: 600; text-decoration: none;
            transition: color .2s, background .2s;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,.1); }
        .nav-link[aria-current="page"] { color: #fff; background: rgba(52,120,246,.35); }
        .nav-link[aria-current="page"]::after {
            content: ""; position: absolute; left: 50%; bottom: 2px;
            width: 18px; height: 3px; border-radius: 3px; background: var(--cyan);
            transform: translateX(-50%);
        }

        .progress {
            position: fixed; left: 0; top: 0; z-index: 60;
            width: 100%; height: 3px;
            background: linear-gradient(90deg, var(--cyan), var(--electric));
            transform: scaleX(0); transform-origin: left;
        }

        /* ---------- Page ---------- */
        .page {
            position: relative; overflow: hidden;
            margin-top: -76px; padding: 76px 0 0;
            background: var(--navy);
            isolation: isolate;
        }
        .page::before {
            content: ""; position: absolute; inset: 0; z-index: -2;
            background:
                linear-gradient(120deg, rgba(8,27,51,.96) 0%, rgba(11,37,69,.9) 55%, rgba(36,87,214,.55) 100%),
                url("{{ asset('images/login-bapelkes.jpg') }}") center / cover no-repeat;
        }
        .orb {
            position: absolute; z-index: -1; border-radius: 50%; pointer-events: none;
            filter: blur(60px); opacity: .5;
            animation: drift 22s ease-in-out infinite alternate;
        }
        .orb.a { width: 420px; height: 420px; left: -120px; top: 80px; background: var(--royal); }
        .orb.b { width: 320px; height: 320px; right: 8%; bottom: 120px; background: var(--cyan); opacity: .22; animation-duration: 28s; }
        @keyframes drift { to { transform: translate3d(50px, -40px, 0); } }

        .hero {
            display: grid; grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr);
            align-items: center; gap: clamp(32px, 6vw, 88px);
            width: min(1240px, 100%); margin: 0 auto;
            padding: clamp(48px, 8vw, 104px) 24px clamp(56px, 8vw, 112px);
        }

        .hero-copy { color: #fff; }
        .badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 14px 8px 10px; margin-bottom: 26px;
            border: 1px solid rgba(255,255,255,.2); border-radius: 999px;
            background: rgba(255,255,255,.08);
            font-size: 13px; font-weight: 600; color: rgba(255,255,255,.92);
        }
        .badge .i { color: var(--cyan); font-size: 16px; }
        .hero-copy h1 {
            margin: 0 0 26px;
            font-family: var(--font-display); font-weight: 800;
            font-size: clamp(2.6rem, 7.2vw, 5.4rem);
            line-height: 1.02; letter-spacing: -.035em;
        }
        .hero-copy h1 span { display: block; color: var(--cyan); }
        .hero-copy p {
            max-width: 520px; margin: 0 0 36px;
            font-size: clamp(1rem, 1.6vw, 1.125rem); line-height: 1.8;
            color: rgba(255,255,255,.86);
        }

        .steps { display: grid; gap: 0; max-width: 520px; margin: 0; padding: 0; list-style: none; }
        .steps li {
            position: relative; display: flex; gap: 16px; align-items: flex-start;
            padding: 0 0 22px;
        }
        .steps li:last-child { padding-bottom: 0; }
        .steps li:not(:last-child)::before {
            content: ""; position: absolute; left: 19px; top: 40px; bottom: 2px;
            width: 2px; background: linear-gradient(var(--cyan), rgba(56,198,232,.1));
        }
        .step-dot {
            display: grid; place-items: center; flex: none;
            width: 40px; height: 40px; border-radius: 12px;
            background: rgba(56,198,232,.14); border: 1px solid rgba(56,198,232,.4);
            color: var(--cyan); font-size: 18px;
        }
        .steps strong { display: block; font-family: var(--font-display); font-size: 15px; font-weight: 600; color: #fff; }
        .steps span { font-size: 14px; color: rgba(255,255,255,.74); line-height: 1.6; }

        /* ---------- Form ---------- */
        .form-wrap { position: relative; }
        .form-wrap::before {
            content: ""; position: absolute; inset: 18px -14px -14px 18px; z-index: -1;
            border-radius: 28px; border: 1px solid rgba(255,255,255,.22);
            background: rgba(255,255,255,.05);
        }
        .card {
            padding: clamp(26px, 4vw, 44px);
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 30px 70px rgba(3, 12, 26, .4);
        }
        .card-head { margin-bottom: 26px; }
        .card-head h2 {
            margin: 0 0 10px;
            font-family: var(--font-display); font-weight: 700;
            font-size: clamp(1.5rem, 3vw, 1.9rem);
            line-height: 1.2; letter-spacing: -.02em; color: var(--navy);
        }
        .card-head p { margin: 0; font-size: 15px; line-height: 1.75; color: var(--slate); }

        .alert {
            display: flex; gap: 12px; margin-bottom: 22px; padding: 14px 16px;
            border: 1px solid var(--red-line); border-radius: 14px;
            background: var(--red-bg); color: var(--red);
            font-size: 14px; line-height: 1.7; overflow-wrap: anywhere;
        }
        .alert > .i { margin-top: 3px; font-size: 18px; }
        .alert ul { margin: 4px 0 0; padding-left: 20px; }

        .field { margin-bottom: 22px; }
        .field label { display: block; margin-bottom: 8px; font-size: 15px; font-weight: 700; color: var(--navy); }
        .control { position: relative; }
        .control .i {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            font-size: 20px; color: var(--slate); pointer-events: none; transition: color .2s;
        }
        .control:focus-within .i { color: var(--royal); }
        .form-control {
            display: block; width: 100%; min-height: 56px;
            padding: 14px 16px 14px 48px;
            border: 1.5px solid var(--border); border-radius: 14px;
            background: var(--ice); color: var(--text);
            font-family: inherit; font-size: 16px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .form-control::placeholder { color: #8796aa; }
        .form-control:hover { border-color: #c5d3e4; }
        .form-control:focus {
            outline: none; background: #fff; border-color: var(--electric);
            box-shadow: 0 0 0 4px rgba(52,120,246,.16);
        }
        .help { display: block; margin-top: 8px; font-size: 13px; line-height: 1.6; color: var(--slate); }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            min-height: 56px; padding: 14px 24px; border: 0; border-radius: 14px;
            font-family: var(--font-display); font-size: 15px; font-weight: 600;
            text-decoration: none; cursor: pointer;
            transition: transform .2s var(--ease), box-shadow .2s, background .2s, color .2s;
        }
        .btn .i { font-size: 18px; transition: transform .2s var(--ease); }
        .btn-primary {
            width: 100%; margin-top: 4px; color: #fff;
            background: linear-gradient(110deg, var(--royal), var(--electric));
            box-shadow: 0 12px 26px rgba(36,87,214,.35);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 16px 32px rgba(36,87,214,.45); }
        .btn-primary:hover .i { transform: translateX(4px); }
        .btn-primary:active { transform: translateY(0); }
        .btn-primary[aria-busy="true"] { opacity: .75; pointer-events: none; }
        .btn-ghost {
            min-height: 44px; padding: 10px 16px; color: var(--royal);
            background: transparent; font-family: var(--font-body); font-weight: 700;
        }
        .btn-ghost:hover { background: var(--soft); }
        .btn-ghost:hover .i { transform: translateX(-4px); }

        .card-foot {
            display: flex; flex-direction: column; align-items: center; gap: 4px;
            margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--border);
            text-align: center;
        }
        .card-foot small { font-size: 12px; line-height: 1.7; color: var(--slate); }

        /* ---------- Footer note ---------- */
        .note {
            background: var(--ice);
        }
        .note-inner {
            display: flex; gap: 18px; align-items: flex-start;
            width: min(1240px, 100%); margin: 0 auto; padding: 36px 24px;
        }
        .note-icon {
            display: grid; place-items: center; flex: none;
            width: 46px; height: 46px; border-radius: 14px;
            background: var(--soft); color: var(--royal); font-size: 22px;
        }
        .note h3 { margin: 0 0 4px; font-family: var(--font-display); font-size: 16px; font-weight: 600; color: var(--navy); }
        .note p { margin: 0; max-width: 760px; font-size: 14px; line-height: 1.8; color: var(--slate); }

        /* ---------- Entrance ---------- */
        .reveal { opacity: 0; transform: translate3d(0, 22px, 0); animation: rise .8s var(--ease) forwards; }
        .reveal.d1 { animation-delay: .08s; }
        .reveal.d2 { animation-delay: .18s; }
        .reveal.d3 { animation-delay: .3s; }
        .reveal.d4 { animation-delay: .42s; }
        @keyframes rise { to { opacity: 1; transform: none; } }

        /* ---------- Responsive ---------- */
        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
            .form-wrap::before { display: none; }
            .steps { display: none; }
            .hero-copy p { margin-bottom: 0; }
        }
        @media (max-width: 560px) {
            .nav-inner { padding: 12px 16px; }
            .nav-sub { display: none; }
            .nav-link span.t { display: none; }
            .hero { padding-left: 16px; padding-right: 16px; }
            .card { border-radius: 20px; }
            .note-inner { padding: 28px 16px; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation: none !important; transition-duration: .01ms !important; }
            .reveal { opacity: 1; transform: none; }
        }
    </style>
</head>

<body>
<div class="progress" id="progress" aria-hidden="true"></div>
<a class="skip" href="#form">Lewati ke formulir</a>

<header class="nav" id="nav">
    <div class="nav-inner">
        <a class="nav-brand" href="{{ route('public.dashboard') }}" aria-label="SIRAPIK, kembali ke dashboard publik">
            <span class="nav-logo">
                <img src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                     alt="Logo Provinsi Kalimantan Selatan" width="44" height="44">
            </span>
            <span class="nav-name">SIRAPIK
                <span class="nav-sub">Bapelkes Provinsi Kalimantan Selatan</span>
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
</header>

<main class="page" id="main">
    <span class="orb a" aria-hidden="true"></span>
    <span class="orb b" aria-hidden="true"></span>

    <div class="hero">
        <section class="hero-copy" aria-labelledby="hero-title">
            <span class="badge reveal">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                Layanan informasi publik
            </span>

            <h1 id="hero-title" class="reveal d1">
                Riwayat pelatihan Anda,
                <span>dalam satu pencarian.</span>
            </h1>

            <p class="reveal d2">
                Periksa pelatihan yang tercatat atas nama Anda di SIRAPIK.
                Masukkan NIK dan nama lengkap sesuai KTP, lalu lihat hasilnya.
            </p>

            <ol class="steps reveal d3">
                <li>
                    <span class="step-dot">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                    </span>
                    <div>
                        <strong>Siapkan KTP</strong>
                        <span>NIK dan nama harus sama persis dengan yang tertera di KTP.</span>
                    </div>
                </li>
                <li>
                    <span class="step-dot">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.4 3.6a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                    </span>
                    <div>
                        <strong>Isi dua kolom</strong>
                        <span>Tidak perlu membuat akun atau masuk terlebih dahulu.</span>
                    </div>
                </li>
                <li>
                    <span class="step-dot">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                    </span>
                    <div>
                        <strong>Lihat riwayat</strong>
                        <span>Daftar pelatihan yang pernah Anda ikuti akan ditampilkan.</span>
                    </div>
                </li>
            </ol>
        </section>

        <section class="form-wrap reveal d2" id="form" aria-labelledby="form-title">
            <div class="card">
                <header class="card-head">
                    <h2 id="form-title">Cari riwayat pelatihan</h2>
                    <p>
                        Isi kedua kolom sesuai identitas pada KTP. NIK dan nama harus
                        cocok dengan data peserta yang terdaftar.
                    </p>
                </header>

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

                    <div class="field">
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
                                autofocus
                            >
                        </div>
                        <span class="help" id="nik-help">
                            Masukkan NIK yang terdaftar pada data peserta SIRAPIK.
                        </span>
                    </div>

                    <div class="field">
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
                            Penulisan nama harus sesuai dengan nama yang tercatat
                            pada data peserta.
                        </span>
                    </div>

                    <button class="btn btn-primary" type="submit" id="submit-btn">
                        <span id="submit-label">Cari Riwayat Pelatihan</span>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </form>

                <footer class="card-foot">
                    <a class="btn btn-ghost" href="{{ route('public.dashboard') }}">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        Kembali ke Dashboard Publik
                    </a>
                    <small>© {{ date('Y') }} SIRAPIK · Bapelkes Provinsi Kalimantan Selatan</small>
                </footer>
            </div>
        </section>
    </div>
</main>

<aside class="note" aria-label="Catatan data">
    <div class="note-inner">
        <span class="note-icon">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
        </span>
        <div>
            <h3>Dari mana data ini berasal?</h3>
            <p>
                Informasi ditampilkan berdasarkan data peserta yang tercatat di SIRAPIK.
                Pastikan NIK dan nama yang dimasukkan benar agar data dapat ditemukan.
            </p>
        </div>
    </div>
</aside>

<script>
    (function () {
        var nav = document.getElementById('nav');
        var bar = document.getElementById('progress');
        var ticking = false;

        function update() {
            var y = window.scrollY || document.documentElement.scrollTop;
            var max = document.documentElement.scrollHeight - window.innerHeight;
            nav.classList.toggle('is-scrolled', y > 12);
            bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(y / max, 1) : 0) + ')';
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) { ticking = true; requestAnimationFrame(update); }
        }, { passive: true });
        update();

        var form = document.getElementById('lookup-form');
        var btn = document.getElementById('submit-btn');
        var label = document.getElementById('submit-label');

        form.addEventListener('submit', function () {
            if (!form.checkValidity()) { return; }
            btn.setAttribute('aria-busy', 'true');
            label.textContent = 'Mencari data…';
        });

        // Tombol "kembali" browser: kembalikan keadaan tombol.
        window.addEventListener('pageshow', function () {
            btn.removeAttribute('aria-busy');
            label.textContent = 'Cari Riwayat Pelatihan';
        });
    })();
</script>
</body>
</html>