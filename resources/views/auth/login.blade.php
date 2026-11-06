<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#081b33">

    <title>Login Internal — SIRAPIK</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/sirapik.css') }}">

    <style>
        /* =====================================================
           SIRAPIK — LOGIN INTERNAL (Premium Blue Editorial)
        ===================================================== */
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

            --green: #166534;
            --green-soft: #f0fdf4;
            --red: #991b1b;
            --red-soft: #fef2f2;

            --f-display: 'Sora', system-ui, sans-serif;
            --f-body: 'Plus Jakarta Sans', system-ui, sans-serif;

            --r: 14px;
            --ease: cubic-bezier(.2, .7, .3, 1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        [hidden] { display: none !important; }

        html { min-height: 100%; }

        body {
            min-height: 100vh;
            margin: 0;
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

        :focus-visible {
            outline: 3px solid rgba(52, 120, 246, .55);
            outline-offset: 3px;
            border-radius: 8px;
        }

        .login-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
            min-height: 100vh;
        }

        /* =====================================================
           BRAND PANEL
        ===================================================== */
        .login-brand {
            position: relative;
            isolation: isolate;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            padding: 48px 56px;
            overflow: hidden;
            color: #fff;
            background: var(--navy);
        }

        .brand-bg {
            position: absolute;
            inset: 0;
            z-index: -3;
            background:
                linear-gradient(160deg, rgba(5,22,42,.97) 0%, rgba(8,34,63,.9) 55%, rgba(9,39,70,.8) 100%),
                url("{{ asset('images/login-bapelkes.jpg') }}") center / cover no-repeat;
        }

        .brand-grid {
            position: absolute;
            inset: 0;
            z-index: -2;
            opacity: .12;
            background-image:
                linear-gradient(rgba(255,255,255,.5) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.5) 1px, transparent 1px);
            background-size: 64px 64px;
            mask-image: linear-gradient(160deg, #000 10%, transparent 80%);
            -webkit-mask-image: linear-gradient(160deg, #000 10%, transparent 80%);
            animation: grid-drift 40s linear infinite;
        }

        @keyframes grid-drift { to { background-position: 64px 64px; } }

        .brand-orbit {
            position: absolute;
            z-index: -1;
            right: -180px;
            bottom: -180px;
            width: 560px;
            height: 560px;
            border: 1px solid rgba(130,183,255,.22);
            border-radius: 50%;
            box-shadow:
                0 0 0 60px rgba(130,183,255,.04),
                0 0 0 120px rgba(130,183,255,.025);
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logo {
            flex: 0 0 56px;
            width: 56px;
            height: 56px;
            padding: 6px;
            background: #fff;
            border-radius: 16px;
        }

        .brand-logo img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-heading strong {
            display: block;
            font-family: var(--f-display);
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1.2;
        }

        .brand-heading span {
            display: block;
            margin-top: 2px;
            color: rgba(255,255,255,.7);
            font-size: 14px;
        }

        .brand-content { margin: 56px 0; }

        .brand-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            padding: 9px 16px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 999px;
            background: rgba(255,255,255,.07);
            color: #bcd8ff;
            font-size: 14px;
            font-weight: 600;
        }

        .brand-label i {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #54d69b;
            box-shadow: 0 0 0 4px rgba(84,214,155,.18);
            animation: pulse 2.6s ease-in-out infinite;
        }

        @keyframes pulse { 50% { box-shadow: 0 0 0 8px rgba(84,214,155,0); } }

        .brand-content h1 {
            margin: 0;
            font-family: var(--f-display);
            font-size: clamp(56px, 8.4vw, 124px);
            font-weight: 800;
            letter-spacing: -.06em;
            line-height: .95;
        }

        .brand-content h1 span { display: block; color: #82b7ff; }

        .brand-content > p {
            max-width: 46ch;
            margin: 28px 0 0;
            color: rgba(255,255,255,.76);
            font-size: clamp(16px, 1.3vw, 18px);
            line-height: 1.8;
        }

        .brand-footer {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,.18);
        }

        .security-icon {
            flex: 0 0 44px;
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 14px;
            background: rgba(255,255,255,.08);
            color: var(--cyan);
        }

        .security-icon svg { width: 22px; height: 22px; }

        .brand-footer p {
            margin: 0;
            color: rgba(255,255,255,.78);
            font-size: 14px;
            line-height: 1.75;
        }

        /* =====================================================
           LOGIN PANEL
        ===================================================== */
        .login-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            padding: 48px clamp(28px, 6vw, 88px);
            background: #fff;
        }

        .login-inner {
            width: 100%;
            max-width: 460px;
            margin-inline: auto;
            animation: rise .7s var(--ease) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: none; }
        }

        .login-heading { margin-bottom: 32px; }

        .login-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            color: var(--royal);
            font-size: 15px;
            font-weight: 700;
        }

        .login-eyebrow::before {
            content: "";
            width: 32px;
            height: 2px;
            background: var(--cyan);
        }

        .login-heading h2 {
            margin: 0 0 12px;
            color: var(--navy);
            font-family: var(--f-display);
            font-size: clamp(34px, 4vw, 48px);
            font-weight: 800;
            letter-spacing: -.045em;
            line-height: 1.08;
        }

        .login-heading p { margin: 0; color: var(--slate); font-size: 17px; line-height: 1.7; }

        /* Alerts */
        .alert {
            margin-bottom: 20px;
            padding: 15px 18px;
            border: 1px solid transparent;
            border-radius: var(--r);
            font-size: 15px;
            line-height: 1.6;
            animation: rise .4s var(--ease) both;
        }

        .alert-success { border-color: #bbf7d0; background: var(--green-soft); color: var(--green); }
        .alert-error { border-color: #fecaca; background: var(--red-soft); color: var(--red); }

        /* Form */
        .form-group { margin-bottom: 22px; }

        .form-group > label,
        .group-label {
            display: block;
            margin-bottom: 9px;
            color: #40536a;
            font-size: 14px;
            font-weight: 700;
        }

        /* Role: segmented control */
        .role-switch {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
            padding: 5px;
            border: 1px solid var(--border);
            border-radius: var(--r);
            background: var(--ice);
        }

        .role-switch label { position: relative; margin: 0; cursor: pointer; }

        .role-switch input {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            opacity: 0;
            cursor: pointer;
        }

        .role-switch span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 8px 6px;
            border-radius: 10px;
            color: #506176;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            line-height: 1.3;
            transition: background .25s var(--ease), color .25s, box-shadow .25s;
        }

        .role-switch label:hover span { color: var(--royal); }

        .role-switch input:checked + span {
            background: #fff;
            color: var(--royal);
            box-shadow: 0 4px 12px rgba(8,27,51,.1);
        }

        .role-switch input:focus-visible + span {
            outline: 3px solid rgba(52,120,246,.55);
            outline-offset: 2px;
        }

        /* Inputs */
        .input-wrap { position: relative; display: flex; align-items: center; }

        .input-icon {
            position: absolute;
            left: 16px;
            z-index: 2;
            display: flex;
            color: #8394aa;
            pointer-events: none;
            transition: color .25s;
        }

        .input-icon svg { width: 20px; height: 20px; }

        .form-control {
            display: block;
            width: 100%;
            height: 56px;
            padding: 0 16px 0 48px;
            border: 1.5px solid var(--border);
            border-radius: var(--r);
            outline: none;
            background: #fff;
            color: var(--ink);
            font-size: 16px;
            transition: border-color .25s, box-shadow .25s;
        }

        .form-control::placeholder { color: #8998ab; }
        .form-control:hover { border-color: #a6bddb; }

        .form-control:focus {
            border-color: var(--royal);
            box-shadow: 0 0 0 4px rgba(36,87,214,.12);
        }

        .input-wrap:focus-within .input-icon { color: var(--royal); }

        .password-input { padding-right: 58px; }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 6px;
            z-index: 3;
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: #8394aa;
            cursor: pointer;
            transform: translateY(-50%);
            transition: background .25s, color .25s;
        }

        .toggle-password:hover { background: var(--soft); color: var(--royal); }
        .toggle-password svg { width: 21px; height: 21px; }

        /* Submit */
        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            min-height: 58px;
            margin-top: 6px;
            padding: 12px 22px;
            border: 0;
            border-radius: 999px;
            background: var(--royal);
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background .25s, transform .25s var(--ease), box-shadow .25s;
        }

        .btn-login svg { width: 20px; height: 20px; transition: transform .25s var(--ease); }

        .btn-login:hover {
            background: var(--navy);
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(36,87,214,.28);
        }

        .btn-login:hover svg { transform: translateX(4px); }
        .btn-login:active { transform: scale(.98); }

        .btn-login:disabled {
            background: #7f9bd8;
            box-shadow: none;
            transform: none;
            cursor: wait;
        }

        /* Bottom */
        .login-bottom {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .login-bottom a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--royal);
            font-size: 15px;
            font-weight: 700;
            transition: color .25s;
        }

        .login-bottom a svg { width: 18px; height: 18px; transition: transform .25s var(--ease); }
        .login-bottom a:hover { color: var(--navy); }
        .login-bottom a:hover svg { transform: translateX(-4px); }

        .login-bottom p { margin: 14px 0 0; color: var(--slate); font-size: 13px; line-height: 1.6; }

        /* =====================================================
           RESPONSIVE
        ===================================================== */
        @media (max-width: 1000px) {
            .login-layout { grid-template-columns: 1fr; }

            .login-brand { padding: 36px 32px; }
            .brand-content { margin: 40px 0; }
            .brand-content h1 { font-size: clamp(56px, 12vw, 100px); }

            .login-panel { padding: 48px 32px 56px; }
        }

        @media (max-width: 560px) {
            .login-brand { padding: 26px 20px; }
            .brand-logo { flex-basis: 48px; width: 48px; height: 48px; border-radius: 14px; }
            .brand-content { margin: 32px 0; }
            .brand-content > p { margin-top: 20px; }
            .brand-footer { padding-top: 18px; }
            .brand-orbit { right: -260px; bottom: -260px; }

            .login-panel { padding: 36px 20px 44px; }
            .login-heading { margin-bottom: 26px; }
            .login-heading p { font-size: 16px; }

            .role-switch span { min-height: 46px; font-size: 13px; }
            .form-control { height: 54px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
<main class="login-layout">

    {{-- BRAND --}}
    <section class="login-brand">
        <div class="brand-bg" aria-hidden="true"></div>
        <div class="brand-grid" aria-hidden="true"></div>
        <div class="brand-orbit" aria-hidden="true"></div>

        <header class="brand-header">
            <div class="brand-logo">
                <img
                    src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                    alt="Logo Provinsi Kalimantan Selatan"
                >
            </div>

            <div class="brand-heading">
                <strong>SIRAPIK</strong>
                <span>Bapelkes Provinsi Kalimantan Selatan</span>
            </div>
        </header>

        <div class="brand-content">
            <span class="brand-label"><i></i> Sistem Informasi</span>

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
                Harap menjaga kerahasiaan informasi akun Anda.
            </p>
        </footer>
    </section>

    {{-- LOGIN --}}
    <section class="login-panel">
        <div class="login-inner">

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

                @php
                    $selectedRole = old('role', $loginRole ?? 'admin');
                @endphp

                <div class="form-group">
                    <span class="group-label" id="roleLabel">Masuk Sebagai</span>

                    <div class="role-switch" role="radiogroup" aria-labelledby="roleLabel">

                        <label>
                            <input type="radio" name="role" value="admin"
                                {{ $selectedRole === 'admin' ? 'checked' : '' }} required>
                            <span>Admin</span>
                        </label>

                        <label>
                            <input type="radio" name="role" value="super-admin"
                                {{ $selectedRole === 'super-admin' ? 'checked' : '' }}>
                            <span>Super Admin</span>
                        </label>

                        <label>
                            <input type="radio" name="role" value="pimpinan"
                                {{ $selectedRole === 'pimpinan' ? 'checked' : '' }}>
                            <span>Pimpinan</span>
                        </label>

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

                <button class="btn-login" type="submit" id="loginSubmit">
                    <span id="loginSubmitText">Masuk ke Sistem</span>

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

        </div>
    </section>
</main>

<script>
    (() => {
        const loginForm = document.getElementById('loginForm');
        const roleInputs = document.querySelectorAll('input[name="role"]');
        const loginBaseUrl = @json(url('/login'));

        /* Tujuan form mengikuti role yang dipilih */
        function updateRole() {
            const checked = document.querySelector('input[name="role"]:checked');
            const role = checked ? checked.value : 'admin';

            loginForm.action = loginBaseUrl + '/' + encodeURIComponent(role);
        }

        roleInputs.forEach((input) => input.addEventListener('change', updateRole));
        updateRole();

        /* Tampilkan / sembunyikan password */
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        togglePassword.addEventListener('click', () => {
            const showPassword = passwordInput.type === 'password';

            passwordInput.type = showPassword ? 'text' : 'password';
            eyeClosed.hidden = showPassword;
            eyeOpen.hidden = !showPassword;

            togglePassword.setAttribute('aria-pressed', String(showPassword));
            togglePassword.setAttribute(
                'aria-label',
                showPassword ? 'Sembunyikan password' : 'Tampilkan password'
            );
        });

        /* Umpan balik saat form dikirim (mencegah klik ganda) */
        const submitButton = document.getElementById('loginSubmit');
        const submitText = document.getElementById('loginSubmitText');

        function resetSubmit() {
            submitButton.disabled = false;
            submitText.textContent = 'Masuk ke Sistem';
        }

        loginForm.addEventListener('submit', () => {
            submitButton.disabled = true;
            submitText.textContent = 'Memproses...';
        });

        /* Pulihkan tombol jika pengguna kembali lewat riwayat browser */
        window.addEventListener('pageshow', resetSubmit);
    })();
</script>
</body>
</html>