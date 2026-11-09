@extends('layouts.app')

@section('title', 'Import Data Peserta Pelatihan')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       IMPORT DATA PESERTA — SIRAPIK (Premium Blue Editorial)
    ===================================================== */
    .im {
        --navy: #081b33; --midnight: #0b2545; --royal: #2457d6;
        --electric: #3478f6; --soft: #eaf2ff; --ice: #f3f7fc;
        --slate: #64748b; --ink: #14243a; --line: #e1e9f2; --cyan: #38c6e8;
        --red: #991b1b; --red-soft: #fef2f2; --red-line: #fecaca;
        --green: #166534; --green-soft: #f0fdf4; --green-line: #bbf7d0;
        --amber: #7a4f0e; --amber-soft: #fffaf0; --amber-line: #f0dfb9;
        --f-display: 'Sora', system-ui, sans-serif;
        --f-body: 'Plus Jakarta Sans', system-ui, sans-serif;
        --ease: cubic-bezier(.2, .7, .3, 1);

        width: 100%;
        color: var(--ink);
        font-family: var(--f-body);
        font-size: 16px;
        line-height: 1.7;
    }

    .im *, .im *::before, .im *::after { box-sizing: border-box; }
    .im svg { display: block; flex: 0 0 auto; }
    .im input, .im select, .im button { font: inherit; }

    .im :focus-visible {
        outline: 3px solid rgba(52, 120, 246, .55);
        outline-offset: 3px;
        border-radius: 8px;
    }

    .im-progress {
        position: fixed; top: 0; left: 0; z-index: 60;
        width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--electric), var(--cyan));
        transform: scaleX(0); transform-origin: 0 50%;
        pointer-events: none;
    }

    .im.is-js [data-reveal] {
        opacity: 0; transform: translateY(20px);
        transition: opacity .7s var(--ease), transform .7s var(--ease);
    }
    .im.is-js [data-reveal].is-in { opacity: 1; transform: none; }

    /* =====================================================
       HERO
    ===================================================== */
    .im-hero {
        position: relative; isolation: isolate; overflow: hidden;
        margin-bottom: 28px; padding: clamp(28px, 5vw, 52px);
        color: #fff; border-radius: 24px;
        background: linear-gradient(160deg, var(--navy) 0%, var(--midnight) 100%);
    }

    .im-hero::before {
        content: ""; position: absolute; inset: 0; z-index: -2; opacity: .1;
        background-image:
            linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px);
        background-size: 56px 56px;
        -webkit-mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
        mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
    }

    .im-orbit {
        position: absolute; z-index: -1; right: -150px; bottom: -190px;
        width: 460px; height: 460px; border-radius: 50%;
        border: 1px solid rgba(130,183,255,.25);
        box-shadow: 0 0 0 54px rgba(130,183,255,.04), 0 0 0 108px rgba(130,183,255,.025);
        animation: im-float 14s ease-in-out infinite;
    }
    @keyframes im-float { 50% { transform: translateY(-18px); } }

    .im-eyebrow {
        display: inline-flex; align-items: center; gap: 12px;
        margin-bottom: 20px; color: #bcd8ff; font-size: 14px; font-weight: 700;
    }
    .im-eyebrow::before { content: ""; width: 32px; height: 2px; background: var(--cyan); }

    .im-hero-grid {
        display: grid; align-items: end; gap: 36px;
        grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
    }

    .im-hero h1 {
        margin: 0; font-family: var(--f-display);
        font-size: clamp(34px, 5.2vw, 68px);
        font-weight: 800; letter-spacing: -.05em; line-height: 1.02;
    }
    .im-hero h1 span { display: block; color: #82b7ff; }

    .im-hero p {
        max-width: 52ch; margin: 20px 0 0;
        color: rgba(255,255,255,.76); font-size: clamp(16px, 1.3vw, 18px); line-height: 1.8;
    }

    .im-flow { margin: 0; padding: 0; list-style: none; border-top: 1px solid rgba(255,255,255,.2); counter-reset: flow; }
    .im-flow li {
        display: flex; align-items: center; gap: 14px; padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,.12);
        color: rgba(255,255,255,.84); font-size: 14px; line-height: 1.5;
        counter-increment: flow;
    }
    .im-flow li::before {
        content: counter(flow); display: grid; place-items: center;
        width: 28px; height: 28px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.3); color: var(--cyan);
        font-family: var(--f-display); font-size: 12px; font-weight: 700;
    }
    .im-flow-title { margin: 0 0 4px; color: #bcd8ff; font-size: 13px; font-weight: 700; }

    /* =====================================================
       ALERT
    ===================================================== */
    .im-alert {
        display: flex; align-items: flex-start; gap: 14px;
        margin-bottom: 24px; padding: 18px 20px;
        border: 1px solid transparent; border-left-width: 4px; border-radius: 14px;
        font-size: 14px; line-height: 1.7;
    }
    .im-alert > svg { width: 22px; height: 22px; margin-top: 2px; }
    .im-alert-body { min-width: 0; flex: 1; }
    .im-alert strong.h { display: block; font-family: var(--f-display); font-size: 16px; font-weight: 700; }
    .im-alert ul { margin: 8px 0 0; padding-left: 20px; }
    .im-alert li { overflow-wrap: anywhere; }
    .im-alert-danger { color: var(--red); background: var(--red-soft); border-color: var(--red-line); }
    .im-alert-success { color: var(--green); background: var(--green-soft); border-color: var(--green-line); }

    /* Hasil import: angka besar */
    .im-result { display: grid; gap: 0; margin-top: 16px; grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .im-result-item { padding: 4px 18px; border-left: 1px solid var(--green-line); }
    .im-result-item:first-child { padding-left: 0; border-left: 0; }
    .im-result-num {
        font-family: var(--f-display); font-size: clamp(28px, 3.4vw, 42px);
        font-weight: 800; letter-spacing: -.04em; line-height: 1;
        font-variant-numeric: tabular-nums; color: var(--navy);
    }
    .im-result-label { margin-top: 6px; font-size: 13px; font-weight: 600; line-height: 1.4; }

    .im-notes { margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--green-line); }

    /* =====================================================
       FORM — SECTION SPLIT
    ===================================================== */
    .im-section {
        display: grid; gap: 28px 56px;
        grid-template-columns: minmax(0, .5fr) minmax(0, 1.5fr);
        padding: 36px 0; border-top: 2px solid var(--navy);
    }

    .im-section-head { align-self: start; position: sticky; top: 90px; }
    .im-num { display: block; margin-bottom: 10px; color: var(--royal); font-family: var(--f-display); font-size: 15px; font-weight: 800; }
    .im-section-head h2 {
        margin: 0; color: var(--navy); font-family: var(--f-display);
        font-size: clamp(24px, 2.6vw, 34px); font-weight: 800; letter-spacing: -.04em; line-height: 1.12;
    }
    .im-section-head p { margin: 10px 0 0; color: var(--slate); font-size: 14px; line-height: 1.7; }

    .im-grid { display: grid; gap: 22px 24px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .im-field { min-width: 0; }
    .im-full { grid-column: 1 / -1; }

    .im-field label {
        display: flex; align-items: center; flex-wrap: wrap; gap: 6px;
        margin-bottom: 9px; color: #40536a; font-size: 14px; font-weight: 700;
    }
    .im-req { color: var(--red); font-size: 16px; line-height: 1; }
    .im-opt { color: #7a8aa0; font-size: 12px; font-weight: 600; }

    .im .im-control {
        display: block; width: 100%; min-width: 0; height: 56px;
        padding: 0 16px; border: 1.5px solid var(--line); border-radius: 14px;
        outline: none; background: #fff; color: var(--ink); font-size: 16px;
        transition: border-color .25s, box-shadow .25s;
    }
    .im .im-control::placeholder { color: #8998ab; }
    .im .im-control:hover { border-color: #a6bddb; }
    .im .im-control:focus { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.12); }
    .im select.im-control { padding-right: 40px; cursor: pointer; }

    .im-help { display: block; margin-top: 8px; color: var(--slate); font-size: 13px; line-height: 1.6; }

    /* Dropzone */
    .im-drop {
        position: relative; display: flex; align-items: center; gap: 18px;
        padding: 28px; border: 2px dashed #a6bddb; border-radius: 18px;
        background: var(--ice);
        transition: border-color .25s, background .25s, box-shadow .25s;
    }
    .im-drop:hover, .im-drop.is-over { border-color: var(--royal); background: var(--soft); }
    .im-drop:focus-within { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.12); }

    .im-drop input[type="file"] {
        position: absolute; inset: 0; z-index: 2; width: 100%; height: 100%;
        margin: 0; opacity: 0; cursor: pointer;
    }

    .im-drop-icon {
        display: grid; place-items: center; width: 56px; height: 56px;
        border-radius: 16px; background: #fff; color: var(--royal);
        box-shadow: 0 8px 20px rgba(8,27,51,.08);
    }
    .im-drop-icon svg { width: 26px; height: 26px; }
    .im-drop strong { display: block; color: var(--navy); font-family: var(--f-display); font-size: 17px; font-weight: 700; }
    .im-drop span.s { display: block; margin-top: 2px; color: var(--slate); font-size: 14px; }

    .im-selected {
        display: flex; align-items: center; gap: 12px; margin-top: 14px;
        padding: 13px 16px; border: 1px solid var(--green-line); border-radius: 14px;
        background: var(--green-soft); color: var(--green);
        font-size: 14px; font-weight: 600; overflow-wrap: anywhere;
    }
    .im-selected[hidden] { display: none; }
    .im-selected svg { width: 20px; height: 20px; }

    /* Aksi */
    .im-footer {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px;
        padding: 26px 0 0; border-top: 2px solid var(--navy);
    }
    .im-note { display: flex; align-items: flex-start; gap: 10px; max-width: 60ch; color: var(--slate); font-size: 13px; line-height: 1.7; }
    .im-note svg { width: 18px; height: 18px; margin-top: 2px; }
    .im-note b { color: var(--red); }
    .im-actions { display: flex; align-items: center; gap: 12px; }

    .im .im-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        min-height: 54px; padding: 12px 26px; border: 1.5px solid transparent; border-radius: 999px;
        font-size: 15px; font-weight: 700; cursor: pointer;
        transition: transform .25s var(--ease), box-shadow .25s, background .25s, color .25s, border-color .25s;
    }
    .im .im-btn svg { width: 18px; height: 18px; }
    .im .im-btn:active { transform: scale(.98); }
    .im .im-btn-primary { background: var(--royal); color: #fff; }
    .im .im-btn-primary:hover { background: var(--navy); transform: translateY(-2px); box-shadow: 0 14px 30px rgba(36,87,214,.28); }
    .im .im-btn-primary:disabled { background: #7f9bd8; box-shadow: none; transform: none; cursor: wait; }
    .im .im-btn-ghost { background: transparent; border-color: var(--line); color: #40536a; }
    .im .im-btn-ghost:hover { border-color: var(--royal); color: var(--royal); }

    @keyframes im-spin { to { transform: rotate(360deg); } }
    .im .is-spin { animation: im-spin 1s linear infinite; }

    /* Informasi penting */
    .im-warn {
        display: flex; align-items: flex-start; gap: 14px; margin-top: 30px;
        padding: 18px 20px; border: 1px solid var(--amber-line); border-left: 4px solid #d9a441;
        border-radius: 14px; background: var(--amber-soft);
    }
    .im-warn svg { width: 22px; height: 22px; margin-top: 2px; color: var(--amber); }
    .im-warn strong { display: block; color: var(--amber); font-size: 15px; }
    .im-warn p { margin: 4px 0 0; color: #6f5a34; font-size: 14px; line-height: 1.7; }

    /* =====================================================
       RESPONSIVE
    ===================================================== */
    @media (max-width: 1000px) {
        .im-hero-grid { grid-template-columns: 1fr; }
        .im-section { grid-template-columns: 1fr; gap: 22px; padding: 30px 0; }
        .im-section-head { position: static; }
        .im-result { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 0; }
        .im-result-item:nth-child(3) { padding-left: 0; border-left: 0; }
    }

    @media (max-width: 640px) {
        .im-hero { border-radius: 18px; }
        .im-grid { grid-template-columns: 1fr; gap: 20px; }
        .im-full { grid-column: auto; }
        .im .im-control { height: 54px; }
        .im-drop { flex-direction: column; align-items: flex-start; padding: 22px; }
        .im-footer { flex-direction: column; align-items: stretch; }
        .im-actions { flex-direction: column-reverse; align-items: stretch; }
        .im .im-btn { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .im *, .im *::before, .im *::after {
            animation: none !important;
            transition-duration: .01ms !important;
        }
        .im.is-js [data-reveal] { opacity: 1; transform: none; }
    }
</style>

<div class="im" id="im">

    <div class="im-progress" id="imProgress" aria-hidden="true"></div>

    {{-- =================================================
         HERO
    ================================================== --}}
    <header class="im-hero" data-reveal>
        <div class="im-orbit" aria-hidden="true"></div>

        <span class="im-eyebrow">Import Excel SIRAPIK</span>

        <div class="im-hero-grid">

            <div>
                <h1>Import data peserta <span>dalam satu proses.</span></h1>

                <p>
                    Unggah file Excel, lengkapi informasi pelatihan, lalu mulai
                    import data peserta dan riwayat pelatihan ke SIRAPIK.
                </p>
            </div>

            <div>
                <p class="im-flow-title">Alur import</p>

                <ol class="im-flow">
                    <li>Unggah file XLSX, XLS, atau CSV</li>
                    <li>Lengkapi informasi penyelenggaraan</li>
                    <li>Tentukan kategori dan durasi</li>
                </ol>
            </div>

        </div>
    </header>

    {{-- =================================================
         VALIDATION ERROR
    ================================================== --}}
    @if ($errors->any())
        <div class="im-alert im-alert-danger" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4"/>
                <path d="M12 16h.01"/>
            </svg>

            <div class="im-alert-body">
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

    {{-- =================================================
         HASIL IMPORT
    ================================================== --}}
    @if (session('import_result'))
        @php($result = session('import_result'))

        <div class="im-alert im-alert-success" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>

            <div class="im-alert-body">
                <strong class="h">Import selesai diproses.</strong>

                <div class="im-result">
                    <div class="im-result-item">
                        <div class="im-result-num" data-count="{{ $result['created_participants'] ?? 0 }}">{{ $result['created_participants'] ?? 0 }}</div>
                        <div class="im-result-label">Peserta baru</div>
                    </div>
                    <div class="im-result-item">
                        <div class="im-result-num" data-count="{{ $result['updated_participants'] ?? 0 }}">{{ $result['updated_participants'] ?? 0 }}</div>
                        <div class="im-result-label">Peserta diperbarui</div>
                    </div>
                    <div class="im-result-item">
                        <div class="im-result-num" data-count="{{ $result['created_trainings'] ?? 0 }}">{{ $result['created_trainings'] ?? 0 }}</div>
                        <div class="im-result-label">Pelatihan baru</div>
                    </div>
                    <div class="im-result-item">
                        <div class="im-result-num" data-count="{{ $result['created_histories'] ?? 0 }}">{{ $result['created_histories'] ?? 0 }}</div>
                        <div class="im-result-label">Riwayat pelatihan baru</div>
                    </div>
                </div>

                @if (!empty($result['errors']))
                    <div class="im-notes">
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

    {{-- =================================================
         FORM
    ================================================== --}}
    <form
        id="importForm"
        action="{{ route('imports.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        {{-- 01 FILE --}}
        <section class="im-section" data-reveal aria-labelledby="imFile">
            <div class="im-section-head">
                <span class="im-num">01</span>
                <h2 id="imFile">File sumber data</h2>
                <p>Unggah file Excel yang berisi data peserta pelatihan.</p>
            </div>

            <div class="im-grid">
                <div class="im-field im-full">
                    <label for="file">
                        File Excel
                        <span class="im-req" aria-hidden="true">*</span>
                    </label>

                    <div class="im-drop" id="dropZone">
                        <div class="im-drop-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 16V4"/>
                                <path d="m7 9 5-5 5 5"/>
                                <path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>
                            </svg>
                        </div>

                        <div>
                            <strong>Pilih atau tarik file ke sini</strong>
                            <span class="s">XLSX, XLS, atau CSV</span>
                        </div>

                        <input
                            type="file"
                            name="file"
                            id="file"
                            accept=".xlsx,.xls,.csv"
                            aria-describedby="file-help"
                            required
                        >
                    </div>

                    <small class="im-help" id="file-help">
                        Format yang didukung: XLSX, XLS, atau CSV.
                        Ukuran file maksimal 10 MB.
                        Silakan download template Excel terlebih dahulu sebelum mengisi dan mengunggah file.
                    </small>

                    <div id="selectedFile" class="im-selected" hidden>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 2v6h6"/>
                            <path d="M8 13h8M8 17h8"/>
                        </svg>
                        <span id="selectedFileName"></span>
                    </div>
                </div>
            </div>
            <a href="{{ route('training.download-template') }}"
   class="btn btn-success">
    <i class="fas fa-download"></i>
    Download Template Excel
</a>
        </section>

        {{-- 02 PENYELENGGARAAN --}}
        <section class="im-section" data-reveal aria-labelledby="imPenyelenggaraan">
            <div class="im-section-head">
                <span class="im-num">02</span>
                <h2 id="imPenyelenggaraan">Penyelenggaraan pelatihan</h2>
                <p>Angkatan, wilayah, dan sumber dana untuk seluruh data dalam file.</p>
            </div>

            <div class="im-grid">

                {{-- ANGKATAN --}}
                <div class="im-field">
                    <label for="angkatan">
                        Angkatan
                        <span class="im-opt">Opsional</span>
                    </label>

                    <input
                        type="text"
                        name="angkatan"
                        id="angkatan"
                        class="im-control"
                        value="{{ old('angkatan') }}"
                        placeholder="Contoh: 1"
                        maxlength="100"
                    >

                    <small class="im-help">
                        Kosongkan jika pelatihan tidak memiliki informasi angkatan.
                    </small>
                </div>

                {{-- WILAYAH --}}
                <div class="im-field">
                    <label for="kabupaten_kota_mitra">
                        Kabupaten/Kota/Mitra/Mandiri
                        <span class="im-req" aria-hidden="true">*</span>
                    </label>

                    <select
                        name="kabupaten_kota_mitra"
                        id="kabupaten_kota_mitra"
                        class="im-control"
                        required
                    >
                        <option value="">-- Pilih wilayah atau mitra --</option>

                        @foreach ($districts as $district)
                            <option
                                value="{{ $district->name }}"
                                @selected(old('kabupaten_kota_mitra') === $district->name)
                            >
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>

                    <small class="im-help">
                        Pilihan mengikuti Master Data SIRAPIK.
                    </small>
                </div>

                {{-- SUMBER DANA --}}
                <div class="im-field im-full">
                    <label for="sumber_dana">
                        Sumber Dana
                        <span class="im-req" aria-hidden="true">*</span>
                    </label>

                    <select
                        name="sumber_dana"
                        id="sumber_dana"
                        class="im-control"
                        required
                    >
                        <option value="">-- Pilih sumber dana --</option>

                        @foreach ($fundingSources as $source)
                            <option
                                value="{{ $source->name }}"
                                @selected(old('sumber_dana') === $source->name)
                            >
                                {{ $source->name }}
                            </option>
                        @endforeach
                    </select>

                    <small class="im-help">
                        Pilihan mengikuti Master Data Sumber Dana SIRAPIK.
                    </small>
                </div>

            </div>
        </section>

        {{-- 03 KLASIFIKASI --}}
        <section class="im-section" data-reveal aria-labelledby="imKlasifikasi">
            <div class="im-section-head">
                <span class="im-num">03</span>
                <h2 id="imKlasifikasi">Klasifikasi dan durasi</h2>
                <p>Kategori dan jumlah JPL, jika informasinya tersedia.</p>
            </div>

            <div class="im-grid">

                {{-- KATEGORI --}}
                <div class="im-field">
                    <label for="training_category_id">
                        Kategori Pelatihan
                        <span class="im-opt">Opsional</span>
                    </label>

                    <select
                        name="training_category_id"
                        id="training_category_id"
                        class="im-control"
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

                    <small class="im-help">
                        Mengikuti Master Data Kategori Pelatihan.
                    </small>
                </div>

                {{-- JPL --}}
                <div class="im-field">
                    <label for="jpl">
                        Jumlah JPL
                        <span class="im-opt">Opsional</span>
                    </label>

                    <input
                        type="number"
                        name="jpl"
                        id="jpl"
                        class="im-control"
                        value="{{ old('jpl') }}"
                        min="0"
                        step="1"
                        placeholder="Contoh: 32"
                    >

                    <small class="im-help">
                        Isi jumlah Jam Pelajaran jika tersedia.
                    </small>
                </div>

            </div>
        </section>

        {{-- AKSI --}}
        <div class="im-footer" data-reveal>

            <div class="im-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4"/>
                    <path d="M12 8h.01"/>
                </svg>

                <span>
                    Kolom bertanda <b>*</b> wajib diisi. Pastikan data sudah
                    benar sebelum diimport.
                </span>
            </div>

            <div class="im-actions">
                <button type="reset" class="im-btn im-btn-ghost" id="resetButton">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>
                    Reset Form
                </button>

                <button type="submit" class="im-btn im-btn-primary" id="submitButton">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 16V4"/>
                        <path d="m7 9 5-5 5 5"/>
                        <path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>
                    </svg>
                    <span>Import Excel</span>
                </button>
            </div>

        </div>

    </form>

    {{-- INFORMASI PENTING --}}
    <aside class="im-warn" data-reveal aria-label="Informasi penting">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
            <path d="M12 9v4"/>
            <path d="M12 17h.01"/>
        </svg>

        <div>
            <strong>Informasi penting</strong>
            <p>
                Peserta yang sudah terdaftar dapat diperbarui berdasarkan
                NIP atau NIK. Riwayat pelatihan yang sama tidak dibuat
                berulang. Kategori Pelatihan dan JPL boleh dikosongkan
                jika informasinya belum tersedia.
            </p>
        </div>
    </aside>

</div>

<script>
(function () {
    function initializeImportPage() {
        const page = document.getElementById('im');

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

        /* Counter hasil import */
        if (!reduce) {
            page.querySelectorAll('[data-count]').forEach(function (el) {
                const target = parseInt(el.dataset.count, 10) || 0;
                if (target === 0) return;

                const start = performance.now();

                function tick(now) {
                    const p = Math.min((now - start) / 900, 1);
                    el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
                    if (p < 1) requestAnimationFrame(tick);
                }

                el.textContent = '0';
                requestAnimationFrame(tick);
            });
        }

        /* Progress bar scroll */
        const bar = document.getElementById('imProgress');
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

        /* Form */
        const fileInput = page.querySelector('#file');
        const dropZone = page.querySelector('#dropZone');
        const selectedFile = page.querySelector('#selectedFile');
        const selectedFileName = page.querySelector('#selectedFileName');
        const importForm = page.querySelector('#importForm');
        const submitButton = page.querySelector('#submitButton');
        const resetButton = page.querySelector('#resetButton');

        const defaultButtonContent = submitButton ? submitButton.innerHTML : '';

        /* Nama file yang dipilih */
        if (fileInput && selectedFile && selectedFileName) {
            fileInput.addEventListener('change', function () {
                const file = this.files && this.files.length ? this.files[0] : null;

                if (!file) {
                    selectedFileName.textContent = '';
                    selectedFile.hidden = true;
                    return;
                }

                const fileSize = (file.size / 1024 / 1024).toFixed(2);

                selectedFileName.textContent = file.name + ' · ' + fileSize + ' MB';
                selectedFile.hidden = false;
            });
        }

        /* Efek saat file diseret ke area unggah */
        if (dropZone) {
            ['dragenter', 'dragover'].forEach(function (name) {
                dropZone.addEventListener(name, function () {
                    dropZone.classList.add('is-over');
                });
            });

            ['dragleave', 'drop'].forEach(function (name) {
                dropZone.addEventListener(name, function () {
                    dropZone.classList.remove('is-over');
                });
            });
        }

        /* Cegah pengiriman berulang */
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
                    <svg class="is-spin" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M21 12a9 9 0 1 1-6.22-8.56"/>
                    </svg>
                    <span>Memproses import...</span>
                `;
            });
        }

        /* Reset tampilan setelah form direset */
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

        /* Pulihkan tombol jika kembali lewat riwayat browser */
        window.addEventListener('pageshow', function () {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = defaultButtonContent;
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeImportPage);
    } else {
        initializeImportPage();
    }
})();
</script>

@endsection