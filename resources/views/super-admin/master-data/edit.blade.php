@extends('layouts.app')

@section('title', 'Edit ' . $config['title'])

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       EDIT MASTER DATA — SIRAPIK (Premium Blue Editorial)
    ===================================================== */
    .md {
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

    .md *, .md *::before, .md *::after { box-sizing: border-box; }
    .md svg { display: block; flex: 0 0 auto; }
    .md input, .md select, .md button { font: inherit; }

    .md :focus-visible {
        outline: 3px solid rgba(52, 120, 246, .55);
        outline-offset: 3px;
        border-radius: 8px;
    }

    .md-progress {
        position: fixed; top: 0; left: 0; z-index: 60;
        width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--electric), var(--cyan));
        transform: scaleX(0); transform-origin: 0 50%;
        pointer-events: none;
    }

    .md.is-js [data-reveal] {
        opacity: 0; transform: translateY(20px);
        transition: opacity .7s var(--ease), transform .7s var(--ease);
    }
    .md.is-js [data-reveal].is-in { opacity: 1; transform: none; }

    /* =====================================================
       HERO
    ===================================================== */
    .md-hero {
        position: relative; isolation: isolate; overflow: hidden;
        margin-bottom: 28px; padding: clamp(28px, 5vw, 52px);
        color: #fff; border-radius: 24px;
        background: linear-gradient(160deg, var(--navy) 0%, var(--midnight) 100%);
    }

    .md-hero::before {
        content: ""; position: absolute; inset: 0; z-index: -2; opacity: .1;
        background-image:
            linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px);
        background-size: 56px 56px;
        -webkit-mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
        mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
    }

    .md-orbit {
        position: absolute; z-index: -1; right: -150px; bottom: -190px;
        width: 460px; height: 460px; border-radius: 50%;
        border: 1px solid rgba(130,183,255,.25);
        box-shadow: 0 0 0 54px rgba(130,183,255,.04), 0 0 0 108px rgba(130,183,255,.025);
        animation: md-float 14s ease-in-out infinite;
    }
    @keyframes md-float { 50% { transform: translateY(-18px); } }

    .md-eyebrow {
        display: inline-flex; align-items: center; gap: 12px;
        margin-bottom: 20px; color: #bcd8ff; font-size: 14px; font-weight: 700;
    }
    .md-eyebrow::before { content: ""; width: 32px; height: 2px; background: var(--cyan); }

    .md-hero-grid {
        display: grid; align-items: end; gap: 36px;
        grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
    }

    .md-hero h1 {
        margin: 0; font-family: var(--f-display);
        font-size: clamp(34px, 5.2vw, 68px);
        font-weight: 800; letter-spacing: -.05em; line-height: 1.02;
        overflow-wrap: anywhere;
    }
    .md-hero h1 span { display: block; color: #82b7ff; }

    .md-hero-grid p {
        max-width: 52ch; margin: 20px 0 0;
        color: rgba(255,255,255,.76); font-size: clamp(16px, 1.3vw, 18px); line-height: 1.8;
    }

    .md-current { border-top: 1px solid rgba(255,255,255,.2); }
    .md-current-label { margin: 14px 0 6px !important; color: #bcd8ff !important; font-size: 13px !important; font-weight: 700; }
    .md-current strong {
        display: block; font-family: var(--f-display); font-size: clamp(18px, 2vw, 24px);
        font-weight: 700; letter-spacing: -.02em; line-height: 1.35; overflow-wrap: anywhere;
    }
    .md-status {
        display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; padding: 6px 14px;
        border: 1px solid rgba(255,255,255,.2); border-radius: 999px;
        background: rgba(255,255,255,.07); color: #bcd8ff; font-size: 13px; font-weight: 600;
    }
    .md-status i { width: 9px; height: 9px; border-radius: 50%; background: #54d69b; box-shadow: 0 0 0 4px rgba(84,214,155,.18); }
    .md-status.off i { background: #94a3b8; box-shadow: 0 0 0 4px rgba(148,163,184,.2); }

    /* Alert */
    .md-alert {
        display: flex; align-items: flex-start; gap: 14px; margin-bottom: 24px;
        padding: 18px 20px; color: var(--red);
        border: 1px solid var(--red-line); border-left: 4px solid #d74747;
        border-radius: 14px; background: var(--red-soft); font-size: 14px; line-height: 1.7;
    }
    .md-alert > svg { width: 22px; height: 22px; margin-top: 2px; }
    .md-alert strong { display: block; font-family: var(--f-display); font-size: 16px; font-weight: 700; }
    .md-alert ul { margin: 8px 0 0; padding-left: 20px; }
    .md-alert li { overflow-wrap: anywhere; }

    /* =====================================================
       FORM
    ===================================================== */
    .md-section {
        display: grid; gap: 28px 56px;
        grid-template-columns: minmax(0, .5fr) minmax(0, 1.5fr);
        padding: 36px 0; border-top: 2px solid var(--navy);
    }

    .md-section-head { align-self: start; position: sticky; top: 90px; }
    .md-num { display: block; margin-bottom: 10px; color: var(--royal); font-family: var(--f-display); font-size: 15px; font-weight: 800; }
    .md-section-head h2 {
        margin: 0; color: var(--navy); font-family: var(--f-display);
        font-size: clamp(24px, 2.6vw, 34px); font-weight: 800; letter-spacing: -.04em; line-height: 1.12;
    }
    .md-section-head p { margin: 10px 0 0; color: var(--slate); font-size: 14px; line-height: 1.7; }

    .md-grid { display: grid; gap: 22px 24px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .md-field { min-width: 0; }
    .md-full { grid-column: 1 / -1; }

    .md-field label {
        display: flex; align-items: center; flex-wrap: wrap; gap: 6px;
        margin-bottom: 9px; color: #40536a; font-size: 14px; font-weight: 700;
    }
    .md-req { color: var(--red); font-size: 16px; line-height: 1; }

    .md .md-control {
        display: block; width: 100%; min-width: 0; height: 56px;
        padding: 0 16px; border: 1.5px solid var(--line); border-radius: 14px;
        outline: none; background: #fff; color: var(--ink); font-size: 16px;
        transition: border-color .25s, box-shadow .25s;
    }
    .md .md-control::placeholder { color: #8998ab; }
    .md .md-control:hover { border-color: #a6bddb; }
    .md .md-control:focus { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.12); }
    .md .md-control.input-error { border-color: #e48a93; background: var(--red-soft); }
    .md select.md-control { padding-right: 40px; cursor: pointer; }

    .md-error { margin-top: 8px; color: var(--red); font-size: 13px; font-weight: 600; line-height: 1.5; }

    /* Aksi */
    .md-footer {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px;
        padding: 26px 0 0; border-top: 2px solid var(--navy);
    }
    .md-note { display: flex; align-items: flex-start; gap: 10px; max-width: 60ch; color: var(--slate); font-size: 13px; line-height: 1.7; }
    .md-note svg { width: 18px; height: 18px; margin-top: 2px; color: var(--royal); }
    .md-note b { color: var(--red); }
    .md-actions { display: flex; align-items: center; gap: 12px; }

    .md .md-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        min-height: 54px; padding: 12px 26px; border: 1.5px solid transparent; border-radius: 999px;
        font-size: 15px; font-weight: 700; text-decoration: none; cursor: pointer;
        transition: transform .25s var(--ease), box-shadow .25s, background .25s, color .25s, border-color .25s;
    }
    .md .md-btn svg { width: 18px; height: 18px; transition: transform .25s var(--ease); }
    .md .md-btn:active { transform: scale(.98); }
    .md .md-btn-primary { background: var(--royal); color: #fff; }
    .md .md-btn-primary:hover { background: var(--navy); transform: translateY(-2px); box-shadow: 0 14px 30px rgba(36,87,214,.28); }
    .md .md-btn-primary:disabled { background: #7f9bd8; box-shadow: none; transform: none; cursor: wait; }
    .md .md-btn-ghost { background: transparent; border-color: var(--line); color: #40536a; }
    .md .md-btn-ghost:hover { border-color: var(--royal); color: var(--royal); }
    .md .md-btn-ghost:hover svg { transform: translateX(-3px); }
    .md .md-btn-light { min-height: 48px; margin-top: 22px; background: #fff; color: var(--royal); }
    .md .md-btn-light:hover { background: var(--royal); color: #fff; transform: translateY(-2px); box-shadow: 0 14px 30px rgba(36,87,214,.35); }
    .md .md-btn-light:hover svg { transform: translateX(-3px); }

    @keyframes md-spin { to { transform: rotate(360deg); } }
    .md .is-spin { animation: md-spin 1s linear infinite; }

    /* =====================================================
       RESPONSIVE
    ===================================================== */
    @media (max-width: 1000px) {
        .md-hero-grid { grid-template-columns: 1fr; }
        .md-section { grid-template-columns: 1fr; gap: 22px; padding: 30px 0; }
        .md-section-head { position: static; }
    }

    @media (max-width: 640px) {
        .md-hero { border-radius: 18px; }
        .md-grid { grid-template-columns: 1fr; gap: 20px; }
        .md-full { grid-column: auto; }
        .md .md-control { height: 54px; }
        .md .md-btn-light { width: 100%; }
        .md-footer { flex-direction: column; align-items: stretch; }
        .md-actions { flex-direction: column-reverse; align-items: stretch; }
        .md .md-btn { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .md *, .md *::before, .md *::after {
            animation: none !important;
            transition-duration: .01ms !important;
        }
        .md.is-js [data-reveal] { opacity: 1; transform: none; }
    }
</style>

@php
    $indexUrl = route('super-admin.master-data.index', ['category' => $category]);

    $itemLabel = $item->name ?? $item->year ?? '-';
@endphp

<div class="md" id="md">

    <div class="md-progress" id="mdProgress" aria-hidden="true"></div>

    {{-- =================================================
         HERO
    ================================================== --}}
    <header class="md-hero" data-reveal>
        <div class="md-orbit" aria-hidden="true"></div>

        <span class="md-eyebrow">Super Admin / Master Data</span>

        <div class="md-hero-grid">

            <div>
                <h1>Edit <span>{{ $config['title'] }}</span></h1>

                <p>Perbarui data referensi SIRAPIK dengan teliti.</p>

                <a href="{{ $indexUrl }}" class="md-btn md-btn-light">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="md-current">
                <p class="md-current-label">Data yang diedit</p>
                <strong>{{ $itemLabel }}</strong>

                <span class="md-status {{ $item->is_active ? '' : 'off' }}">
                    <i aria-hidden="true"></i>
                    Status saat ini: {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

        </div>
    </header>

    {{-- =================================================
         VALIDATION ERRORS
    ================================================== --}}
    @if ($errors->any())
        <div class="md-alert" role="alert">
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
        id="masterDataForm"
        method="POST"
        action="{{ route('super-admin.master-data.update', [
            'category' => $category,
            'id' => $item->id,
        ]) }}"
    >
        @csrf
        @method('PUT')

        <section class="md-section" data-reveal aria-labelledby="mdData">
            <div class="md-section-head">
                <span class="md-num">01</span>
                <h2 id="mdData">Data {{ $config['title'] }}</h2>
                <p>Perbarui isian dan status data referensi.</p>
            </div>

            <div class="md-grid">

                @if ($category === 'districts')

                    <div class="md-field md-full">
                        <label for="province_id">
                            Provinsi <span class="md-req" aria-hidden="true">*</span>
                        </label>

                        <select
                            name="province_id"
                            id="province_id"
                            class="md-control @error('province_id') input-error @enderror"
                            required
                            @error('province_id') aria-invalid="true" @enderror
                        >
                            <option value="">-- Pilih Provinsi --</option>

                            @foreach ($provinces as $province)
                                <option
                                    value="{{ $province->id }}"
                                    @selected((string) old('province_id', $item->province_id) === (string) $province->id)
                                >
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('province_id')
                            <div class="md-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="md-field">
                        <label for="name">
                            Nama Wilayah/Mitra/Mandiri
                            <span class="md-req" aria-hidden="true">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $item->name) }}"
                            maxlength="255"
                            class="md-control @error('name') input-error @enderror"
                            required
                            @error('name') aria-invalid="true" @enderror
                        >

                        @error('name')
                            <div class="md-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="md-field">
                        <label for="type">
                            Jenis Wilayah <span class="md-req" aria-hidden="true">*</span>
                        </label>

                        <select
                            name="type"
                            id="type"
                            class="md-control @error('type') input-error @enderror"
                            required
                            @error('type') aria-invalid="true" @enderror
                        >
                            <option value="kabupaten" @selected(old('type', $item->type) === 'kabupaten')>
                                Kabupaten
                            </option>
                            <option value="kota" @selected(old('type', $item->type) === 'kota')>
                                Kota
                            </option>
                            <option value="mitra" @selected(old('type', $item->type) === 'mitra')>
                                Mitra
                            </option>
                            <option value="mandiri" @selected(old('type', $item->type) === 'mandiri')>
                                Mandiri
                            </option>
                        </select>

                        @error('type')
                            <div class="md-error">{{ $message }}</div>
                        @enderror
                    </div>

                @elseif ($category === 'training-years')

                    <div class="md-field">
                        <label for="year">
                            Tahun Pelatihan <span class="md-req" aria-hidden="true">*</span>
                        </label>

                        <input
                            type="number"
                            name="year"
                            id="year"
                            value="{{ old('year', $item->year) }}"
                            min="1900"
                            max="2155"
                            class="md-control @error('year') input-error @enderror"
                            required
                            @error('year') aria-invalid="true" @enderror
                        >

                        @error('year')
                            <div class="md-error">{{ $message }}</div>
                        @enderror
                    </div>

                @else

                    @php
                        $fieldLabel = match ($category) {
                            'provinces' => 'Nama Provinsi',
                            'funding-sources' => 'Nama Sumber Dana',
                            'health-worker-types' => 'Nama Jenis Tenaga Kesehatan',
                            'training-categories' => 'Nama Kategori Pelatihan',
                            default => 'Nama',
                        };
                    @endphp

                    <div class="md-field">
                        <label for="name">
                            {{ $fieldLabel }} <span class="md-req" aria-hidden="true">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $item->name) }}"
                            maxlength="255"
                            class="md-control @error('name') input-error @enderror"
                            required
                            @error('name') aria-invalid="true" @enderror
                        >

                        @error('name')
                            <div class="md-error">{{ $message }}</div>
                        @enderror
                    </div>

                @endif

                {{-- STATUS --}}
                <div class="md-field">
                    <label for="is_active">
                        Status <span class="md-req" aria-hidden="true">*</span>
                    </label>

                    <select
                        name="is_active"
                        id="is_active"
                        class="md-control @error('is_active') input-error @enderror"
                        required
                        @error('is_active') aria-invalid="true" @enderror
                    >
                        <option value="1" @selected((string) old('is_active', (int) $item->is_active) === '1')>
                            Aktif
                        </option>
                        <option value="0" @selected((string) old('is_active', (int) $item->is_active) === '0')>
                            Nonaktif
                        </option>
                    </select>

                    @error('is_active')
                        <div class="md-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </section>

        {{-- AKSI --}}
        <div class="md-footer" data-reveal>

            <div class="md-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4"/>
                    <path d="M12 8h.01"/>
                </svg>

                <span>
                    Kolom bertanda <b>*</b> wajib diisi. Periksa kembali data
                    sebelum menyimpan.
                </span>
            </div>

            <div class="md-actions">
                <a href="{{ $indexUrl }}" class="md-btn md-btn-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Batal
                </a>

                <button type="submit" class="md-btn md-btn-primary" id="saveMasterDataButton">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12.5 9.5 17 19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </div>

    </form>

</div>

<script>
(function () {
    function initializeMasterDataForm() {
        const page = document.getElementById('md');

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
        const bar = document.getElementById('mdProgress');
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
        const form = page.querySelector('#masterDataForm');
        const submitButton = page.querySelector('#saveMasterDataButton');

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
        document.addEventListener('DOMContentLoaded', initializeMasterDataForm);
    } else {
        initializeMasterDataForm();
    }
})();
</script>

@endsection