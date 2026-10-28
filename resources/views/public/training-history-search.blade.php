<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#12345a">

    <title>Cari Riwayat Pelatihan — SIRAPIK</title>

    <link rel="stylesheet" href="{{ asset('css/sirapik.css') }}">

    <style>
        :root {
            --navy: #0b2340;
            --blue: #12345a;
            --blue-light: #2463a6;
            --ink: #26364a;
            --muted: #64748b;
            --border: #dce4ee;
            --red: #991b1b;
            --red-bg: #fef2f2;
        }

        * {
            box-sizing: border-box;
        }

        [hidden] {
            display: none !important;
        }

        body {
            position: relative;
            isolation: isolate;
            min-height: 100vh;
            margin: 0;
            padding: 32px 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            background: #f3f6fa;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(
                    125deg,
                    rgba(11, 35, 64, .80),
                    rgba(18, 52, 90, .69),
                    rgba(29, 76, 124, .74)
                ),
                url("{{ asset('images/login-bapelkes.jpg') }}")
                center / cover no-repeat;
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background: radial-gradient(
                circle at 15% 10%,
                rgba(255,255,255,.12),
                transparent 40%
            );
        }

        .layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: min(1050px, 100%);
            min-height: 560px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.7);
            border-radius: 17px;
            background: rgba(255,255,255,.97);
            box-shadow: 0 24px 65px rgba(5,22,44,.28);
        }

        .brand-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            padding: 36px;
            color: #fff;
            background:
                linear-gradient(
                    145deg,
                    rgba(7,25,48,.96),
                    rgba(11,35,64,.90),
                    rgba(29,76,124,.78)
                ),
                url("{{ asset('images/login-bapelkes.jpg') }}")
                center / cover no-repeat;
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 56px;
            width: 56px;
            height: 56px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.4);
            border-radius: 12px;
            background: #fff;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            padding: 4px;
            object-fit: contain;
        }

        .brand-heading h2 {
            margin: 0 0 4px;
            font-size: 24px;
            font-weight: 800;
        }

        .brand-heading p {
            margin: 0;
            color: rgba(255,255,255,.87);
            font-size: 13px;
            line-height: 1.6;
        }

        .brand-content {
            margin: 35px 0;
        }

        .brand-label {
            display: inline-block;
            margin-bottom: 16px;
            padding: 8px 13px;
            border: 1px solid rgba(255,255,255,.24);
            border-radius: 30px;
            background: rgba(255,255,255,.1);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .brand-content h1 {
            margin: 0 0 16px;
            font-size: clamp(32px, 4vw, 43px);
            line-height: 1.2;
        }

        .brand-content p {
            max-width: 390px;
            margin: 0;
            color: rgba(255,255,255,.92);
            font-size: 15px;
            line-height: 1.9;
        }

        .brand-footer {
            padding-top: 19px;
            border-top: 1px solid rgba(255,255,255,.22);
            color: rgba(255,255,255,.9);
            font-size: 13px;
            line-height: 1.8;
        }

        .form-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            padding: 42px;
            background: linear-gradient(145deg, #fff, #edf4fc);
        }

        .eyebrow {
            display: block;
            margin-bottom: 10px;
            color: var(--blue-light);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .form-heading {
            margin-bottom: 27px;
        }

        .form-heading h2 {
            margin: 0 0 11px;
            color: var(--navy);
            font-size: clamp(27px, 3vw, 33px);
            line-height: 1.3;
        }

        .form-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .alert {
            margin-bottom: 19px;
            padding: 14px 15px;
            border: 1px solid #fecaca;
            border-radius: 9px;
            background: var(--red-bg);
            color: var(--red);
            font-size: 14px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .alert ul {
            margin: 5px 0 0;
            padding-left: 22px;
        }

        .form-group {
            margin-bottom: 21px;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            color: var(--navy);
            font-size: 15px;
            font-weight: 750;
        }

        .form-control {
            display: block;
            width: 100%;
            min-height: 51px;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            outline: none;
            background: #fff;
            color: var(--ink);
            font-family: inherit;
            font-size: 16px;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control::placeholder {
            color: #91a0b4;
        }

        .form-control:focus {
            border-color: var(--blue-light);
            box-shadow: 0 0 0 3px rgba(36,99,166,.13);
        }

        .help-text {
            display: block;
            margin-top: 7px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            min-height: 51px;
            margin-top: 6px;
            padding: 12px 18px;
            border: 0;
            border-radius: 9px;
            background: linear-gradient(110deg, #0b2340, #12345a, #2463a6);
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            transition: filter .2s, transform .2s;
        }

        .btn-submit:hover {
            filter: brightness(1.12);
            transform: translateY(-1px);
        }

        .bottom {
            margin-top: 25px;
            padding-top: 19px;
            border-top: 1px solid rgba(18,52,90,.13);
            text-align: center;
        }

        .bottom a {
            color: var(--blue);
            font-size: 14px;
            font-weight: 750;
            text-decoration: none;
        }

        .bottom a:hover {
            text-decoration: underline;
        }

        .bottom p {
            margin: 14px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        @media (max-width: 760px) {
            body {
                padding: 18px 12px;
            }

            .layout {
                grid-template-columns: 1fr;
                width: min(500px, 100%);
                min-height: auto;
                border-radius: 14px;
            }

            .brand-panel {
                padding: 24px;
            }

            .brand-content {
                margin: 27px 0;
            }

            .brand-content h1 {
                font-size: 32px;
            }

            .brand-content p {
                font-size: 15px;
            }

            .brand-footer {
                font-size: 12px;
            }

            .form-panel {
                padding: 28px 23px;
            }

            .form-heading {
                margin-bottom: 23px;
            }

            .form-heading h2 {
                font-size: 28px;
            }

            .form-heading p {
                font-size: 15px;
            }

            .form-control {
                min-height: 50px;
                font-size: 16px;
            }
        }

        @media (max-width: 360px) {
            body {
                padding: 10px;
            }

            .brand-panel {
                padding: 19px;
            }

            .brand-logo {
                flex-basis: 46px;
                width: 46px;
                height: 46px;
            }

            .brand-heading h2 {
                font-size: 21px;
            }

            .brand-heading p {
                font-size: 11px;
            }

            .form-panel {
                padding: 23px 17px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
<main class="layout">

    <section class="brand-panel">
        <header class="brand-header">
            <div class="brand-logo">
                <img
                    src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                    alt="Logo Provinsi Kalimantan Selatan"
                >
            </div>

            <div class="brand-heading">
                <h2>SIRAPIK</h2>
                <p>Bapelkes Provinsi Kalimantan Selatan</p>
            </div>
        </header>

        <div class="brand-content">
            <span class="brand-label">Layanan Informasi Publik</span>

            <h1>Riwayat Pelatihan Peserta</h1>

            <p>
                Temukan informasi riwayat pelatihan yang tercatat di SIRAPIK.
                Masukkan NIK dan nama lengkap sesuai KTP untuk memeriksa data
                pelatihan Anda.
            </p>
        </div>

        <footer class="brand-footer">
            Informasi ditampilkan berdasarkan data peserta yang tercatat
            di SIRAPIK. Pastikan data yang dimasukkan benar.
        </footer>
    </section>

    <section class="form-panel">
        <header class="form-heading">
            <span class="eyebrow">Pencarian Data Peserta</span>
            <h2>Cari Riwayat Pelatihan</h2>
            <p>
                Isi kedua kolom berikut sesuai identitas pada KTP.
                NIK dan nama harus cocok dengan data peserta yang terdaftar.
            </p>
        </header>

        @if (session('lookup_error'))
            <div class="alert" role="alert">
                <strong>Data tidak ditemukan.</strong>
                <div>{{ session('lookup_error') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert" role="alert">
                <strong>Periksa kembali data yang diisi.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('public.training-history.lookup') }}"
        >
            @csrf

            <div class="form-group">
                <label for="nik">NIK sesuai KTP</label>
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
                    required
                    autofocus
                >
                <span class="help-text">
                    Masukkan NIK yang terdaftar pada data peserta SIRAPIK.
                </span>
            </div>

            <div class="form-group">
                <label for="name">Nama lengkap sesuai KTP</label>
                <input
                    class="form-control"
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap sesuai KTP"
                    maxlength="255"
                    autocomplete="name"
                    required
                >
                <span class="help-text">
                    Penulisan nama harus sesuai dengan nama yang tercatat
                    pada data peserta.
                </span>
            </div>

            <button class="btn-submit" type="submit">
                Cari Riwayat Pelatihan <span aria-hidden="true">→</span>
            </button>
        </form>

        <footer class="bottom">
            <a href="{{ route('public.dashboard') }}">
                ← Kembali ke Dashboard Publik
            </a>

            <p>
                © {{ date('Y') }} SIRAPIK<br>
                Bapelkes Provinsi Kalimantan Selatan
            </p>
        </footer>
    </section>

</main>
</body>
</html>