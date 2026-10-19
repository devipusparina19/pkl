<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Internal — SIRAPIK</title>
    <link rel="stylesheet" href="{{ asset('css/sirapik.css') }}">

    <style>
        * { box-sizing: border-box; }

        body.login-page {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #17345a;
            background: #f3f6fb;
        }

        .login-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        .login-brand-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            min-height: 100vh;
            padding: 42px 52px;
            color: white;
            background-color: #102b52;
            background-image:
                linear-gradient(
                    135deg,
                    rgba(9, 34, 68, .90),
                    rgba(16, 59, 105, .70)
                ),
                url("/images/login-bapelkes.jpg");
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand-mark-large {
            display: grid;
            place-items: center;
            width: 58px;
            height: 58px;
            flex-shrink: 0;
            border-radius: 15px;
            background: white;
            color: #174a80;
            font-size: 30px;
            font-weight: 800;
        }

        .login-brand strong {
            display: block;
            color: white;
            font-size: 23px;
            letter-spacing: 1px;
        }

        .login-brand span {
            display: block;
            margin-top: 5px;
            color: #e0ebf8;
            font-size: 13px;
        }

        .login-hero {
            width: 100%;
            max-width: 530px;
            margin: 60px auto;
        }

        .eyebrow {
            display: block;
            color: #2468b7;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            line-height: 1.6;
        }

        .light-eyebrow { color: #c8e1ff; }

        .login-hero h1 {
            margin: 22px 0 20px;
            color: white;
            font-size: clamp(34px, 3.2vw, 48px);
            line-height: 1.2;
        }

        .login-hero p {
            max-width: 470px;
            margin: 0;
            color: #e5edf8;
            font-size: 15px;
            line-height: 1.9;
        }

        .login-panel-footer {
            color: #e0ebf8;
            font-size: 12px;
        }

        .login-form-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            min-height: 100vh;
            padding: 45px 38px 25px;
            background: white;
        }

        .login-form-wrap {
            width: 100%;
            max-width: 440px;
            margin: auto;
        }

        .login-heading { margin-bottom: 28px; }

        .login-heading .eyebrow { margin-bottom: 10px; }

        .login-heading h2 {
            margin: 0 0 12px;
            color: #14345b;
            font-size: 32px;
            line-height: 1.25;
        }

        .login-heading p {
            margin: 0;
            color: #687991;
            font-size: 14px;
            line-height: 1.8;
        }

        .form-field { margin-bottom: 20px; }

        .form-field label {
            display: block;
            margin-bottom: 9px;
            color: #203b5e;
            font-size: 13px;
            font-weight: 700;
        }

        .form-field input,
        .form-field select {
            display: block;
            width: 100%;
            height: 52px;
            padding: 0 14px;
            border: 1px solid #d0dbea;
            border-radius: 9px;
            outline: none;
            background: #f9fbfe;
            color: #17345a;
            font: inherit;
            font-size: 14px;
        }

        .form-field input:focus,
        .form-field select:focus {
            border-color: #2874c7;
            background: white;
            box-shadow: 0 0 0 4px rgba(40, 116, 199, .12);
        }

        /* Dropdown jenis akun */
        .role-select-wrap {
            position: relative;
        }

        .role-select-wrap select {
            padding-right: 42px;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
        }

        .role-select-icon {
            position: absolute;
            top: 50%;
            right: 14px;
            width: 18px;
            height: 18px;
            transform: translateY(-50%);
            color: #315779;
            pointer-events: none;
        }

        /* Input password dengan ikon mata */
        .password-field {
            position: relative;
        }

        .password-field input {
            padding-right: 54px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            padding: 0;
            transform: translateY(-50%);
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: #315779;
            cursor: pointer;
        }

        .password-toggle:hover {
            background: #eaf2fb;
        }

        .password-toggle svg {
            width: 22px;
            height: 22px;
        }

        .password-toggle:focus-visible {
            outline: 2px solid #2874c7;
            outline-offset: 2px;
        }

        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 52px;
            margin-top: 7px;
            padding: 13px 18px;
            border: 0;
            border-radius: 9px;
            background: #1d63b5;
            color: white;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s ease;
        }

        .btn-login:hover { background: #164f94; }

        .btn-login:disabled {
            cursor: not-allowed;
            opacity: .7;
        }

        .login-security {
            margin-top: 20px;
            color: #73839a;
            font-size: 12px;
            text-align: center;
        }

        .login-security span { color: #15966a; }

        .login-participant-link {
            margin-top: 24px;
            text-align: center;
        }

        .login-participant-link a {
            color: #1d63b5;
            font-size: 13px;
            font-weight: 650;
            text-decoration: none;
        }

        .login-participant-link a:hover {
            text-decoration: underline;
        }

        .login-copyright {
            margin-top: 35px;
            color: #8997a9;
            font-size: 11px;
            text-align: center;
        }

        .alert {
            margin-bottom: 20px;
            padding: 13px 15px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert-success {
            border-color: #b8e5d0;
            background: #effaf4;
            color: #176842;
        }

        .alert-error {
            border-color: #f0c4c4;
            background: #fff4f4;
            color: #a12d2d;
        }

        @media (max-width: 900px) {
            .login-brand-panel { padding: 32px; }
            .login-form-panel { padding: 35px 25px; }
            .login-hero h1 { font-size: 34px; }
        }

        @media (max-width: 680px) {
            .login-layout {
                display: flex;
                flex-direction: column;
            }

            .login-brand-panel {
                min-height: 290px;
                padding: 25px;
            }

            .brand-mark-large {
                width: 48px;
                height: 48px;
                font-size: 25px;
            }

            .login-brand strong { font-size: 20px; }

            .login-hero { margin: 35px 0 25px; }

            .login-hero h1 {
                margin: 15px 0;
                font-size: 30px;
            }

            .login-hero p {
                font-size: 14px;
                line-height: 1.7;
            }

            .login-form-panel {
                min-height: auto;
                padding: 35px 24px 24px;
            }

            .login-heading h2 { font-size: 28px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition: none !important;
            }
        }
    </style>
</head>

<body class="login-page">
<div class="login-layout">

    <section class="login-brand-panel">
        <div class="login-brand">
            <div class="brand-mark brand-mark-large">S</div>
            <div>
                <strong>SIRAPIK</strong>
                <span>Bapelkes Kalimantan Selatan</span>
            </div>
        </div>

        <div class="login-hero">
            <div class="eyebrow light-eyebrow">
                SISTEM INFORMASI PELATIHAN
            </div>

            <h1>Riwayat pelatihan, tersusun dalam satu sistem.</h1>

            <p>
                Memudahkan pengelolaan data peserta, penelusuran
                riwayat berdasarkan NIP, dan pemantauan keikutsertaan
                pelatihan secara terstruktur.
            </p>
        </div>

        <div class="login-panel-footer">
            Sistem administrasi riwayat pelatihan
        </div>
    </section>

    <section class="login-form-panel">
        <div class="login-form-wrap">

            <div class="login-heading">
                <span class="eyebrow">AREA LOGIN INTERNAL</span>
                <h2>Masuk ke SIRAPIK</h2>
                <p>Pilih jenis akun, lalu masukkan username/email dan password.</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                id="loginForm"
                method="POST"
                action="{{ route('login.store', [
                    'role' => $loginRole ?? request()->route('role', 'admin')
                ]) }}"
            >
                @csrf

                {{-- Jenis akun --}}
                <div class="form-field">
                    <label for="role">Jenis akun</label>

                    <div class="role-select-wrap">
                        <select id="role" name="role" required>
                            <option value="super-admin"
                                @selected(old('role', $loginRole ?? request()->route('role', '')) === 'super-admin')>
                                Super Admin
                            </option>

                            <option value="admin"
                                @selected(old('role', $loginRole ?? request()->route('role', 'admin')) === 'admin')>
                                Admin
                            </option>

                            <option value="pimpinan"
                                @selected(old('role', $loginRole ?? request()->route('role', '')) === 'pimpinan')>
                                Pimpinan
                            </option>
                        </select>

                        <svg class="role-select-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </div>
                </div>

                {{-- Username/email --}}
                <div class="form-field">
                    <label for="email">Username / Email</label>

                    <input
                        id="email"
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        placeholder="Masukkan username atau email"
                        maxlength="255"
                        required
                        autofocus
                    >
                </div>

                {{-- Password dengan ikon mata --}}
                <div class="form-field">
                    <label for="password">Password</label>

                    <div class="password-field">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            required
                        >

                        <button
                            class="password-toggle"
                            type="button"
                            id="togglePassword"
                            aria-controls="password"
                            aria-label="Tampilkan password"
                            title="Tampilkan password"
                        >
                            {{-- Ikon mata terbuka --}}
                            <svg
                                id="eyeOpen"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>

                            {{-- Ikon mata tertutup --}}
                            <svg
                                id="eyeClosed"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                                hidden
                            >
                                <path d="m3 3 18 18"/>
                                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a15.6 15.6 0 0 1-3.1 3.9"/>
                                <path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7c1.3 0 2.5-.3 3.6-.8"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button class="btn-login" type="submit">
                    Masuk sebagai
                    <span id="roleButtonLabel" style="margin-left: 4px">Admin</span>
                    <span style="margin-left: 7px">→</span>
                </button>
            </form>

            <div class="login-security">
                <span>●</span>
                Akses khusus akun internal yang terdaftar
            </div>

            <div class="login-participant-link">
                <a href="{{ route('public.dashboard') }}">
                    ← Kembali ke halaman publik
                </a>
            </div>
        </div>

        <div class="login-copyright">
            © {{ date('Y') }} SIRAPIK · Bapelkes Kalsel
        </div>
    </section>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    const roleSelect = document.getElementById('role');
    const roleButtonLabel = document.getElementById('roleButtonLabel');
    const loginForm = document.getElementById('loginForm');

    const roleLabels = {
        'super-admin': 'Super Admin',
        'admin': 'Admin',
        'pimpinan': 'Pimpinan'
    };

    /*
     * Perbarui label tombol dan URL login ketika jenis akun berubah.
     */
    function updateRole() {
        const role = roleSelect.value;

        roleButtonLabel.textContent = roleLabels[role] || 'Pengguna';

        loginForm.action = @json(url('/login')) + '/'
            + encodeURIComponent(role);
    }

    roleSelect.addEventListener('change', updateRole);

    /*
     * Klik ikon mata untuk menampilkan atau menyembunyikan password.
     */
    togglePassword.addEventListener('click', function () {
        const showPassword = passwordInput.type === 'password';

        passwordInput.type = showPassword ? 'text' : 'password';

        eyeOpen.hidden = showPassword;
        eyeClosed.hidden = !showPassword;

        const label = showPassword
            ? 'Sembunyikan password'
            : 'Tampilkan password';

        togglePassword.setAttribute('aria-label', label);
        togglePassword.setAttribute('title', label);
    });

    updateRole();
</script>
</body>
</html>