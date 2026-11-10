@extends('layouts.app')

@section('title', $participant->exists ? 'Edit Peserta' : 'Tambah Peserta')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       FORM PESERTA — SIRAPIK (Premium Blue Editorial)
    ===================================================== */
    .fm {
        --navy: #081b33; --midnight: #0b2545; --royal: #2457d6;
        --electric: #3478f6; --soft: #eaf2ff; --ice: #f3f7fc;
        --slate: #64748b; --ink: #14243a; --line: #e1e9f2; --cyan: #38c6e8;
        --red: #b42333; --red-soft: #fef2f2;
        --f-display: 'Sora', system-ui, sans-serif;
        --f-body: 'Plus Jakarta Sans', system-ui, sans-serif;
        --ease: cubic-bezier(.2, .7, .3, 1);

        width: 100%;
        color: var(--ink);
        font-family: var(--f-body);
        font-size: 16px;
        line-height: 1.7;
    }

    .fm *, .fm *::before, .fm *::after { box-sizing: border-box; }
    .fm svg { display: block; flex: 0 0 auto; }
    .fm input, .fm select, .fm button { font: inherit; }

    .fm :focus-visible {
        outline: 3px solid rgba(52, 120, 246, .55);
        outline-offset: 3px;
        border-radius: 8px;
    }

    .fm-progress {
        position: fixed; top: 0; left: 0; z-index: 60;
        width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--electric), var(--cyan));
        transform: scaleX(0); transform-origin: 0 50%;
        pointer-events: none;
    }

    .fm.is-js [data-reveal] {
        opacity: 0; transform: translateY(20px);
        transition: opacity .7s var(--ease), transform .7s var(--ease);
    }
    .fm.is-js [data-reveal].is-in { opacity: 1; transform: none; }

    /* =====================================================
       HERO
    ===================================================== */
    .fm-hero {
        position: relative; isolation: isolate; overflow: hidden;
        margin-bottom: 28px;
        padding: clamp(28px, 5vw, 52px);
        color: #fff;
        background: linear-gradient(160deg, var(--navy) 0%, var(--midnight) 100%);
        border-radius: 24px;
    }

    .fm-hero::before {
        content: ""; position: absolute; inset: 0; z-index: -2; opacity: .1;
        background-image:
            linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px);
        background-size: 56px 56px;
        -webkit-mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
        mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
    }

    .fm-orbit {
        position: absolute; z-index: -1; right: -150px; bottom: -190px;
        width: 460px; height: 460px; border-radius: 50%;
        border: 1px solid rgba(130,183,255,.25);
        box-shadow: 0 0 0 54px rgba(130,183,255,.04), 0 0 0 108px rgba(130,183,255,.025);
        animation: fm-float 14s ease-in-out infinite;
    }
    @keyframes fm-float { 50% { transform: translateY(-18px); } }

    .fm-eyebrow {
        display: inline-flex; align-items: center; gap: 12px;
        margin-bottom: 20px; color: #bcd8ff; font-size: 14px; font-weight: 700;
    }
    .fm-eyebrow::before { content: ""; width: 32px; height: 2px; background: var(--cyan); }

    .fm-hero-grid {
        display: grid; align-items: end; gap: 36px;
        grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
    }

    .fm-hero h1 {
        margin: 0;
        font-family: var(--f-display);
        font-size: clamp(34px, 5.2vw, 68px);
        font-weight: 800; letter-spacing: -.05em; line-height: 1.02;
    }

    .fm-hero p {
        max-width: 52ch; margin: 20px 0 0;
        color: rgba(255,255,255,.76); font-size: clamp(16px, 1.3vw, 18px); line-height: 1.8;
    }

    .fm-guide { margin: 0; padding: 0; list-style: none; border-top: 1px solid rgba(255,255,255,.2); }
    .fm-guide li {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,.12);
        color: rgba(255,255,255,.82); font-size: 14px; line-height: 1.5;
    }
    .fm-guide svg { width: 18px; height: 18px; color: var(--cyan); }
    .fm-guide-title { margin: 0 0 4px; color: #bcd8ff; font-size: 13px; font-weight: 700; }

    /* Notice */
    .fm-notice {
        display: flex; align-items: flex-start; gap: 14px;
        margin-bottom: 28px; padding: 18px 20px;
        border: 1px solid #cfe0fb; border-left: 4px solid var(--royal);
        border-radius: 14px; background: var(--ice);
    }
    .fm-notice svg { width: 22px; height: 22px; margin-top: 2px; color: var(--royal); }
    .fm-notice strong { display: block; color: var(--navy); font-size: 15px; }
    .fm-notice p { margin: 4px 0 0; color: #40536a; font-size: 14px; line-height: 1.7; }

    /* =====================================================
       FORM — SECTION SPLIT
    ===================================================== */
    .fm-section {
        display: grid; gap: 28px 56px;
        grid-template-columns: minmax(0, .5fr) minmax(0, 1.5fr);
        padding: 36px 0;
        border-top: 2px solid var(--navy);
    }
    .fm-section:first-of-type { margin-top: 0; }

    .fm-section-head { align-self: start; position: sticky; top: 90px; }

    .fm-num {
        display: block; margin-bottom: 10px; color: var(--royal);
        font-family: var(--f-display); font-size: 15px; font-weight: 800;
    }

    .fm-section-head h2 {
        margin: 0; color: var(--navy);
        font-family: var(--f-display); font-size: clamp(24px, 2.6vw, 34px);
        font-weight: 800; letter-spacing: -.04em; line-height: 1.12;
    }
    .fm-section-head p { margin: 10px 0 0; color: var(--slate); font-size: 14px; line-height: 1.7; }

    .fm-grid { display: grid; gap: 22px 24px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .fm-field { min-width: 0; }

    .fm-field label {
        display: flex; align-items: center; flex-wrap: wrap; gap: 6px;
        margin-bottom: 9px; color: #40536a; font-size: 14px; font-weight: 700;
    }
    .fm-req { color: var(--red); font-size: 16px; line-height: 1; }
    .fm-opt { color: #7a8aa0; font-size: 12px; font-weight: 600; }

    .fm .fm-control {
        display: block; width: 100%; min-width: 0; height: 56px;
        padding: 0 16px; border: 1.5px solid var(--line); border-radius: 14px;
        outline: none; background: #fff; color: var(--ink); font-size: 16px;
        transition: border-color .25s, box-shadow .25s;
    }
    .fm .fm-control::placeholder { color: #8998ab; }
    .fm .fm-control:hover { border-color: #a6bddb; }
    .fm .fm-control:focus { border-color: var(--royal); box-shadow: 0 0 0 4px rgba(36,87,214,.12); }
    .fm .fm-control[aria-invalid="true"] { border-color: #e48a93; background: var(--red-soft); }

    .fm select.fm-control { padding-right: 40px; cursor: pointer; }

    .fm-error {
        display: flex; align-items: flex-start; gap: 7px;
        margin-top: 8px; color: var(--red); font-size: 13px; font-weight: 600; line-height: 1.5;
    }
    .fm-error svg { width: 16px; height: 16px; margin-top: 2px; }

    /* =====================================================
       AKSI
    ===================================================== */
    .fm-footer {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px;
        padding: 26px 0 0; border-top: 2px solid var(--navy);
    }
    .fm-note { display: flex; align-items: flex-start; gap: 10px; max-width: 60ch; color: var(--slate); font-size: 13px; line-height: 1.7; }
    .fm-note svg { width: 18px; height: 18px; margin-top: 2px; }
    .fm-note b { color: var(--red); }

    .fm-actions { display: flex; align-items: center; gap: 12px; }

    .fm .fm-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        min-height: 54px; padding: 12px 26px; border: 1.5px solid transparent; border-radius: 999px;
        font-size: 15px; font-weight: 700; text-decoration: none; cursor: pointer;
        transition: transform .25s var(--ease), box-shadow .25s, background .25s, color .25s, border-color .25s;
    }
    .fm .fm-btn svg { width: 18px; height: 18px; transition: transform .25s var(--ease); }
    .fm .fm-btn:active { transform: scale(.98); }

    .fm .fm-btn-primary { background: var(--royal); color: #fff; }
    .fm .fm-btn-primary:hover { background: var(--navy); transform: translateY(-2px); box-shadow: 0 14px 30px rgba(36,87,214,.28); }
    .fm .fm-btn-primary:disabled { background: #7f9bd8; box-shadow: none; transform: none; cursor: wait; }

    .fm .fm-btn-ghost { background: transparent; border-color: var(--line); color: #40536a; }
    .fm .fm-btn-ghost:hover { border-color: var(--royal); color: var(--royal); }
    .fm .fm-btn-ghost:hover svg { transform: translateX(-3px); }

    @keyframes fm-spin { to { transform: rotate(360deg); } }
    .fm .is-spin { animation: fm-spin 1s linear infinite; }

    /* =====================================================
       RESPONSIVE
    ===================================================== */
    @media (max-width: 1000px) {
        .fm-hero-grid { grid-template-columns: 1fr; }
        .fm-section { grid-template-columns: 1fr; gap: 22px; padding: 30px 0; }
        .fm-section-head { position: static; }
    }

    @media (max-width: 640px) {
        .fm-hero { border-radius: 18px; }
        .fm-grid { grid-template-columns: 1fr; gap: 20px; }
        .fm .fm-control { height: 54px; }
        .fm-footer { flex-direction: column; align-items: stretch; }
        .fm-actions { flex-direction: column-reverse; align-items: stretch; }
        .fm .fm-btn { width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .fm *, .fm *::before, .fm *::after {
            animation: none !important;
            transition-duration: .01ms !important;
        }
        .fm.is-js [data-reveal] { opacity: 1; transform: none; }
    }
</style>

@php
    $isEditing = $participant->exists;
@endphp

<div class="fm" id="fm">

    <div class="fm-progress" id="fmProgress" aria-hidden="true"></div>

    {{-- =================================================
         HERO
    ================================================== --}}
    <header class="fm-hero" data-reveal>
        <div class="fm-orbit" aria-hidden="true"></div>

        <span class="fm-eyebrow">Data Master SIRAPIK</span>

        <div class="fm-hero-grid">

            <div>
                <h1>{{ $isEditing ? 'Edit Data Peserta' : 'Tambah Peserta Baru' }}</h1>

                <p>
                    {{ $isEditing
                        ? 'Perbarui informasi identitas, pekerjaan, dan data kepegawaian peserta.'
                        : 'Tambahkan identitas dan informasi kepegawaian peserta ke dalam sistem SIRAPIK.'
                    }}
                </p>
            </div>

            <div>
                <p class="fm-guide-title">Panduan pengisian</p>

                <ul class="fm-guide">
                    @foreach ([
                        'Gunakan nama lengkap sesuai identitas resmi.',
                        'Pastikan NIP tidak salah ketik.',
                        'Gunakan alamat email yang masih aktif.',
                        'Pilih Kabupaten/Kota/Mitra/Mandiri sesuai asal atau wilayah kerja peserta.',
                    ] as $tip)
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.2" stroke-linecap="round"
                                 stroke-linejoin="round" aria-hidden="true">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>
                            {{ $tip }}
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </header>

    {{-- =================================================
         INFORMASI UNTUK PESERTA BARU
    ================================================== --}}
    @if (!$isEditing)
        <div class="fm-notice" role="status" data-reveal>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.8" stroke-linecap="round"
                 stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>

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

    {{-- =================================================
         FORM
    ================================================== --}}
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

        {{-- 01 IDENTITAS --}}
        <section class="fm-section" data-reveal aria-labelledby="fmIdentitas">
            <div class="fm-section-head">
                <span class="fm-num">01</span>
                <h2 id="fmIdentitas">Identitas Pribadi</h2>
                <p>Data diri peserta sesuai dokumen resmi.</p>
            </div>

            <div class="fm-grid">

                <div class="fm-field">
                    <label for="name">Nama Lengkap <span class="fm-req" aria-hidden="true">*</span></label>
                    <input
                        class="fm-control" id="name" name="name" type="text"
                        value="{{ old('name', $participant->name) }}"
                        maxlength="255" placeholder="Masukkan nama lengkap"
                        autocomplete="name" required
                        @error('name') aria-invalid="true" @enderror
                    >
                    @error('name')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fm-field">
                    <label for="nik">NIK <span class="fm-opt">Opsional</span></label>
                    <input
                        class="fm-control" id="nik" name="nik" type="text"
                        value="{{ old('nik', $participant->nik) }}"
                        maxlength="30" inputmode="numeric" placeholder="Masukkan NIK"
                        @error('nik') aria-invalid="true" @enderror
                    >
                    @error('nik')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fm-field">
                    <label for="nip">NIP <span class="fm-req" aria-hidden="true">*</span></label>
                    <input
                        class="fm-control" id="nip" name="nip" type="text"
                        value="{{ old('nip', $participant->nip) }}"
                        maxlength="30" placeholder="Masukkan NIP" required
                        @error('nip') aria-invalid="true" @enderror
                    >
                    @error('nip')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fm-field">
                    <label for="gender">Jenis Kelamin <span class="fm-opt">Opsional</span></label>
                    <select
                        class="fm-control" id="gender" name="gender"
                        @error('gender') aria-invalid="true" @enderror
                    >
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" @selected(old('gender', $participant->gender) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('gender', $participant->gender) === 'P')>Perempuan</option>
                    </select>
                    @error('gender')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </section>

        {{-- 02 KEPEGAWAIAN --}}
        <section class="fm-section" data-reveal aria-labelledby="fmKepegawaian">
            <div class="fm-section-head">
                <span class="fm-num">02</span>
                <h2 id="fmKepegawaian">Informasi Kepegawaian</h2>
                <p>Pangkat, profesi, dan asal instansi peserta.</p>
            </div>

            <div class="fm-grid">

                <div class="fm-field">
                    <label for="rank">Pangkat/Golongan <span class="fm-opt">Opsional</span></label>
                    <input
                        class="fm-control" id="rank" name="rank" type="text"
                        value="{{ old('rank', $participant->rank) }}"
                        maxlength="100" placeholder="Contoh: Penata / III-c"
                        @error('rank') aria-invalid="true" @enderror
                    >
                    @error('rank')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fm-field">
                    <label for="profession">Profesi Tenaga Kesehatan <span class="fm-opt">Opsional</span></label>
                    <input
                        class="fm-control" id="profession" name="profession" type="text"
                        value="{{ old('profession', $participant->profession) }}"
                        maxlength="255" placeholder="Contoh: Perawat"
                        @error('profession') aria-invalid="true" @enderror
                    >
                    @error('profession')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fm-field">
                    <label for="institution">Instansi/Unit Kerja <span class="fm-opt">Opsional</span></label>
                    <input
                        class="fm-control" id="institution" name="institution" type="text"
                        value="{{ old('institution', $participant->institution) }}"
                        maxlength="255" placeholder="Masukkan nama instansi"
                        @error('institution') aria-invalid="true" @enderror
                    >
                    @error('institution')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fm-field">
                    <label for="district">Kabupaten/Kota/Mitra/Mandiri <span class="fm-req" aria-hidden="true">*</span></label>
                    <select
                        class="fm-control" id="district" name="district" required
                        @error('district') aria-invalid="true" @enderror
                    >
                        <option value="">-- Pilih wilayah --</option>

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
                            <small style="display:block; margin-top:8px; color:#64748b; font-size:12px;">
    Wilayah ini merupakan data asal/wilayah kerja peserta.
    Perubahan akan memperbarui data peserta dan menjadi acuan rekapitulasi laporan serta dashboard.
</small>
                        @endforeach
                    </select>
                    @error('district')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </section>

        {{-- 03 KONTAK --}}
        <section class="fm-section" data-reveal aria-labelledby="fmKontak">
            <div class="fm-section-head">
                <span class="fm-num">03</span>
                <h2 id="fmKontak">Informasi Kontak</h2>
                <p>Email dan nomor telepon yang dapat dihubungi.</p>
            </div>

            <div class="fm-grid">

                <div class="fm-field">
                    <label for="email">Alamat Email <span class="fm-req" aria-hidden="true">*</span></label>
                    <input
                        class="fm-control" id="email" name="email" type="email"
                        value="{{ old('email', $participant->email) }}"
                        maxlength="255" autocomplete="email"
                        placeholder="nama@email.com" required
                        @error('email') aria-invalid="true" @enderror
                    >
                    @error('email')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fm-field">
                    <label for="phone">Nomor Telepon <span class="fm-opt">Opsional</span></label>
                    <input
                        class="fm-control" id="phone" name="phone" type="text"
                        value="{{ old('phone', $participant->phone) }}"
                        maxlength="30" inputmode="tel" autocomplete="tel"
                        placeholder="Contoh: 081234567890"
                        @error('phone') aria-invalid="true" @enderror
                    >
                    @error('phone')
                        <small class="fm-error">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </section>

        {{-- AKSI --}}
        <div class="fm-footer" data-reveal>

            <div class="fm-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4M12 8h.01"/>
                </svg>

                <span>
                    Kolom bertanda <b>*</b> wajib diisi. Periksa kembali data
                    sebelum menyimpan.
                </span>
            </div>

            <div class="fm-actions">
                <a class="fm-btn fm-btn-ghost" href="{{ route('participants.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Batal
                </a>

                <button class="fm-btn fm-btn-primary" type="submit" id="saveParticipantButton">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                        <path d="M17 21v-8H7v8M7 3v5h8"/>
                    </svg>
                    <span>{{ $isEditing ? 'Simpan Perubahan' : 'Simpan Peserta' }}</span>
                </button>
            </div>

        </div>

    </form>

</div>

<script>
(function () {
    function initializeParticipantForm() {
        const page = document.getElementById('fm');

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
        const bar = document.getElementById('fmProgress');
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

        /* Kirim form: cegah klik ganda */
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
                <svg class="is-spin" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M21 12a9 9 0 1 1-6.2-8.55"/>
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
        document.addEventListener('DOMContentLoaded', initializeParticipantForm);
    } else {
        initializeParticipantForm();
    }
})();
</script>

@endsection