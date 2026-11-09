@extends('layouts.app')

@section('title', 'Edit Pelatihan')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       EDIT PELATIHAN — SIRAPIK (Premium Blue Editorial)
    ===================================================== */
    .tr {
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

    .tr *, .tr *::before, .tr *::after { box-sizing: border-box; }
    .tr svg { display: block; flex: 0 0 auto; }
    .tr input, .tr select, .tr button { font: inherit; }

    .tr :focus-visible {
        outline: 3px solid rgba(52, 120, 246, .55);
        outline-offset: 3px;
        border-radius: 8px;
    }

    .tr-progress {
        position: fixed; top: 0; left: 0; z-index: 60;
        width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--electric), var(--cyan));
        transform: scaleX(0); transform-origin: 0 50%;
        pointer-events: none;
    }

    .tr.is-js [data-reveal] {
        opacity: 0; transform: translateY(20px);
        transition: opacity .7s var(--ease), transform .7s var(--ease);
    }
    .tr.is-js [data-reveal].is-in { opacity: 1; transform: none; }

    /* =====================================================
       HERO
    ===================================================== */
    .tr-hero {
        position: relative; isolation: isolate; overflow: hidden;
        margin-bottom: 28px; padding: clamp(28px, 5vw, 52px);
        color: #fff; border-radius: 24px;
        background: linear-gradient(160deg, var(--navy) 0%, var(--midnight) 100%);
    }

    .tr-hero::before {
        content: ""; position: absolute; inset: 0; z-index: -2; opacity: .1;
        background-image:
            linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px);
        background-size: 56px 56px;
        -webkit-mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
        mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
    }

    .tr-orbit {
        position: absolute; z-index: -1; right: -150px; bottom: -190px;
        width: 460px; height: 460px; border-radius: 50%;
        border: 1px solid rgba(130,183,255,.25);
        box-shadow: 0 0 0 54px rgba(130,183,255,.04), 0 0 0 108px rgba(130,183,255,.025);
        animation: tr-float 14s ease-in-out infinite;
    }
    @keyframes tr-float { 50% { transform: translateY(-18px); } }

    .tr-eyebrow {
        display: inline-flex; align-items: center; gap: 12px;
        margin-bottom: 20px; color: #bcd8ff; font-size: 14px; font-weight: 700;
    }
    .tr-eyebrow::before { content: ""; width: 32px; height: 2px; background: var(--cyan); }

    .tr-hero-grid {
        display: grid; align-items: end; gap: 36px;
        grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
    }

    .tr-hero h1 {
        margin: 0; font-family: var(--f-display);
        font-size: clamp(34px, 5.2vw, 68px);
        font-weight: 800; letter-spacing: -.05em; line-height: 1.02;
    }
    .tr-hero h1 span { display: block; color: #82b7ff; }

    .tr-hero p {
        max-width: 52ch; margin: 20px 0 0;
        color: rgba(255,255,255,.76); font-size: clamp(16px, 1.3vw, 18px); line-height: 1.8;
    }

    .tr-current { border-top: 1px solid rgba(255,255,255,.2); }
    .tr-current-label { margin: 14px 0 6px; color: #bcd8ff; font-size: 13px; font-weight: 700; }
    .tr-current strong {
        display: block; font-family: var(--f-display); font-size: clamp(18px, 2vw, 24px);
        font-weight: 700; letter-spacing: -.02em; line-height: 1.35; overflow-wrap: anywhere;
    }
    .tr-tag {
        display: inline-flex; align-items: center; gap: 8px; margin-top: 16px; padding: 7px 14px;
        border: 1px solid rgba(255,255,255,.2); border-radius: 999px;
        background: rgba(255,255,255,.07); color: #bcd8ff; font-size: 13px; font-weight: 600;
    }
    .tr-tag svg { width: 15px; height: 15px; }

    /* =====================================================
       ALERT
    ===================================================== */
    .tr-alert {
        display: flex; align-items: flex-start; gap: 14px; margin-bottom: 24px;
        padding: 18px 20px; color: var(--red);
        border: 1px solid var(--red-line); border-left: 4px solid #d74747;
        border-radius: 14px; background: var(--red-soft); font-size: 14px; line-height: 1.7;
    }
    .tr-alert > svg { width: 22px; height: 22px; margin-top: 2px; }
    .tr-alert strong { display: block; font-family: var(--f-display); font-size: 16px; font-weight: 700; }
    .tr-alert ul { margin: 8px 0 0; padding-left: 20px; }
    .tr-alert li { overflow-wrap: anywhere; }

    /* =====================================================
       FORM — SECTION SPLIT
    ===================================================== */
    .tr-section {
        display: grid; gap: 28px 56px;
        grid-template-columns: minmax(0, .5fr) minmax(0, 1.5fr);
        padding: 36px 0; border-top: 2px solid var(--navy);
    }

    .tr-section-head { align-self: start; position: sticky; top: 90px; }
    .tr-num { display: block; margin-bottom: 10px; color: var(--royal); font-family: var(--f-display); font-size: 15px; font-weight: 800; }
    .tr-section-head h2 {
        margin: 0; color: var(--navy); font-family: var(--f-display);
        font-size: clamp(24px, 2.6vw, 34px); font-weight: 800; letter-spacing: -.04em; line-height: 1.12;
    }
    .tr-section-head p { margin: 10px 0 0; color: var(--slate); font-size: 14px; line-height: 1.7; }

    .tr-grid { display: grid; gap: 22px 24px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .tr-field { min-width: 0; }
    .tr-full { grid-column: 1 / -1; }

    .tr-field label {
        display: flex; align-items: center; flex-wrap: wrap; gap: 6px;
        margin-bottom: 9px; color: #40536a; font-size: 14px; font-weight: 700;
    }
    .tr-req { color: var(--red); font-size: 16px; line-height: 1; }

    .tr .tr-control {
        display: block; width: 100%; min-width: 0; height: 56px;
        padding: 0 16px; border: 1.5px solid var(--line); border-radius: 14px;
        outline: none; background: #fff; color: var(--ink); font-size: 16px;
        transition: border-color .25s, box-shadow .25s;
    }
    .tr .tr-control::placeholder { color: #8998ab; }
    .tr .tr-control:hover { border-color: #a6bddb; }
    .tr .tr-control:focus { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.12); }
    .tr .tr-control.input-error { border-color: #e48a93; background: var(--red-soft); }
    .tr select.tr-control { padding-right: 40px; cursor: pointer; }

    .tr-suffix-wrap { position: relative; }
    .tr-suffix-wrap .tr-control { padding-right: 70px; }
    .tr-suffix {
        position: absolute; top: 50%; right: 16px; transform: translateY(-50%);
        color: var(--slate); font-size: 13px; font-weight: 700; pointer-events: none;
    }

    .tr-error {
        display: flex; align-items: flex-start; gap: 7px;
        margin: 8px 0 0; color: var(--red); font-size: 13px; font-weight: 600; line-height: 1.5;
    }
    .tr-error svg { width: 16px; height: 16px; margin-top: 2px; }

    /* =====================================================
       AKSI
    ===================================================== */
    .tr-footer {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px;
        padding: 26px 0 0; border-top: 2px solid var(--navy);
    }
    .tr-note { color: var(--slate); font-size: 13px; line-height: 1.7; }
    .tr-note b { color: var(--red); }
    .tr-actions { display: flex; align-items: center; gap: 12px; }

    .tr .tr-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        min-height: 54px; padding: 12px 26px; border: 1.5px solid transparent; border-radius: 999px;
        font-size: 15px; font-weight: 700; text-decoration: none; cursor: pointer;
        transition: transform .25s var(--ease), box-shadow .25s, background .25s, color .25s, border-color .25s;
    }
    .tr .tr-btn svg { width: 18px; height: 18px; transition: transform .25s var(--ease); }
    .tr .tr-btn:active { transform: scale(.98); }
    .tr .tr-btn-primary { background: var(--royal); color: #fff; }
    .tr .tr-btn-primary:hover { background: var(--navy); transform: translateY(-2px); box-shadow: 0 14px 30px rgba(36,87,214,.28); }
    .tr .tr-btn-primary:disabled { background: #7f9bd8; box-shadow: none; transform: none; cursor: wait; }
    .tr .tr-btn-ghost { background: transparent; border-color: var(--line); color: #40536a; }
    .tr .tr-btn-ghost:hover { border-color: var(--royal); color: var(--royal); }
    .tr .tr-btn-ghost:hover svg { transform: translateX(-3px); }

    @keyframes tr-spin { to { transform: rotate(360deg); } }
    .tr .is-spin { animation: tr-spin 1s linear infinite; }

    .tr-bottom {
        display: flex; align-items: center; gap: 10px; margin-top: 22px;
        color: var(--slate); font-size: 13px; line-height: 1.6;
    }
    .tr-bottom::before { content: ""; width: 8px; height: 8px; flex: 0 0 auto; border-radius: 50%; background: var(--cyan); }

    /* =====================================================
       RESPONSIVE
    ===================================================== */
    @media (max-width: 1000px) {
        .tr-hero-grid { grid-template-columns: 1fr; }
        .tr-section { grid-template-columns: 1fr; gap: 22px; padding: 30px 0; }
        .tr-section-head { position: static; }
    }

    @media (max-width: 640px) {
        .tr-hero { border-radius: 18px; }
        .tr-grid { grid-template-columns: 1fr; gap: 20px; }
        .tr-full { grid-column: auto; }
        .tr .tr-control { height: 54px; }
        .tr-footer { flex-direction: column; align-items: stretch; }
        .tr-actions { flex-direction: column-reverse; align-items: stretch; }
        .tr .tr-btn { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .tr *, .tr *::before, .tr *::after {
            animation: none !important;
            transition-duration: .01ms !important;
        }
        .tr.is-js [data-reveal] { opacity: 1; transform: none; }
    }
</style>

<div class="tr" id="tr">

    <div class="tr-progress" id="trProgress" aria-hidden="true"></div>

    {{-- =================================================
         HERO
    ================================================== --}}
    <header class="tr-hero" data-reveal>
        <div class="tr-orbit" aria-hidden="true"></div>

        <span class="tr-eyebrow">Data Master / Pelatihan</span>

        <div class="tr-hero-grid">

            <div>
                <h1>Edit <span>Pelatihan</span></h1>

                <p>
                    Perbarui informasi pelatihan untuk memastikan data tetap
                    akurat, lengkap, dan tersimpan dengan baik.
                </p>
            </div>

            <div class="tr-current">
                <p class="tr-current-label">Pelatihan yang diedit</p>
                <strong>{{ $training->name ?: '-' }}</strong>

                <span class="tr-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                    </svg>
                    Mode: Perubahan Data
                </span>
            </div>

        </div>
    </header>

    {{-- =================================================
         FORM ERROR
    ================================================== --}}
    @if ($errors->any())
        <div class="tr-alert" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4"/>
                <path d="M12 16h.01"/>
            </svg>

            <div>
                <strong>Periksa kembali data yang dimasukkan.</strong>
                <span>Ada beberapa bagian yang perlu diperbaiki:</span>

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
        id="trainingForm"
        action="{{ route('trainings.update', $training) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        {{-- 01 INFORMASI PELATIHAN --}}
        <section class="tr-section" data-reveal aria-labelledby="trInfo">
            <div class="tr-section-head">
                <span class="tr-num">01</span>
                <h2 id="trInfo">Informasi Pelatihan</h2>
                <p>Identitas dan klasifikasi program pelatihan.</p>
            </div>

            <div class="tr-grid">

                {{-- Nama Pelatihan --}}
                <div class="tr-field tr-full">
                    <label for="name">
                        Nama Pelatihan <span class="tr-req" aria-hidden="true">*</span>
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $training->name) }}"
                        maxlength="255"
                        class="tr-control @error('name') input-error @enderror"
                        placeholder="Contoh: Pelatihan Kepemimpinan Administrator"
                        autocomplete="off"
                        required
                        @error('name') aria-invalid="true" @enderror
                    >

                    @error('name')
                        <p class="tr-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="tr-field">
                    <label for="training_category_id">
                        Kategori Pelatihan <span class="tr-req" aria-hidden="true">*</span>
                    </label>

                    <select
                        id="training_category_id"
                        name="training_category_id"
                        class="tr-control @error('training_category_id') input-error @enderror"
                        required
                        @error('training_category_id') aria-invalid="true" @enderror
                    >
                        <option value="">Pilih kategori pelatihan</option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(
                                    (string) old(
                                        'training_category_id',
                                        $training->training_category_id
                                    ) === (string) $category->id
                                )
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('training_category_id')
                        <p class="tr-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Angkatan --}}
                <div class="tr-field">
                    <label for="batch_number">Angkatan</label>

                    <input
                        id="batch_number"
                        type="text"
                        name="batch_number"
                        value="{{ old('batch_number', $training->batch_number) }}"
                        maxlength="100"
                        class="tr-control @error('batch_number') input-error @enderror"
                        placeholder="Masukkan nomor angkatan"
                        @error('batch_number') aria-invalid="true" @enderror
                    >

                    @error('batch_number')
                        <p class="tr-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </section>

        {{-- 02 PENYELENGGARAAN --}}
        <section class="tr-section" data-reveal aria-labelledby="trOrg">
            <div class="tr-section-head">
                <span class="tr-num">02</span>
                <h2 id="trOrg">Penyelenggaraan</h2>
                <p>Informasi pendanaan, penyelenggara, dan lokasi.</p>
            </div>

            <div class="tr-grid">

                {{-- Sumber Dana --}}
                <div class="tr-field">
                    <label for="funding_source">Sumber Dana</label>

                    <select
                        id="funding_source"
                        name="funding_source"
                        class="tr-control @error('funding_source') input-error @enderror"
                        @error('funding_source') aria-invalid="true" @enderror
                    >
                        <option value="">Pilih sumber dana</option>

                        @foreach ($fundingSources as $source)
                            <option
                                value="{{ $source->name }}"
                                @selected(
                                    old('funding_source', $training->funding_source) === $source->name
                                )
                            >
                                {{ $source->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('funding_source')
                        <p class="tr-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Penyelenggara --}}
                <div class="tr-field">
                    <label for="organizer">Penyelenggara</label>

                    <input
                        id="organizer"
                        type="text"
                        name="organizer"
                        value="{{ old('organizer', $training->organizer) }}"
                        maxlength="255"
                        class="tr-control @error('organizer') input-error @enderror"
                        placeholder="Nama instansi penyelenggara"
                        @error('organizer') aria-invalid="true" @enderror
                    >

                    @error('organizer')
                        <p class="tr-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kabupaten/Kota --}}
                <div class="tr-field tr-full">
                    <label for="district">Kabupaten/Kota</label>

                    <select
                        id="district"
                        name="district"
                        class="tr-control @error('district') input-error @enderror"
                        @error('district') aria-invalid="true" @enderror
                    >
                        <option value="">Pilih kabupaten/kota</option>

                        @foreach ($districts as $district)
                            <option
                                value="{{ $district->name }}"
                                @selected(
                                    old('district', $training->district) === $district->name
                                )
                            >
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('district')
                        <p class="tr-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </section>

        {{-- 03 WAKTU & DURASI --}}
        <section class="tr-section" data-reveal aria-labelledby="trTime">
            <div class="tr-section-head">
                <span class="tr-num">03</span>
                <h2 id="trTime">Waktu &amp; Durasi</h2>
                <p>Atur jadwal pelaksanaan dan jumlah jam pelajaran.</p>
            </div>

            <div class="tr-grid">

                {{-- Tanggal Mulai --}}
                <div class="tr-field">
                    <label for="start_date">
                        Tanggal Mulai <span class="tr-req" aria-hidden="true">*</span>
                    </label>

                    <input
                        id="start_date"
                        type="date"
                        name="start_date"
                        value="{{ old('start_date', $training->start_date?->format('Y-m-d')) }}"
                        class="tr-control @error('start_date') input-error @enderror"
                        required
                        @error('start_date') aria-invalid="true" @enderror
                    >

                    @error('start_date')
                        <p class="tr-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Selesai --}}
                <div class="tr-field">
                    <label for="end_date">Tanggal Selesai</label>

                    <input
                        id="end_date"
                        type="date"
                        name="end_date"
                        value="{{ old('end_date', $training->end_date?->format('Y-m-d')) }}"
                        class="tr-control @error('end_date') input-error @enderror"
                        @error('end_date') aria-invalid="true" @enderror
                    >

                    @error('end_date')
                        <p class="tr-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- JPL --}}
                <div class="tr-field">
                    <label for="jpl">Jumlah Jam Pelajaran (JPL)</label>

                    <div class="tr-suffix-wrap">
                        <input
                            id="jpl"
                            type="number"
                            name="jpl"
                            value="{{ old('jpl', $training->jpl) }}"
                            min="0"
                            step="1"
                            class="tr-control @error('jpl') input-error @enderror"
                            placeholder="Contoh: 40"
                            @error('jpl') aria-invalid="true" @enderror
                        >
                        <span class="tr-suffix" aria-hidden="true">JPL</span>
                    </div>

                    @error('jpl')
                        <p class="tr-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </section>

        {{-- AKSI --}}
        <div class="tr-footer" data-reveal>

            <div class="tr-note">
                <b>*</b> Wajib diisi
            </div>

            <div class="tr-actions">
                <a href="{{ route('trainings.index') }}" class="tr-btn tr-btn-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Batal
                </a>

                <button type="submit" class="tr-btn tr-btn-primary" id="saveTrainingButton">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12.5 9.5 17 19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </div>

    </form>

    <div class="tr-bottom" data-reveal>
        Pastikan informasi pelatihan sudah benar sebelum menyimpan perubahan.
    </div>

</div>

<script>
(function () {
    function initializeTrainingForm() {
        const page = document.getElementById('tr');

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
        const bar = document.getElementById('trProgress');
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

        /* Cegah pengiriman berulang */
        const form = page.querySelector('#trainingForm');
        const submitButton = page.querySelector('#saveTrainingButton');

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
        document.addEventListener('DOMContentLoaded', initializeTrainingForm);
    } else {
        initializeTrainingForm();
    }
})();
</script>

@endsection