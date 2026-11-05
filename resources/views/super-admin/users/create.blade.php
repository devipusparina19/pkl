@extends('layouts.app')

@section('title', 'Tambah Akun')

@section('content')

<style>
    /* =========================================================
       SIRAPIK — CREATE USER PAGE
       Scoped styles: tidak mengubah komponen halaman lain
    ========================================================= */

    .account-create {
        --ac-navy: #102f57;
        --ac-blue: #2163a5;
        --ac-blue-dark: #174c83;
        --ac-blue-soft: #edf4fb;
        --ac-sky: #dceafa;
        --ac-text: #1c2d42;
        --ac-muted: #68798d;
        --ac-border: #dce5ef;
        --ac-surface: #ffffff;
        --ac-bg: #f5f8fc;
        --ac-radius: 18px;

        color: var(--ac-text);
        padding: 12px 0 36px;
        font-family: inherit;
    }

    .account-create * {
        box-sizing: border-box;
    }

    .account-create a,
    .account-create button,
    .account-create input,
    .account-create select {
        -webkit-tap-highlight-color: transparent;
    }

    /* Breadcrumb */

    .ac-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 26px;
        color: var(--ac-muted);
        font-size: 14px;
        font-weight: 500;
    }

    .ac-breadcrumb a {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: var(--ac-blue);
        text-decoration: none;
        transition: color .2s ease;
    }

    .ac-breadcrumb a:hover {
        color: var(--ac-navy);
    }

    .ac-breadcrumb .separator {
        color: #aab7c6;
        font-size: 12px;
    }

    .ac-breadcrumb .current {
        color: #53677f;
    }

    /* Page heading */

    .ac-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 30px;
    }

    .ac-heading-main {
        max-width: 650px;
    }

    .ac-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 12px;
        color: var(--ac-blue);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .ac-eyebrow::before {
        content: "";
        width: 25px;
        height: 3px;
        border-radius: 10px;
        background: var(--ac-blue);
    }

    .ac-heading h1 {
        margin: 0;
        color: var(--ac-navy);
        font-size: clamp(28px, 3vw, 38px);
        font-weight: 800;
        letter-spacing: -1.3px;
        line-height: 1.2;
    }

    .ac-heading p {
        margin: 12px 0 0;
        color: var(--ac-muted);
        font-size: 16px;
        line-height: 1.7;
    }

    .ac-heading-mark {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 62px;
        height: 62px;
        border: 1px solid #d9e7f6;
        border-radius: 19px;
        background: var(--ac-blue-soft);
        color: var(--ac-blue);
        font-size: 25px;
    }

    /* Validation */

    .account-create .ac-validation {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-bottom: 24px;
        padding: 17px 19px;
        border: 1px solid #f2c8c8;
        border-radius: 13px;
        background: #fff6f6;
        color: #963b3b;
        animation: ac-appear .35s ease both;
    }

    .ac-validation-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #ffe6e6;
        font-size: 17px;
    }

    .ac-validation strong {
        display: block;
        margin-bottom: 5px;
        font-size: 15px;
    }

    .ac-validation ul {
        margin: 0;
        padding-left: 19px;
        font-size: 14px;
        line-height: 1.8;
    }

    /* Main layout */

    .ac-layout {
        display: grid;
        grid-template-columns: minmax(245px, .72fr) minmax(0, 1.8fr);
        align-items: stretch;
        gap: 22px;
    }

    /* Information panel */

    .ac-info-panel {
        position: relative;
        display: flex;
        flex-direction: column;
        min-height: 100%;
        overflow: hidden;
        padding: 30px 27px;
        border-radius: var(--ac-radius);
        background: var(--ac-navy);
        color: white;
        isolation: isolate;
    }

    .ac-info-panel::before {
        position: absolute;
        z-index: -1;
        top: -105px;
        right: -115px;
        width: 260px;
        height: 260px;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 50%;
        content: "";
    }

    .ac-info-panel::after {
        position: absolute;
        z-index: -1;
        right: -65px;
        bottom: -120px;
        width: 230px;
        height: 230px;
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 50%;
        content: "";
    }

    .ac-info-top {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 35px;
    }

    .ac-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 13px;
        background: rgba(255,255,255,.1);
        font-size: 19px;
    }

    .ac-info-top span {
        display: block;
        color: #c5d9ef;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
    }

    .ac-info-top strong {
        display: block;
        margin-top: 3px;
        font-size: 15px;
        font-weight: 750;
    }

    .ac-info-panel h2 {
        margin: 0 0 13px;
        color: #fff;
        font-size: 25px;
        font-weight: 800;
        letter-spacing: -.6px;
        line-height: 1.3;
    }

    .ac-info-description {
        margin: 0;
        color: #c4d3e4;
        font-size: 14px;
        line-height: 1.8;
    }

    .ac-info-divider {
        height: 1px;
        margin: 27px 0;
        border: 0;
        background: rgba(255,255,255,.16);
    }

    .ac-info-label {
        margin-bottom: 17px;
        color: #a9c5e2;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.3px;
        text-transform: uppercase;
    }

    .ac-role-info {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 19px;
    }

    .ac-role-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 35px;
        height: 35px;
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 10px;
        background: rgba(255,255,255,.09);
        color: #dcecff;
        font-size: 15px;
    }

    .ac-role-info strong {
        display: block;
        margin-bottom: 4px;
        color: #fff;
        font-size: 14px;
    }

    .ac-role-info p {
        margin: 0;
        color: #bdcde0;
        font-size: 12px;
        line-height: 1.65;
    }

    .ac-security-note {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-top: auto;
        padding: 15px;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 13px;
        background: rgba(255,255,255,.07);
        color: #d4e2f0;
        font-size: 12px;
        line-height: 1.7;
    }

    .ac-security-note i {
        margin-top: 2px;
        color: #9bc8fa;
        font-size: 16px;
    }

    /* Form panel */

    .ac-form-panel {
        min-width: 0;
        overflow: hidden;
        border: 1px solid var(--ac-border);
        border-radius: var(--ac-radius);
        background: var(--ac-surface);
        box-shadow: 0 8px 30px rgba(26, 55, 90, .045);
    }

    .ac-form-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 25px 30px;
        border-bottom: 1px solid #e9eef4;
    }

    .ac-form-header h2 {
        margin: 0;
        color: var(--ac-navy);
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .ac-form-header p {
        margin: 6px 0 0;
        color: var(--ac-muted);
        font-size: 13px;
    }

    .ac-required-note {
        flex-shrink: 0;
        color: #77879a;
        font-size: 12px;
    }

    .ac-required-note span {
        color: #dc5656;
        font-weight: 800;
    }

    .ac-form-body {
        padding: 29px 30px 30px;
    }

    .ac-section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
    }

    .ac-section-number {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 33px;
        height: 33px;
        border-radius: 10px;
        background: var(--ac-blue-soft);
        color: var(--ac-blue);
        font-size: 13px;
        font-weight: 800;
    }

    .ac-section-heading h3 {
        margin: 0;
        color: var(--ac-navy);
        font-size: 16px;
        font-weight: 800;
    }

    .ac-section-heading p {
        margin: 3px 0 0;
        color: var(--ac-muted);
        font-size: 12px;
    }

    .ac-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 19px;
    }

    .ac-field {
        min-width: 0;
    }

    .ac-field label {
        display: block;
        margin-bottom: 9px;
        color: #2b4058;
        font-size: 14px;
        font-weight: 700;
    }

    .ac-field label .required {
        color: #d84e58;
        font-weight: 800;
    }

    .ac-input-wrap {
        position: relative;
    }

    .ac-field .form-control,
    .ac-field .form-select {
        width: 100%;
        min-height: 49px;
        padding: 11px 14px;
        border: 1px solid #d8e2ed;
        border-radius: 10px;
        background-color: #fff;
        color: var(--ac-text);
        font-family: inherit;
        font-size: 14px;
        box-shadow: none;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background-color .2s ease;
    }

    .ac-field .form-control::placeholder {
        color: #a2afbd;
        opacity: 1;
    }

    .ac-field .form-control:hover,
    .ac-field .form-select:hover {
        border-color: #aabed4;
    }

    .ac-field .form-control:focus,
    .ac-field .form-select:focus {
        border-color: var(--ac-blue);
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(33,99,165,.11);
        outline: none;
    }

    .ac-field .form-control.is-invalid,
    .ac-field .form-select.is-invalid {
        border-color: #dc6262;
        background-image: none;
    }

    .ac-field .form-control.is-invalid:focus,
    .ac-field .form-select.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(220,98,98,.1);
    }

    .ac-field .invalid-feedback {
        margin-top: 7px;
        font-size: 12px;
    }

    .ac-field .form-text {
        margin-top: 9px;
        color: #78899c;
        font-size: 12px;
        line-height: 1.7;
    }

    .ac-password-toggle {
        position: absolute;
        top: 50%;
        right: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0;
        transform: translateY(-50%);
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #7c8da1;
        cursor: pointer;
        transition: background .2s ease, color .2s ease;
    }

    .ac-password-toggle:hover {
        background: var(--ac-blue-soft);
        color: var(--ac-blue);
    }

    .ac-password-toggle:focus-visible {
        outline: 2px solid var(--ac-blue);
        outline-offset: 2px;
    }

    .ac-password-input {
        padding-right: 50px !important;
    }

    /* Section separator */

    .ac-form-divider {
        display: flex;
        align-items: center;
        gap: 13px;
        margin: 31px 0 26px;
    }

    .ac-form-divider::after {
        flex: 1;
        height: 1px;
        background: #e9eef4;
        content: "";
    }

    /* Password hint */

    .ac-password-hint {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 22px;
        padding: 13px 15px;
        border: 1px solid #dce9f6;
        border-radius: 11px;
        background: #f5f9fe;
        color: #526b85;
        font-size: 12px;
        line-height: 1.7;
    }

    .ac-password-hint i {
        margin-top: 2px;
        color: var(--ac-blue);
        font-size: 15px;
    }

    /* Form actions */

    .ac-form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 29px;
        padding-top: 23px;
        border-top: 1px solid #e9eef4;
    }

    .ac-action-note {
        color: #7c8b9d;
        font-size: 12px;
    }

    .ac-action-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .account-create .ac-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 45px;
        padding: 11px 19px;
        border: 1px solid transparent;
        border-radius: 10px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 750;
        text-decoration: none;
        cursor: pointer;
        transition:
            transform .2s ease,
            background .2s ease,
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .account-create .ac-btn:hover {
        transform: translateY(-2px);
    }

    .account-create .ac-btn:active {
        transform: translateY(0);
    }

    .ac-btn-cancel {
        border-color: #dce4ed !important;
        background: #fff;
        color: #52647a;
    }

    .ac-btn-cancel:hover {
        border-color: #aebed0 !important;
        background: #f7f9fc;
        color: var(--ac-navy);
    }

    .ac-btn-primary {
        background: var(--ac-blue);
        color: #fff;
        box-shadow: 0 5px 13px rgba(33,99,165,.17);
    }

    .ac-btn-primary:hover {
        background: var(--ac-blue-dark);
        color: #fff;
        box-shadow: 0 8px 18px rgba(33,99,165,.23);
    }

    .ac-btn:focus-visible {
        outline: 3px solid rgba(33,99,165,.25);
        outline-offset: 3px;
    }

    /* Animation */

    @keyframes ac-appear {
        from {
            opacity: 0;
            transform: translateY(9px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .ac-heading,
    .ac-info-panel,
    .ac-form-panel {
        animation: ac-appear .45s ease both;
    }

    .ac-form-panel {
        animation-delay: .06s;
    }

    /* Responsive */

    @media (max-width: 991.98px) {
        .ac-layout {
            grid-template-columns: 1fr;
        }

        .ac-info-panel {
            min-height: auto;
        }

        .ac-info-top {
            margin-bottom: 22px;
        }

        .ac-info-panel h2 {
            max-width: 500px;
        }

        .ac-security-note {
            margin-top: 12px;
        }
    }

    @media (max-width: 575.98px) {
        .account-create {
            padding-top: 5px;
        }

        .ac-breadcrumb {
            margin-bottom: 20px;
            font-size: 12px;
        }

        .ac-heading {
            align-items: flex-start;
            margin-bottom: 23px;
        }

        .ac-heading h1 {
            font-size: 29px;
        }

        .ac-heading p {
            font-size: 14px;
        }

        .ac-heading-mark {
            width: 47px;
            height: 47px;
            border-radius: 14px;
            font-size: 19px;
        }

        .ac-info-panel {
            padding: 23px 21px;
            border-radius: 15px;
        }

        .ac-info-panel h2 {
            font-size: 22px;
        }

        .ac-form-panel {
            border-radius: 15px;
        }

        .ac-form-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 21px 20px;
        }

        .ac-form-body {
            padding: 23px 20px;
        }

        .ac-fields {
            grid-template-columns: 1fr;
            gap: 19px;
        }

        .ac-form-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .ac-action-buttons {
            display: grid;
            grid-template-columns: 1fr 1.4fr;
            width: 100%;
        }

        .ac-action-buttons .ac-btn {
            width: 100%;
            padding-right: 10px;
            padding-left: 10px;
        }

        .ac-action-note {
            order: 2;
            text-align: center;
        }

        .ac-required-note {
            font-size: 11px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .account-create *,
        .account-create *::before,
        .account-create *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="account-create">

    {{-- Breadcrumb --}}
    <nav class="ac-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('super-admin.users.index') }}">
            <i class="bi bi-people"></i>
            Manajemen Akun
        </a>

        <span class="separator">
            <i class="bi bi-chevron-right"></i>
        </span>

        <span class="current" aria-current="page">
            Tambah Akun
        </span>
    </nav>

    {{-- Page heading --}}
    <header class="ac-heading">
        <div class="ac-heading-main">
            <div class="ac-eyebrow">
                Administrasi Pengguna
            </div>

            <h1>Tambah Akun Pengguna</h1>

            <p>
                Buat akun baru untuk mendukung pengelolaan
                sistem informasi pelatihan Bapelkes Kalimantan Selatan.
            </p>
        </div>

        <div class="ac-heading-mark" aria-hidden="true">
            <i class="bi bi-person-plus"></i>
        </div>
    </header>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="ac-validation" role="alert">
            <div class="ac-validation-icon">
                <i class="bi bi-exclamation-circle"></i>
            </div>

            <div>
                <strong>Periksa kembali data yang dimasukkan.</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="ac-layout">

        {{-- Information panel --}}
        <aside class="ac-info-panel">

            <div class="ac-info-top">
                <div class="ac-info-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <span>SIRAPIK</span>
                    <strong>Manajemen Akses</strong>
                </div>
            </div>

            <h2>
                Satu akun untuk
                pengelolaan yang lebih terarah.
            </h2>

            <p class="ac-info-description">
                Tambahkan pengguna baru dan tentukan tingkat
                aksesnya sesuai tanggung jawab masing-masing
                dalam sistem SIRAPIK.
            </p>

            <hr class="ac-info-divider">

            <div class="ac-info-label">
                Jenis akses pengguna
            </div>

            <div class="ac-role-info">
                <div class="ac-role-icon">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>

                <div>
                    <strong>Admin</strong>
                    <p>
                        Mengelola data operasional dan aktivitas
                        administrasi sesuai kewenangannya.
                    </p>
                </div>
            </div>

            <div class="ac-role-info">
                <div class="ac-role-icon">
                    <i class="bi bi-bar-chart-line"></i>
                </div>

                <div>
                    <strong>Pimpinan</strong>
                    <p>
                        Memantau informasi dan laporan sesuai
                        hak akses yang diberikan sistem.
                    </p>
                </div>
            </div>

            <div class="ac-security-note">
                <i class="bi bi-lock-fill"></i>

                <span>
                    Pastikan setiap akun menggunakan identitas
                    yang benar dan password yang tidak mudah
                    ditebak.
                </span>
            </div>

        </aside>

        {{-- Form panel --}}
        <section class="ac-form-panel">

            <div class="ac-form-header">
                <div>
                    <h2>Informasi Akun</h2>
                    <p>
                        Lengkapi informasi pengguna pada formulir berikut.
                    </p>
                </div>

                <div class="ac-required-note">
                    <span>*</span> Wajib diisi
                </div>
            </div>

            <div class="ac-form-body">

                <form
                    action="{{ route('super-admin.users.store') }}"
                    method="POST"
                >
                    @csrf

                    {{-- Section 1 --}}
                    <div class="ac-section-heading">
                        <div class="ac-section-number">01</div>

                        <div>
                            <h3>Identitas Pengguna</h3>
                            <p>Informasi dasar akun yang akan dibuat.</p>
                        </div>
                    </div>

                    <div class="ac-fields">

                        {{-- Name --}}
                        <div class="ac-field">
                            <label for="name">
                                Nama Lengkap
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                required
                                maxlength="255"
                                autocomplete="name"
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div class="ac-field">
                            <label for="username">
                                Username
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username') }}"
                                placeholder="Masukkan username"
                                required
                                maxlength="255"
                                autocomplete="username"
                            >

                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="ac-field">
                            <label for="email">
                                Email
                                <span class="required">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="nama@instansi.go.id"
                                required
                                maxlength="255"
                                autocomplete="email"
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div class="ac-field">
                            <label for="role">
                                Role Pengguna
                                <span class="required">*</span>
                            </label>

                            <select
                                name="role"
                                id="role"
                                class="form-select @error('role') is-invalid @enderror"
                                required
                            >
                                <option value="">-- Pilih Role --</option>

                                <option
                                    value="admin"
                                    @selected(old('role') === 'admin')
                                >
                                    Admin
                                </option>

                                <option
                                    value="pimpinan"
                                    @selected(old('role') === 'pimpinan')
                                >
                                    Pimpinan
                                </option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Pilih role berdasarkan tanggung jawab
                                dan kewenangan pengguna.
                            </div>
                        </div>

                    </div>

                    {{-- Section divider --}}
                    <div class="ac-form-divider"></div>

                    {{-- Section 2 --}}
                    <div class="ac-section-heading">
                        <div class="ac-section-number">02</div>

                        <div>
                            <h3>Keamanan Akun</h3>
                            <p>Tentukan password untuk akses pengguna.</p>
                        </div>
                    </div>

                    <div class="ac-fields">

                        {{-- Password --}}
                        <div class="ac-field">
                            <label for="password">
                                Password
                                <span class="required">*</span>
                            </label>

                            <div class="ac-input-wrap">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control ac-password-input @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    class="ac-password-toggle"
                                    data-password-target="password"
                                    aria-label="Tampilkan password"
                                    aria-pressed="false"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password confirmation --}}
                        <div class="ac-field">
                            <label for="password_confirmation">
                                Konfirmasi Password
                                <span class="required">*</span>
                            </label>

                            <div class="ac-input-wrap">
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control ac-password-input"
                                    placeholder="Ulangi password"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    class="ac-password-toggle"
                                    data-password-target="password_confirmation"
                                    aria-label="Tampilkan konfirmasi password"
                                    aria-pressed="false"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="ac-password-hint">
                        <i class="bi bi-info-circle-fill"></i>

                        <span>
                            Gunakan password minimal 8 karakter.
                            Pastikan password konfirmasi sama dengan
                            password yang dimasukkan.
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="ac-form-actions">

                        <div class="ac-action-note">
                            <i class="bi bi-shield-check me-1"></i>
                            Pastikan seluruh data sudah benar.
                        </div>

                        <div class="ac-action-buttons">

                            <a
                                href="{{ route('super-admin.users.index') }}"
                                class="ac-btn ac-btn-cancel"
                            >
                                <i class="bi bi-arrow-left"></i>
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="ac-btn ac-btn-primary"
                            >
                                <i class="bi bi-person-plus"></i>
                                Simpan Akun
                            </button>

                        </div>
                    </div>

                </form>

            </div>
        </section>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButtons = document.querySelectorAll(
            '.account-create .ac-password-toggle'
        );

        toggleButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const targetId = button.getAttribute(
                    'data-password-target'
                );

                const input = document.getElementById(targetId);
                const icon = button.querySelector('i');

                if (!input || !icon) return;

                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';

                button.setAttribute(
                    'aria-pressed',
                    isPassword ? 'true' : 'false'
                );

                button.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Sembunyikan password'
                        : 'Tampilkan password'
                );

                icon.classList.toggle('bi-eye', !isPassword);
                icon.classList.toggle('bi-eye-slash', isPassword);
            });
        });
    });
</script>

@endsection