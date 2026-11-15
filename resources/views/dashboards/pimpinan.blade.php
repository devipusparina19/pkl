@extends('layouts.app')

@section('title', 'Dashboard Pimpinan · ARTA')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

@php
    $periodLabel = $year === 'all' ? 'Semua Tahun' : 'Tahun ' . $year;
    $periodInline = $year === 'all' ? 'seluruh tahun' : 'tahun ' . $year;
    $maxFieldTotal = max(1, (int) $fieldStats->max('total'));
    $categoryCount = $fieldStats->count();
@endphp

{{-- Icon sprite (gaya Lucide, inline, tanpa dependency) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <symbol id="i-pulse" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/><path d="M8 7h8"/><path d="M8 11h7"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></symbol>
    <symbol id="i-bars" viewBox="0 0 24 24"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></symbol>
    <symbol id="i-trend" viewBox="0 0 24 24"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m7 14 4-4 3 3 5-7"/><path d="M15 6h4v4"/></symbol>
    <symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></symbol>
    <symbol id="i-down" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="i-layers" viewBox="0 0 24 24"><path d="M12 3 3 8l9 5 9-5-9-5Z"/><path d="m3 12 9 5 9-5"/><path d="m3 16 9 5 9-5"/></symbol>
</svg>

<div class="ld-progress" id="ldProgress" aria-hidden="true"></div>

<div class="leader-dashboard" id="ldPage">

    {{-- ================= HERO ================= --}}
    <header class="ld-hero">
        <span class="ld-grid-bg" aria-hidden="true"></span>
        <span class="ld-orb ld-orb-1" aria-hidden="true"></span>
        <span class="ld-orb ld-orb-2" aria-hidden="true"></span>

        <div class="ld-hero-main">
            <div class="ld-brand" data-reveal>
                <span class="ld-brand-mark" aria-hidden="true"><svg class="i"><use href="#i-pulse"/></svg></span>
                ARTA &middot; Dashboard Pimpinan
            </div>

            <h1 data-reveal style="--d:.08s">
                Ringkasan
                <span>Pelatihan.</span>
            </h1>

            <p class="ld-lead" data-reveal style="--d:.16s">
                Pusat informasi <strong>ARTA</strong> &mdash; Aplikasi Rekam Riwayat Pelatihan
                Tenaga Kesehatan. Pantau pelaksanaan pelatihan, pahami distribusi program,
                dan lihat perkembangan keikutsertaan peserta.
            </p>

            <div class="ld-period" data-reveal style="--d:.22s">
                <svg class="i" aria-hidden="true"><use href="#i-calendar"/></svg>
                Periode aktif: <strong>{{ $periodLabel }}</strong>
            </div>
        </div>

        <div class="ld-bento" data-reveal style="--d:.28s">
            <div class="ld-tile ld-tile-main">
                <small><svg class="i" aria-hidden="true"><use href="#i-users"/></svg>Total peserta</small>
                <strong data-count="{{ (int) $totalParticipants }}">{{ number_format($totalParticipants) }}</strong>
                <span>{{ ucfirst($periodInline) }}</span>
            </div>

            <div class="ld-tile">
                <b data-count="{{ (int) $totalTrainings }}">{{ number_format($totalTrainings) }}</b>
                <span>Total pelatihan</span>
            </div>

            <div class="ld-tile">
                <b data-count="{{ (int) $categoryCount }}">{{ $categoryCount }}</b>
                <span>Kategori pelatihan</span>
            </div>
        </div>
    </header>

    {{-- ================= FILTER TAHUN ================= --}}
    <section class="ld-filter" aria-label="Filter tahun dashboard" data-reveal>
        <div class="ld-filter-copy">
            <strong>Filter Periode Pelatihan</strong>
            <span>Pilih tahun untuk melihat ringkasan data pelatihan pada periode tersebut.</span>
        </div>

        @if ($years->isNotEmpty())
            <form action="{{ route('dashboard') }}" method="GET" class="ld-year-form" id="ldYearForm">
                <label for="dashboard-year">Tahun Pelatihan</label>

                <div class="ld-select">
                    <select name="year" id="dashboard-year" required>
                        <option value="all" {{ $year === 'all' ? 'selected' : '' }}>Semua Tahun</option>

                        @foreach ($years as $availableYear)
                            <option value="{{ $availableYear }}"
                                {{ (string) $year === (string) $availableYear ? 'selected' : '' }}>
                                {{ $availableYear }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="i" aria-hidden="true"><use href="#i-down"/></svg>
                </div>

                <button type="submit" class="ld-btn ld-btn-primary">
                    <svg class="i" aria-hidden="true"><use href="#i-arrow"/></svg>
                    Tampilkan Data
                </button>
            </form>
        @else
            <div class="ld-filter-empty" role="status">
                Belum ada tahun aktif. Silakan Super Admin menambahkan dan mengaktifkan
                tahun pada menu Master Data Tahun Pelatihan.
            </div>
        @endif
    </section>

    {{-- ================= STATISTIK UTAMA ================= --}}
    <section aria-labelledby="leader-summary-title" class="ld-block">
        <div class="ld-intro" data-reveal>
            <div>
                <span class="ld-kicker">Ikhtisar sistem</span>
                <h2 id="leader-summary-title">Gambaran Umum Pelatihan {{ $periodLabel }}</h2>
                <p>Indikator utama pelatihan dan keikutsertaan peserta pada {{ $periodInline }}.</p>
            </div>
            <span class="ld-number" aria-hidden="true">01</span>
        </div>

        <div class="ld-stats">
            {{-- TOTAL PESERTA --}}
            <article class="ld-stat" style="--accent:#2457D6;--soft:#EAF2FF;--d:.05s" data-reveal>
                <div class="ld-stat-top">
                    <span class="ld-stat-icon" aria-hidden="true"><svg class="i"><use href="#i-users"/></svg></span>
                    <span class="ld-stat-index">01 / SDM</span>
                </div>
                <div class="ld-stat-label">Total Peserta</div>
                <div class="ld-stat-number" data-count="{{ (int) $totalParticipants }}">{{ number_format($totalParticipants) }}</div>
                <div class="ld-stat-note">Peserta yang mengikuti pelatihan pada {{ $periodInline }}</div>
            </article>

            {{-- TOTAL PELATIHAN --}}
            <article class="ld-stat" style="--accent:#0E8FA8;--soft:#E3F6FB;--d:.12s" data-reveal>
                <div class="ld-stat-top">
                    <span class="ld-stat-icon" aria-hidden="true"><svg class="i"><use href="#i-book"/></svg></span>
                    <span class="ld-stat-index">02 / PROGRAM</span>
                </div>
                <div class="ld-stat-label">Total Pelatihan</div>
                <div class="ld-stat-number" data-count="{{ (int) $totalTrainings }}">{{ number_format($totalTrainings) }}</div>
                <div class="ld-stat-note">Jumlah pelaksanaan pelatihan pada {{ $periodInline }}</div>
            </article>
        </div>
    </section>

    {{-- ================= DISTRIBUSI KATEGORI ================= --}}
    <section class="ld-panel" aria-labelledby="field-distribution-title" data-reveal>
        <div class="ld-panel-head">
            <div>
                <span class="ld-kicker">Komposisi program</span>
                <h2 id="field-distribution-title">Distribusi Kategori Pelatihan</h2>
                <p>
                    Perbandingan jumlah pelaksanaan pelatihan berdasarkan
                    kategori pada {{ $periodInline }}.
                </p>
            </div>

            <div class="ld-tag">
                <svg class="i" aria-hidden="true"><use href="#i-bars"/></svg>
                Distribusi
            </div>
        </div>

        <div class="ld-dist" id="ldDist">
            @forelse ($fieldStats as $stat)
                @php
                    $fieldName = $stat->name ?? 'Kategori tidak diketahui';
                    $total = (int) ($stat->total ?? 0);
                    $percentage = min(100, max(0, ($total / $maxFieldTotal) * 100));
                @endphp

                <div class="ld-field">
                    <div class="ld-field-line">
                        <span>{{ $fieldName }}</span>
                        <strong>{{ number_format($total) }}</strong>
                    </div>

                    <div class="ld-track"
                         role="progressbar"
                         aria-label="Jumlah pelatihan kategori {{ $fieldName }}"
                         aria-valuenow="{{ $total }}"
                         aria-valuemin="0"
                         aria-valuemax="{{ $maxFieldTotal }}">
                        <div class="ld-fill" data-w="{{ round($percentage, 2) }}" style="--w: {{ round($percentage, 2) }}"></div>
                    </div>
                </div>
            @empty
                <div class="ld-empty">
                    <span class="ld-empty-icon" aria-hidden="true"><svg class="i"><use href="#i-info"/></svg></span>
                    <div>Belum ada data kategori pelatihan pada {{ $periodInline }}.</div>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ================= CATATAN PEMANTAUAN ================= --}}
    <section class="ld-monitor" aria-labelledby="monitoring-title" data-reveal>
        <span class="ld-orb ld-orb-3" aria-hidden="true"></span>

        <div class="ld-monitor-body">
            <div class="ld-monitor-icon" aria-hidden="true"><svg class="i"><use href="#i-trend"/></svg></div>

            <div>
                <span class="ld-kicker ld-kicker-light">Informasi lanjutan</span>
                <h2 id="monitoring-title">Pemantauan Pelaksanaan Pelatihan</h2>
                <p>
                    Dashboard ini sedang menampilkan data <strong>{{ $periodInline }}</strong>.
                    Gunakan menu <strong>Laporan Pelatihan</strong> pada sidebar untuk melihat
                    rincian laporan dan memantau pelaksanaan pelatihan berdasarkan data yang
                    tercatat di ARTA.
                </p>
            </div>
        </div>

        <div class="ld-monitor-deco" aria-hidden="true"><svg class="i"><use href="#i-layers"/></svg></div>
    </section>

</div>

<style>
    /* =====================================================
       ARTA — PREMIUM BLUE EDITORIAL · DASHBOARD PIMPINAN
       Semua selector di-scope ke .leader-dashboard / .ld-progress
       ===================================================== */

    .ld-progress {
        position: fixed; top: 0; left: 0; right: 0; z-index: 2000;
        height: 3px; pointer-events: none;
        background: linear-gradient(90deg, #38C6E8, #3478F6, #2457D6);
        transform: scaleX(0); transform-origin: 0 50%;
    }

    .leader-dashboard {
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
        --font-display: "Sora", "Segoe UI", Arial, sans-serif;
        --font-body: "DM Sans", "Segoe UI", Arial, sans-serif;
        --ease: cubic-bezier(.2, .7, .2, 1);

        width: 100%; max-width: none; min-width: 0;
        padding-bottom: 34px;
        color: var(--text);
        font-family: var(--font-body); font-size: 16px; line-height: 1.65;
        -webkit-font-smoothing: antialiased;
    }
    .leader-dashboard *, .leader-dashboard *::before, .leader-dashboard *::after { box-sizing: border-box; }
    .leader-dashboard button, .leader-dashboard select { font: inherit; }
    .leader-dashboard a, .leader-dashboard button { -webkit-tap-highlight-color: transparent; }
    .leader-dashboard svg.i {
        width: 1em; height: 1em; flex: none;
        fill: none; stroke: currentColor;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
    .leader-dashboard :focus-visible { outline: 3px solid var(--cyan); outline-offset: 3px; border-radius: 8px; }

    /* ---------- Reveal (aktif hanya bila JS jalan) ---------- */
    .leader-dashboard.js [data-reveal] {
        opacity: 0; transform: translate3d(0, 22px, 0);
        transition: opacity .7s var(--ease), transform .7s var(--ease);
        transition-delay: var(--d, 0s);
        will-change: opacity, transform;
    }
    .leader-dashboard.js [data-reveal].is-in { opacity: 1; transform: none; }

    /* ---------- Hero ---------- */
    .ld-hero {
        position: relative; isolation: isolate; overflow: hidden;
        display: grid; grid-template-columns: minmax(0, 1.45fr) minmax(0, .85fr);
        align-items: end; gap: clamp(24px, 4vw, 64px);
        margin-bottom: 24px;
        padding: clamp(28px, 4.8vw, 64px);
        border-radius: 28px; color: #fff;
        background:
            radial-gradient(800px 380px at 90% -10%, rgba(52,120,246,.5), transparent 60%),
            radial-gradient(600px 320px at 0% 110%, rgba(56,198,232,.2), transparent 60%),
            linear-gradient(125deg, var(--navy) 0%, var(--midnight) 60%, #123A74 100%);
    }
    .ld-grid-bg {
        position: absolute; inset: 0; z-index: -1;
        background-image:
            linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px);
        background-size: 52px 52px;
        -webkit-mask-image: radial-gradient(ellipse at 75% 25%, #000 10%, transparent 70%);
        mask-image: radial-gradient(ellipse at 75% 25%, #000 10%, transparent 70%);
    }
    .ld-orb {
        position: absolute; z-index: -1; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.14);
        animation: ld-float 16s ease-in-out infinite;
    }
    .ld-orb-1 { width: 400px; height: 400px; top: -170px; right: -80px; }
    .ld-orb-2 { width: 210px; height: 210px; bottom: -100px; right: 32%; background: rgba(56,198,232,.07); animation-delay: -6s; }

    .ld-brand {
        display: inline-flex; align-items: center; gap: 12px; margin-bottom: 20px;
        font-size: 12px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--cyan);
    }
    .ld-brand-mark {
        display: grid; place-items: center; width: 40px; height: 40px; border-radius: 12px; font-size: 19px; color: #fff;
        background: linear-gradient(135deg, var(--electric), var(--cyan));
        box-shadow: 0 10px 28px rgba(52,120,246,.45);
    }
    .ld-hero h1 {
        margin: 0 0 20px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.6rem, 7.4vw, 5.4rem); line-height: 1; letter-spacing: -.045em;
        overflow-wrap: anywhere;
    }
    .ld-hero h1 span {
        display: block;
        background: linear-gradient(95deg, #8FB8FF 0%, var(--cyan) 60%, #B6F0FF 100%);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .ld-lead { max-width: 580px; margin: 0; font-size: clamp(15px, 1.4vw, 17px); line-height: 1.8; color: rgba(255,255,255,.82); }
    .ld-lead strong { color: #fff; }
    .ld-period {
        display: inline-flex; align-items: center; gap: 10px; margin-top: 26px; padding: 10px 16px;
        border: 1px solid rgba(255,255,255,.18); border-radius: 999px; background: rgba(255,255,255,.08);
        font-size: 14px; color: rgba(255,255,255,.85);
    }
    .ld-period .i { font-size: 17px; color: var(--cyan); }
    .ld-period strong { color: #fff; }

    .ld-bento { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .ld-tile {
        padding: 20px 18px; border: 1px solid rgba(255,255,255,.15); border-radius: 20px;
        background: rgba(255,255,255,.07);
        -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
    }
    .ld-tile b {
        display: block; font-family: var(--font-display); font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 800; line-height: 1; letter-spacing: -.04em; font-variant-numeric: tabular-nums;
    }
    .ld-tile span { display: block; margin-top: 8px; font-size: 12.5px; font-weight: 500; line-height: 1.4; color: #A9C3E6; }
    .ld-tile-main { grid-column: 1 / -1; padding: 24px 22px; background: rgba(255,255,255,.1); }
    .ld-tile-main small { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 500; color: rgba(255,255,255,.82); }
    .ld-tile-main small .i { font-size: 18px; color: var(--cyan); }
    .ld-tile-main strong {
        display: block; margin: 6px 0 2px; font-family: var(--font-display); font-weight: 800; line-height: 1;
        font-size: clamp(2.8rem, 5.5vw, 4.4rem); letter-spacing: -.05em; font-variant-numeric: tabular-nums;
    }
    .ld-tile-main span { margin: 0; font-size: 13px; color: rgba(255,255,255,.7); }

    /* ---------- Buttons ---------- */
    .ld-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        min-height: 50px; padding: 10px 22px; border: 1.5px solid transparent; border-radius: 13px;
        font-family: var(--font-display); font-size: 14px; font-weight: 600; white-space: nowrap; cursor: pointer;
        transition: transform .2s var(--ease), box-shadow .2s, background-color .2s, color .2s;
    }
    .ld-btn .i { font-size: 17px; }
    .ld-btn:active { transform: scale(.97); }
    .ld-btn-primary { color: #fff; background: linear-gradient(110deg, var(--royal), var(--electric)); box-shadow: 0 12px 26px rgba(36,87,214,.4); }
    .ld-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 18px 34px rgba(36,87,214,.5); }

    .leader-dashboard .is-loading { pointer-events: none; opacity: .85; cursor: progress; }
    .leader-dashboard .is-loading > .i { display: none; }
    .leader-dashboard .is-loading::before {
        content: ""; width: 14px; height: 14px; flex: none;
        border: 2px solid currentColor; border-right-color: transparent; border-radius: 50%;
        animation: ld-spin .75s linear infinite;
    }

    /* ---------- Filter ---------- */
    .ld-filter {
        display: flex; align-items: center; justify-content: space-between; gap: 22px;
        margin-bottom: 44px; padding: clamp(18px, 2.6vw, 26px);
        border: 1px solid var(--border); border-radius: 22px; background: #fff;
        box-shadow: 0 14px 36px rgba(8,27,51,.05);
    }
    .ld-filter-copy { min-width: 0; }
    .ld-filter-copy strong { display: block; margin-bottom: 4px; font-family: var(--font-display); font-size: 17px; font-weight: 700; letter-spacing: -.02em; color: var(--navy); }
    .ld-filter-copy span { font-size: 14px; color: var(--slate); }
    .ld-year-form { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
    .ld-year-form label { font-size: 13.5px; font-weight: 700; color: var(--navy); }
    .ld-select {
        position: relative; display: flex; align-items: center; min-width: 170px; min-height: 52px;
        border: 1.5px solid var(--border); border-radius: 13px; background: var(--ice);
        transition: border-color .2s, box-shadow .2s, background-color .2s;
    }
    .ld-select:hover { border-color: #c5d3e4; }
    .ld-select:focus-within { background: #fff; border-color: var(--electric); box-shadow: 0 0 0 4px rgba(52,120,246,.16); }
    .ld-select select {
        width: 100%; height: 49px; padding: 0 42px 0 14px; appearance: none; -webkit-appearance: none;
        border: 0; outline: none; background: transparent; color: var(--navy); font-size: 15px; font-weight: 700; cursor: pointer;
    }
    .ld-select .i { position: absolute; right: 14px; font-size: 18px; color: var(--slate); pointer-events: none; }
    .ld-filter-empty {
        flex: 1; padding: 13px 16px; border: 1px solid #F0DFBD; border-radius: 13px;
        background: #FFF8E9; color: #7A4F0F; font-size: 14px;
    }

    /* ---------- Section intro ---------- */
    .ld-block { margin-bottom: 44px; }
    .ld-intro { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 22px; }
    .ld-kicker { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 8px; font-size: 12px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--royal); }
    .ld-kicker::before { content: ""; width: 26px; height: 3px; border-radius: 9px; background: currentColor; }
    .ld-kicker-light { color: var(--cyan); }
    .ld-intro h2 {
        margin: 0; font-family: var(--font-display); font-weight: 800; color: var(--navy);
        font-size: clamp(1.7rem, 4vw, 2.8rem); line-height: 1.12; letter-spacing: -.035em;
    }
    .ld-intro p { max-width: 560px; margin: 10px 0 0; font-size: 15px; line-height: 1.8; color: var(--slate); }
    .ld-number { flex-shrink: 0; font-family: var(--font-display); font-size: clamp(2.6rem, 6vw, 4.4rem); font-weight: 800; line-height: 1; letter-spacing: -.05em; color: #CBD7E6; }

    /* ---------- Stat cards ---------- */
    .ld-stats { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .ld-stat {
        position: relative; display: flex; flex-direction: column; min-width: 0; min-height: 250px; overflow: hidden;
        padding: 26px 26px 22px; border: 1px solid var(--border); border-radius: 24px; background: #fff;
        box-shadow: 0 14px 36px rgba(8,27,51,.05);
        transition: transform .25s var(--ease), box-shadow .25s;
    }
    .ld-stat::before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: var(--accent); }
    .ld-stat::after {
        content: ""; position: absolute; right: -60px; bottom: -80px; width: 220px; height: 220px; border-radius: 50%;
        background: var(--soft); z-index: 0;
    }
    .ld-stat > * { position: relative; z-index: 1; }
    .ld-stat:hover { transform: translateY(-4px); box-shadow: 0 24px 46px -14px rgba(8,27,51,.2); }
    .ld-stat-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .ld-stat-icon { display: grid; place-items: center; width: 52px; height: 52px; border-radius: 16px; background: var(--soft); color: var(--accent); font-size: 25px; }
    .ld-stat-index { font-size: 11.5px; font-weight: 700; letter-spacing: 1.2px; color: #7C8DA3; }
    .ld-stat-label { font-size: 15px; font-weight: 600; color: var(--slate); }
    .ld-stat-number {
        margin-top: 4px; font-family: var(--font-display); font-weight: 800; color: var(--navy);
        font-size: clamp(2.6rem, 5vw, 4rem); line-height: 1.15; letter-spacing: -.05em; font-variant-numeric: tabular-nums;
    }
    .ld-stat-note {
        display: flex; align-items: center; gap: 9px; margin-top: auto; padding-top: 16px;
        border-top: 1px solid #E8EEF5; font-size: 13px; line-height: 1.6; color: #6B7C90;
    }
    .ld-stat-note::before { content: ""; width: 8px; height: 8px; flex: 0 0 8px; border-radius: 50%; background: var(--accent); }

    /* ---------- Panel distribusi ---------- */
    .ld-panel {
        margin-bottom: 24px; padding: clamp(22px, 3.2vw, 40px);
        border: 1px solid var(--border); border-radius: 26px; background: #fff;
        box-shadow: 0 22px 50px -20px rgba(8,27,51,.18);
    }
    .ld-panel-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; margin-bottom: 26px; }
    .ld-panel-head h2 { margin: 0; font-family: var(--font-display); font-weight: 800; font-size: clamp(1.5rem, 3vw, 2.2rem); line-height: 1.2; letter-spacing: -.035em; color: var(--navy); }
    .ld-panel-head p { max-width: 640px; margin: 10px 0 0; font-size: 15px; line-height: 1.8; color: var(--slate); }
    .ld-tag {
        display: inline-flex; align-items: center; gap: 9px; flex-shrink: 0; padding: 10px 15px; border-radius: 12px;
        border: 1px solid #D3E2FB; background: var(--soft); color: var(--royal);
        font-size: 12px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    }
    .ld-tag .i { font-size: 18px; }

    .ld-dist { display: grid; grid-template-columns: 1fr 1fr; column-gap: 48px; }
    .ld-field { min-width: 0; padding: 19px 0; border-bottom: 1px solid #E8EEF5; }
    .ld-field-line { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 12px; }
    .ld-field-line span {
        display: flex; align-items: center; gap: 11px; min-width: 0;
        font-size: 15px; font-weight: 600; line-height: 1.5; color: #2E4560; overflow-wrap: anywhere;
    }
    .ld-field-line span::before { content: ""; width: 9px; height: 9px; flex: 0 0 9px; border-radius: 3px; background: var(--cyan); transform: rotate(45deg); }
    .ld-field-line strong {
        flex-shrink: 0; min-width: 48px; padding: 6px 11px; border: 1px solid #D3E2FB; border-radius: 9px;
        background: var(--soft); color: var(--royal); font-size: 13.5px; font-weight: 700; text-align: center; font-variant-numeric: tabular-nums;
    }
    .ld-track { width: 100%; height: 12px; overflow: hidden; border-radius: 99px; background: #E9EFF6; }
    .ld-fill {
        height: 100%; width: 100%; border-radius: 99px;
        background: linear-gradient(90deg, var(--royal), var(--cyan));
        transform: scaleX(calc(var(--w, 0) / 100)); transform-origin: 0 50%;
    }
    .leader-dashboard.js .ld-fill { transform: scaleX(0); transition: transform 1.1s var(--ease); }
    .leader-dashboard.js .ld-fill.is-grown { transform: scaleX(calc(var(--w, 0) / 100)); }

    .ld-empty {
        grid-column: 1 / -1; display: flex; align-items: center; gap: 15px; padding: 24px;
        border: 1px dashed #C9D8EA; border-radius: 16px; background: var(--ice); color: var(--slate); font-size: 15px; line-height: 1.8;
    }
    .ld-empty-icon { display: grid; place-items: center; flex: 0 0 44px; width: 44px; height: 44px; border-radius: 13px; background: var(--soft); color: var(--royal); font-size: 22px; }

    /* ---------- Monitoring ---------- */
    .ld-monitor {
        position: relative; isolation: isolate; overflow: hidden;
        display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 28px;
        padding: clamp(24px, 4vw, 48px); border-radius: 26px; color: #fff;
        background:
            radial-gradient(600px 300px at 100% 0%, rgba(52,120,246,.5), transparent 60%),
            linear-gradient(130deg, var(--navy), var(--midnight) 70%, #123A74);
    }
    .ld-orb-3 { width: 300px; height: 300px; top: -150px; right: 60px; }
    .ld-monitor-body { display: flex; align-items: flex-start; gap: 20px; min-width: 0; }
    .ld-monitor-icon {
        display: grid; place-items: center; flex: 0 0 56px; width: 56px; height: 56px; border-radius: 17px; font-size: 27px;
        border: 1px solid rgba(255,255,255,.18); background: rgba(255,255,255,.1); color: var(--cyan);
    }
    .ld-monitor h2 { margin: 0; font-family: var(--font-display); font-weight: 800; font-size: clamp(1.4rem, 2.8vw, 2rem); letter-spacing: -.03em; }
    .ld-monitor p { max-width: 760px; margin: 10px 0 0; font-size: 15px; line-height: 1.9; color: rgba(255,255,255,.8); }
    .ld-monitor p strong { color: #fff; }
    .ld-monitor-deco {
        display: grid; place-items: center; width: 84px; height: 84px; border-radius: 26px; font-size: 38px;
        border: 1px solid rgba(255,255,255,.18); background: rgba(255,255,255,.08); color: #BFE8F5;
        transform: rotate(-7deg); animation: ld-float 12s ease-in-out infinite;
    }

    /* ---------- Keyframes ---------- */
    @keyframes ld-float { 0%, 100% { transform: translate3d(0,0,0); } 50% { transform: translate3d(-16px, 20px, 0); } }
    @keyframes ld-spin { to { transform: rotate(360deg); } }
    .ld-monitor-deco { animation-name: ld-bob; }
    @keyframes ld-bob { 0%, 100% { transform: rotate(-7deg) translate3d(0,0,0); } 50% { transform: rotate(-7deg) translate3d(0,-10px,0); } }

    /* ---------- Responsive ---------- */
    @media (max-width: 1050px) {
        .ld-hero { grid-template-columns: 1fr; align-items: start; }
        .ld-bento { max-width: 560px; }
    }
    @media (max-width: 900px) {
        .ld-filter { flex-direction: column; align-items: stretch; }
        .ld-year-form { flex-wrap: wrap; }
        .ld-dist { column-gap: 28px; }
    }
    @media (max-width: 700px) {
        .ld-hero, .ld-panel, .ld-monitor { border-radius: 22px; }
        .ld-filter { border-radius: 20px; margin-bottom: 32px; }
        .ld-year-form { flex-direction: column; align-items: stretch; }
        .ld-select, .ld-year-form .ld-btn { width: 100%; }
        .ld-block { margin-bottom: 32px; }
        .ld-stats { grid-template-columns: 1fr; }
        .ld-stat { min-height: 0; border-radius: 20px; }
        .ld-panel-head { flex-direction: column; gap: 14px; }
        .ld-dist { grid-template-columns: 1fr; }
        .ld-monitor { grid-template-columns: 1fr; }
        .ld-monitor-deco { display: none; }
        .ld-monitor-body { gap: 14px; }
    }
    @media (max-width: 420px) {
        .ld-bento { grid-template-columns: 1fr 1fr; }
        .ld-intro { align-items: flex-start; }
        .ld-number { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        .leader-dashboard *, .leader-dashboard *::before, .leader-dashboard *::after {
            animation: none !important;
            transition-duration: .01ms !important;
            scroll-behavior: auto !important;
        }
        .leader-dashboard.js [data-reveal] { opacity: 1; transform: none; }
        .leader-dashboard.js .ld-fill { transform: scaleX(calc(var(--w, 0) / 100)); }
    }
</style>

<script>
(function () {
    'use strict';

    var root = document.getElementById('ldPage');
    if (!root) { return; }
    root.classList.add('js');

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- 1. Jaga posisi scroll (tidak lompat ke atas) ---------- */
    var KEY = 'arta:ld-scroll:' + location.pathname;

    function saveScroll() {
        try {
            sessionStorage.setItem(KEY, JSON.stringify({
                y: window.pageYOffset || document.documentElement.scrollTop || 0,
                t: Date.now()
            }));
        } catch (e) {}
    }

    function readScroll() {
        try {
            var raw = sessionStorage.getItem(KEY);
            if (!raw) { return null; }
            var data = JSON.parse(raw);
            /* kedaluwarsa 20 detik agar tidak "nyangkut" di kunjungan lain */
            if (!data || (Date.now() - data.t) > 20000) {
                sessionStorage.removeItem(KEY);
                return null;
            }
            return data.y || 0;
        } catch (e) { return null; }
    }

    var savedY = readScroll();
    if (savedY !== null) {
        window.scrollTo(0, savedY);
        window.addEventListener('load', function () {
            window.scrollTo(0, savedY);
            try { sessionStorage.removeItem(KEY); } catch (e) {}
        });
    }

    /* ---------- 2. Scroll progress bar ---------- */
    var bar = document.getElementById('ldProgress');
    var ticking = false;
    function updateBar() {
        var h = document.documentElement;
        var max = (h.scrollHeight - h.clientHeight) || 1;
        var p = Math.min(1, Math.max(0, (window.pageYOffset || h.scrollTop) / max));
        bar.style.transform = 'scaleX(' + p + ')';
        ticking = false;
    }
    window.addEventListener('scroll', function () {
        if (!ticking) { ticking = true; requestAnimationFrame(updateBar); }
    }, { passive: true });
    window.addEventListener('resize', updateBar);
    updateBar();

    /* ---------- 3. Scroll reveal ---------- */
    var reveals = root.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window && !reduce) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
            });
        }, { threshold: .06, rootMargin: '0px 0px -30px 0px' });
        reveals.forEach(function (el) { io.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('is-in'); });
    }

    /* ---------- 4. Counter angka ---------- */
    var fmt = new Intl.NumberFormat('en-US');
    root.querySelectorAll('[data-count]').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
        if (reduce || target < 1) { return; }
        var start = null, dur = 1100;
        el.textContent = '0';
        setTimeout(function () {
            requestAnimationFrame(function step(ts) {
                if (start === null) { start = ts; }
                var p = Math.min((ts - start) / dur, 1);
                el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 3))));
                if (p < 1) { requestAnimationFrame(step); }
            });
        }, 300);
    });

    /* ---------- 5. Bar distribusi tumbuh saat terlihat ---------- */
    var fills = root.querySelectorAll('.ld-fill');
    if ('IntersectionObserver' in window && !reduce) {
        var fio = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) {
                    var el = en.target;
                    var idx = Array.prototype.indexOf.call(fills, el);
                    setTimeout(function () { el.classList.add('is-grown'); }, Math.min(idx, 10) * 70);
                    fio.unobserve(el);
                }
            });
        }, { threshold: .2 });
        fills.forEach(function (el) { fio.observe(el); });
    } else {
        fills.forEach(function (el) { el.classList.add('is-grown'); });
    }

    /* ---------- 6. Form filter: loading + simpan posisi ---------- */
    var form = document.getElementById('ldYearForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (e.defaultPrevented) { return; }
            if (form.dataset.sending === '1') { e.preventDefault(); return; }

            form.dataset.sending = '1';
            saveScroll();

            var btn = e.submitter || form.querySelector('button[type="submit"]');
            if (btn) {
                btn.classList.add('is-loading');
                btn.setAttribute('aria-busy', 'true');
                /* disable setelah event selesai agar data form tetap terkirim */
                setTimeout(function () { btn.disabled = true; }, 0);
            }
        });
    }

    /* ---------- 7. Tombol Back (bfcache): reset status loading ---------- */
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) { return; }
        if (form) { delete form.dataset.sending; }
        root.querySelectorAll('.is-loading').forEach(function (b) {
            b.classList.remove('is-loading');
            b.removeAttribute('aria-busy');
            b.disabled = false;
        });
    });
})();
</script>

@endsection