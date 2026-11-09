@extends('layouts.app')

@section('title', 'Profil Peserta')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       PROFIL PESERTA — SIRAPIK (Premium Blue Editorial)
    ===================================================== */
    .pf {
        --navy: #081b33; --midnight: #0b2545; --royal: #2457d6;
        --electric: #3478f6; --soft: #eaf2ff; --ice: #f3f7fc;
        --slate: #64748b; --ink: #14243a; --line: #e1e9f2; --cyan: #38c6e8;
        --f-display: 'Sora', system-ui, sans-serif;
        --f-body: 'Plus Jakarta Sans', system-ui, sans-serif;
        --ease: cubic-bezier(.2, .7, .3, 1);

        width: 100%;
        color: var(--ink);
        font-family: var(--f-body);
        font-size: 16px;
        line-height: 1.7;
    }

    .pf *, .pf *::before, .pf *::after { box-sizing: border-box; }
    .pf a { text-decoration: none; }

    .pf :focus-visible {
        outline: 3px solid rgba(52, 120, 246, .55);
        outline-offset: 3px;
        border-radius: 8px;
    }

    /* Progress scroll */
    .pf-progress {
        position: fixed; top: 0; left: 0; z-index: 60;
        width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--electric), var(--cyan));
        transform: scaleX(0); transform-origin: 0 50%;
        pointer-events: none;
    }

    /* Reveal */
    .pf.is-js [data-reveal] {
        opacity: 0; transform: translateY(20px);
        transition: opacity .7s var(--ease), transform .7s var(--ease);
        transition-delay: var(--d, 0ms);
    }
    .pf.is-js [data-reveal].is-in { opacity: 1; transform: none; }

    /* =====================================================
       HERO
    ===================================================== */
    .pf-hero {
        position: relative; isolation: isolate; overflow: hidden;
        margin-bottom: 28px;
        padding: clamp(28px, 5vw, 56px);
        color: #fff;
        background: linear-gradient(160deg, var(--navy) 0%, var(--midnight) 100%);
        border-radius: 24px;
    }

    .pf-hero::before {
        content: ""; position: absolute; inset: 0; z-index: -2; opacity: .1;
        background-image:
            linear-gradient(rgba(255,255,255,.6) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.6) 1px, transparent 1px);
        background-size: 56px 56px;
        -webkit-mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
        mask-image: linear-gradient(160deg, #000 5%, transparent 75%);
    }

    .pf-orbit {
        position: absolute; z-index: -1; right: -160px; bottom: -200px;
        width: 480px; height: 480px; border-radius: 50%;
        border: 1px solid rgba(130,183,255,.25);
        box-shadow: 0 0 0 54px rgba(130,183,255,.04), 0 0 0 108px rgba(130,183,255,.025);
        animation: pf-float 14s ease-in-out infinite;
    }
    @keyframes pf-float { 50% { transform: translateY(-18px); } }

    .pf-eyebrow {
        display: inline-flex; align-items: center; gap: 12px;
        margin-bottom: 22px; color: #bcd8ff;
        font-size: 14px; font-weight: 700;
    }
    .pf-eyebrow::before { content: ""; width: 32px; height: 2px; background: var(--cyan); }

    .pf-hero-grid {
        display: grid; align-items: end; gap: 36px;
        grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
    }

    .pf-identity { display: flex; align-items: flex-start; gap: 22px; min-width: 0; }

    .pf-avatar {
        flex: 0 0 auto; display: grid; place-items: center;
        width: 76px; height: 76px; border-radius: 22px;
        background: #fff; color: var(--royal);
        font-family: var(--f-display); font-size: 28px; font-weight: 800;
        letter-spacing: -.03em;
    }

    .pf-name {
        margin: 0;
        font-family: var(--f-display);
        font-size: clamp(32px, 5vw, 64px);
        font-weight: 800; letter-spacing: -.05em; line-height: 1.02;
        overflow-wrap: anywhere;
    }

    .pf-nip { margin: 14px 0 0; color: rgba(255,255,255,.78); font-size: 16px; }
    .pf-nip strong { color: #82b7ff; font-weight: 700; }

    .pf-tag {
        display: inline-flex; align-items: center; gap: 8px;
        margin-top: 18px; padding: 7px 14px;
        border: 1px solid rgba(255,255,255,.2); border-radius: 999px;
        background: rgba(255,255,255,.07); color: #bcd8ff;
        font-size: 13px; font-weight: 600;
    }

    .pf svg { display: block; flex: 0 0 auto; }

    .pf-stats { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0; }

    .pf-stat { padding: 4px 22px; border-left: 1px solid rgba(255,255,255,.2); }
    .pf-stat-num {
        font-family: var(--f-display);
        font-size: clamp(40px, 5vw, 68px);
        font-weight: 800; letter-spacing: -.05em; line-height: 1;
        font-variant-numeric: tabular-nums; color: #fff;
    }
    .pf-stat-label { margin-top: 10px; color: rgba(255,255,255,.78); font-size: 14px; font-weight: 600; line-height: 1.5; }
    .pf-stat-note { margin-top: 4px; color: rgba(255,255,255,.55); font-size: 12px; line-height: 1.5; }

    /* Tombol */
    .pf-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        min-height: 48px; margin-top: 26px; padding: 12px 24px;
        border: 0; border-radius: 999px; background: #fff; color: var(--royal);
        font: 700 15px var(--f-body); cursor: pointer;
        transition: transform .25s var(--ease), box-shadow .25s, background .25s, color .25s;
    }
    .pf-btn:hover { color: #fff; background: var(--royal); transform: translateY(-2px); box-shadow: 0 14px 30px rgba(36,87,214,.35); }
    .pf-btn:active { transform: scale(.98); }
    .pf-btn svg { width: 18px; height: 18px; }

    /* =====================================================
       BODY: IDENTITAS + RIWAYAT
    ===================================================== */
    .pf-body {
        display: grid; align-items: start; gap: 28px;
        grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
    }

    .pf-sticky { position: sticky; top: 90px; }

    .pf-section-title { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 8px; }
    .pf-section-title h2 {
        margin: 0; color: var(--navy);
        font-family: var(--f-display); font-size: clamp(24px, 2.6vw, 32px);
        font-weight: 800; letter-spacing: -.04em; line-height: 1.15;
    }
    .pf-lead { margin: 0 0 22px; color: var(--slate); font-size: 15px; }

    .pf-count {
        padding: 5px 14px; border-radius: 999px;
        background: var(--soft); color: var(--royal);
        font-size: 13px; font-weight: 700; white-space: nowrap;
    }

    /* Identitas (daftar editorial, bukan card) */
    .pf-dl { margin: 0; border-top: 2px solid var(--navy); }
    .pf-dl > div { padding: 14px 0; border-bottom: 1px solid var(--line); }
    .pf-dl dt { margin: 0 0 3px; color: var(--slate); font-size: 13px; font-weight: 600; }
    .pf-dl dd { margin: 0; color: var(--ink); font-size: 16px; font-weight: 600; overflow-wrap: anywhere; }

    /* Timeline */
    .pf-timeline { position: relative; margin: 0; padding: 0; list-style: none; }
    .pf-timeline::before {
        content: ""; position: absolute; top: 8px; bottom: 8px; left: 19px;
        width: 2px; background: var(--line);
    }

    .pf-item { position: relative; padding: 0 0 22px 60px; }
    .pf-item:last-child { padding-bottom: 0; }

    .pf-dot {
        position: absolute; top: 0; left: 0; display: grid; place-items: center;
        width: 40px; height: 40px; border-radius: 50%;
        border: 2px solid var(--royal); background: #fff; color: var(--royal);
        font-family: var(--f-display); font-size: 14px; font-weight: 800;
    }

    .pf-card {
        padding: 22px; border: 1px solid var(--line); border-radius: 18px; background: #fff;
        transition: border-color .25s, box-shadow .25s, transform .25s var(--ease);
    }
    .pf-card:hover { border-color: #a6bddb; box-shadow: 0 14px 34px rgba(8,27,51,.08); transform: translateY(-2px); }

    .pf-card-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .pf-card h3 {
        margin: 0; color: var(--navy);
        font-family: var(--f-display); font-size: 18px; font-weight: 700;
        letter-spacing: -.02em; line-height: 1.35; overflow-wrap: anywhere;
    }

    .pf-status {
        flex: 0 0 auto; padding: 4px 12px; border-radius: 999px;
        background: var(--soft); color: var(--royal);
        font-size: 12px; font-weight: 700; white-space: nowrap;
    }

    .pf-meta {
        display: grid; gap: 14px 20px; margin: 18px 0 0; padding-top: 18px;
        border-top: 1px solid var(--line);
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    .pf-meta dt { margin: 0 0 2px; color: var(--slate); font-size: 12px; font-weight: 600; }
    .pf-meta dd { margin: 0; color: var(--ink); font-size: 14px; font-weight: 600; line-height: 1.5; overflow-wrap: anywhere; }
    .pf-meta .wide { grid-column: span 2; }

    /* Kosong */
    .pf-empty {
        padding: 44px 22px; border: 1.5px dashed #a6bddb; border-radius: 18px;
        background: var(--ice); color: var(--slate); text-align: center;
    }
    .pf-empty-icon {
        display: grid; place-items: center; width: 52px; height: 52px;
        margin: 0 auto 14px; border-radius: 50%; background: var(--soft); color: var(--royal);
    }
    .pf-empty strong { display: block; color: var(--navy); font-family: var(--f-display); font-size: 18px; }

    /* =====================================================
       RESPONSIVE
    ===================================================== */
    @media (max-width: 1000px) {
        .pf-hero-grid, .pf-body { grid-template-columns: 1fr; }
        .pf-sticky { position: static; }
        .pf-stat:first-child { padding-left: 0; border-left: 0; }
    }

    @media (max-width: 640px) {
        .pf-hero { border-radius: 18px; }
        .pf-identity { flex-direction: column; gap: 16px; }
        .pf-avatar { width: 60px; height: 60px; border-radius: 18px; font-size: 22px; }
        .pf-btn { width: 100%; }
        .pf-card { padding: 18px; }
        .pf-card-head { flex-direction: column-reverse; }
        .pf-meta { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .pf-meta .wide { grid-column: span 2; }
        .pf-item { padding-left: 52px; }
        .pf-dot { width: 34px; height: 34px; font-size: 13px; }
        .pf-timeline::before { left: 16px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .pf *, .pf *::before, .pf *::after {
            animation: none !important;
            transition-duration: .01ms !important;
            transition-delay: 0ms !important;
        }
        .pf.is-js [data-reveal] { opacity: 1; transform: none; }
    }
</style>

@php
    $isAdmin = auth()->user()->role === 'admin';

    $totalHistories = $participant->histories->count();

    $totalTrainingDays = $participant->histories->sum(
        fn ($history) => $history->training?->duration_days ?? 0
    );

    $initials = collect(
        preg_split('/\s+/', trim($participant->name ?? ''))
    )
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');

    $initials = $initials ?: 'P';
@endphp

<div class="pf" id="pf">

    <div class="pf-progress" id="pfProgress" aria-hidden="true"></div>

    {{-- =================================================
         HERO: IDENTITAS + STATISTIK
    ================================================== --}}
    <header class="pf-hero" data-reveal>
        <div class="pf-orbit" aria-hidden="true"></div>

        <span class="pf-eyebrow">{{ $isAdmin ? 'Data Master Peserta' : 'Profil Saya' }}</span>

        <div class="pf-hero-grid">

            <div class="pf-identity">
                <div class="pf-avatar" aria-hidden="true">{{ $initials }}</div>

                <div style="min-width: 0;">
                    <h1 class="pf-name">{{ $participant->name ?: '-' }}</h1>

                    <p class="pf-nip">NIP <strong>{{ $participant->nip ?: '-' }}</strong></p>

                    <span class="pf-tag">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Data Peserta
                    </span>

                    @if ($isAdmin)
                        <div>
                            <a class="pf-btn" href="{{ route('participants.edit', $participant) }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                </svg>
                                Edit Profil
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="pf-stats">
                <div class="pf-stat">
                    <div class="pf-stat-num" data-count="{{ $totalHistories }}">{{ $totalHistories }}</div>
                    <div class="pf-stat-label">Total Riwayat Pelatihan</div>
                    <div class="pf-stat-note">Keikutsertaan yang tercatat</div>
                </div>

                <div class="pf-stat">
                    <div class="pf-stat-num" data-count="{{ $totalTrainingDays }}">{{ $totalTrainingDays }}</div>
                    <div class="pf-stat-label">Total Hari Pelatihan</div>
                    <div class="pf-stat-note">Akumulasi durasi yang tersedia</div>
                </div>
            </div>

        </div>
    </header>

    <div class="pf-body">

        {{-- =================================================
             INFORMASI IDENTITAS
        ================================================== --}}
        <section class="pf-sticky" data-reveal aria-labelledby="pfIdentitas">
            <div class="pf-section-title">
                <h2 id="pfIdentitas">Identitas Peserta</h2>
            </div>
            <p class="pf-lead">Data pribadi dan informasi asal instansi peserta.</p>

            <dl class="pf-dl">
                <div>
                    <dt>Nama Lengkap</dt>
                    <dd>{{ $participant->name ?: '-' }}</dd>
                </div>
                <div>
                    <dt>NIK</dt>
                    <dd>{{ $participant->nik ?: '-' }}</dd>
                </div>
                <div>
                    <dt>NIP</dt>
                    <dd>{{ $participant->nip ?: '-' }}</dd>
                </div>
                <div>
                    <dt>Jenis Kelamin</dt>
                    <dd>
                        @if ($participant->gender === 'L')
                            Laki-laki
                        @elseif ($participant->gender === 'P')
                            Perempuan
                        @else
                            {{ $participant->gender ?: '-' }}
                        @endif
                    </dd>
                </div>
                <div>
                    <dt>Pangkat/Golongan</dt>
                    <dd>{{ $participant->rank ?: '-' }}</dd>
                </div>
                <div>
                    <dt>Profesi Nakes</dt>
                    <dd>{{ $participant->profession ?: '-' }}</dd>
                </div>
                <div>
                    <dt>Instansi/Unit Kerja</dt>
                    <dd>{{ $participant->institution ?: '-' }}</dd>
                </div>
                <div>
                    <dt>Kabupaten/Kota/Mitra/Mandiri</dt>
                    <dd>{{ $participant->district ?: '-' }}</dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd>{{ $participant->email ?: '-' }}</dd>
                </div>
                <div>
                    <dt>Nomor Telepon</dt>
                    <dd>{{ $participant->phone ?: '-' }}</dd>
                </div>
            </dl>
        </section>

        {{-- =================================================
             RIWAYAT PELATIHAN (TIMELINE)
        ================================================== --}}
        <section aria-labelledby="pfRiwayat">
            <div class="pf-section-title" data-reveal>
                <h2 id="pfRiwayat">Riwayat Pelatihan</h2>
                <span class="pf-count">{{ $totalHistories }} Data</span>
            </div>
            <p class="pf-lead" data-reveal>
                Daftar pelatihan yang pernah diikuti
                {{ $isAdmin ? 'peserta ini' : 'oleh Anda' }}.
            </p>

            @if ($participant->histories->isNotEmpty())

                <ol class="pf-timeline">

                    @foreach ($participant->histories as $history)

                        @php
                            $training = $history->training;
                        @endphp

                        <li class="pf-item" data-reveal>

                            <span class="pf-dot" aria-hidden="true">{{ $loop->iteration }}</span>

                            <article class="pf-card">

                                <div class="pf-card-head">
                                    <h3>{{ $training?->name ?? 'Pelatihan tidak tersedia' }}</h3>
                                    <span class="pf-status">{{ ucfirst($history->status ?? '-') }}</span>
                                </div>

                                <dl class="pf-meta">
                                    <div>
                                        <dt>Angkatan</dt>
                                        <dd>{{ $training?->batch_number ?: '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt>Sumber Dana</dt>
                                        <dd>{{ $training?->funding_source ?: '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt>Kategori</dt>
                                        <dd>{{ $training?->category?->name ?: '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt>JPL</dt>
                                        <dd>{{ $training?->jpl ?? '-' }}</dd>
                                    </div>
                                    <div class="wide">
                                        <dt>Penyelenggara</dt>
                                        <dd>{{ $training?->organizer ?: '-' }}</dd>
                                    </div>
                                    <div class="wide">
                                        <dt>Tanggal Pelatihan</dt>
                                        <dd>
                                            @if ($training?->start_date)
                                                {{ $training->start_date->format('d/m/Y') }}
                                                @if ($training->end_date)
                                                    – {{ $training->end_date->format('d/m/Y') }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt>Durasi</dt>
                                        <dd>{{ $training?->duration_days ?? 0 }} hari</dd>
                                    </div>
                                </dl>

                            </article>

                        </li>

                    @endforeach

                </ol>

            @else

                <div class="pf-empty" data-reveal>
                    <div class="pf-empty-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 6h13"/>
                            <path d="M8 12h13"/>
                            <path d="M8 18h13"/>
                            <path d="M3 6h.01"/>
                            <path d="M3 12h.01"/>
                            <path d="M3 18h.01"/>
                        </svg>
                    </div>
                    <strong>Belum Ada Riwayat Pelatihan</strong>
                    Belum terdapat data keikutsertaan pelatihan untuk peserta ini.
                </div>

            @endif
        </section>

    </div>

</div>

<script>
    (() => {
        const root = document.getElementById('pf');
        if (!root) return;

        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* Scroll reveal bertahap */
        if ('IntersectionObserver' in window && !reduce) {
            root.classList.add('is-js');

            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-in');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: .12, rootMargin: '0px 0px -40px 0px' });

            root.querySelectorAll('[data-reveal]').forEach((el, i) => {
                el.style.setProperty('--d', Math.min(i % 4, 3) * 80 + 'ms');
                io.observe(el);
            });
        }

        /* Counter statistik */
        if (!reduce) {
            root.querySelectorAll('[data-count]').forEach((el) => {
                const target = parseInt(el.dataset.count, 10) || 0;
                if (target === 0) return;

                const start = performance.now();
                const duration = 1000;

                const tick = (now) => {
                    const p = Math.min((now - start) / duration, 1);
                    el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
                    if (p < 1) requestAnimationFrame(tick);
                };

                el.textContent = '0';
                requestAnimationFrame(tick);
            });
        }

        /* Progress bar scroll */
        const bar = document.getElementById('pfProgress');
        let ticking = false;

        const update = () => {
            const max = document.documentElement.scrollHeight - window.innerHeight;
            const ratio = max > 0 ? window.scrollY / max : 0;
            bar.style.transform = 'scaleX(' + Math.min(Math.max(ratio, 0), 1) + ')';
            ticking = false;
        };

        window.addEventListener('scroll', () => {
            if (!ticking) {
                ticking = true;
                requestAnimationFrame(update);
            }
        }, { passive: true });

        update();
    })();
</script>

@endsection