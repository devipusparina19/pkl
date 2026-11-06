@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       PREMIUM BLUE EDITORIAL — DASHBOARD SUPER ADMIN
       Semua selector di-scope ke .sa-dashboard
       ===================================================== */
    .sa-dashboard {
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
        --ok: #0F6B4A;
        --ok-bg: #E8F6EF;
        --off: #A3202F;
        --off-bg: #FFF1F2;
        --violet: #6A4FB3;
        --violet-bg: #F1ECFB;
        --font-display: "Sora", "Segoe UI", Arial, sans-serif;
        --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
        --ease: cubic-bezier(.2, .7, .2, 1);

        width: 100%; padding-bottom: 32px; color: var(--text);
        font-family: var(--font-body); font-size: 15px; line-height: 1.65;
    }
    .sa-dashboard *, .sa-dashboard *::before, .sa-dashboard *::after { box-sizing: border-box; }
    .sa-dashboard svg.i {
        width: 1em; height: 1em; flex: none;
        fill: none; stroke: currentColor;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
    .sa-dashboard :focus-visible { outline: 3px solid var(--cyan); outline-offset: 3px; border-radius: 6px; }

    /* ---------- Buttons ---------- */
    .sa-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        min-height: 54px; padding: 12px 24px; border: 1.5px solid transparent; border-radius: 14px;
        font-family: var(--font-display); font-size: 15px; font-weight: 600; text-decoration: none; white-space: nowrap;
        transition: transform .2s var(--ease), box-shadow .2s, background .2s, color .2s, border-color .2s;
    }
    .sa-btn .i { font-size: 18px; }
    .sa-btn:active { transform: scale(.97); }
    .sa-btn-primary { color: #fff; background: linear-gradient(110deg, var(--royal), var(--electric)); box-shadow: 0 12px 26px rgba(36,87,214,.35); }
    .sa-btn-primary:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 16px 32px rgba(36,87,214,.45); }
    .sa-btn-light { color: var(--navy); background: #fff; }
    .sa-btn-light:hover { color: var(--navy); transform: translateY(-2px); box-shadow: 0 14px 28px rgba(0,0,0,.25); }
    .sa-btn-light:hover .arrow { transform: translateX(4px); }
    .sa-btn .arrow { transition: transform .2s var(--ease); }

    /* ---------- Hero ---------- */
    .sa-hero {
        position: relative; isolation: isolate; overflow: hidden;
        display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, .7fr);
        align-items: end; gap: clamp(24px, 4vw, 64px);
        padding: clamp(32px, 5vw, 64px); border-radius: 26px; color: #fff;
        background: linear-gradient(120deg, var(--navy) 0%, var(--midnight) 60%, #173d86 100%);
        animation: sa-rise .8s var(--ease) both;
    }
    .sa-hero::before {
        content: ""; position: absolute; z-index: -1; right: -90px; top: -110px;
        width: 400px; height: 400px; border-radius: 50%;
        background: var(--royal); opacity: .45; filter: blur(60px);
        animation: sa-drift 24s ease-in-out infinite alternate;
    }
    .sa-hero::after {
        content: ""; position: absolute; z-index: -1; left: 28%; bottom: -130px;
        width: 260px; height: 260px; border-radius: 50%; background: var(--cyan); opacity: .14; filter: blur(60px);
    }
    .sa-hero h1 {
        margin: 0 0 16px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.4rem, 5.8vw, 4.6rem); line-height: 1.04; letter-spacing: -.035em;
    }
    .sa-hero h1 span { display: block; color: var(--cyan); }
    .sa-hero p { max-width: 580px; margin: 0; font-size: clamp(1rem, 1.5vw, 1.0625rem); line-height: 1.85; color: rgba(255,255,255,.82); }
    .sa-hero-cta { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; justify-self: end; }
    .sa-hero-cta small { font-size: 13px; color: rgba(255,255,255,.7); }

    /* ---------- Sections ---------- */
    .sa-section { padding-top: clamp(34px, 4.8vw, 56px); }
    .sa-section-head { margin-bottom: 20px; }
    .sa-section-head h2 {
        margin: 0 0 6px; font-family: var(--font-display); font-weight: 700;
        font-size: clamp(1.5rem, 3vw, 2.1rem); letter-spacing: -.025em; color: var(--navy);
    }
    .sa-section-head p { margin: 0; max-width: 640px; font-size: 14.5px; color: var(--slate); }

    /* ---------- Statistik: bento 2 grup ---------- */
    .sa-bento { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr); gap: 18px; }
    .sa-block {
        min-width: 0; padding: clamp(20px, 3vw, 30px);
        border: 1px solid var(--border); border-radius: 24px; background: #fff;
        box-shadow: 0 18px 44px rgba(8,27,51,.07);
    }
    .sa-block h3 { margin: 0 0 18px; font-family: var(--font-display); font-size: 15px; font-weight: 600; color: var(--slate); }
    .sa-pair { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .sa-stat { position: relative; padding: 4px 0; }
    .sa-pair .sa-stat + .sa-stat { padding-left: clamp(18px, 3vw, 34px); border-left: 1px solid var(--border); }
    .sa-stat-icon {
        display: grid; place-items: center; width: 44px; height: 44px; margin-bottom: 16px;
        border-radius: 13px; font-size: 22px;
    }
    .sa-stat-label { font-size: 14px; font-weight: 600; color: var(--slate); }
    .sa-stat-number {
        display: block; margin: 4px 0 6px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.6rem, 5vw, 3.8rem); line-height: 1; letter-spacing: -.04em; color: var(--navy);
        font-variant-numeric: tabular-nums; overflow-wrap: anywhere;
    }
    .sa-stat-note { font-size: 13px; line-height: 1.6; color: var(--slate); }
    .sa-icon-blue { background: var(--soft); color: var(--royal); }
    .sa-icon-violet { background: var(--violet-bg); color: var(--violet); }
    .sa-icon-ok { background: var(--ok-bg); color: var(--ok); }
    .sa-icon-off { background: var(--off-bg); color: var(--off); }

    .sa-ratio { margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--border); }
    .sa-ratio-bar { display: flex; height: 10px; overflow: hidden; border-radius: 999px; background: var(--off-bg); }
    .sa-ratio-fill { height: 100%; background: linear-gradient(90deg, #14A37A, #38C6E8); transform-origin: left; animation: sa-grow 1s var(--ease) both; }
    .sa-ratio-meta { display: flex; justify-content: space-between; gap: 10px; margin-top: 9px; font-size: 13px; color: var(--slate); }
    .sa-ratio-meta strong { color: var(--navy); }

    /* ---------- Pengelolaan: split ---------- */
    .sa-split { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr); gap: 18px; align-items: stretch; }
    .sa-users {
        min-width: 0; display: flex; flex-direction: column;
        padding: clamp(22px, 3vw, 34px); border: 1px solid var(--border); border-radius: 24px; background: #fff;
    }
    .sa-card-top { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
    .sa-card-icon {
        display: grid; place-items: center; width: 52px; height: 52px; flex: none;
        border-radius: 15px; background: var(--soft); color: var(--royal); font-size: 26px;
    }
    .sa-card-top h3 { margin: 0; font-family: var(--font-display); font-size: clamp(1.2rem, 2.2vw, 1.45rem); font-weight: 700; letter-spacing: -.02em; color: var(--navy); }
    .sa-card-top small { display: block; font-size: 12.5px; color: var(--slate); }
    .sa-desc { margin: 0 0 14px; font-size: 15px; line-height: 1.85; color: #3B4F66; }
    .sa-detail { margin: 0 0 20px; padding-top: 14px; border-top: 1px solid var(--border); font-size: 14px; line-height: 1.85; color: var(--slate); }
    .sa-access-note {
        display: flex; align-items: flex-start; gap: 11px; margin-top: auto; padding: 14px 16px;
        border: 1px solid #D3E2FB; border-radius: 14px; background: var(--soft);
        font-size: 13.5px; line-height: 1.75; color: #2F4766;
    }
    .sa-access-note .i { margin-top: 3px; font-size: 18px; color: var(--royal); }

    .sa-master {
        position: relative; isolation: isolate; overflow: hidden; min-width: 0; display: flex; flex-direction: column;
        padding: clamp(22px, 3vw, 34px); border-radius: 24px; color: #fff; background: var(--navy);
    }
    .sa-master::before {
        content: ""; position: absolute; z-index: -1; right: -70px; bottom: -90px;
        width: 260px; height: 260px; border-radius: 50%; background: var(--royal); opacity: .5; filter: blur(50px);
    }
    .sa-master .sa-card-icon { background: rgba(56,198,232,.15); color: var(--cyan); }
    .sa-master .sa-card-top h3 { color: #fff; }
    .sa-master .sa-card-top small { color: rgba(255,255,255,.7); }
    .sa-master .sa-desc { color: rgba(255,255,255,.86); }
    .sa-master .sa-detail { border-top-color: rgba(255,255,255,.18); color: rgba(255,255,255,.72); }
    .sa-master-actions { margin-top: auto; padding-top: 22px; }

    @keyframes sa-rise { from { opacity: 0; transform: translate3d(0, 18px, 0); } to { opacity: 1; transform: none; } }
    @keyframes sa-drift { to { transform: translate3d(-40px, 40px, 0); } }
    @keyframes sa-grow { from { transform: scaleX(0); } to { transform: scaleX(1); } }

    /* ---------- Responsive ---------- */
    @media (max-width: 1000px) {
        .sa-hero { grid-template-columns: 1fr; align-items: start; }
        .sa-hero-cta { justify-self: start; }
        .sa-bento, .sa-split { grid-template-columns: 1fr; }
    }
    @media (max-width: 560px) {
        .sa-hero-cta, .sa-hero-cta .sa-btn, .sa-master-actions .sa-btn { width: 100%; }
        .sa-pair { grid-template-columns: 1fr; }
        .sa-pair .sa-stat + .sa-stat { padding-left: 0; padding-top: 20px; margin-top: 20px; border-left: 0; border-top: 1px solid var(--border); }
    }
    @media (prefers-reduced-motion: reduce) {
        .sa-dashboard *, .sa-dashboard *::before, .sa-dashboard *::after { animation: none !important; transition-duration: .01ms !important; scroll-behavior: auto !important; }
    }
</style>

@php
    $totalStatus = max(1, (int) $totalAktif + (int) $totalNonaktif);
    $activePercent = round(((int) $totalAktif / $totalStatus) * 100);
@endphp

<div class="sa-dashboard">

    {{-- HERO --}}
    <header class="sa-hero">
        <div>
            <h1>Dashboard <span>Super Admin.</span></h1>

            <p>
                Pusat kendali akun pengguna dan konfigurasi data master.
                Kelola akses serta referensi sistem SIRAPIK dalam satu
                ruang pengelolaan yang terintegrasi.
            </p>
        </div>

        <div class="sa-hero-cta">
            <a href="{{ route('super-admin.users.create') }}" class="sa-btn sa-btn-primary">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                Tambah Akun
            </a>
            <small>Buat akun Admin atau Pimpinan baru.</small>
        </div>
    </header>

    {{-- RINGKASAN AKUN --}}
    <section class="sa-section" aria-labelledby="sa-account-title">
        <div class="sa-section-head">
            <h2 id="sa-account-title">Ringkasan akun sistem</h2>
            <p>Gambaran jumlah akun berdasarkan peran dan status pengguna.</p>
        </div>

        <div class="sa-bento">

            {{-- PERAN --}}
            <div class="sa-block">
                <h3>Berdasarkan peran</h3>

                <div class="sa-pair">
                    <div class="sa-stat">
                        <div class="sa-stat-icon sa-icon-blue" aria-hidden="true">
                            <svg class="i" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div class="sa-stat-label">Total Akun Admin</div>
                        <strong class="sa-stat-number sa-counter" data-target="{{ (int) $totalAdmin }}">{{ number_format($totalAdmin, 0, ',', '.') }}</strong>
                        <div class="sa-stat-note">Pengelola data operasional.</div>
                    </div>

                    <div class="sa-stat">
                        <div class="sa-stat-icon sa-icon-violet" aria-hidden="true">
                            <svg class="i" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/><path d="m8 13.5 4 2.5 4-2.5"/></svg>
                        </div>
                        <div class="sa-stat-label">Total Akun Pimpinan</div>
                        <strong class="sa-stat-number sa-counter" data-target="{{ (int) $totalPimpinan }}">{{ number_format($totalPimpinan, 0, ',', '.') }}</strong>
                        <div class="sa-stat-note">Akun untuk pemantauan sistem.</div>
                    </div>
                </div>
            </div>

            {{-- STATUS --}}
            <div class="sa-block">
                <h3>Berdasarkan status</h3>

                <div class="sa-pair">
                    <div class="sa-stat">
                        <div class="sa-stat-icon sa-icon-ok" aria-hidden="true">
                            <svg class="i" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                        <div class="sa-stat-label">Akun Aktif</div>
                        <strong class="sa-stat-number sa-counter" data-target="{{ (int) $totalAktif }}">{{ number_format($totalAktif, 0, ',', '.') }}</strong>
                        <div class="sa-stat-note">Akun Admin dan Pimpinan aktif.</div>
                    </div>

                    <div class="sa-stat">
                        <div class="sa-stat-icon sa-icon-off" aria-hidden="true">
                            <svg class="i" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                        </div>
                        <div class="sa-stat-label">Akun Nonaktif</div>
                        <strong class="sa-stat-number sa-counter" data-target="{{ (int) $totalNonaktif }}">{{ number_format($totalNonaktif, 0, ',', '.') }}</strong>
                        <div class="sa-stat-note">Akun Admin dan Pimpinan nonaktif.</div>
                    </div>
                </div>

                <div class="sa-ratio">
                    <div class="sa-ratio-bar" role="img" aria-label="{{ $activePercent }} persen akun aktif">
                        <div class="sa-ratio-fill" style="width: {{ $activePercent }}%"></div>
                    </div>
                    <div class="sa-ratio-meta">
                        <span><strong>{{ $activePercent }}%</strong> aktif</span>
                        <span><strong>{{ 100 - $activePercent }}%</strong> nonaktif</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- PUSAT PENGELOLAAN --}}
    <section class="sa-section" aria-labelledby="sa-management-title">
        <div class="sa-section-head">
            <h2 id="sa-management-title">Pusat pengelolaan</h2>
            <p>Akses pengaturan utama berdasarkan kewenangan Super Admin.</p>
        </div>

        <div class="sa-split">

            {{-- MANAJEMEN PENGGUNA --}}
            <article class="sa-users">
                <div class="sa-card-top">
                    <div class="sa-card-icon" aria-hidden="true">
                        <svg class="i" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M19 8v6"/><path d="M22 11h-6"/></svg>
                    </div>
                    <div>
                        <h3>Manajemen Pengguna</h3>
                        <small>Akses pengguna</small>
                    </div>
                </div>

                <p class="sa-desc">
                    Kelola akun Admin dan Pimpinan, mulai dari penambahan akun
                    hingga pengaturan akses pengguna.
                </p>

                <p class="sa-detail">
                    Gunakan menu Kelola Akun pada sidebar untuk melihat dan
                    mengelola akun yang terdaftar di dalam sistem.
                </p>

                <div class="sa-access-note">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <span>Pastikan setiap pengguna memiliki akses yang sesuai dengan perannya.</span>
                </div>
            </article>

            {{-- DATA MASTER --}}
            <article class="sa-master">
                <div class="sa-card-top">
                    <div class="sa-card-icon" aria-hidden="true">
                        <svg class="i" viewBox="0 0 24 24"><path d="M12 3 3 8l9 5 9-5-9-5Z"/><path d="m3 12 9 5 9-5"/><path d="m3 16 9 5 9-5"/></svg>
                    </div>
                    <div>
                        <h3>Data Master</h3>
                        <small>Konfigurasi referensi</small>
                    </div>
                </div>

                <p class="sa-desc">
                    Kelola data referensi yang digunakan sebagai pilihan dan
                    acuan pada berbagai fitur SIRAPIK.
                </p>

                <p class="sa-detail">
                    Pengelolaan mencakup provinsi, kabupaten/kota, mitra,
                    sumber dana, kategori pelatihan, tahun pelatihan, dan
                    jenis tenaga kesehatan.
                </p>

                <div class="sa-master-actions">
                    <a href="{{ route('super-admin.master-data.index', ['category' => 'provinces']) }}"
                       class="sa-btn sa-btn-light">
                        Buka Data Master
                        <svg class="i arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </article>

        </div>
    </section>

</div>

<script>
    (function () {
        // Counter angka statistik. Teks awal sudah berisi nilai asli dari server.
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }

        var fmt = new Intl.NumberFormat('id-ID');

        document.querySelectorAll('.sa-dashboard .sa-counter').forEach(function (el) {
            var target = parseInt(el.getAttribute('data-target'), 10) || 0;
            if (target < 1) { return; }

            var start = null, dur = 900;
            el.textContent = '0';

            requestAnimationFrame(function step(t) {
                if (start === null) { start = t; }
                var p = Math.min((t - start) / dur, 1);
                el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 3))));
                if (p < 1) { requestAnimationFrame(step); }
            });
        });
    })();
</script>

@endsection