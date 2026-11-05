@extends('layouts.app')

@section('title', $participant->exists ? 'Edit Peserta' : 'Tambah Peserta')

@section('content')
<div class="sirapik-participant-form">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap');

        .sirapik-participant-form {
            --pf-navy: #102b50;
            --pf-blue: #1761b5;
            --pf-blue-dark: #124b91;
            --pf-cyan: #28a6c7;
            --pf-ink: #1d2d42;
            --pf-muted: #697b91;
            --pf-line: #dce5ef;
            --pf-soft: #f3f7fc;
            --pf-white: #fff;
            --pf-red: #c34d55;

            width: 100%;
            max-width: none;
            padding: 26px clamp(18px, 2.2vw, 38px) 44px;
            color: var(--pf-ink);
            font-family: 'DM Sans', sans-serif;
            box-sizing: border-box;
        }

        .sirapik-participant-form *,
        .sirapik-participant-form *::before,
        .sirapik-participant-form *::after {
            box-sizing: border-box;
        }

        .sirapik-participant-form input,
        .sirapik-participant-form select,
        .sirapik-participant-form button {
            font: inherit;
        }

        /* HEADER */

        .pf-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 22px;
            margin-bottom: 27px;
        }

        .pf-header-main {
            min-width: 0;
        }

        .pf-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 12px;
            color: var(--pf-blue);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .pf-eyebrow::before {
            width: 26px;
            height: 3px;
            border-radius: 10px;
            background: var(--pf-cyan);
            content: '';
        }

        .pf-header h1 {
            margin: 0;
            color: var(--pf-navy);
            font-family: 'Manrope', sans-serif;
            font-size: clamp(29px, 3vw, 40px);
            font-weight: 800;
            letter-spacing: -1.5px;
            line-height: 1.2;
        }

        .pf-header p {
            max-width: 720px;
            margin: 11px 0 0;
            color: var(--pf-muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .pf-header-status {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 0 0 auto;
            padding: 13px 17px;
            border: 1px solid #d8e6f5;
            border-radius: 12px;
            background: #f5f9fe;
        }

        .pf-status-icon {
            display: grid;
            width: 40px;
            height: 40px;
            place-items: center;
            border-radius: 10px;
            background: #e2efff;
            color: var(--pf-blue);
        }

        .pf-header-status strong {
            display: block;
            color: var(--pf-navy);
            font-size: 13px;
            font-weight: 800;
        }

        .pf-header-status span {
            display: block;
            margin-top: 3px;
            color: var(--pf-muted);
            font-size: 11px;
        }

        /* INFORMATION NOTICE */

        .pf-notice {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            margin-bottom: 20px;
            padding: 17px 19px;
            border: 1px solid #d5e5f7;
            border-radius: 12px;
            background: #f2f7fd;
            animation: pf-enter .4s ease both;
        }

        .pf-notice-icon {
            display: grid;
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            place-items: center;
            border-radius: 9px;
            background: #dfecfc;
            color: var(--pf-blue);
        }

        .pf-notice strong {
            display: block;
            margin: 1px 0 5px;
            color: var(--pf-navy);
            font-size: 14px;
            font-weight: 800;
        }

        .pf-notice p {
            margin: 0;
            color: #536b86;
            font-size: 13px;
            line-height: 1.8;
        }

        /* FORM LAYOUT */

        .pf-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            align-items: start;
            gap: 21px;
        }

        .pf-main {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--pf-line);
            border-radius: 16px;
            background: var(--pf-white);
            box-shadow: 0 5px 22px rgba(20, 48, 82, .045);
        }

        .pf-main-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 23px 27px;
            border-bottom: 1px solid #e8eef5;
        }

        .pf-heading-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .pf-main-icon {
            display: grid;
            width: 49px;
            height: 49px;
            flex: 0 0 49px;
            place-items: center;
            border-radius: 13px;
            background: #eaf2fc;
            color: var(--pf-blue);
        }

        .pf-main-heading h2 {
            margin: 0;
            color: var(--pf-navy);
            font-family: 'Manrope', sans-serif;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.35px;
        }

        .pf-main-heading p {
            margin: 5px 0 0;
            color: var(--pf-muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .pf-required-note {
            flex: 0 0 auto;
            color: #78899e;
            font-size: 12px;
        }

        .pf-required-note b {
            color: var(--pf-red);
        }

        .pf-form-body {
            padding: 27px;
        }

        /* SECTION */

        .pf-section {
            margin-bottom: 30px;
        }

        .pf-section:last-child {
            margin-bottom: 0;
        }

        .pf-section-heading {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 20px;
        }

        .pf-section-number {
            display: grid;
            width: 29px;
            height: 29px;
            flex: 0 0 29px;
            place-items: center;
            border-radius: 8px;
            background: var(--pf-navy);
            color: #fff;
            font-size: 12px;
            font-weight: 800;
        }

        .pf-section-heading strong {
            color: var(--pf-navy);
            font-size: 14px;
            font-weight: 800;
        }

        .pf-section-heading span:last-child {
            height: 1px;
            flex: 1;
            background: #e7edf4;
        }

        /* GRID AND FIELDS */

        .pf-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 21px 24px;
        }

        .pf-field {
            min-width: 0;
        }

        .pf-field label {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 9px;
            color: #293c53;
            font-size: 14px;
            font-weight: 800;
        }

        .pf-required {
            color: var(--pf-red);
            font-size: 15px;
        }

        .pf-optional {
            margin-left: 3px;
            color: #8492a4;
            font-size: 11px;
            font-weight: 600;
        }

        .sirapik-participant-form .pf-control {
            display: block;
            width: 100%;
            min-width: 0;
            min-height: 49px;
            padding: 12px 15px;
            border: 1px solid #cdd9e7;
            border-radius: 9px;
            outline: none;
            background: #fff;
            color: #25384e;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            box-shadow: 0 1px 2px rgba(16, 43, 80, .025);
            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }

        .sirapik-participant-form .pf-control::placeholder {
            color: #9aa8b8;
        }

        .sirapik-participant-form .pf-control:hover {
            border-color: #9cb6d5;
        }

        .sirapik-participant-form .pf-control:focus {
            border-color: var(--pf-blue);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(23, 97, 181, .11);
        }

        .sirapik-participant-form select.pf-control {
            padding-right: 39px;
            cursor: pointer;
        }

        .pf-field-error {
            display: block;
            margin-top: 7px;
            color: #b83e49;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.6;
        }

        .pf-field-error::before {
            content: '!';
            display: inline-grid;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            place-items: center;
            border-radius: 50%;
            background: #fbe7e8;
            font-size: 10px;
        }

        .pf-field-help {
            display: block;
            margin-top: 7px;
            color: #7b899b;
            font-size: 12px;
            line-height: 1.6;
        }

        /* FOOTER ACTION */

        .pf-form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 31px;
            padding-top: 22px;
            border-top: 1px solid #e7edf4;
        }

        .pf-footer-message {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #78899e;
            font-size: 12px;
        }

        .pf-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sirapik-participant-form .pf-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 46px;
            padding: 11px 20px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            transition:
                transform .2s ease,
                background .2s ease,
                border-color .2s ease,
                box-shadow .2s ease;
        }

        .sirapik-participant-form .pf-btn:focus-visible {
            outline: 3px solid rgba(40, 166, 199, .35);
            outline-offset: 3px;
        }

        .sirapik-participant-form .pf-btn-primary {
            background: var(--pf-blue);
            color: #fff;
            box-shadow: 0 4px 10px rgba(23, 97, 181, .16);
        }

        .sirapik-participant-form .pf-btn-primary:hover {
            transform: translateY(-2px);
            background: var(--pf-blue-dark);
            box-shadow: 0 7px 15px rgba(23, 97, 181, .2);
        }

        .sirapik-participant-form .pf-btn-primary:disabled {
            opacity: .7;
            cursor: wait;
            transform: none;
        }

        .sirapik-participant-form .pf-btn-light {
            border-color: #d4deea;
            background: #fff;
            color: #52647a;
        }

        .sirapik-participant-form .pf-btn-light:hover {
            border-color: #aabdd4;
            background: #f5f8fc;
        }

        /* SIDE PANEL */

        .pf-side {
            display: flex;
            min-width: 0;
            flex-direction: column;
            gap: 16px;
        }

        .pf-side-card {
            overflow: hidden;
            border: 1px solid var(--pf-line);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 4px 17px rgba(20, 48, 82, .035);
        }

        .pf-side-blue {
            position: relative;
            padding: 22px 20px;
            border-color: #183c68;
            background: var(--pf-navy);
            color: white;
        }

        .pf-side-blue::before {
            position: absolute;
            top: -30px;
            right: -35px;
            width: 130px;
            height: 130px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 50%;
            content: '';
        }

        .pf-side-blue::after {
            position: absolute;
            top: -10px;
            right: -14px;
            width: 83px;
            height: 83px;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 50%;
            content: '';
        }

        .pf-side-blue .pf-side-icon {
            display: grid;
            width: 42px;
            height: 42px;
            margin-bottom: 17px;
            place-items: center;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 11px;
            background: rgba(255,255,255,.1);
            color: #a9ddff;
        }

        .pf-side-blue h3 {
            position: relative;
            z-index: 1;
            margin: 0 0 9px;
            color: #fff;
            font-family: 'Manrope', sans-serif;
            font-size: 17px;
            font-weight: 800;
            line-height: 1.45;
        }

        .pf-side-blue p {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #c3d5e9;
            font-size: 12px;
            line-height: 1.8;
        }

        .pf-side-content {
            padding: 19px 19px 20px;
        }

        .pf-side-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 15px;
            color: var(--pf-navy);
            font-size: 14px;
            font-weight: 800;
        }

        .pf-check-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pf-check-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #5e7086;
            font-size: 12px;
            line-height: 1.7;
        }

        .pf-check {
            display: grid;
            width: 21px;
            height: 21px;
            flex: 0 0 21px;
            margin-top: 1px;
            place-items: center;
            border-radius: 6px;
            background: #e9f3ff;
            color: var(--pf-blue);
        }

        .pf-side-tip {
            padding: 17px 18px;
            border: 1px solid #d5e5f7;
            border-radius: 12px;
            background: #f2f7fd;
        }

        .pf-tip-heading {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 8px;
            color: var(--pf-navy);
            font-size: 13px;
            font-weight: 800;
        }

        .pf-side-tip p {
            margin: 0;
            color: #536b86;
            font-size: 12px;
            line-height: 1.8;
        }

        /* ANIMATION */

        @keyframes pf-enter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pf-header,
        .pf-notice,
        .pf-main,
        .pf-side {
            animation: pf-enter .4s ease both;
        }

        .pf-side {
            animation-delay: .08s;
        }

        /* RESPONSIVE */

        @media (min-width: 1500px) {
            .pf-layout {
                grid-template-columns: minmax(0, 1fr) 310px;
                gap: 24px;
            }

            .pf-form-body {
                padding: 31px 33px;
            }

            .pf-main-heading {
                padding: 25px 33px;
            }

            .pf-grid {
                column-gap: 30px;
                row-gap: 24px;
            }
        }

        @media (max-width: 1050px) {
            .pf-layout {
                grid-template-columns: minmax(0, 1fr) 245px;
                gap: 15px;
            }

            .pf-form-body {
                padding: 22px;
            }

            .pf-main-heading {
                padding: 21px 22px;
            }

            .pf-grid {
                gap: 19px 16px;
            }
        }

        @media (max-width: 820px) {
            .sirapik-participant-form {
                padding: 23px 18px 34px;
            }

            .pf-layout {
                grid-template-columns: minmax(0, 1fr);
            }

            .pf-side {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                align-items: stretch;
            }

            .pf-side-blue {
                grid-row: span 2;
            }
        }

        @media (max-width: 600px) {
            .sirapik-participant-form {
                padding: 20px 13px 30px;
            }

            .pf-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 17px;
                margin-bottom: 21px;
            }

            .pf-header h1 {
                font-size: 28px;
                letter-spacing: -.8px;
            }

            .pf-header p {
                font-size: 13px;
            }

            .pf-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 18px;
            }

            .pf-form-body {
                padding: 19px 16px 20px;
            }

            .pf-main-heading {
                align-items: flex-start;
                padding: 18px 16px;
            }

            .pf-main-heading h2 {
                font-size: 17px;
            }

            .pf-main-heading p {
                font-size: 12px;
            }

            .pf-main-icon {
                width: 43px;
                height: 43px;
                flex-basis: 43px;
            }

            .pf-required-note {
                display: none;
            }

            .pf-form-footer {
                align-items: stretch;
                flex-direction: column;
            }

            .pf-actions {
                display: grid;
                grid-template-columns: 1fr;
                width: 100%;
            }

            .sirapik-participant-form .pf-btn {
                width: 100%;
            }

            .pf-side {
                grid-template-columns: minmax(0, 1fr);
            }

            .pf-side-blue {
                grid-row: auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .sirapik-participant-form *,
            .sirapik-participant-form *::before,
            .sirapik-participant-form *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>

    @php
        $isEditing = $participant->exists;
    @endphp

    {{-- HEADER --}}
    <header class="pf-header">
        <div class="pf-header-main">
            <div class="pf-eyebrow">Data Master SIRAPIK</div>

            <h1>{{ $isEditing ? 'Edit Data Peserta' : 'Tambah Peserta Baru' }}</h1>

            <p>
                {{ $isEditing
                    ? 'Perbarui informasi identitas, pekerjaan, dan data kepegawaian peserta.'
                    : 'Tambahkan identitas dan informasi kepegawaian peserta ke dalam sistem SIRAPIK.'
                }}
            </p>
        </div>

        <div class="pf-header-status">
            <div class="pf-status-icon" aria-hidden="true">
                @if ($isEditing)
                    <svg width="21" height="21" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                    </svg>
                @else
                    <svg width="21" height="21" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="10" cy="7" r="4"/>
                        <path d="M19 8v6M22 11h-6"/>
                    </svg>
                @endif
            </div>

            <div>
                <strong>{{ $isEditing ? 'Mode Pengeditan' : 'Data Peserta Baru' }}</strong>
                <span>{{ $isEditing ? 'Perubahan data peserta' : 'Pendaftaran data master' }}</span>
            </div>
        </div>
    </header>

    {{-- NOTICE FOR NEW PARTICIPANT --}}
    @if (!$isEditing)
        <div class="pf-notice" role="status">
            <div class="pf-notice-icon" aria-hidden="true">
                <svg width="19" height="19" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4M12 8h.01"/>
                </svg>
            </div>

            <div>
                <strong>Informasi identitas akun peserta</strong>
                <p>
                    Nama lengkap dan NIP digunakan sebagai identitas peserta.
                    Pastikan kedua informasi tersebut ditulis dengan benar
                    sebelum menyimpan data.
                </p>
            </div>
        </div>
    @endif

    {{-- MAIN LAYOUT --}}
    <div class="pf-layout">

        {{-- FORM --}}
        <section class="pf-main">

            <div class="pf-main-heading">
                <div class="pf-heading-left">
                    <div class="pf-main-icon" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>

                    <div>
                        <h2>Informasi Peserta</h2>
                        <p>Lengkapi setiap bagian formulir berikut.</p>
                    </div>
                </div>

                <div class="pf-required-note">
                    <b>*</b> Wajib diisi
                </div>
            </div>

            <div class="pf-form-body">
                <form
                    id="participantForm"
                    method="POST"
                    action="{{ $isEditing
                        ? route('participants.update', $participant)
                        : route('participants.store') }}"
                >
                    @csrf

                    @if ($isEditing)
                        @method('PUT')
                    @endif

                    {{-- SECTION 1: IDENTITAS --}}
                    <div class="pf-section">
                        <div class="pf-section-heading">
                            <span class="pf-section-number">01</span>
                            <strong>Identitas Pribadi</strong>
                            <span></span>
                        </div>

                        <div class="pf-grid">

                            {{-- NAMA --}}
                            <div class="pf-field">
                                <label for="name">
                                    Nama Lengkap
                                    <span class="pf-required">*</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name', $participant->name) }}"
                                    maxlength="255"
                                    placeholder="Masukkan nama lengkap"
                                    autocomplete="name"
                                    required
                                >

                                @error('name')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- NIK --}}
                            <div class="pf-field">
                                <label for="nik">
                                    Nomor Induk Kependudukan (NIK)
                                    <span class="pf-optional">Opsional</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="nik"
                                    name="nik"
                                    type="text"
                                    value="{{ old('nik', $participant->nik) }}"
                                    maxlength="30"
                                    inputmode="numeric"
                                    placeholder="Masukkan NIK"
                                >

                                @error('nik')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- NIP --}}
                            <div class="pf-field">
                                <label for="nip">
                                    Nomor Induk Pegawai (NIP)
                                    <span class="pf-required">*</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="nip"
                                    name="nip"
                                    type="text"
                                    value="{{ old('nip', $participant->nip) }}"
                                    maxlength="30"
                                    placeholder="Masukkan NIP"
                                    required
                                >

                                @error('nip')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- GENDER --}}
                            <div class="pf-field">
                                <label for="gender">
                                    Jenis Kelamin
                                    <span class="pf-optional">Opsional</span>
                                </label>

                                <select class="pf-control" id="gender" name="gender">
                                    <option value="">-- Pilih Jenis Kelamin --</option>

                                    <option
                                        value="L"
                                        @selected(old('gender', $participant->gender) === 'L')
                                    >
                                        Laki-laki
                                    </option>

                                    <option
                                        value="P"
                                        @selected(old('gender', $participant->gender) === 'P')
                                    >
                                        Perempuan
                                    </option>
                                </select>

                                @error('gender')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 2: KEPEGAWAIAN --}}
                    <div class="pf-section">
                        <div class="pf-section-heading">
                            <span class="pf-section-number">02</span>
                            <strong>Informasi Kepegawaian</strong>
                            <span></span>
                        </div>

                        <div class="pf-grid">

                            {{-- RANK --}}
                            <div class="pf-field">
                                <label for="rank">
                                    Pangkat/Golongan
                                    <span class="pf-optional">Opsional</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="rank"
                                    name="rank"
                                    type="text"
                                    value="{{ old('rank', $participant->rank) }}"
                                    maxlength="100"
                                    placeholder="Contoh: Penata / III-c"
                                >

                                @error('rank')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- PROFESSION --}}
                            <div class="pf-field">
                                <label for="profession">
                                    Profesi Tenaga Kesehatan
                                    <span class="pf-optional">Opsional</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="profession"
                                    name="profession"
                                    type="text"
                                    value="{{ old('profession', $participant->profession) }}"
                                    maxlength="255"
                                    placeholder="Contoh: Perawat"
                                >

                                @error('profession')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- INSTITUTION --}}
                            <div class="pf-field">
                                <label for="institution">
                                    Instansi/Unit Kerja
                                    <span class="pf-optional">Opsional</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="institution"
                                    name="institution"
                                    type="text"
                                    value="{{ old('institution', $participant->institution) }}"
                                    maxlength="255"
                                    placeholder="Masukkan nama instansi"
                                >

                                @error('institution')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- DISTRICT --}}
                            <div class="pf-field">
                                <label for="district">
                                    Kabupaten/Kota/Mitra/Mandiri
                                    <span class="pf-required">*</span>
                                </label>

                                <select
                                    class="pf-control"
                                    id="district"
                                    name="district"
                                    required
                                >
                                    <option value="">
                                        -- Pilih wilayah --
                                    </option>

                                    @foreach ($districts as $district)
                                        <option
                                            value="{{ $district }}"
                                            @selected(
                                                (string) old('district', $participant->district) ===
                                                (string) $district
                                            )
                                        >
                                            {{ $district }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('district')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 3: KONTAK --}}
                    <div class="pf-section">
                        <div class="pf-section-heading">
                            <span class="pf-section-number">03</span>
                            <strong>Informasi Kontak</strong>
                            <span></span>
                        </div>

                        <div class="pf-grid">

                            {{-- EMAIL --}}
                            <div class="pf-field">
                                <label for="email">
                                    Alamat Email
                                    <span class="pf-required">*</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $participant->email) }}"
                                    maxlength="255"
                                    autocomplete="email"
                                    placeholder="nama@email.com"
                                    required
                                >

                                @error('email')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- PHONE --}}
                            <div class="pf-field">
                                <label for="phone">
                                    Nomor Telepon
                                    <span class="pf-optional">Opsional</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="phone"
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $participant->phone) }}"
                                    maxlength="30"
                                    inputmode="tel"
                                    autocomplete="tel"
                                    placeholder="Contoh: 081234567890"
                                >

                                @error('phone')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 4: INFORMASI PELATIHAN --}}
                    <div class="pf-section">
                        <div class="pf-section-heading">
                            <span class="pf-section-number">04</span>
                            <strong>Informasi Pelatihan</strong>
                            <span></span>
                        </div>

                        <div class="pf-grid">

                            {{-- ANGKATAN --}}
                            <div class="pf-field">
                                <label for="angkatan">
                                    Angkatan
                                    <span class="pf-optional">Opsional</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="angkatan"
                                    name="angkatan"
                                    type="text"
                                    value="{{ old('angkatan') }}"
                                    maxlength="50"
                                    placeholder="Contoh: 1"
                                >

                                @error('angkatan')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- FUNDING --}}
                            <div class="pf-field">
                                <label for="sumber_dana">
                                    Sumber Dana
                                    <span class="pf-optional">Opsional</span>
                                </label>

                                <input
                                    class="pf-control"
                                    id="sumber_dana"
                                    name="sumber_dana"
                                    type="text"
                                    value="{{ old('sumber_dana') }}"
                                    maxlength="255"
                                    placeholder="Contoh: APBN"
                                >

                                @error('sumber_dana')
                                    <small class="pf-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="pf-form-footer">

                        <div class="pf-footer-message">
                            <svg width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round"
                                 stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 16v-4M12 8h.01"/>
                            </svg>

                            Periksa kembali data sebelum menyimpan.
                        </div>

                        <div class="pf-actions">
                            <a
                                class="pf-btn pf-btn-light"
                                href="{{ route('participants.index') }}"
                            >
                                <svg width="16" height="16" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="1.8" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true">
                                    <path d="m15 18-6-6 6-6"/>
                                </svg>
                                Batal
                            </a>

                            <button
                                class="pf-btn pf-btn-primary"
                                type="submit"
                                id="saveParticipantButton"
                            >
                                <svg width="17" height="17" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                                    <path d="M17 21v-8H7v8M7 3v5h8"/>
                                </svg>

                                <span>
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Peserta' }}
                                </span>
                            </button>
                        </div>

                    </div>

                </form>
            </div>
        </section>

        {{-- SIDE INFORMATION --}}
        <aside class="pf-side">

            <section class="pf-side-card pf-side-blue">
                <div class="pf-side-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M12 3 4 7v5c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-4z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>

                <h3>Data peserta yang akurat</h3>

                <p>
                    Data identitas yang lengkap dan benar membantu menjaga
                    konsistensi informasi peserta dalam seluruh laporan
                    dan riwayat pelatihan SIRAPIK.
                </p>
            </section>

            <section class="pf-side-card">
                <div class="pf-side-content">
                    <div class="pf-side-title">
                        <svg width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="#1761b5" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="m5 12 4 4L19 6"/>
                        </svg>
                        Panduan pengisian
                    </div>

                    <ul class="pf-check-list">
                        <li>
                            <span class="pf-check" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </span>
                            Gunakan nama lengkap sesuai identitas resmi.
                        </li>

                        <li>
                            <span class="pf-check" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </span>
                            Pastikan NIP tidak salah ketik.
                        </li>

                        <li>
                            <span class="pf-check" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </span>
                            Gunakan alamat email yang masih aktif.
                        </li>

                        <li>
                            <span class="pf-check" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </span>
                            Pilih wilayah kerja sesuai data peserta.
                        </li>
                    </ul>
                </div>
            </section>

            <section class="pf-side-tip">
                <div class="pf-tip-heading">
                    <svg width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4M12 8h.01"/>
                    </svg>
                    Catatan pengisian
                </div>

                <p>
                    Kolom dengan tanda bintang (*) wajib diisi.
                    Informasi lainnya dapat dilengkapi jika tersedia.
                    Data angkatan dan sumber dana pada formulir ini
                    tetap menggunakan input yang sudah tersedia.
                </p>
            </section>

        </aside>
    </div>
</div>

<script>
(function () {
    function initializeParticipantForm() {
        const page = document.querySelector('.sirapik-participant-form');

        if (!page || page.dataset.initialized === 'true') {
            return;
        }

        page.dataset.initialized = 'true';

        const form = page.querySelector('#participantForm');
        const submitButton = page.querySelector('#saveParticipantButton');

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
                <svg width="17" height="17" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2v4"/>
                    <path d="M12 18v4"/>
                    <path d="m4.93 4.93 2.83 2.83"/>
                    <path d="m16.24 16.24 2.83 2.83"/>
                    <path d="M2 12h4"/>
                    <path d="M18 12h4"/>
                    <path d="m4.93 19.07 2.83-2.83"/>
                    <path d="m16.24 7.76 2.83-2.83"/>
                </svg>
                <span>Menyimpan data...</span>
            `;
        });

        // Jika browser kembali ke halaman melalui back-forward cache,
        // tombol dikembalikan ke kondisi normal.
        window.addEventListener('pageshow', function () {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonContent;
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeParticipantForm);
    } else {
        initializeParticipantForm();
    }
})();
</script>
@endsection