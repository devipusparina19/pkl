@extends('layouts.app')

@section('title', 'Import Data Peserta Pelatihan')

@section('content')
<div class="sirapik-import">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap');

        .sirapik-import {
            --si-navy: #102b50;
            --si-blue: #1761b5;
            --si-blue-dark: #124b91;
            --si-cyan: #28a6c7;
            --si-ink: #1d2d42;
            --si-muted: #697b91;
            --si-line: #dce5ef;
            --si-soft: #f3f7fc;
            --si-white: #ffffff;
            --si-amber: #c58a2a;

            width: 100%;
            max-width: none;
            padding: 26px clamp(18px, 2.2vw, 38px) 42px;
            color: var(--si-ink);
            font-family: 'DM Sans', sans-serif;
            box-sizing: border-box;
        }

        .sirapik-import *,
        .sirapik-import *::before,
        .sirapik-import *::after {
            box-sizing: border-box;
        }

        .sirapik-import button,
        .sirapik-import input,
        .sirapik-import select {
            font: inherit;
        }

        /* HEADER */

        .si-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 27px;
        }

        .si-heading-copy {
            min-width: 0;
        }

        .si-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 12px;
            color: var(--si-blue);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .si-eyebrow::before {
            content: '';
            width: 26px;
            height: 3px;
            border-radius: 10px;
            background: var(--si-cyan);
        }

        .si-header h1 {
            margin: 0;
            color: var(--si-navy);
            font-family: 'Manrope', sans-serif;
            font-size: clamp(29px, 3vw, 40px);
            font-weight: 800;
            letter-spacing: -1.5px;
            line-height: 1.2;
        }

        .si-header p {
            max-width: 720px;
            margin: 11px 0 0;
            color: var(--si-muted);
            font-size: 15px;
            line-height: 1.8;
        }

        .si-header-mark {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 0 0 auto;
            padding: 13px 17px;
            border: 1px solid #d8e6f5;
            border-radius: 12px;
            background: #f5f9fe;
        }

        .si-header-mark-icon {
            display: grid;
            width: 39px;
            height: 39px;
            place-items: center;
            border-radius: 10px;
            background: #e2efff;
            color: var(--si-blue);
        }

        .si-header-mark strong {
            display: block;
            color: var(--si-navy);
            font-size: 13px;
            font-weight: 800;
        }

        .si-header-mark span {
            display: block;
            margin-top: 3px;
            color: var(--si-muted);
            font-size: 11px;
        }

        /* ALERT */

        .si-alert {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            margin-bottom: 19px;
            padding: 17px 19px;
            border: 1px solid transparent;
            border-radius: 12px;
            animation: si-enter .35s ease both;
        }

        .si-alert-icon {
            display: grid;
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            place-items: center;
            border-radius: 9px;
        }

        .si-alert-content {
            min-width: 0;
            flex: 1;
        }

        .si-alert strong {
            display: block;
            margin: 2px 0 5px;
            font-size: 14px;
            font-weight: 800;
        }

        .si-alert ul {
            margin: 7px 0 0;
            padding-left: 19px;
            font-size: 13px;
            line-height: 1.8;
        }

        .si-alert li {
            overflow-wrap: anywhere;
        }

        .si-alert-danger {
            color: #902f37;
            background: #fff4f3;
            border-color: #f1d1d0;
        }

        .si-alert-danger .si-alert-icon {
            color: #a3343d;
            background: #fce2e1;
        }

        .si-alert-success {
            color: #24623f;
            background: #f0faf3;
            border-color: #cce9d5;
        }

        .si-alert-success .si-alert-icon {
            color: #267346;
            background: #dcefe2;
        }

        .si-import-notes {
            margin-top: 14px;
            padding-top: 13px;
            border-top: 1px solid #cce9d5;
        }

        /* MAIN LAYOUT */

        .si-workspace {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            align-items: start;
            gap: 21px;
        }

        .si-main-panel {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--si-line);
            border-radius: 16px;
            background: var(--si-white);
            box-shadow: 0 5px 22px rgba(20, 48, 82, .045);
        }

        .si-panel-heading {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 23px 26px;
            border-bottom: 1px solid #e8eef5;
        }

        .si-panel-icon {
            display: grid;
            width: 49px;
            height: 49px;
            flex: 0 0 49px;
            place-items: center;
            border-radius: 13px;
            background: #eaf2fc;
            color: var(--si-blue);
        }

        .si-panel-heading h2 {
            margin: 0;
            color: var(--si-navy);
            font-family: 'Manrope', sans-serif;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.35px;
        }

        .si-panel-heading p {
            margin: 5px 0 0;
            color: var(--si-muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .si-form-body {
            padding: 25px 27px 27px;
        }

        /* SECTION LABEL */

        .si-section-label {
            display: flex;
            align-items: center;
            gap: 11px;
            margin: 0 0 19px;
        }

        .si-section-number {
            display: grid;
            width: 27px;
            height: 27px;
            flex: 0 0 27px;
            place-items: center;
            border-radius: 8px;
            background: var(--si-navy);
            color: white;
            font-size: 12px;
            font-weight: 800;
        }

        .si-section-label strong {
            color: var(--si-navy);
            font-size: 14px;
            font-weight: 800;
        }

        .si-section-label span:last-child {
            height: 1px;
            flex: 1;
            background: #e7edf4;
        }

        /* FORM */

        .si-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 21px 23px;
        }

        .si-field {
            min-width: 0;
        }

        .si-field-full {
            grid-column: 1 / -1;
        }

        .si-field label {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 9px;
            color: #293c53;
            font-size: 14px;
            font-weight: 800;
        }

        .si-required {
            color: #d34c51;
            font-size: 15px;
        }

        .si-optional {
            margin-left: 3px;
            color: #8492a4;
            font-size: 11px;
            font-weight: 600;
        }

        .si-control-wrap {
            position: relative;
        }

        .sirapik-import .si-control {
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

        .sirapik-import .si-control::placeholder {
            color: #9aa8b8;
        }

        .sirapik-import .si-control:hover {
            border-color: #9cb6d5;
        }

        .sirapik-import .si-control:focus {
            border-color: var(--si-blue);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(23, 97, 181, .11);
        }

        .sirapik-import select.si-control {
            padding-right: 39px;
            cursor: pointer;
        }

        .sirapik-import input[type="file"].si-control {
            padding: 8px 10px;
            background: #f8fbff;
        }

        .sirapik-import input[type="file"]::file-selector-button {
            min-height: 31px;
            margin-right: 12px;
            padding: 5px 12px;
            border: 0;
            border-radius: 6px;
            background: #e5effc;
            color: var(--si-blue-dark);
            font-family: inherit;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: background .2s ease;
        }

        .sirapik-import input[type="file"]::file-selector-button:hover {
            background: #d5e6fb;
        }

        .si-field-help {
            display: block;
            margin-top: 8px;
            color: #748399;
            font-size: 12px;
            line-height: 1.65;
        }

        .si-selected-file {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 11px;
            padding: 11px 13px;
            border: 1px solid #d6e6f8;
            border-radius: 9px;
            background: #f2f7fd;
            color: var(--si-blue-dark);
            font-size: 13px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .si-selected-file[hidden] {
            display: none;
        }

        .si-file-symbol {
            display: grid;
            width: 31px;
            height: 31px;
            flex: 0 0 31px;
            place-items: center;
            border-radius: 7px;
            background: #dfebfb;
        }

        /* FORM FOOTER */

        .si-form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 29px;
            padding-top: 21px;
            border-top: 1px solid #e7edf4;
        }

        .si-footer-note {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #7a899b;
            font-size: 12px;
        }

        .si-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sirapik-import .si-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 45px;
            padding: 11px 19px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition:
                transform .2s ease,
                background .2s ease,
                border-color .2s ease,
                box-shadow .2s ease;
        }

        .sirapik-import .si-btn:focus-visible {
            outline: 3px solid rgba(40, 166, 199, .35);
            outline-offset: 3px;
        }

        .sirapik-import .si-btn-primary {
            background: var(--si-blue);
            color: white;
            box-shadow: 0 4px 10px rgba(23, 97, 181, .16);
        }

        .sirapik-import .si-btn-primary:hover {
            transform: translateY(-2px);
            background: var(--si-blue-dark);
            box-shadow: 0 7px 15px rgba(23, 97, 181, .2);
        }

        .sirapik-import .si-btn-primary:disabled {
            transform: none;
            opacity: .7;
            cursor: wait;
            box-shadow: none;
        }

        .sirapik-import .si-btn-secondary {
            border-color: #d4deea;
            background: white;
            color: #52647a;
        }

        .sirapik-import .si-btn-secondary:hover {
            border-color: #aabdd4;
            background: #f5f8fc;
        }

        /* SIDE PANEL */

        .si-side-column {
            display: flex;
            min-width: 0;
            flex-direction: column;
            gap: 16px;
        }

        .si-side-card {
            overflow: hidden;
            border: 1px solid var(--si-line);
            border-radius: 14px;
            background: white;
            box-shadow: 0 4px 17px rgba(20, 48, 82, .035);
        }

        .si-side-blue {
            position: relative;
            padding: 22px 20px;
            border-color: #183c68;
            background: var(--si-navy);
            color: white;
        }

        .si-side-blue::after {
            position: absolute;
            top: -39px;
            right: -35px;
            width: 130px;
            height: 130px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 50%;
            content: '';
        }

        .si-side-blue::before {
            position: absolute;
            top: -17px;
            right: -13px;
            width: 85px;
            height: 85px;
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 50%;
            content: '';
        }

        .si-side-blue .si-side-icon {
            display: grid;
            width: 41px;
            height: 41px;
            margin-bottom: 17px;
            place-items: center;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 11px;
            background: rgba(255,255,255,.1);
            color: #a9ddff;
        }

        .si-side-blue h3 {
            position: relative;
            z-index: 1;
            margin: 0 0 9px;
            color: white;
            font-family: 'Manrope', sans-serif;
            font-size: 17px;
            font-weight: 800;
            line-height: 1.45;
        }

        .si-side-blue p {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #c3d5e9;
            font-size: 12px;
            line-height: 1.8;
        }

        .si-side-content {
            padding: 19px 19px 20px;
        }

        .si-side-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 15px;
            color: var(--si-navy);
            font-size: 14px;
            font-weight: 800;
        }

        .si-check-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .si-check-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #5e7086;
            font-size: 12px;
            line-height: 1.7;
        }

        .si-check {
            display: grid;
            width: 20px;
            height: 20px;
            flex: 0 0 20px;
            margin-top: 1px;
            place-items: center;
            border-radius: 6px;
            background: #e9f3ff;
            color: var(--si-blue);
        }

        .si-side-warning {
            padding: 17px 18px;
            border: 1px solid #f0e1c3;
            border-radius: 12px;
            background: #fffaf0;
        }

        .si-warning-heading {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 8px;
            color: #94631b;
            font-size: 13px;
            font-weight: 800;
        }

        .si-side-warning p {
            margin: 0;
            color: #826e4e;
            font-size: 12px;
            line-height: 1.8;
        }

        /* ANIMATION */

        @keyframes si-enter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .si-header,
        .si-main-panel,
        .si-side-column {
            animation: si-enter .45s ease both;
        }

        .si-side-column {
            animation-delay: .08s;
        }

        /* RESPONSIVE */

        @media (min-width: 1500px) {
            .si-workspace {
                grid-template-columns: minmax(0, 1fr) 310px;
                gap: 24px;
            }

            .si-form-body {
                padding: 29px 32px 31px;
            }

            .si-panel-heading {
                padding: 25px 32px;
            }

            .si-form-grid {
                column-gap: 30px;
                row-gap: 24px;
            }
        }

        @media (max-width: 1050px) {
            .si-workspace {
                grid-template-columns: minmax(0, 1fr) 245px;
                gap: 15px;
            }

            .si-form-body {
                padding: 22px;
            }

            .si-panel-heading {
                padding: 21px 22px;
            }

            .si-form-grid {
                gap: 19px 16px;
            }
        }

        @media (max-width: 820px) {
            .sirapik-import {
                padding: 23px 18px 34px;
            }

            .si-workspace {
                grid-template-columns: minmax(0, 1fr);
            }

            .si-side-column {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                align-items: stretch;
            }

            .si-side-blue {
                grid-row: span 2;
            }
        }

        @media (max-width: 600px) {
            .sirapik-import {
                padding: 20px 13px 30px;
            }

            .si-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 17px;
                margin-bottom: 21px;
            }

            .si-header h1 {
                font-size: 28px;
                letter-spacing: -.8px;
            }

            .si-header p {
                font-size: 13px;
            }

            .si-header-mark {
                padding: 10px 13px;
            }

            .si-form-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 18px;
            }

            .si-field-full {
                grid-column: auto;
            }

            .si-panel-heading {
                padding: 18px 16px;
            }

            .si-panel-heading h2 {
                font-size: 17px;
            }

            .si-panel-heading p {
                font-size: 12px;
            }

            .si-panel-icon {
                width: 43px;
                height: 43px;
                flex-basis: 43px;
            }

            .si-form-body {
                padding: 19px 16px 20px;
            }

            .si-form-footer {
                align-items: stretch;
                flex-direction: column;
            }

            .si-footer-note {
                order: 2;
            }

            .si-actions {
                display: grid;
                grid-template-columns: 1fr;
                width: 100%;
            }

            .sirapik-import .si-btn {
                width: 100%;
            }

            .si-side-column {
                grid-template-columns: minmax(0, 1fr);
            }

            .si-side-blue {
                grid-row: auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .sirapik-import *,
            .sirapik-import *::before,
            .sirapik-import *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>

    {{-- HEADER --}}
    <header class="si-header">
        <div class="si-heading-copy">
            <div class="si-eyebrow">Manajemen Data SIRAPIK</div>

            <h1>Import Data Peserta</h1>

            <p>
                Kelola data peserta dan riwayat pelatihan melalui satu proses
                import yang terstruktur. Unggah file Excel, lengkapi informasi
                pelatihan, lalu mulai proses import.
            </p>
        </div>

        <div class="si-header-mark">
            <div class="si-header-mark-icon" aria-hidden="true">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 16V4"/>
                    <path d="m7 9 5-5 5 5"/>
                    <path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>
                </svg>
            </div>
            <div>
                <strong>Import Excel</strong>
                <span>Pengelolaan data peserta</span>
            </div>
        </div>
    </header>

    {{-- VALIDATION ERROR --}}
    @if ($errors->any())
        <div class="si-alert si-alert-danger" role="alert">
            <div class="si-alert-icon" aria-hidden="true">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4"/>
                    <path d="M12 16h.01"/>
                </svg>
            </div>

            <div class="si-alert-content">
                <strong>Import belum dapat diproses.</strong>
                <span>Periksa kembali informasi berikut:</span>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- IMPORT RESULT --}}
    @if (session('import_result'))
        @php($result = session('import_result'))

        <div class="si-alert si-alert-success" role="status">
            <div class="si-alert-icon" aria-hidden="true">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="m5 12 4 4L19 6"/>
                </svg>
            </div>

            <div class="si-alert-content">
                <strong>Import selesai diproses.</strong>

                <ul>
                    <li>
                        Peserta baru:
                        <strong>{{ $result['created_participants'] ?? 0 }}</strong>
                    </li>
                    <li>
                        Peserta diperbarui:
                        <strong>{{ $result['updated_participants'] ?? 0 }}</strong>
                    </li>
                    <li>
                        Pelatihan baru:
                        <strong>{{ $result['created_trainings'] ?? 0 }}</strong>
                    </li>
                    <li>
                        Riwayat pelatihan baru:
                        <strong>{{ $result['created_histories'] ?? 0 }}</strong>
                    </li>
                </ul>

                @if (!empty($result['errors']))
                    <div class="si-import-notes">
                        <strong>Catatan baris yang perlu diperiksa:</strong>
                        <ul>
                            @foreach ($result['errors'] as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- MAIN WORKSPACE --}}
    <div class="si-workspace">

        {{-- FORM PANEL --}}
        <section class="si-main-panel">

            <div class="si-panel-heading">
                <div class="si-panel-icon" aria-hidden="true">
                    <svg width="25" height="25" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 16V4"/>
                        <path d="m7 9 5-5 5 5"/>
                        <path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>
                    </svg>
                </div>

                <div>
                    <h2>Unggah file Excel</h2>
                    <p>Lengkapi informasi pelatihan sebelum memulai import data.</p>
                </div>
            </div>

            <div class="si-form-body">

                <form
                    id="importForm"
                    action="{{ route('imports.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf

                    {{-- SECTION 1 --}}
                    <div class="si-section-label">
                        <span class="si-section-number">01</span>
                        <strong>File sumber data</strong>
                        <span></span>
                    </div>

                    <div class="si-form-grid">

                        <div class="si-field si-field-full">
                            <label for="file">
                                File Excel
                                <span class="si-required">*</span>
                            </label>

                            <input
                                type="file"
                                name="file"
                                id="file"
                                class="si-control"
                                accept=".xlsx,.xls,.csv"
                                required
                            >

                            <small class="si-field-help">
                                Format yang didukung: XLSX, XLS, atau CSV.
                                Ukuran file maksimal 10 MB.
                            </small>

                            <div
                                id="selectedFile"
                                class="si-selected-file"
                                hidden
                            >
                                <span class="si-file-symbol" aria-hidden="true">
                                    <svg width="17" height="17" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="1.8" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <path d="M14 2v6h6"/>
                                        <path d="M8 13h8M8 17h8"/>
                                    </svg>
                                </span>
                                <span id="selectedFileName"></span>
                            </div>
                        </div>

                    </div>

                    {{-- SECTION 2 --}}
                    <div class="si-section-label" style="margin-top:30px">
                        <span class="si-section-number">02</span>
                        <strong>Informasi penyelenggaraan pelatihan</strong>
                        <span></span>
                    </div>

                    <div class="si-form-grid">

                        {{-- ANGKATAN --}}
                        <div class="si-field">
                            <label for="angkatan">
                                Angkatan
                                <span class="si-optional">Opsional</span>
                            </label>

                            <input
                                type="text"
                                name="angkatan"
                                id="angkatan"
                                class="si-control"
                                value="{{ old('angkatan') }}"
                                placeholder="Contoh: Angkatan I Tahun 2026"
                                maxlength="100"
                            >

                            <small class="si-field-help">
                                Kosongkan jika pelatihan tidak memiliki informasi angkatan.
                            </small>
                        </div>

                        {{-- WILAYAH --}}
                        <div class="si-field">
                            <label for="kabupaten_kota_mitra">
                                Kabupaten/Kota/Mitra/Mandiri
                                <span class="si-required">*</span>
                            </label>

                            <select
                                name="kabupaten_kota_mitra"
                                id="kabupaten_kota_mitra"
                                class="si-control"
                                required
                            >
                                <option value="">
                                    -- Pilih wilayah atau mitra --
                                </option>

                                @foreach ($districts as $district)
                                    <option
                                        value="{{ $district->name }}"
                                        @selected(
                                            old('kabupaten_kota_mitra') === $district->name
                                        )
                                    >
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="si-field-help">
                                Pilihan mengikuti Master Data SIRAPIK.
                            </small>
                        </div>

                        {{-- SUMBER DANA --}}
                        <div class="si-field si-field-full">
                            <label for="sumber_dana">
                                Sumber Dana
                                <span class="si-required">*</span>
                            </label>

                            <select
                                name="sumber_dana"
                                id="sumber_dana"
                                class="si-control"
                                required
                            >
                                <option value="">
                                    -- Pilih sumber dana --
                                </option>

                                @foreach ($fundingSources as $source)
                                    <option
                                        value="{{ $source->name }}"
                                        @selected(
                                            old('sumber_dana') === $source->name
                                        )
                                    >
                                        {{ $source->name }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="si-field-help">
                                Pilihan mengikuti Master Data Sumber Dana SIRAPIK.
                            </small>
                        </div>

                    </div>

                    {{-- SECTION 3 --}}
                    <div class="si-section-label" style="margin-top:30px">
                        <span class="si-section-number">03</span>
                        <strong>Klasifikasi dan durasi pelatihan</strong>
                        <span></span>
                    </div>

                    <div class="si-form-grid">

                        {{-- CATEGORY --}}
                        <div class="si-field">
                            <label for="training_category_id">
                                Kategori Pelatihan
                                <span class="si-optional">Opsional</span>
                            </label>

                            <select
                                name="training_category_id"
                                id="training_category_id"
                                class="si-control"
                            >
                                <option value="">
                                    -- Pilih kategori --
                                </option>

                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(
                                            (string) old('training_category_id', '') ===
                                            (string) $category->id
                                        )
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="si-field-help">
                                Mengikuti Master Data Kategori Pelatihan.
                            </small>
                        </div>

                        {{-- JPL --}}
                        <div class="si-field">
                            <label for="jpl">
                                Jumlah JPL
                                <span class="si-optional">Opsional</span>
                            </label>

                            <input
                                type="number"
                                name="jpl"
                                id="jpl"
                                class="si-control"
                                value="{{ old('jpl') }}"
                                min="0"
                                step="1"
                                placeholder="Contoh: 32"
                            >

                            <small class="si-field-help">
                                Isi jumlah Jam Pelajaran jika tersedia.
                            </small>
                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="si-form-footer">

                        <div class="si-footer-note">
                            <svg width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round"
                                 stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 16v-4M12 8h.01"/>
                            </svg>
                            Pastikan data sudah benar sebelum diimport.
                        </div>

                        <div class="si-actions">
                            <button
                                type="reset"
                                class="si-btn si-btn-secondary"
                                id="resetButton"
                            >
                                <svg width="16" height="16" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="1.8" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                                    <path d="M3 3v5h5"/>
                                </svg>
                                Reset Form
                            </button>

                            <button
                                type="submit"
                                class="si-btn si-btn-primary"
                                id="submitButton"
                            >
                                <svg width="17" height="17" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 16V4"/>
                                    <path d="m7 9 5-5 5 5"/>
                                    <path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>
                                </svg>
                                <span>Import Excel</span>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </section>

        {{-- SIDE INFORMATION --}}
        <aside class="si-side-column">

            <section class="si-side-card si-side-blue">
                <div class="si-side-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M12 3 4 7v5c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-4z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>

                <h3>Import data lebih terstruktur</h3>

                <p>
                    SIRAPIK membantu mengelola data peserta dan riwayat
                    pelatihan dalam satu sistem yang terintegrasi.
                    Pastikan file sumber dan informasi pelatihan sesuai
                    sebelum memulai proses.
                </p>
            </section>

            <section class="si-side-card">
                <div class="si-side-content">
                    <div class="si-side-title">
                        <svg width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="#1761b5" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="m5 12 4 4L19 6"/>
                        </svg>
                        Checklist sebelum import
                    </div>

                    <ul class="si-check-list">
                        <li>
                            <span class="si-check" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </span>
                            File Excel memiliki susunan kolom yang sesuai.
                        </li>

                        <li>
                            <span class="si-check" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </span>
                            Data identitas peserta sudah diperiksa.
                        </li>

                        <li>
                            <span class="si-check" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </span>
                            Wilayah dan sumber dana sudah dipilih.
                        </li>

                        <li>
                            <span class="si-check" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="m5 12 4 4L19 6"/>
                                </svg>
                            </span>
                            Kategori dan JPL diisi jika informasinya tersedia.
                        </li>
                    </ul>
                </div>
            </section>

            <section class="si-side-warning">
                <div class="si-warning-heading">
                    <svg width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M10.3 3.9 2.5 17.4A2 2 0 0 0 4.2 20h15.6a2 2 0 0 0 1.7-2.6L13.7 3.9a2 2 0 0 0-3.4 0z"/>
                        <path d="M12 9v4M12 17h.01"/>
                    </svg>
                    Informasi penting
                </div>

                <p>
                    Peserta yang sudah terdaftar dapat diperbarui berdasarkan
                    NIP atau NIK. Riwayat pelatihan yang sama tidak dibuat
                    berulang. Kategori Pelatihan dan JPL boleh dikosongkan
                    jika informasinya belum tersedia.
                </p>
            </section>

        </aside>
    </div>
</div>

<script>
(function () {
    function initializeImportPage() {
        const page = document.querySelector('.sirapik-import');

        if (!page || page.dataset.initialized === 'true') {
            return;
        }

        page.dataset.initialized = 'true';

        const fileInput = page.querySelector('#file');
        const selectedFile = page.querySelector('#selectedFile');
        const selectedFileName = page.querySelector('#selectedFileName');
        const importForm = page.querySelector('#importForm');
        const submitButton = page.querySelector('#submitButton');
        const resetButton = page.querySelector('#resetButton');

        const defaultButtonContent = submitButton
            ? submitButton.innerHTML
            : '';

        // Menampilkan nama file yang dipilih.
        if (fileInput && selectedFile && selectedFileName) {
            fileInput.addEventListener('change', function () {
                const file = this.files && this.files.length
                    ? this.files[0]
                    : null;

                if (!file) {
                    selectedFileName.textContent = '';
                    selectedFile.hidden = true;
                    return;
                }

                const fileSize = (file.size / 1024 / 1024).toFixed(2);

                selectedFileName.textContent =
                    file.name + ' · ' + fileSize + ' MB';

                selectedFile.hidden = false;
            });
        }

        // Mencegah pengiriman berulang saat proses import dimulai.
        if (importForm && submitButton) {
            importForm.addEventListener('submit', function (event) {
                if (!importForm.checkValidity()) {
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
                    <span>Memproses import...</span>
                `;
            });
        }

        // Reset tampilan setelah form direset.
        if (resetButton) {
            resetButton.addEventListener('click', function () {
                window.setTimeout(function () {
                    if (selectedFileName) {
                        selectedFileName.textContent = '';
                    }

                    if (selectedFile) {
                        selectedFile.hidden = true;
                    }

                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = defaultButtonContent;
                    }
                }, 0);
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeImportPage);
    } else {
        initializeImportPage();
    }
})();
</script>
@endsection