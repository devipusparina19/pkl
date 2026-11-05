@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')

<style>
    /* =====================================================
       SIRAPIK — EDIT USER PAGE
       Konsisten dengan halaman Tambah Akun
    ===================================================== */

    .account-edit {
        --ae-navy: #102f57;
        --ae-blue: #2163a5;
        --ae-blue-dark: #174c83;
        --ae-blue-soft: #edf4fb;
        --ae-text: #1c2d42;
        --ae-muted: #68798d;
        --ae-border: #dce5ef;

        color: var(--ae-text);
        padding: 12px 0 36px;
        font-family: inherit;
    }

    .account-edit * {
        box-sizing: border-box;
    }

    /* Breadcrumb */

    .ae-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 26px;
        color: var(--ae-muted);
        font-size: 14px;
        font-weight: 500;
    }

    .ae-breadcrumb a {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: var(--ae-blue);
        text-decoration: none;
        transition: color .2s ease;
    }

    .ae-breadcrumb a:hover {
        color: var(--ae-navy);
    }

    .ae-breadcrumb .separator {
        color: #aab7c6;
        font-size: 12px;
    }

    .ae-breadcrumb .current {
        color: #53677f;
    }

    /* Heading */

    .ae-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
    }

    .ae-heading-main {
        max-width: 680px;
    }

    .ae-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 12px;
        color: var(--ae-blue);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .ae-eyebrow::before {
        width: 25px;
        height: 3px;
        border-radius: 10px;
        background: var(--ae-blue);
        content: "";
    }

    .ae-heading h1 {
        margin: 0;
        color: var(--ae-navy);
        font-size: clamp(28px, 3vw, 38px);
        font-weight: 800;
        letter-spacing: -1.3px;
        line-height: 1.2;
    }

    .ae-heading p {
        margin: 12px 0 0;
        color: var(--ae-muted);
        font-size: 16px;
        line-height: 1.7;
    }

    .ae-heading-mark {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 62px;
        height: 62px;
        border: 1px solid #d9e7f6;
        border-radius: 19px;
        background: var(--ae-blue-soft);
        color: var(--ae-blue);
        font-size: 25px;
    }

    /* Validation */

    .account-edit .ae-validation {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-bottom: 24px;
        padding: 17px 19px;
        border: 1px solid #f2c8c8;
        border-radius: 13px;
        background: #fff6f6;
        color: #963b3b;
        animation: ae-appear .35s ease both;
    }

    .ae-validation-icon {
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

    .ae-validation strong {
        display: block;
        margin-bottom: 5px;
        font-size: 15px;
    }

    .ae-validation ul {
        margin: 0;
        padding-left: 19px;
        font-size: 14px;
        line-height: 1.8;
    }

    /* Main layout */

    .ae-layout {
        display: grid;
        grid-template-columns: minmax(245px, .72fr) minmax(0, 1.8fr);
        align-items: stretch;
        gap: 22px;
    }

    /* Profile information panel */

    .ae-profile-panel {
        position: relative;
        display: flex;
        flex-direction: column;
        min-height: 100%;
        overflow: hidden;
        padding: 30px 27px;
        border-radius: 18px;
        background: var(--ae-navy);
        color: #fff;
        isolation: isolate;
    }

    .ae-profile-panel::before {
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

    .ae-profile-panel::after {
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

    .ae-profile-top {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 29px;
    }

    .ae-profile-icon {
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

    .ae-profile-top span {
        display: block;
        color: #c5d9ef;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
    }

    .ae-profile-top strong {
        display: block;
        margin-top: 3px;
        font-size: 15px;
        font-weight: 750;
    }

    .ae-profile-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 76px;
        height: 76px;
        margin-bottom: 19px;
        border: 1px solid rgba(255,255,255,.24);
        border-radius: 23px;
        background: rgba(255,255,255,.11);
        color: #fff;
        font-size: 29px;
        font-weight: 800;
    }

    .ae-profile-name {
        overflow-wrap: anywhere;
        margin: 0 0 7px;
        color: #fff;
        font-size: 23px;
        font-weight: 800;
        letter-spacing: -.5px;
        line-height: 1.3;
    }

    .ae-profile-username {
        overflow-wrap: anywhere;
        margin: 0;
        color: #c3d4e7;
        font-size: 14px;
    }

    .ae-profile-divider {
        height: 1px;
        margin: 26px 0;
        border: 0;
        background: rgba(255,255,255,.16);
    }

    .ae-profile-label {
        margin-bottom: 13px;
        color: #a9c5e2;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.3px;
        text-transform: uppercase;
    }

    .ae-current-role {
        display: inline-flex;
        align-items: center;
        align-self: flex-start;
        gap: 8px;
        padding: 9px 13px;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 9px;
        background: rgba(255,255,255,.1);
        color: #fff;
        font-size: 13px;
        font-weight: 750;
    }

    .ae-current-role i {
        color: #a8d1ff;
    }

    .ae-profile-description {
        margin: 14px 0 0;
        color: #c4d3e4;
        font-size: 13px;
        line-height: 1.8;
    }

    .ae-profile-note {
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

    .ae-profile-note i {
        margin-top: 2px;
        color: #9bc8fa;
        font-size: 16px;
    }

    /* Form panel */

    .ae-form-panel {
        min-width: 0;
        overflow: hidden;
        border: 1px solid var(--ae-border);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 30px rgba(26,55,90,.045);
    }

    .ae-form-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 25px 30px;
        border-bottom: 1px solid #e9eef4;
    }

    .ae-form-header h2 {
        margin: 0;
        color: var(--ae-navy);
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .ae-form-header p {
        margin: 6px 0 0;
        color: var(--ae-muted);
        font-size: 13px;
    }

    .ae-edit-status {
        display: inline-flex;
        align-items: center;
        flex-shrink: 0;
        gap: 7px;
        padding: 8px 11px;
        border: 1px solid #d7e9df;
        border-radius: 9px;
        background: #f1faf4;
        color: #32754a;
        font-size: 12px;
        font-weight: 750;
    }

    .ae-edit-status i {
        font-size: 13px;
    }

    .ae-form-body {
        padding: 29px 30px 30px;
    }

    .ae-section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
    }

    .ae-section-number {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 33px;
        height: 33px;
        border-radius: 10px;
        background: var(--ae-blue-soft);
        color: var(--ae-blue);
        font-size: 13px;
        font-weight: 800;
    }

    .ae-section-heading h3 {
        margin: 0;
        color: var(--ae-navy);
        font-size: 16px;
        font-weight: 800;
    }

    .ae-section-heading p {
        margin: 3px 0 0;
        color: var(--ae-muted);
        font-size: 12px;
    }

    .ae-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 19px;
    }

    .ae-field {
        min-width: 0;
    }

    .ae-field label {
        display: block;
        margin-bottom: 9px;
        color: #2b4058;
        font-size: 14px;
        font-weight: 700;
    }

    .ae-field label .required {
        color: #d84e58;
        font-weight: 800;
    }

    .ae-input-wrap {
        position: relative;
    }

    .ae-field .form-control,
    .ae-field .form-select {
        width: 100%;
        min-height: 49px;
        padding: 11px 14px;
        border: 1px solid #d8e2ed;
        border-radius: 10px;
        background-color: #fff;
        color: var(--ae-text);
        font-family: inherit;
        font-size: 14px;
        box-shadow: none;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background-color .2s ease;
    }

    .ae-field .form-control::placeholder {
        color: #a2afbd;
        opacity: 1;
    }

    .ae-field .form-control:hover,
    .ae-field .form-select:hover {
        border-color: #aabed4;
    }

    .ae-field .form-control:focus,
    .ae-field .form-select:focus {
        border-color: var(--ae-blue);
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(33,99,165,.11);
        outline: none;
    }

    .ae-field .form-control.is-invalid,
    .ae-field .form-select.is-invalid {
        border-color: #dc6262;
        background-image: none;
    }

    .ae-field .form-control.is-invalid:focus,
    .ae-field .form-select.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(220,98,98,.1);
    }

    .ae-field .invalid-feedback {
        margin-top: 7px;
        font-size: 12px;
    }

    .ae-field .form-text {
        margin-top: 9px;
        color: #78899c;
        font-size: 12px;
        line-height: 1.7;
    }

    /* Password visibility */

    .ae-password-input {
        padding-right: 50px !important;
    }

    .ae-password-toggle {
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

    .ae-password-toggle:hover {
        background: var(--ae-blue-soft);
        color: var(--ae-blue);
    }

    .ae-password-toggle:focus-visible {
        outline: 2px solid var(--ae-blue);
        outline-offset: 2px;
    }

    /* Password optional information */

    .ae-password-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 22px;
        padding: 14px 15px;
        border: 1px solid #dce9f6;
        border-radius: 11px;
        background: #f5f9fe;
        color: #526b85;
        font-size: 13px;
        line-height: 1.7;
    }

    .ae-password-notice i {
        margin-top: 2px;
        color: var(--ae-blue);
        font-size: 16px;
    }

    /* Separator */

    .ae-form-divider {
        display: flex;
        align-items: center;
        gap: 13px;
        margin: 31px 0 26px;
    }

    .ae-form-divider::after {
        flex: 1;
        height: 1px;
        background: #e9eef4;
        content: "";
    }

    /* Actions */

    .ae-form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 30px;
        padding-top: 23px;
        border-top: 1px solid #e9eef4;
    }

    .ae-action-note {
        color: #7c8b9d;
        font-size: 12px;
    }

    .ae-action-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .account-edit .ae-btn {
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

    .account-edit .ae-btn:hover {
        transform: translateY(-2px);
    }

    .account-edit .ae-btn:active {
        transform: translateY(0);
    }

    .ae-btn-cancel {
        border-color: #dce4ed !important;
        background: #fff;
        color: #52647a;
    }

    .ae-btn-cancel:hover {
        border-color: #aebed0 !important;
        background: #f7f9fc;
        color: var(--ae-navy);
    }

    .ae-btn-primary {
        background: var(--ae-blue);
        color: #fff;
        box-shadow: 0 5px 13px rgba(33,99,165,.17);
    }

    .ae-btn-primary:hover {
        background: var(--ae-blue-dark);
        color: #fff;
        box-shadow: 0 8px 18px rgba(33,99,165,.23);
    }

    .ae-btn:focus-visible {
        outline: 3px solid rgba(33,99,165,.25);
        outline-offset: 3px;
    }

    /* Animation */

    @keyframes ae-appear {
        from {
            opacity: 0;
            transform: translateY(9px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .ae-heading,
    .ae-profile-panel,
    .ae-form-panel {
        animation: ae-appear .45s ease both;
    }

    .ae-form-panel {
        animation-delay: .06s;
    }

    /* Responsive */

    @media (max-width: 991.98px) {
        .ae-layout {
            grid-template-columns: 1fr;
        }

        .ae-profile-panel {
            min-height: auto;
        }

        .ae-profile-note {
            margin-top: 20px;
        }
    }

    @media (max-width: 575.98px) {
        .account-edit {
            padding-top: 5px;
        }

        .ae-breadcrumb {
            margin-bottom: 20px;
            font-size: 12px;
        }

        .ae-heading {
            align-items: flex-start;
            margin-bottom: 23px;
        }

        .ae-heading h1 {
            font-size: 29px;
        }

        .ae-heading p {
            font-size: 14px;
        }

        .ae-heading-mark {
            width: 47px;
            height: 47px;
            border-radius: 14px;
            font-size: 19px;
        }

        .ae-profile-panel {
            padding: 23px 21px;
            border-radius: 15px;
        }

        .ae-profile-name {
            font-size: 21px;
        }

        .ae-form-panel {
            border-radius: 15px;
        }

        .ae-form-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 21px 20px;
        }

        .ae-form-body {
            padding: 23px 20px;
        }

        .ae-fields {
            grid-template-columns: 1fr;
            gap: 19px;
        }

        .ae-form-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .ae-action-buttons {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            width: 100%;
        }

        .ae-action-buttons .ae-btn {
            width: 100%;
            padding-right: 9px;
            padding-left: 9px;
        }

        .ae-action-note {
            order: 2;
            text-align: center;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .account-edit *,
        .account-edit *::before,
        .account-edit *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="account-edit">

    {{-- Breadcrumb --}}
    <nav class="ae-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('super-admin.users.index') }}">
            <i class="bi bi-people"></i>
            Manajemen Akun
        </a>

        <span class="separator">
            <i class="bi bi-chevron-right"></i>
        </span>

        <span class="current" aria-current="page">
            Edit Akun
        </span>
    </nav>

    {{-- Page heading --}}
    <header class="ae-heading">
        <div class="ae-heading-main">
            <div class="ae-eyebrow">
                Administrasi Pengguna
            </div>

            <h1>Edit Akun Pengguna</h1>

            <p>
                Perbarui informasi identitas, hak akses, dan keamanan
                akun pengguna SIRAPIK.
            </p>
        </div>

        <div class="ae-heading-mark" aria-hidden="true">
            <i class="bi bi-person-gear"></i>
        </div>
    </header>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="ae-validation" role="alert">
            <div class="ae-validation-icon">
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

    <div class="ae-layout">

        {{-- Existing user information --}}
        <aside class="ae-profile-panel">

            <div class="ae-profile-top">
                <div class="ae-profile-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <span>SIRAPIK</span>
                    <strong>Profil Pengguna</strong>
                </div>
            </div>

            <div class="ae-profile-avatar" aria-hidden="true">
                <i class="bi bi-person"></i>
            </div>

            <h2 class="ae-profile-name">
                {{ $user->name }}
            </h2>

            <p class="ae-profile-username">
                <i class="bi bi-at me-1"></i>{{ $user->username }}
            </p>

            <hr class="ae-profile-divider">

            <div class="ae-profile-label">
                Role saat ini
            </div>

            <div class="ae-current-role">
                <i class="bi bi-person-badge"></i>

                {{ ucfirst($user->role) }}
            </div>

            <p class="ae-profile-description">
                Informasi ini menunjukkan akun yang sedang diperbarui.
                Pastikan perubahan identitas dan hak akses sesuai
                dengan tanggung jawab pengguna.
            </p>

            <div class="ae-profile-note">
                <i class="bi bi-info-circle-fill"></i>

                <span>
                    Role Super Admin tidak dapat ditetapkan melalui
                    formulir ini. Pilihan akses hanya Admin dan Pimpinan.
                </span>
            </div>

        </aside>

        {{-- Edit form --}}
        <section class="ae-form-panel">

            <div class="ae-form-header">
                <div>
                    <h2>Informasi Akun</h2>
                    <p>
                        Perbarui informasi pengguna melalui formulir berikut.
                    </p>
                </div>

                <div class="ae-edit-status">
                    <i class="bi bi-pencil-square"></i>
                    Mode Edit
                </div>
            </div>

            <div class="ae-form-body">

                <form
                    action="{{ route('super-admin.users.update', $user->id) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    {{-- Section 1 --}}
                    <div class="ae-section-heading">
                        <div class="ae-section-number">01</div>

                        <div>
                            <h3>Identitas Pengguna</h3>
                            <p>Perbarui informasi dasar akun.</p>
                        </div>
                    </div>

                    <div class="ae-fields">

                        {{-- Name --}}
                        <div class="ae-field">
                            <label for="name">
                                Nama Lengkap
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}"
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
                        <div class="ae-field">
                            <label for="username">
                                Username
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}"
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
                        <div class="ae-field">
                            <label for="email">
                                Email
                                <span class="required">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}"
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
                        <div class="ae-field">
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
                                <option
                                    value="admin"
                                    @selected(old('role', $user->role) === 'admin')
                                >
                                    Admin
                                </option>

                                <option
                                    value="pimpinan"
                                    @selected(old('role', $user->role) === 'pimpinan')
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
                                Role Super Admin tidak dapat ditetapkan
                                melalui formulir ini.
                            </div>
                        </div>

                    </div>

                    {{-- Divider --}}
                    <div class="ae-form-divider"></div>

                    {{-- Section 2 --}}
                    <div class="ae-section-heading">
                        <div class="ae-section-number">02</div>

                        <div>
                            <h3>Keamanan Akun</h3>
                            <p>Perbarui password jika diperlukan.</p>
                        </div>
                    </div>

                    <div class="ae-password-notice">
                        <i class="bi bi-info-circle-fill"></i>

                        <span>
                            Password bersifat opsional. Kosongkan kedua
                            kolom password apabila tidak ingin mengubah
                            password akun ini.
                        </span>
                    </div>

                    <div class="ae-fields">

                        {{-- New password --}}
                        <div class="ae-field">
                            <label for="password">
                                Password Baru
                            </label>

                            <div class="ae-input-wrap">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control ae-password-input @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter"
                                    minlength="8"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    class="ae-password-toggle"
                                    data-password-target="password"
                                    aria-label="Tampilkan password baru"
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

                        {{-- Confirmation --}}
                        <div class="ae-field">
                            <label for="password_confirmation">
                                Konfirmasi Password Baru
                            </label>

                            <div class="ae-input-wrap">
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control ae-password-input"
                                    placeholder="Ulangi password baru"
                                    minlength="8"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    class="ae-password-toggle"
                                    data-password-target="password_confirmation"
                                    aria-label="Tampilkan konfirmasi password"
                                    aria-pressed="false"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="ae-form-actions">

                        <div class="ae-action-note">
                            <i class="bi bi-shield-check me-1"></i>
                            Periksa kembali perubahan sebelum disimpan.
                        </div>

                        <div class="ae-action-buttons">

                            <a
                                href="{{ route('super-admin.users.index') }}"
                                class="ae-btn ae-btn-cancel"
                            >
                                <i class="bi bi-arrow-left"></i>
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="ae-btn ae-btn-primary"
                            >
                                <i class="bi bi-save"></i>
                                Simpan Perubahan
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
            '.account-edit .ae-password-toggle'
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