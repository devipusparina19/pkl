@extends('layouts.app')

@section('title', 'Import Data Peserta Pelatihan')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<div class="sirapik-import">

    <style>
        /* =====================================================
           PREMIUM BLUE EDITORIAL — IMPORT DATA PESERTA
           Semua selector di-scope ke .sirapik-import
           ===================================================== */
        .sirapik-import {
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
            --danger: #A3202F;
            --danger-bg: #FFF1F2;
            --danger-line: #F7D1D5;
            --ok: #0F6B4A;
            --ok-bg: #E8F6EF;
            --ok-line: #B7E2CC;
            --warn: #8A5A12;
            --warn-bg: #FFF8EB;
            --warn-line: #F0DFB9;
            --font-display: "Sora", "Segoe UI", Arial, sans-serif;
            --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
            --ease: cubic-bezier(.2, .7, .2, 1);

            width: 100%; color: var(--text);
            font-family: var(--font-body); font-size: 15px; line-height: 1.65;
        }
        .sirapik-import *, .sirapik-import *::before, .sirapik-import *::after { box-sizing: border-box; }
        .sirapik-import button, .sirapik-import input, .sirapik-import select { font: inherit; }
        .sirapik-import svg.i {
            width: 1em; height: 1em; flex: none;
            fill: none; stroke: currentColor;
            stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        }
        .sirapik-import :focus-visible { outline: 3px solid var(--cyan); outline-offset: 3px; border-radius: 6px; }

        /* ---------- Header ---------- */
        .si-head {
            position: relative; isolation: isolate; overflow: hidden;
            display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, .7fr);
            align-items: end; gap: clamp(20px, 4vw, 56px);
            margin-bottom: 26px; padding: clamp(28px, 4vw, 48px);
            border-radius: 24px; color: #fff;
            background: linear-gradient(120deg, var(--navy) 0%, var(--midnight) 60%, #173d86 100%);
            animation: si-rise .8s var(--ease) both;
        }
        .si-head::before {
            content: ""; position: absolute; z-index: -1; right: -90px; top: -110px;
            width: 340px; height: 340px; border-radius: 50%;
            background: var(--royal); opacity: .45; filter: blur(60px);
            animation: si-drift 24s ease-in-out infinite alternate;
        }
        .si-head h1 {
            margin: 0 0 12px; font-family: var(--font-display); font-weight: 800;
            font-size: clamp(2rem, 4.6vw, 3.4rem); line-height: 1.08; letter-spacing: -.035em;
        }
        .si-head h1 span { display: block; color: var(--cyan); }
        .si-head p { max-width: 560px; margin: 0; font-size: 15px; line-height: 1.8; color: rgba(255,255,255,.82); }
        .si-head-mark {
            display: flex; align-items: center; gap: 14px; padding: 18px 20px;
            border-radius: 18px; border: 1px solid rgba(255,255,255,.2); background: rgba(255,255,255,.08);
        }
        .si-head-mark .ic {
            display: grid; place-items: center; width: 46px; height: 46px; flex: none;
            border-radius: 13px; background: rgba(56,198,232,.15); color: var(--cyan); font-size: 22px;
        }
        .si-head-mark strong { display: block; font-family: var(--font-display); font-size: 15px; font-weight: 600; }
        .si-head-mark span { font-size: 13px; color: rgba(255,255,255,.72); }

        /* ---------- Alerts ---------- */
        .si-alert {
            display: flex; align-items: flex-start; gap: 13px; margin-bottom: 18px;
            padding: 16px 18px; border: 1px solid transparent; border-radius: 16px;
            font-size: 14px; line-height: 1.7;
        }
        .si-alert > .i { margin-top: 3px; font-size: 20px; }
        .si-alert-content { min-width: 0; flex: 1; }
        .si-alert strong.h { display: block; margin-bottom: 2px; font-family: var(--font-display); font-size: 15px; font-weight: 600; }
        .si-alert ul { margin: 6px 0 0; padding-left: 20px; }
        .si-alert li { overflow-wrap: anywhere; }
        .si-alert-danger { color: var(--danger); background: var(--danger-bg); border-color: var(--danger-line); }
        .si-alert-success { color: var(--ok); background: var(--ok-bg); border-color: var(--ok-line); }
        .si-import-notes { margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--ok-line); }

        /* ---------- Workspace ---------- */
        .si-workspace {
            display: grid; grid-template-columns: minmax(0, 1fr) 320px;
            align-items: start; gap: 24px;
        }
        .si-main-panel {
            min-width: 0; overflow: hidden; border: 1px solid var(--border); border-radius: 22px;
            background: #fff; box-shadow: 0 18px 44px rgba(8,27,51,.07);
        }
        .si-panel-heading {
            display: flex; align-items: center; gap: 14px;
            padding: 22px clamp(20px, 3vw, 32px); border-bottom: 1px solid var(--border);
        }
        .si-panel-icon {
            display: grid; place-items: center; width: 48px; height: 48px; flex: none;
            border-radius: 14px; background: var(--soft); color: var(--royal); font-size: 24px;
        }
        .si-panel-heading h2 { margin: 0; font-family: var(--font-display); font-size: 19px; font-weight: 700; letter-spacing: -.02em; color: var(--navy); }
        .si-panel-heading p { margin: 3px 0 0; font-size: 13px; color: var(--slate); }
        .si-form-body { padding: clamp(20px, 3vw, 32px); }

        /* Langkah form: urutan nyata, jadi bernomor */
        .si-step { display: flex; align-items: center; gap: 12px; margin: 0 0 18px; }
        .si-step + .si-form-grid { margin-bottom: 0; }
        .si-step-number {
            display: grid; place-items: center; width: 30px; height: 30px; flex: none;
            border-radius: 9px; background: var(--navy); color: #fff;
            font-family: var(--font-display); font-size: 12px; font-weight: 700;
        }
        .si-step strong { font-family: var(--font-display); font-size: 15px; font-weight: 600; color: var(--navy); }
        .si-step i { flex: 1; height: 1px; background: var(--border); }
        .si-step.gap { margin-top: 34px; }

        .si-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px 24px; }
        .si-field { min-width: 0; }
        .si-field-full { grid-column: 1 / -1; }
        .si-field label {
            display: flex; align-items: center; flex-wrap: wrap; gap: 6px;
            margin-bottom: 8px; font-size: 14px; font-weight: 700; color: var(--navy);
        }
        .si-required { color: #C8323F; font-size: 15px; }
        .si-optional {
            padding: 2px 8px; border-radius: 999px; background: var(--ice);
            color: var(--slate); font-size: 11px; font-weight: 600;
        }
        .sirapik-import .si-control {
            display: block; width: 100%; min-width: 0; min-height: 52px; padding: 12px 15px;
            color: var(--text); background: var(--ice);
            border: 1.5px solid var(--border); border-radius: 12px;
            font-size: 14px; line-height: 1.5;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .sirapik-import .si-control::placeholder { color: #8796aa; }
        .sirapik-import .si-control:hover { border-color: #c5d3e4; }
        .sirapik-import .si-control:focus {
            outline: none; background: #fff; border-color: var(--electric);
            box-shadow: 0 0 0 4px rgba(52,120,246,.16);
        }
        .sirapik-import select.si-control { cursor: pointer; }
        .sirapik-import input[type="file"].si-control { padding: 9px 10px; background: var(--soft); border-style: dashed; border-color: #AFC8F2; }
        .sirapik-import input[type="file"]::file-selector-button {
            min-height: 34px; margin-right: 12px; padding: 6px 14px; border: 0; border-radius: 9px;
            background: var(--royal); color: #fff; font-family: var(--font-display);
            font-size: 12px; font-weight: 600; cursor: pointer; transition: background .2s;
        }
        .sirapik-import input[type="file"]::file-selector-button:hover { background: var(--electric); }
        .si-field-help { display: block; margin-top: 7px; font-size: 12.5px; line-height: 1.6; color: var(--slate); }

        .si-selected-file {
            display: flex; align-items: center; gap: 10px; margin-top: 12px; padding: 11px 14px;
            border: 1px solid var(--ok-line); border-radius: 12px; background: var(--ok-bg);
            color: var(--ok); font-size: 13px; font-weight: 600; overflow-wrap: anywhere;
        }
        .si-selected-file[hidden] { display: none; }
        .si-selected-file .i { font-size: 20px; }

        /* ---------- Footer & buttons ---------- */
        .si-form-footer {
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
            margin-top: 32px; padding-top: 22px; border-top: 1px solid var(--border);
        }
        .si-footer-note { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--slate); }
        .si-footer-note .i { font-size: 17px; color: var(--royal); }
        .si-actions { display: flex; align-items: center; gap: 10px; }
        .sirapik-import .si-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 9px;
            min-height: 52px; padding: 12px 22px; border: 1.5px solid transparent; border-radius: 14px;
            font-family: var(--font-display); font-size: 14px; font-weight: 600; cursor: pointer;
            transition: transform .2s var(--ease), box-shadow .2s, background .2s, color .2s;
        }
        .sirapik-import .si-btn .i { font-size: 18px; }
        .sirapik-import .si-btn:active { transform: scale(.97); }
        .sirapik-import .si-btn-primary { color: #fff; background: linear-gradient(110deg, var(--royal), var(--electric)); box-shadow: 0 12px 26px rgba(36,87,214,.3); }
        .sirapik-import .si-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 16px 32px rgba(36,87,214,.4); }
        .sirapik-import .si-btn-primary:disabled { transform: none; opacity: .75; cursor: wait; box-shadow: none; }
        .sirapik-import .si-btn-ghost { color: var(--slate); background: transparent; }
        .sirapik-import .si-btn-ghost:hover { color: var(--navy); background: var(--ice); }
        .si-spin { animation: si-spin 1s linear infinite; }

        /* ---------- Sisi: panduan editorial, bukan tumpukan kartu ---------- */
        .si-side-column { position: sticky; top: 20px; display: flex; min-width: 0; flex-direction: column; gap: 18px; animation: si-rise .8s .1s var(--ease) both; }
        .si-side-dark {
            position: relative; isolation: isolate; overflow: hidden;
            padding: 24px; border-radius: 22px; color: #fff; background: var(--navy);
        }
        .si-side-dark::after {
            content: ""; position: absolute; z-index: -1; right: -60px; bottom: -80px;
            width: 200px; height: 200px; border-radius: 50%; background: var(--royal); opacity: .5; filter: blur(40px);
        }
        .si-side-dark .ic {
            display: grid; place-items: center; width: 44px; height: 44px; margin-bottom: 14px;
            border-radius: 13px; background: rgba(56,198,232,.15); color: var(--cyan); font-size: 22px;
        }
        .si-side-dark h3 { margin: 0 0 8px; font-family: var(--font-display); font-size: 17px; font-weight: 600; line-height: 1.4; }
        .si-side-dark p { margin: 0; font-size: 13px; line-height: 1.8; color: rgba(255,255,255,.78); }

        .si-checklist h3 { margin: 0 0 14px; font-family: var(--font-display); font-size: 15px; font-weight: 600; color: var(--navy); }
        .si-check-list { display: flex; flex-direction: column; margin: 0; padding: 0; list-style: none; }
        .si-check-list li {
            display: flex; align-items: flex-start; gap: 12px; padding: 12px 0;
            border-top: 1px solid var(--border); font-size: 13px; line-height: 1.65; color: #4B5E75;
        }
        .si-check { display: grid; place-items: center; width: 22px; height: 22px; flex: none; margin-top: 1px; border-radius: 7px; background: var(--soft); color: var(--royal); font-size: 13px; }

        .si-side-warning { padding: 18px 20px; border: 1px solid var(--warn-line); border-radius: 18px; background: var(--warn-bg); }
        .si-warning-heading { display: flex; align-items: center; gap: 9px; margin-bottom: 6px; font-family: var(--font-display); font-size: 14px; font-weight: 600; color: var(--warn); }
        .si-warning-heading .i { font-size: 18px; }
        .si-side-warning p { margin: 0; font-size: 13px; line-height: 1.8; color: #6F5A34; }

        @keyframes si-rise { from { opacity: 0; transform: translate3d(0, 18px, 0); } to { opacity: 1; transform: none; } }
        @keyframes si-drift { to { transform: translate3d(-40px, 40px, 0); } }
        @keyframes si-spin { to { transform: rotate(360deg); } }

        /* ---------- Responsive ---------- */
        @media (max-width: 1100px) {
            .si-workspace { grid-template-columns: minmax(0, 1fr); }
            .si-side-column { position: static; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: start; }
            .si-side-dark { grid-column: 1 / -1; }
        }
        @media (max-width: 900px) { .si-head { grid-template-columns: 1fr; } }
        @media (max-width: 640px) {
            .si-form-grid { grid-template-columns: minmax(0, 1fr); gap: 18px; }
            .si-field-full { grid-column: auto; }
            .si-side-column { grid-template-columns: minmax(0, 1fr); }
            .si-form-footer { align-items: stretch; flex-direction: column; }
            .si-footer-note { order: 2; }
            .si-actions { display: grid; grid-template-columns: 1fr; width: 100%; }
            .sirapik-import .si-btn { width: 100%; }
        }
        @media (prefers-reduced-motion: reduce) {
            .sirapik-import *, .sirapik-import *::before, .sirapik-import *::after {
                animation: none !important; transition-duration: .01ms !important; scroll-behavior: auto !important;
            }
        }
    </style>

    {{-- HEADER --}}
    <header class="si-head">
        <div>
            <h1>Import data peserta <span>dalam satu proses.</span></h1>
            <p>
                Unggah file Excel, lengkapi informasi pelatihan, lalu mulai
                import data peserta dan riwayat pelatihan ke SIRAPIK.
            </p>
        </div>

        <div class="si-head-mark">
            <span class="ic" aria-hidden="true">
                <svg class="i" viewBox="0 0 24 24"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
            </span>
            <div>
                <strong>Import Excel</strong>
                <span>XLSX, XLS, atau CSV</span>
            </div>
        </div>
    </header>

    {{-- VALIDATION ERROR --}}
    @if ($errors->any())
        <div class="si-alert si-alert-danger" role="alert">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>

            <div class="si-alert-content">
                <strong class="h">Import belum dapat diproses.</strong>
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
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>

            <div class="si-alert-content">
                <strong class="h">Import selesai diproses.</strong>

                <ul>
                    <li>Peserta baru: <strong>{{ $result['created_participants'] ?? 0 }}</strong></li>
                    <li>Peserta diperbarui: <strong>{{ $result['updated_participants'] ?? 0 }}</strong></li>
                    <li>Pelatihan baru: <strong>{{ $result['created_trainings'] ?? 0 }}</strong></li>
                    <li>Riwayat pelatihan baru: <strong>{{ $result['created_histories'] ?? 0 }}</strong></li>
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
        <section class="si-main-panel" aria-labelledby="si-form-title">

            <div class="si-panel-heading">
                <div class="si-panel-icon" aria-hidden="true">
                    <svg class="i" viewBox="0 0 24 24"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                </div>
                <div>
                    <h2 id="si-form-title">Unggah file Excel</h2>
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

                    {{-- LANGKAH 1 --}}
                    <div class="si-step">
                        <span class="si-step-number">1</span>
                        <strong>File sumber data</strong>
                        <i></i>
                    </div>

                    <div class="si-form-grid">
                        <div class="si-field si-field-full">
                            <label for="file">
                                File Excel
                                <span class="si-required" aria-hidden="true">*</span>
                            </label>

                            <input
                                type="file"
                                name="file"
                                id="file"
                                class="si-control"
                                accept=".xlsx,.xls,.csv"
                                aria-describedby="file-help"
                                required
                            >

                            <small class="si-field-help" id="file-help">
                                Format yang didukung: XLSX, XLS, atau CSV.
                                Ukuran file maksimal 10 MB.
                            </small>

                            <div id="selectedFile" class="si-selected-file" hidden>
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h8"/></svg>
                                <span id="selectedFileName"></span>
                            </div>
                        </div>
                    </div>

                    {{-- LANGKAH 2 --}}
                    <div class="si-step gap">
                        <span class="si-step-number">2</span>
                        <strong>Informasi penyelenggaraan pelatihan</strong>
                        <i></i>
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
                                <span class="si-required" aria-hidden="true">*</span>
                            </label>

                            <select
                                name="kabupaten_kota_mitra"
                                id="kabupaten_kota_mitra"
                                class="si-control"
                                required
                            >
                                <option value="">-- Pilih wilayah atau mitra --</option>

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
                                <span class="si-required" aria-hidden="true">*</span>
                            </label>

                            <select
                                name="sumber_dana"
                                id="sumber_dana"
                                class="si-control"
                                required
                            >
                                <option value="">-- Pilih sumber dana --</option>

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

                    {{-- LANGKAH 3 --}}
                    <div class="si-step gap">
                        <span class="si-step-number">3</span>
                        <strong>Klasifikasi dan durasi pelatihan</strong>
                        <i></i>
                    </div>

                    <div class="si-form-grid">

                        {{-- KATEGORI --}}
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
                                <option value="">-- Pilih kategori --</option>

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
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Pastikan data sudah benar sebelum diimport.
                        </div>

                        <div class="si-actions">
                            <button type="reset" class="si-btn si-btn-ghost" id="resetButton">
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                                Reset Form
                            </button>

                            <button type="submit" class="si-btn si-btn-primary" id="submitButton">
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                                <span>Import Excel</span>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </section>

        {{-- SIDE INFORMATION --}}
        <aside class="si-side-column" aria-label="Panduan import">

            <section class="si-side-dark">
                <div class="ic" aria-hidden="true">
                    <svg class="i" viewBox="0 0 24 24"><path d="M12 3 4 7v5c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-4z"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <h3>Import data lebih terstruktur</h3>
                <p>
                    SIRAPIK mengelola data peserta dan riwayat pelatihan dalam
                    satu sistem terintegrasi. Pastikan file sumber dan informasi
                    pelatihan sesuai sebelum memulai proses.
                </p>
            </section>

            <section class="si-checklist">
                <h3>Checklist sebelum import</h3>

                <ul class="si-check-list">
                    <li>
                        <span class="si-check" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg></span>
                        File Excel memiliki susunan kolom yang sesuai.
                    </li>
                    <li>
                        <span class="si-check" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg></span>
                        Data identitas peserta sudah diperiksa.
                    </li>
                    <li>
                        <span class="si-check" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg></span>
                        Wilayah dan sumber dana sudah dipilih.
                    </li>
                    <li>
                        <span class="si-check" aria-hidden="true"><svg class="i" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg></span>
                        Kategori dan JPL diisi jika informasinya tersedia.
                    </li>
                </ul>
            </section>

            <section class="si-side-warning">
                <div class="si-warning-heading">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
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
                    <svg class="i si-spin" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M21 12a9 9 0 1 1-6.22-8.56"/>
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