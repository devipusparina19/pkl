@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       EDIT AKUN — SIRAPIK (Premium Blue Editorial)
    ===================================================== */
    .ua {
        --navy: #081b33; --midnight: #0b2545; --royal: #2457d6;
        --electric: #3478f6; --soft: #eaf2ff; --ice: #f3f7fc;
        --slate: #64748b; --ink: #14243a; --line: #e1e9f2; --cyan: #38c6e8;
        --red: #b42333; --red-soft: #fef2f2; --red-line: #fecaca;
        --f-display: 'Sora', system-ui, sans-serif;
        --f-body: 'Plus Jakarta Sans', system-ui, sans-serif;
        --ease: cubic-bezier(.2, .7, .3, 1);

        width: 100%;
        color: var(--ink);
        font-family: var(--f-body);
        font-size: 16px;
        line-height: 1.7;
    }

    .ua *, .ua *::before, .ua *::after { box-sizing: border-box; }
    .ua svg { display: block; flex: 0 0 auto; }
    .ua input, .ua select, .ua button { font: inherit; }
    .ua [hidden] { display: none !important; }

    .ua :focus-visible {
        outline: 3px solid rgba(52, 120, 246, .55);
        outline-offset: 3px;
        border-radius: 8px;
    }

    .ua-progress {
        position: fixed; top: 0; left: 0; z-index: 60;
        width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--electric), var(--cyan));
        transform: scaleX(0); transform-origin: 0 50%;
        pointer-events: none;
    }

    .ua.is-js [data-reveal] {
        opacity: 0; transform: translateY(20px);
        transition: opacity .7s var(--ease), transform .7s var(--ease);
    }
    .ua.is-js [data-reveal].is-in { opacity: 1; transform: none; }

    /* Breadcrumb */
    .ua-crumb {
        display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
        margin-bottom: 20px; color: var(--slate); font-size: 14px; font-weight: 600;
    }
    .ua-crumb a {
        display: inline-flex; align-items: center; gap: 8px;
        color: var(--royal); text-decoration: none; transition: color .25s;
    }
    .ua-crumb a:hover { color: var(--navy); }
    .ua-crumb svg { width: 16px; height: 16px; }
    .ua-crumb .sep svg { width: 14px; height: 14px; color: #a6b4c6; }

    /* =====================================================
       HERO
    ===================================================== */
    .ua-hero {
        position: relative; isolation: isolate; overflow: hidden;
        margin-bottom: 28px; padding: clamp(28px, 5vw, 52px);
        color: #fff; border-radius: 24px;
        background: linear-gradient(160deg, var(--navy) 0%, var(--midnight) 100%);
    }

    .ua-hero::before {
        content: ""; position: absolute; inset: 0; z-index: -2; opacity: .1;
        background-image:
            linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px);
        background-size: 56px 56px;
        -webkit-mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
        mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
    }

    .ua-orbit {
        position: absolute; z-index: -1; right: -150px; bottom: -190px;
        width: 460px; height: 460px; border-radius: 50%;
        border: 1px solid rgba(130,183,255,.25);
        box-shadow: 0 0 0 54px rgba(130,183,255,.04), 0 0 0 108px rgba(130,183,255,.025);
        animation: ua-float 14s ease-in-out infinite;
    }
    @keyframes ua-float { 50% { transform: translateY(-18px); } }

    .ua-eyebrow {
        display: inline-flex; align-items: center; gap: 12px;
        margin-bottom: 20px; color: #bcd8ff; font-size: 14px; font-weight: 700;
    }
    .ua-eyebrow::before { content: ""; width: 32px; height: 2px; background: var(--cyan); }

    .ua-hero-grid {
        display: grid; align-items: end; gap: 36px;
        grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
    }

    .ua-hero h1 {
        margin: 0; font-family: var(--f-display);
        font-size: clamp(34px, 5.2vw, 68px);
        font-weight: 800; letter-spacing: -.05em; line-height: 1.02;
    }
    .ua-hero h1 span { display: block; color: #82b7ff; }

    .ua-hero > .ua-hero-grid p {
        max-width: 52ch; margin: 20px 0 0;
        color: rgba(255,255,255,.76); font-size: clamp(16px, 1.3vw, 18px); line-height: 1.8;
    }

    /* Identitas akun */
    .ua-user { display: flex; align-items: center; gap: 18px; padding-top: 18px; border-top: 1px solid rgba(255,255,255,.2); }
    .ua-avatar {
        display: grid; place-items: center; width: 68px; height: 68px;
        border-radius: 20px; background: #fff; color: var(--royal);
        font-family: var(--f-display); font-size: 26px; font-weight: 800; letter-spacing: -.03em;
    }
    .ua-user-info { min-width: 0; }
    .ua-user-name {
        margin: 0; font-family: var(--f-display); font-size: clamp(18px, 2vw, 24px);
        font-weight: 700; letter-spacing: -.02em; line-height: 1.3; overflow-wrap: anywhere;
    }
    .ua-user-handle { margin: 2px 0 0; color: rgba(255,255,255,.72); font-size: 14px; overflow-wrap: anywhere; }
    .ua-role {
        display: inline-flex; align-items: center; gap: 8px; margin-top: 10px; padding: 5px 12px;
        border: 1px solid rgba(255,255,255,.2); border-radius: 999px;
        background: rgba(255,255,255,.07); color: #bcd8ff; font-size: 13px; font-weight: 600;
    }
    .ua-role svg { width: 14px; height: 14px; }

    /* =====================================================
       ALERT
    ===================================================== */
    .ua-alert {
        display: flex; align-items: flex-start; gap: 14px; margin-bottom: 24px;
        padding: 18px 20px; color: var(--red);
        border: 1px solid var(--red-line); border-left: 4px solid #d74747;
        border-radius: 14px; background: var(--red-soft); font-size: 14px; line-height: 1.7;
    }
    .ua-alert > svg { width: 22px; height: 22px; margin-top: 2px; }
    .ua-alert strong { display: block; font-family: var(--f-display); font-size: 16px; font-weight: 700; }
    .ua-alert ul { margin: 8px 0 0; padding-left: 20px; }
    .ua-alert li { overflow-wrap: anywhere; }

    /* =====================================================
       FORM — SECTION SPLIT
    ===================================================== */
    .ua-section {
        display: grid; gap: 28px 56px;
        grid-template-columns: minmax(0, .5fr) minmax(0, 1.5fr);
        padding: 36px 0; border-top: 2px solid var(--navy);
    }

    .ua-section-head { align-self: start; position: sticky; top: 90px; }
    .ua-num { display: block; margin-bottom: 10px; color: var(--royal); font-family: var(--f-display); font-size: 15px; font-weight: 800; }
    .ua-section-head h2 {
        margin: 0; color: var(--navy); font-family: var(--f-display);
        font-size: clamp(24px, 2.6vw, 34px); font-weight: 800; letter-spacing: -.04em; line-height: 1.12;
    }
    .ua-section-head p { margin: 10px 0 0; color: var(--slate); font-size: 14px; line-height: 1.7; }

    .ua-grid { display: grid; gap: 22px 24px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .ua-field { min-width: 0; }
    .ua-full { grid-column: 1 / -1; }

    .ua-field label {
        display: flex; align-items: center; flex-wrap: wrap; gap: 6px;
        margin-bottom: 9px; color: #40536a; font-size: 14px; font-weight: 700;
    }
    .ua-req { color: var(--red); font-size: 16px; line-height: 1; }

    .ua .ua-control {
        display: block; width: 100%; min-width: 0; height: 56px;
        padding: 0 16px; border: 1.5px solid var(--line); border-radius: 14px;
        outline: none; background: #fff; color: var(--ink); font-size: 16px;
        transition: border-color .25s, box-shadow .25s;
    }
    .ua .ua-control::placeholder { color: #8998ab; }
    .ua .ua-control:hover { border-color: #a6bddb; }
    .ua .ua-control:focus { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.12); }
    .ua .ua-control.input-error { border-color: #e48a93; background: var(--red-soft); }
    .ua select.ua-control { padding-right: 40px; cursor: pointer; }

    .ua-help { display: block; margin-top: 8px; color: var(--slate); font-size: 13px; line-height: 1.6; }

    .ua-error {
        display: flex; align-items: flex-start; gap: 7px;
        margin: 8px 0 0; color: var(--red); font-size: 13px; font-weight: 600; line-height: 1.5;
    }

    /* Password */
    .ua-pw { position: relative; }
    .ua-pw .ua-control { padding-right: 60px; }
    .ua-toggle {
        position: absolute; top: 50%; right: 6px; display: grid; place-items: center;
        width: 44px; height: 44px; padding: 0; border: 0; border-radius: 10px;
        background: transparent; color: #8394aa; cursor: pointer; transform: translateY(-50%);
        transition: background .25s, color .25s;
    }
    .ua-toggle:hover { background: var(--soft); color: var(--royal); }
    .ua-toggle svg { width: 21px; height: 21px; }

    .ua-notice {
        display: flex; align-items: flex-start; gap: 14px; margin-bottom: 22px;
        padding: 16px 18px; border: 1px solid #cfe0fb; border-left: 4px solid var(--royal);
        border-radius: 14px; background: var(--ice); color: #40536a; font-size: 14px; line-height: 1.7;
    }
    .ua-notice svg { width: 22px; height: 22px; margin-top: 2px; color: var(--royal); }

    /* Aksi */
    .ua-footer {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px;
        padding: 26px 0 0; border-top: 2px solid var(--navy);
    }
    .ua-note { display: flex; align-items: flex-start; gap: 10px; max-width: 60ch; color: var(--slate); font-size: 13px; line-height: 1.7; }
    .ua-note svg { width: 18px; height: 18px; margin-top: 2px; color: var(--royal); }
    .ua-note b { color: var(--red); }
    .ua-actions { display: flex; align-items: center; gap: 12px; }

    .ua .ua-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        min-height: 54px; padding: 12px 26px; border: 1.5px solid transparent; border-radius: 999px;
        font-size: 15px; font-weight: 700; text-decoration: none; cursor: pointer;
        transition: transform .25s var(--ease), box-shadow .25s, background .25s, color .25s, border-color .25s;
    }
    .ua .ua-btn svg { width: 18px; height: 18px; transition: transform .25s var(--ease); }
    .ua .ua-btn:active { transform: scale(.98); }
    .ua .ua-btn-primary { background: var(--royal); color: #fff; }
    .ua .ua-btn-primary:hover { background: var(--navy); transform: translateY(-2px); box-shadow: 0 14px 30px rgba(36,87,214,.28); }
    .ua .ua-btn-primary:disabled { background: #7f9bd8; box-shadow: none; transform: none; cursor: wait; }
    .ua .ua-btn-ghost { background: transparent; border-color: var(--line); color: #40536a; }
    .ua .ua-btn-ghost:hover { border-color: var(--royal); color: var(--royal); }
    .ua .ua-btn-ghost:hover svg { transform: translateX(-3px); }

    @keyframes ua-spin { to { transform: rotate(360deg); } }
    .ua .is-spin { animation: ua-spin 1s linear infinite; }

    /* =====================================================
       RESPONSIVE
    ===================================================== */
    @media (max-width: 1000px) {
        .ua-hero-grid { grid-template-columns: 1fr; }
        .ua-section { grid-template-columns: 1fr; gap: 22px; padding: 30px 0; }
        .ua-section-head { position: static; }
    }

    @media (max-width: 640px) {
        .ua-hero { border-radius: 18px; }
        .ua-grid { grid-template-columns: 1fr; gap: 20px; }
        .ua-full { grid-column: auto; }
        .ua .ua-control { height: 54px; }
        .ua-user { align-items: flex-start; }
        .ua-avatar { width: 56px; height: 56px; border-radius: 16px; font-size: 21px; }
        .ua-footer { flex-direction: column; align-items: stretch; }
        .ua-actions { flex-direction: column-reverse; align-items: stretch; }
        .ua .ua-btn { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .ua *, .ua *::before, .ua *::after {
            animation: none !important;
            transition-duration: .01ms !important;
        }
        .ua.is-js [data-reveal] { opacity: 1; transform: none; }
    }
</style>

@php
    $initials = collect(preg_split('/\s+/', trim($user->name ?? '')))
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');

    $initials = $initials ?: 'U';
@endphp

<div class="ua" id="ua">

    <div class="ua-progress" id="uaProgress" aria-hidden="true"></div>

    {{-- Breadcrumb --}}
    <nav class="ua-crumb" aria-label="Breadcrumb">
        <a href="{{ route('super-admin.users.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Manajemen Akun
        </a>

        <span class="sep" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6"/>
            </svg>
        </span>

        <span aria-current="page">Edit Akun</span>
    </nav>

    {{-- =================================================
         HERO
    ================================================== --}}
    <header class="ua-hero" data-reveal>
        <div class="ua-orbit" aria-hidden="true"></div>

        <span class="ua-eyebrow">Administrasi Pengguna</span>

        <div class="ua-hero-grid">

            <div>
                <h1>Edit Akun <span>Pengguna</span></h1>

                <p>
                    Perbarui informasi identitas, hak akses, dan keamanan
                    akun pengguna SIRAPIK.
                </p>
            </div>

            <div class="ua-user">
                <div class="ua-avatar" aria-hidden="true">{{ $initials }}</div>

                <div class="ua-user-info">
                    <p class="ua-user-name">{{ $user->name }}</p>
                    <p class="ua-user-handle">{{ '@' . $user->username }}</p>

                    <span class="ua-role">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3 4 7v5c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-4z"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                        Role saat ini: {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

        </div>
    </header>

    {{-- =================================================
         VALIDATION ERRORS
    ================================================== --}}
    @if ($errors->any())
        <div class="ua-alert" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4"/>
                <path d="M12 16h.01"/>
            </svg>

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

    {{-- =================================================
         FORM
    ================================================== --}}
    <form
        id="userForm"
        action="{{ route('super-admin.users.update', $user->id) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        {{-- 01 IDENTITAS --}}
        <section class="ua-section" data-reveal aria-labelledby="uaIdentitas">
            <div class="ua-section-head">
                <span class="ua-num">01</span>
                <h2 id="uaIdentitas">Identitas Pengguna</h2>
                <p>Perbarui informasi dasar dan hak akses akun.</p>
            </div>

            <div class="ua-grid">

                {{-- Name --}}
                <div class="ua-field">
                    <label for="name">
                        Nama Lengkap <span class="ua-req" aria-hidden="true">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="ua-control @error('name') input-error @enderror"
                        value="{{ old('name', $user->name) }}"
                        placeholder="Masukkan nama lengkap"
                        required
                        maxlength="255"
                        autocomplete="name"
                        @error('name') aria-invalid="true" @enderror
                    >

                    @error('name')
                        <div class="ua-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="ua-field">
                    <label for="username">
                        Username <span class="ua-req" aria-hidden="true">*</span>
                    </label>

                    <input
                        type="text"
                        name="username"
                        id="username"
                        class="ua-control @error('username') input-error @enderror"
                        value="{{ old('username', $user->username) }}"
                        placeholder="Masukkan username"
                        required
                        maxlength="255"
                        autocomplete="username"
                        @error('username') aria-invalid="true" @enderror
                    >

                    @error('username')
                        <div class="ua-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="ua-field">
                    <label for="email">
                        Email <span class="ua-req" aria-hidden="true">*</span>
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="ua-control @error('email') input-error @enderror"
                        value="{{ old('email', $user->email) }}"
                        placeholder="nama@instansi.go.id"
                        required
                        maxlength="255"
                        autocomplete="email"
                        @error('email') aria-invalid="true" @enderror
                    >

                    @error('email')
                        <div class="ua-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="ua-field">
                    <label for="role">
                        Role Pengguna <span class="ua-req" aria-hidden="true">*</span>
                    </label>

                    <select
                        name="role"
                        id="role"
                        class="ua-control @error('role') input-error @enderror"
                        required
                        @error('role') aria-invalid="true" @enderror
                    >
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>
                            Admin
                        </option>

                        <option value="pimpinan" @selected(old('role', $user->role) === 'pimpinan')>
                            Pimpinan
                        </option>
                    </select>

                    @error('role')
                        <div class="ua-error">{{ $message }}</div>
                    @enderror

                    <small class="ua-help">
                        Role Super Admin tidak dapat ditetapkan melalui
                        formulir ini.
                    </small>
                </div>

            </div>
        </section>

        {{-- 02 KEAMANAN --}}
        <section class="ua-section" data-reveal aria-labelledby="uaKeamanan">
            <div class="ua-section-head">
                <span class="ua-num">02</span>
                <h2 id="uaKeamanan">Keamanan Akun</h2>
                <p>Perbarui password jika diperlukan.</p>
            </div>

            <div>
                <div class="ua-notice">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8h.01"/>
                    </svg>

                    <span>
                        Password bersifat opsional. Kosongkan kedua kolom
                        password apabila tidak ingin mengubah password
                        akun ini.
                    </span>
                </div>

                <div class="ua-grid">

                    {{-- New password --}}
                    <div class="ua-field">
                        <label for="password">Password Baru</label>

                        <div class="ua-pw">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="ua-control @error('password') input-error @enderror"
                                placeholder="Minimal 8 karakter"
                                minlength="8"
                                autocomplete="new-password"
                                @error('password') aria-invalid="true" @enderror
                            >

                            <button
                                type="button"
                                class="ua-toggle"
                                data-password-target="password"
                                aria-label="Tampilkan password baru"
                                aria-pressed="false"
                            >
                                <svg data-eye="closed" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 3l18 18"/>
                                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                    <path d="M9.9 5.1A10.9 10.9 0 0 1 12 4.9c5 0 8.3 4.1 9 7.1a9.7 9.7 0 0 1-2 3.7"/>
                                    <path d="M6.6 6.6A11.8 11.8 0 0 0 3 12c.7 3 4 7.1 9 7.1a9.8 9.8 0 0 0 5.4-1.6"/>
                                </svg>

                                <svg data-eye="open" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true" hidden>
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <div class="ua-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirmation --}}
                    <div class="ua-field">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>

                        <div class="ua-pw">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="ua-control"
                                placeholder="Ulangi password baru"
                                minlength="8"
                                autocomplete="new-password"
                            >

                            <button
                                type="button"
                                class="ua-toggle"
                                data-password-target="password_confirmation"
                                aria-label="Tampilkan konfirmasi password"
                                aria-pressed="false"
                            >
                                <svg data-eye="closed" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 3l18 18"/>
                                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                                    <path d="M9.9 5.1A10.9 10.9 0 0 1 12 4.9c5 0 8.3 4.1 9 7.1a9.7 9.7 0 0 1-2 3.7"/>
                                    <path d="M6.6 6.6A11.8 11.8 0 0 0 3 12c.7 3 4 7.1 9 7.1a9.8 9.8 0 0 0 5.4-1.6"/>
                                </svg>

                                <svg data-eye="open" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true" hidden>
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- AKSI --}}
        <div class="ua-footer" data-reveal>

            <div class="ua-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3 4 7v5c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-4z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>

                <span>
                    Kolom bertanda <b>*</b> wajib diisi. Periksa kembali
                    perubahan sebelum disimpan.
                </span>
            </div>

            <div class="ua-actions">
                <a href="{{ route('super-admin.users.index') }}" class="ua-btn ua-btn-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Batal
                </a>

                <button type="submit" class="ua-btn ua-btn-primary" id="saveUserButton">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                        <path d="M17 21v-8H7v8M7 3v5h8"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </div>

    </form>

</div>

<script>
(function () {
    function initializeUserForm() {
        const page = document.getElementById('ua');

        if (!page || page.dataset.initialized === 'true') {
            return;
        }

        page.dataset.initialized = 'true';

        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* Scroll reveal */
        if ('IntersectionObserver' in window && !reduce) {
            page.classList.add('is-js');

            const io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-in');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: .1, rootMargin: '0px 0px -40px 0px' });

            page.querySelectorAll('[data-reveal]').forEach(function (el) {
                io.observe(el);
            });
        }

        /* Progress bar scroll */
        const bar = document.getElementById('uaProgress');
        let ticking = false;

        function update() {
            const max = document.documentElement.scrollHeight - window.innerHeight;
            const ratio = max > 0 ? window.scrollY / max : 0;
            bar.style.transform = 'scaleX(' + Math.min(Math.max(ratio, 0), 1) + ')';
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(update);
            }
        }, { passive: true });

        update();

        /* Tampilkan / sembunyikan password */
        page.querySelectorAll('.ua-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(
                    button.getAttribute('data-password-target')
                );

                if (!input) return;

                const show = input.type === 'password';

                input.type = show ? 'text' : 'password';

                button.querySelector('[data-eye="closed"]').hidden = show;
                button.querySelector('[data-eye="open"]').hidden = !show;

                button.setAttribute('aria-pressed', show ? 'true' : 'false');
                button.setAttribute(
                    'aria-label',
                    show ? 'Sembunyikan password' : 'Tampilkan password'
                );
            });
        });

        /* Cegah pengiriman berulang */
        const form = page.querySelector('#userForm');
        const submitButton = page.querySelector('#saveUserButton');

        if (!form || !submitButton) {
            return;
        }

        const originalButtonContent = submitButton.innerHTML;

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                return;
            }

            if (submitButton.disabled) {
                event.preventDefault();
                return;
            }

            submitButton.disabled = true;

            submitButton.innerHTML = `
                <svg class="is-spin" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M21 12a9 9 0 1 1-6.22-8.56"/>
                </svg>
                <span>Menyimpan data...</span>
            `;
        });

        /* Pulihkan tombol jika kembali lewat riwayat browser */
        window.addEventListener('pageshow', function () {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonContent;
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeUserForm);
    } else {
        initializeUserForm();
    }
})();
</script>

@endsection