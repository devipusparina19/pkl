<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Internal — SIRAPIK</title>

    <link rel="stylesheet" href="{{ asset('css/sirapik.css') }}">

    <style>
        :root {
            --blue-dark: #0b2340;
            --blue-primary: #12345a;
            --blue-light: #1d4c7c;
            --blue-hover: #194c87;
            --blue-accent: #2463a6;
            --blue-soft: #edf4fc;
            --blue-pale: #f3f6fa;
            --blue-text: #12345a;
            --ink: #26364a;
            --muted: #64748b;
            --border: #dce4ee;
            --white: #ffffff;
            --green: #207653;
            --green-soft: #e9f6ef;
            --red: #b34545;
            --red-soft: #fbefef;
        }

        * {
            box-sizing: border-box;
        }

        [hidden] {
            display: none !important;
        }

        html {
            min-height: 100%;
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
            background: var(--blue-pale);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background-image:
                linear-gradient(
                    125deg,
                    rgba(11, 35, 64, .76),
                    rgba(18, 52, 90, .66),
                    rgba(29, 76, 124, .72)
                ),
                url("{{ asset('images/login-bapelkes.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transform: scale(1.02);
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                linear-gradient(
                    135deg,
                    rgba(7, 25, 48, .20),
                    rgba(11, 35, 64, .08),
                    rgba(18, 52, 90, .24)
                ),
                radial-gradient(
                    circle at 15% 10%,
                    rgba(255, 255, 255, .13),
                    transparent 40%
                );
        }

        .login-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: min(1000px, 100%);
            min-height: 570px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .75);
            border-radius: 16px;
            background: rgba(255, 255, 255, .96);
            box-shadow:
                0 24px 65px rgba(5, 22, 44, .28),
                0 3px 12px rgba(5, 22, 44, .10);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .login-brand {
            position: relative;
            isolation: isolate;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            padding: 34px 36px;
            overflow: hidden;
            color: var(--white);
            background:
                linear-gradient(
                    145deg,
                    rgba(7, 25, 48, .96),
                    rgba(11, 35, 64, .92) 42%,
                    rgba(18, 52, 90, .82) 75%,
                    rgba(29, 76, 124, .75)
                ),
                url("{{ asset('images/login-bapelkes.jpg') }}")
                center / cover no-repeat;
        }

        .login-brand::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background:
                linear-gradient(
                    180deg,
                    rgba(7, 25, 48, .40),
                    rgba(11, 35, 64, .25) 45%,
                    rgba(7, 25, 48, .76)
                ),
                url("{{ asset('images/login-bapelkes.jpg') }}")
                center / cover no-repeat;
            opacity: .95;
        }

        .login-brand::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background: linear-gradient(
                135deg,
                transparent 0 62%,
                rgba(36, 99, 166, .14) 62% 76%,
                transparent 76%
            );
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo {
            flex: 0 0 56px;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .40);
            border-radius: 14px;
            background: rgba(255, 255, 255, .10);
            box-shadow: inset 0 0 15px rgba(255, 255, 255, .06);
            color: var(--white);
            font-size: 28px;
            font-weight: 800;
        }

        .brand-heading h2 {
            margin: 0 0 5px;
            color: var(--white);
            font-size: 24px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand-heading p {
            margin: 0;
            color: rgba(255, 255, 255, .88);
            font-size: 12px;
            line-height: 1.6;
        }

        .brand-content {
            margin: 35px 0;
        }

        .brand-label {
            display: inline-flex;
            align-items: center;
            margin-bottom: 16px;
            padding: 8px 14px;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 30px;
            background: linear-gradient(100deg, #12345a, #1d4c7c);
            color: var(--white);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 14px rgba(0, 15, 35, .18);
        }

        .brand-content h1 {
            margin: 0 0 12px;
            color: var(--white);
            font-size: clamp(34px, 4vw, 44px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: .5px;
        }

        .brand-content > p {
            max-width: 350px;
            margin: 0;
            color: rgba(255, 255, 255, .93);
            font-size: 14px;
            line-height: 1.8;
        }

        .brand-footer {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, .22);
        }

        .security-icon {
            flex: 0 0 38px;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 11px;
            background: rgba(255, 255, 255, .10);
        }

        .security-icon svg {
            width: 21px;
            height: 21px;
        }

        .brand-footer p {
            margin: 2px 0 0;
            color: rgba(255, 255, 255, .90);
            font-size: 11px;
            line-height: 1.8;
        }

        .login-panel {
            position: relative;
            isolation: isolate;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            padding: 38px 42px;
            background: rgba(255, 255, 255, .94);
        }

        .login-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                linear-gradient(
                    145deg,
                    rgba(255, 255, 255, .91),
                    rgba(237, 244, 252, .91)
                ),
                url("{{ asset('images/login-bapelkes.jpg') }}")
                center / cover no-repeat;
        }

        .login-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background: linear-gradient(
                135deg,
                rgba(220, 232, 247, .16),
                rgba(255, 255, 255, .22)
            );
        }

        .login-panel > * {
            position: relative;
            z-index: 1;
        }

        .login-heading {
            margin-bottom: 25px;
        }

        .login-eyebrow {
            display: block;
            margin-bottom: 9px;
            color: var(--blue-light);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .login-heading h2 {
            margin: 0 0 9px;
            color: var(--blue-dark);
            font-size: 30px;
            font-weight: 800;
            line-height: 1.25;
        }

        .login-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .alert {
            margin-bottom: 17px;
            padding: 12px 14px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert-success {
            border-color: #b9e3cd;
            background: var(--green-soft);
            color: var(--green);
        }

        .alert-error {
            border-color: #f0caca;
            background: var(--red-soft);
            color: var(--red);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--blue-text);
            font-size: 13px;
            font-weight: 750;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 13px;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--blue-light);
            pointer-events: none;
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
        }

        .form-control {
            display: block;
            width: 100%;
            height: 48px;
            padding: 0 14px 0 43px;
            border: 1px solid var(--border);
            border-radius: 9px;
            outline: none;
            background: rgba(255, 255, 255, .92);
            color: var(--ink);
            font-family: inherit;
            font-size: 13px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .form-control::placeholder {
            color: #91a0b4;
        }

        .form-control:focus {
            border-color: var(--blue-primary);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(18, 52, 90, .12);
        }

        select.form-control {
            cursor: pointer;
        }

        .password-input {
            padding-right: 53px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 4px;
            transform: translateY(-50%);
            z-index: 3;
            width: 40px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: var(--blue-light);
            cursor: pointer;
            transition: background .2s, color .2s;
        }

        .toggle-password:hover {
            background: var(--blue-soft);
            color: var(--blue-dark);
        }

        .toggle-password:focus-visible {
            outline: 2px solid var(--blue-primary);
            outline-offset: -2px;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
        }

        .btn-login {
            position: relative;
            width: 100%;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 4px;
            padding: 12px 18px;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 9px;
            background: linear-gradient(
                110deg,
                #0b2340,
                #12345a 55%,
                #1d4c7c
            );
            color: var(--white);
            font-family: inherit;
            font-size: 14px;
            font-weight: 800;
            box-shadow: 0 5px 13px rgba(11, 35, 64, .17);
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, background .2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            background: linear-gradient(
                110deg,
                #071930,
                #0b2340 55%,
                #194c87
            );
            box-shadow: 0 8px 18px rgba(11, 35, 64, .23);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:focus-visible {
            outline: 3px solid rgba(36, 99, 166, .35);
            outline-offset: 3px;
        }

        .btn-login svg {
            width: 19px;
            height: 19px;
        }

        .login-bottom {
            margin-top: 24px;
            padding-top: 19px;
            border-top: 1px solid rgba(18, 52, 90, .12);
            text-align: center;
        }

        .login-bottom a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            color: var(--blue-primary);
            font-size: 12px;
            font-weight: 750;
            text-decoration: none;
            transition: color .2s;
        }

        .login-bottom a:hover {
            color: var(--blue-dark);
            text-decoration: underline;
        }

        .login-bottom a svg {
            width: 16px;
            height: 16px;
        }

        .login-bottom p {
            margin: 14px 0 0;
            color: var(--muted);
            font-size: 10px;
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            body {
                padding: 24px 16px;
            }

            .login-layout {
                width: min(820px, 100%);
                min-height: 530px;
            }

            .login-brand {
                padding: 29px 26px;
            }

            .login-panel {
                padding: 30px 27px;
            }

            .brand-heading h2 {
                font-size: 21px;
            }

            .brand-content h1 {
                font-size: 35px;
            }

            .brand-content > p {
                font-size: 13px;
            }

            .login-heading h2 {
                font-size: 27px;
            }
        }

        @media (max-width: 680px) {
            body {
                min-height: 100vh;
                padding: 16px 12px;
                align-items: center;
            }

            .login-layout {
                grid-template-columns: 1fr;
                width: min(430px, 100%);
                min-height: auto;
                border-radius: 13px;
            }

            .login-brand {
                min-height: 250px;
                padding: 23px;
            }

            .brand-logo {
                flex-basis: 46px;
                width: 46px;
                height: 46px;
                border-radius: 11px;
                font-size: 23px;
            }

            .brand-heading h2 {
                font-size: 20px;
            }

            .brand-heading p {
                font-size: 11px;
            }

            .brand-content {
                margin: 25px 0;
            }

            .brand-label {
                margin-bottom: 11px;
                padding: 7px 11px;
                font-size: 9px;
            }

            .brand-content h1 {
                margin-bottom: 9px;
                font-size: 31px;
            }

            .brand-content > p {
                max-width: 340px;
                font-size: 12px;
                line-height: 1.7;
            }

            .brand-footer {
                padding-top: 13px;
            }

            .brand-footer p {
                font-size: 10px;
            }

            .login-panel {
                padding: 27px 23px 23px;
            }

            .login-heading {
                margin-bottom: 21px;
            }

            .login-eyebrow {
                font-size: 10px;
                letter-spacing: 1.5px;
            }

            .login-heading h2 {
                font-size: 26px;
            }

            .login-heading p {
                font-size: 12px;
            }

            .form-group {
                margin-bottom: 16px;
            }

            .form-control {
                height: 46px;
            }

            .btn-login {
                min-height: 47px;
            }

            .login-bottom {
                margin-top: 21px;
                padding-top: 16px;
            }
        }

        @media (max-width: 360px) {
            body {
                padding: 10px;
            }

            .login-brand {
                padding: 19px;
            }

            .login-panel {
                padding: 23px 17px;
            }

            .brand-content h1 {
                font-size: 28px;
            }

            .login-heading h2 {
                font-size: 23px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
<main class="login-layout">

    <section class="login-brand">
        <header class="brand-header">
            <div class="brand-logo" aria-hidden="true">S</div>

            <div class="brand-heading">
                <h2>SIRAPIK</h2>
                <p>Bapelkes Provinsi Kalimantan Selatan</p>
            </div>
        </header>

        <div class="brand-content">
            <span class="brand-label">Sistem Informasi</span>
            <h1>SIRAPIK</h1>
            <p>
                Sistem informasi internal untuk mendukung
                pengelolaan data dan pelayanan yang lebih tertib,
                efektif, dan terpadu.
            </p>
        </div>

        <footer class="brand-footer">
            <div class="security-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>

            <p>
                Akses khusus bagi pengguna yang memiliki akun resmi.
                <br>
                Harap menjaga kerahasiaan informasi akun Anda.
            </p>
        </footer>
    </section>

    <section class="login-panel">
        <header class="login-heading">
            <span class="login-eyebrow">Area Login Internal</span>
            <h2>Selamat Datang</h2>
            <p>Silakan masuk menggunakan akun internal Anda.</p>
        </header>

        @if (session('success'))
            <div class="alert alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form
            id="loginForm"
            method="POST"
            action="{{ url('/login/admin') }}"
        >
            @csrf

            <div class="form-group">
                <label for="role">Masuk Sebagai</label>

                <div class="input-wrap">
                    <span class="input-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                        </svg>
                    </span>

                    <select class="form-control" id="role" name="role" required>
                        <option value="admin"
                            {{ old('role', $loginRole ?? 'admin') === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="super-admin"
                            {{ old('role', $loginRole ?? '') === 'super-admin' ? 'selected' : '' }}>
                            Super Admin
                        </option>

                        <option value="pimpinan"
                            {{ old('role', $loginRole ?? '') === 'pimpinan' ? 'selected' : '' }}>
                            Pimpinan
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="username">Username atau Email</label>

                <div class="input-wrap">
                    <span class="input-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                            <path d="m3 7 9 6 9-6"/>
                        </svg>
                    </span>

                    <input
                        class="form-control"
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username', old('email')) }}"
                        placeholder="Masukkan username atau email"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <div class="input-wrap">
                    <span class="input-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="10" width="16" height="11" rx="2"/>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                            <path d="M12 14v3"/>
                        </svg>
                    </span>

                    <input
                        class="form-control password-input"
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        class="toggle-password"
                        type="button"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M3 3l18 18"/>
                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                            <path d="M9.9 5.1A10.9 10.9 0 0 1 12 4.9c5 0 8.3 4.1 9 7.1a9.7 9.7 0 0 1-2 3.7"/>
                            <path d="M6.6 6.6A11.8 11.8 0 0 0 3 12c.7 3 4 7.1 9 7.1a9.8 9.8 0 0 0 5.4-1.6"/>
                        </svg>

                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true" hidden>
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button class="btn-login" type="submit">
                <span>Masuk ke Sistem</span>

                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                </svg>
            </button>
        </form>

        <footer class="login-bottom">
            <a href="{{ route('public.dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M19 12H5"/>
                    <path d="m12 19-7-7 7-7"/>
                </svg>

                Kembali ke Dashboard Publik
            </a>

            <p>
                &copy; {{ date('Y') }} SIRAPIK. Seluruh hak dilindungi.
            </p>
        </footer>
    </section>
</main>

<script>
    (() => {
        const roleSelect = document.getElementById('role');
        const loginForm = document.getElementById('loginForm');

        const loginBaseUrl = @json(url('/login'));

        function updateRole() {
            const role = roleSelect.value;

            loginForm.action =
                loginBaseUrl + '/' + encodeURIComponent(role);
        }

        roleSelect.addEventListener('change', updateRole);
        updateRole();

        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        togglePassword.addEventListener('click', () => {
            const showPassword = passwordInput.type === 'password';

            passwordInput.type = showPassword ? 'text' : 'password';
            eyeClosed.hidden = showPassword;
            eyeOpen.hidden = !showPassword;

            togglePassword.setAttribute(
                'aria-pressed',
                String(showPassword)
            );

            togglePassword.setAttribute(
                'aria-label',
                showPassword ? 'Sembunyikan password' : 'Tampilkan password'
            );
        });
    })();
</script>
</body>
</html>