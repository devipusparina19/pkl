@extends('layouts.app')

@section('title', 'Laporan Pelatihan')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       PREMIUM BLUE EDITORIAL — LAPORAN PELATIHAN
       Semua selector di-scope ke .report-page
       ===================================================== */
    .report-page {
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
        --warn: #8A5A12;
        --warn-bg: #FFF4E3;
        --font-display: "Sora", "Segoe UI", Arial, sans-serif;
        --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
        --ease: cubic-bezier(.2, .7, .2, 1);

        position: relative; width: 100%; color: var(--text);
        font-family: var(--font-body); font-size: 16px; line-height: 1.65;
    }
    .report-page *, .report-page *::before, .report-page *::after { box-sizing: border-box; }
    .report-page [hidden] { display: none !important; }
    .report-page button, .report-page input, .report-page select { font: inherit; }
    .report-page svg.i {
        width: 1em; height: 1em; flex: none;
        fill: none; stroke: currentColor;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
    .report-page :focus-visible { outline: 3px solid var(--cyan); outline-offset: 3px; border-radius: 6px; }
    .rp-shell { width: min(100%, 1500px); margin: 0 auto; }
    .rp-sr { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }

    /* ---------- Progress scroll ---------- */
    .rp-progress {
        position: fixed; z-index: 1000; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--royal), var(--cyan));
        transform: scaleX(0); transform-origin: left; pointer-events: none;
    }

    /* ---------- Reveal (hanya aktif jika JS jalan) ---------- */
    .rp-js .rp-reveal { opacity: 0; transform: translate3d(0, 22px, 0); transition: opacity .7s var(--ease), transform .7s var(--ease); }
    .rp-js .rp-reveal.in { opacity: 1; transform: none; }

    /* ---------- Hero ---------- */
    .rp-hero {
        position: relative; isolation: isolate; overflow: hidden;
        display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, .8fr);
        align-items: end; gap: clamp(24px, 4vw, 64px);
        padding: clamp(30px, 5vw, 64px); border-radius: 28px; color: #fff;
        background: linear-gradient(120deg, var(--navy) 0%, var(--midnight) 60%, #173d86 100%);
        animation: rp-rise .8s var(--ease) both;
    }
    .rp-hero::before {
        content: ""; position: absolute; z-index: -1; right: -90px; top: -110px;
        width: 400px; height: 400px; border-radius: 50%;
        background: var(--royal); opacity: .45; filter: blur(60px);
        animation: rp-drift 24s ease-in-out infinite alternate;
    }
    .rp-hero::after {
        content: ""; position: absolute; z-index: -1; left: 30%; bottom: -120px;
        width: 260px; height: 260px; border-radius: 50%;
        background: var(--cyan); opacity: .14; filter: blur(60px);
    }
    .rp-eyebrow {
        display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px;
        font-size: 13px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--cyan);
    }
    .rp-eyebrow::before { content: ""; width: 28px; height: 2px; background: var(--cyan); }
    .rp-hero h1 {
        margin: 0 0 18px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.5rem, 6.6vw, 5.2rem); line-height: 1.03; letter-spacing: -.04em;
    }
    .rp-hero h1 span { display: block; color: var(--cyan); }
    .rp-hero p { max-width: 560px; margin: 0 0 24px; font-size: 16px; line-height: 1.8; color: rgba(255,255,255,.84); }
    .rp-chips { display: flex; flex-wrap: wrap; gap: 10px; }
    .rp-chip {
        display: inline-flex; align-items: center; gap: 8px; padding: 8px 15px;
        border: 1px solid rgba(255,255,255,.22); border-radius: 999px;
        background: rgba(255,255,255,.08); font-size: 13.5px; font-weight: 600; color: #E3EEFB;
    }
    .rp-chip .i { font-size: 15px; color: var(--cyan); }

    .rp-hero-stats { display: grid; border-left: 1px solid rgba(255,255,255,.2); padding-left: clamp(20px, 3vw, 40px); }
    .rp-hero-stat { padding: 16px 0; }
    .rp-hero-stat + .rp-hero-stat { border-top: 1px solid rgba(255,255,255,.16); }
    .rp-hero-stat small { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; color: rgba(255,255,255,.8); }
    .rp-hero-stat small .i { font-size: 17px; color: var(--cyan); }
    .rp-hero-stat strong {
        display: block; margin: 4px 0 2px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.3rem, 4.6vw, 3.6rem); line-height: 1; letter-spacing: -.04em; font-variant-numeric: tabular-nums;
    }
    .rp-hero-stat span { font-size: 13px; color: rgba(255,255,255,.7); }

    /* ---------- Navigasi section ---------- */
    .rp-nav-wrap { position: sticky; top: 12px; z-index: 50; display: flex; justify-content: center; padding: 16px 0 0; pointer-events: none; }
    .rp-nav {
        display: flex; gap: 4px; max-width: 100%; overflow-x: auto; padding: 5px;
        border: 1px solid var(--border); border-radius: 999px; pointer-events: auto;
        background: rgba(255,255,255,.9); backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(8,27,51,.1); scrollbar-width: none;
    }
    .rp-nav::-webkit-scrollbar { display: none; }
    .rp-nav a {
        padding: 8px 16px; border-radius: 999px; font-size: 13.5px; font-weight: 600;
        color: var(--slate); text-decoration: none; white-space: nowrap; transition: background .2s, color .2s;
    }
    .rp-nav a:hover { color: var(--navy); background: var(--ice); }
    .rp-nav a.active { color: #fff; background: var(--royal); }

    /* ---------- Sections ---------- */
    .rp-section { padding-top: clamp(36px, 5vw, 64px); scroll-margin-top: 80px; }
    .rp-section-head { margin-bottom: 22px; }
    .rp-section-head h2 {
        margin: 0 0 8px; font-family: var(--font-display); font-weight: 700;
        font-size: clamp(1.6rem, 3.6vw, 2.5rem); line-height: 1.15; letter-spacing: -.03em; color: var(--navy);
    }
    .rp-section-head p { margin: 0; max-width: 680px; font-size: 16px; color: var(--slate); }

    /* ---------- Filter ---------- */
    .rp-filter {
        padding: clamp(18px, 2.6vw, 28px); border: 1px solid var(--border);
        border-radius: 22px; background: #fff; box-shadow: 0 14px 36px rgba(8,27,51,.05);
    }
    .rp-filter-grid { display: grid; grid-template-columns: .8fr .8fr 1.6fr auto; gap: 14px; align-items: end; }
    .rp-field label { display: block; margin-bottom: 8px; font-size: 13.5px; font-weight: 700; color: var(--navy); }
    .rp-field input, .rp-field select {
        display: block; width: 100%; min-width: 0; height: 50px; padding: 0 14px;
        color: var(--text); background: var(--ice); border: 1.5px solid var(--border); border-radius: 12px;
        font-size: 15px; transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .rp-field input::placeholder { color: #7a8aa0; }
    .rp-field input:hover, .rp-field select:hover { border-color: #c5d3e4; }
    .rp-field input:focus, .rp-field select:focus { outline: none; background: #fff; border-color: var(--electric); box-shadow: 0 0 0 4px rgba(52,120,246,.16); }
    .rp-filter-actions { display: flex; gap: 8px; }

    /* ---------- Buttons ---------- */
    .rp-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        min-height: 50px; padding: 0 20px; border: 1.5px solid transparent; border-radius: 12px;
        font-family: var(--font-display); font-size: 14px; font-weight: 600; text-decoration: none; white-space: nowrap; cursor: pointer;
        transition: transform .2s var(--ease), box-shadow .2s, background .2s, color .2s;
    }
    .rp-btn .i { font-size: 17px; }
    .rp-btn:active { transform: scale(.97); }
    .rp-btn-primary { color: #fff; background: linear-gradient(110deg, var(--royal), var(--electric)); box-shadow: 0 10px 22px rgba(36,87,214,.28); }
    .rp-btn-primary:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 14px 28px rgba(36,87,214,.38); }
    .rp-btn-secondary { color: var(--royal); background: #fff; border-color: #C5D6F5; }
    .rp-btn-secondary:hover { color: var(--royal); background: var(--soft); transform: translateY(-2px); }
    .rp-btn-ghost { color: var(--slate); background: transparent; }
    .rp-btn-ghost:hover { color: var(--navy); background: var(--ice); }
    .rp-btn[disabled] { opacity: .45; cursor: not-allowed; transform: none; }

    /* ---------- Panel ---------- */
    .rp-grid-2 { display: grid; grid-template-columns: minmax(0, .85fr) minmax(0, 1.15fr); gap: 20px; align-items: start; }
    .rp-panel {
        min-width: 0; overflow: hidden; border: 1px solid var(--border); border-radius: 22px;
        background: #fff; box-shadow: 0 18px 44px rgba(8,27,51,.07);
    }
    .rp-panel-top {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
        padding: 18px clamp(18px, 2.6vw, 26px); border-bottom: 1px solid var(--border);
    }
    .rp-panel-top h3 { margin: 0; font-family: var(--font-display); font-size: 17px; font-weight: 600; color: var(--navy); }
    .rp-panel-top form { margin: 0; }
    .rp-panel-body { padding: clamp(18px, 2.6vw, 28px); }
    .rp-panel-body h3 { margin: 0 0 4px; font-family: var(--font-display); font-size: 18px; font-weight: 600; color: var(--navy); }
    .rp-panel-body > p { margin: 0 0 18px; font-size: 14.5px; color: var(--slate); }
    .rp-note {
        margin: 0; padding: 14px clamp(18px, 2.6vw, 26px); border-bottom: 1px solid var(--border);
        background: var(--ice); font-size: 14px; line-height: 1.75; color: var(--slate);
    }
    .rp-panel-empty { padding: 22px 0; font-size: 14.5px; color: var(--slate); text-align: center; }

    /* ---------- Filter urutan profesi (segmented) ---------- */
    .rp-seg {
        display: inline-flex; gap: 4px; padding: 4px;
        border: 1px solid var(--border); border-radius: 12px; background: var(--ice);
    }
    .rp-seg button {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        min-height: 38px; padding: 0 14px; border: 0; border-radius: 9px;
        background: transparent; color: var(--slate); cursor: pointer;
        font-family: var(--font-display); font-size: 13px; font-weight: 600; white-space: nowrap;
        transition: background .2s, color .2s, box-shadow .2s;
    }
    .rp-seg button .i { font-size: 15px; }
    .rp-seg button:hover { color: var(--navy); background: #fff; }
    .rp-seg button[aria-pressed="true"] { color: #fff; background: var(--royal); box-shadow: 0 6px 14px rgba(36,87,214,.28); }
    .rp-sort-note {
        margin: 0; padding: 12px clamp(18px, 2.6vw, 26px); border-bottom: 1px solid var(--border);
        background: var(--ice); font-size: 13.5px; color: var(--slate);
    }
    .rp-sort-note strong { color: var(--navy); }

    /* ---------- Gender ---------- */
    .rp-chart-box { position: relative; width: 100%; max-width: 380px; height: 380px; margin: 6px auto 22px; }
    .rp-gender-list { display: grid; gap: 10px; margin: 0; padding: 0; list-style: none; }
    .rp-gender-list li { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid var(--border); border-radius: 14px; background: var(--ice); }
    .rp-dot { width: 12px; height: 12px; border-radius: 50%; flex: none; }
    .rp-gender-list b { display: block; font-size: 14.5px; color: var(--navy); }
    .rp-gender-list span { font-size: 13.5px; color: var(--slate); }
    .rp-gender-list em { margin-left: auto; font-style: normal; font-family: var(--font-display); font-weight: 700; font-size: 1.35rem; color: var(--navy); }

    /* ---------- Tabel ---------- */
    .rp-table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: thin; }
    .rp-table { width: 100%; min-width: 520px; border-collapse: collapse; text-align: left; }
    .rp-table thead th {
        padding: 14px 18px; background: var(--navy); color: #fff;
        font-size: 12px; font-weight: 700; letter-spacing: .06em; white-space: nowrap;
    }
    .rp-table tbody td { padding: 14px 18px; border-bottom: 1px solid #EBF0F6; font-size: 14.5px; color: #33465C; vertical-align: middle; }
    .rp-table tbody tr { transition: background .2s; }
    .rp-table tbody tr:hover { background: #F5F9FF; }
    .rp-table tbody tr:last-child td { border-bottom: 0; }
    .rp-number { width: 64px; font-weight: 700; color: #6F8097 !important; font-variant-numeric: tabular-nums; }
    .rp-participant { font-weight: 700; color: var(--navy) !important; }
    .rp-nip { color: var(--slate) !important; font-variant-numeric: tabular-nums; }
    .rp-count {
        display: inline-grid; place-items: center; min-width: 40px; height: 32px; padding: 0 10px;
        border-radius: 10px; background: var(--soft); color: var(--royal);
        font-family: var(--font-display); font-weight: 700; font-variant-numeric: tabular-nums;
    }
    .rp-status {
        display: inline-flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 999px;
        font-size: 13px; font-weight: 700; white-space: nowrap;
    }
    .rp-status .i { font-size: 14px; }
    .rp-status-review { color: var(--warn); background: var(--warn-bg); }
    .rp-status-eligible { color: var(--ok); background: var(--ok-bg); }
    .rp-empty { padding: 44px 18px !important; text-align: center; color: var(--slate) !important; }
    .rp-empty strong { display: block; margin-bottom: 4px; font-family: var(--font-display); font-size: 16px; color: var(--navy); }
    .rp-pagination { padding: 16px clamp(18px, 2.6vw, 26px); border-top: 1px solid var(--border); overflow-x: auto; }
    .rp-pagination:empty { display: none; }
    .rp-pagination svg { width: 17px; height: 17px; }

    /* ---------- Pager Next/Sebelumnya (JS) ---------- */
    .rp-pager {
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        padding: 14px clamp(18px, 2.6vw, 26px); border-top: 1px solid var(--border); background: #fff;
    }
    .rp-pager .rp-btn { min-height: 42px; padding: 0 16px; }
    .rp-pager-info { font-size: 13.5px; font-weight: 600; color: var(--slate); text-align: center; font-variant-numeric: tabular-nums; }
    .rp-panel-body .rp-pager { padding-left: 0; padding-right: 0; padding-bottom: 0; margin-top: 8px; }

    /* ---------- Bar ---------- */
    .rp-bars { display: flex; flex-direction: column; margin: 0; padding: 0; list-style: none; }
    .rp-bars.cols { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); column-gap: 44px; }
    .rp-bars li { padding: 12px 0; border-top: 1px solid var(--border); }
    .rp-bar-row { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin-bottom: 7px; font-size: 14.5px; }
    .rp-bar-name { min-width: 0; color: #33465C; overflow-wrap: anywhere; }
    .rp-bar-total { flex: none; font-family: var(--font-display); font-weight: 700; color: var(--navy); font-variant-numeric: tabular-nums; }
    .rp-bar-track { height: 7px; overflow: hidden; border-radius: 999px; background: var(--soft); }
    .rp-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--royal), var(--cyan)); transform-origin: left; animation: rp-grow .9s var(--ease) both; }

    @keyframes rp-rise { from { opacity: 0; transform: translate3d(0, 18px, 0); } to { opacity: 1; transform: none; } }
    @keyframes rp-drift { to { transform: translate3d(-40px, 40px, 0); } }
    @keyframes rp-grow { from { transform: scaleX(0); } to { transform: scaleX(1); } }

    /* ---------- Responsive ---------- */
    @media (max-width: 1100px) {
        .rp-hero { grid-template-columns: 1fr; align-items: start; }
        .rp-hero-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); border-left: 0; padding-left: 0; border-top: 1px solid rgba(255,255,255,.2); padding-top: 8px; }
        .rp-hero-stat + .rp-hero-stat { border-top: 0; padding-left: 20px; border-left: 1px solid rgba(255,255,255,.16); }
        .rp-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .rp-field-search, .rp-filter-actions { grid-column: 1 / -1; }
        .rp-grid-2 { grid-template-columns: 1fr; }
        .rp-bars.cols { grid-template-columns: 1fr; }
    }
    @media (max-width: 700px) {
        .rp-hero-stats { grid-template-columns: 1fr; }
        .rp-hero-stat + .rp-hero-stat { padding-left: 0; border-left: 0; border-top: 1px solid rgba(255,255,255,.16); }
        .rp-filter-grid { grid-template-columns: 1fr; }
        .rp-filter-actions { display: grid; grid-template-columns: 1fr; }
        .rp-btn { width: 100%; }
        .rp-pager .rp-btn { width: auto; }
        .rp-nav-wrap { justify-content: flex-start; }
        .rp-seg { width: 100%; }
        .rp-seg button { flex: 1; padding: 0 8px; }

        /* tabel menjadi kartu per baris */
        .rp-table-wrap.cards { overflow: visible; }
        .rp-table-wrap.cards .rp-table { display: block; min-width: 0; }
        .rp-table-wrap.cards thead { display: none; }
        .rp-table-wrap.cards tbody { display: grid; gap: 12px; padding: 12px; }
        .rp-table-wrap.cards tbody tr { display: block; overflow: hidden; border: 1px solid var(--border); border-radius: 14px; }
        .rp-table-wrap.cards tbody td {
            display: grid; grid-template-columns: 118px minmax(0, 1fr); gap: 10px;
            padding: 11px 14px; font-size: 14.5px; border-bottom: 1px solid #EDF1F5;
        }
        .rp-table-wrap.cards tbody td[data-label]::before { content: attr(data-label); font-size: 13px; font-weight: 600; color: var(--slate); }
        .rp-table-wrap.cards tbody tr td:last-child { border-bottom: 0; }
        .rp-table-wrap.cards .rp-number { width: auto; }
        .rp-table-wrap.cards td.rp-empty { display: block; }
    }
    @media (prefers-reduced-motion: reduce) {
        .report-page *, .report-page *::before, .report-page *::after { animation: none !important; transition-duration: .01ms !important; scroll-behavior: auto !important; }
        .rp-js .rp-reveal { opacity: 1; transform: none; }
    }
</style>

@php
    $districtMax = max(1, (int) collect($districtRecap)->max('total'));
@endphp

<div class="rp-progress" id="rpProgress" aria-hidden="true"></div>

<div class="report-page" id="reportPage">
    <div class="rp-shell">

        {{-- ================= HERO ================= --}}
        <section class="rp-hero" aria-labelledby="rp-title">
            <div>
                <div class="rp-eyebrow">Laporan Tahunan</div>
                <h1 id="rp-title">Laporan riwayat <span>pelatihan.</span></h1>

                <p>
                    Pantau frekuensi keikutsertaan, distribusi wilayah, dan
                    komposisi peserta berdasarkan tahun pelatihan.
                </p>

                <div class="rp-chips">
                    <span class="rp-chip">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4m8-4v4M3 10h18"/></svg>
                        Tahun {{ $year }}
                    </span>
                    <span class="rp-chip">
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/></svg>
                        Batas peninjauan {{ $minimum }}
                    </span>
                </div>
            </div>

            <div class="rp-hero-stats">
                <div class="rp-hero-stat">
                    <small>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M19 8v6"/><path d="M22 11h-6"/></svg>
                        Peserta sudah dilatih
                    </small>
                    <strong class="rp-counter" data-target="{{ (int) $totalTrainedParticipants }}">{{ number_format((int) $totalTrainedParticipants, 0, ',', '.') }}</strong>
                    <span>Jumlah peserta unik yang mengikuti pelatihan</span>
                </div>

                <div class="rp-hero-stat">
                    <small>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/></svg>
                        Peserta perlu ditinjau
                    </small>
                    <strong class="rp-counter" data-target="{{ (int) $participantsToReview }}">{{ number_format((int) $participantsToReview, 0, ',', '.') }}</strong>
                    <span>Batas indikator: {{ $minimum }} pelatihan per tahun</span>
                </div>

                <div class="rp-hero-stat">
                    <small>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        Total pelatihan tahun {{ $year }}
                    </small>
                    <strong class="rp-counter" data-target="{{ (int) $totalTrainings }}">{{ number_format((int) $totalTrainings, 0, ',', '.') }}</strong>
                    <span>Jumlah kegiatan pelatihan pada tahun terpilih</span>
                </div>
            </div>
        </section>

        {{-- ================= NAV SECTION ================= --}}
        <div class="rp-nav-wrap">
            <nav class="rp-nav" aria-label="Navigasi laporan" id="rpNav">
                <a href="#filter" class="active">Filter</a>
                <a href="#ringkasan">Ringkasan</a>
                <a href="#sebaran">Wilayah</a>
                <a href="#peserta">Daftar Peserta</a>
            </nav>
        </div>

        {{-- ================= FILTER ================= --}}
        <section class="rp-section rp-reveal" id="filter" aria-labelledby="rp-filter-title">
            <div class="rp-section-head">
                <h2 id="rp-filter-title">Sesuaikan data</h2>
                <p>Tentukan tahun, batas peninjauan, dan kata kunci peserta yang ingin ditampilkan.</p>
            </div>

            <div class="rp-filter">
                <form method="GET" action="{{ route('reports.index') }}">
                    <div class="rp-filter-grid">
                        <div class="rp-field">
                            <label for="year">Tahun Pelatihan</label>
                            <select id="year" name="year" required>
                                @forelse ($years as $availableYear)
                                    <option value="{{ $availableYear }}"
                                        @selected((int) $year === (int) $availableYear)>
                                        {{ $availableYear }}
                                    </option>
                                @empty
                                    <option value="{{ $year }}" selected>{{ $year }}</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="rp-field">
                            <label for="minimum">Batas Peninjauan</label>
                            <input id="minimum" type="number" name="minimum" min="1" max="100" value="{{ $minimum }}" required>
                        </div>

                        <div class="rp-field rp-field-search">
                            <label for="search">Pencarian Peserta</label>
                            <input id="search" type="search" name="search" value="{{ $search }}" placeholder="Cari nama, NIP, atau NIK">
                        </div>

                        <div class="rp-filter-actions">
                            <button type="submit" class="rp-btn rp-btn-primary">
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 3H2l8 9.46V19l4 2v-8.54z"/></svg>
                                Terapkan Filter
                            </button>

                            <a href="{{ route('reports.index') }}" class="rp-btn rp-btn-ghost">
                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                                Reset Filter
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- ================= RINGKASAN: GENDER + PROFESI ================= --}}
        <section class="rp-section rp-reveal" id="ringkasan" aria-labelledby="rp-sum-title">
            <div class="rp-section-head">
                <h2 id="rp-sum-title">Ringkasan peserta</h2>
                <p>Komposisi peserta berdasarkan jenis kelamin dan profesi pada tahun {{ $year }}.</p>
            </div>

            <div class="rp-grid-2">

                {{-- JENIS KELAMIN --}}
                <div class="rp-panel">
                    <div class="rp-panel-body">
                        <h3>Jenis kelamin peserta</h3>
                        <p>
                            Total peserta dengan jenis kelamin tercatat:
                            <strong>{{ number_format($genderTotal, 0, ',', '.') }} orang</strong>
                        </p>

                        @if ($genderTotal > 0)
                            <div class="rp-chart-box">
                                <canvas id="reportGenderChart" role="img"
                                    aria-label="Diagram peserta laki-laki {{ $maleCount }} orang dan perempuan {{ $femaleCount }} orang"></canvas>
                            </div>

                            <ul class="rp-gender-list">
                                <li>
                                    <span class="rp-dot" style="background:#2457D6" aria-hidden="true"></span>
                                    <div>
                                        <b>Laki-laki</b>
                                        <span>{{ number_format($maleCount, 0, ',', '.') }} orang</span>
                                    </div>
                                    <em>{{ number_format($malePercent, 1, ',', '.') }}%</em>
                                </li>
                                <li>
                                    <span class="rp-dot" style="background:#0E8FB3" aria-hidden="true"></span>
                                    <div>
                                        <b>Perempuan</b>
                                        <span>{{ number_format($femaleCount, 0, ',', '.') }} orang</span>
                                    </div>
                                    <em>{{ number_format($femalePercent, 1, ',', '.') }}%</em>
                                </li>
                            </ul>
                        @else
                            <div class="rp-panel-empty">Belum ada data jenis kelamin pada tahun {{ $year }}.</div>
                        @endif
                    </div>
                </div>

                {{-- PROFESI (filter tertinggi/terendah + Next/Sebelumnya, 8 baris per halaman) --}}
                <div class="rp-panel">
                    <div class="rp-panel-top">
                        <h3>Total peserta berdasarkan profesi</h3>

                        @if (collect($nakesByType)->isNotEmpty())
                            <div class="rp-seg" role="group" aria-label="Urutkan profesi" id="rpProfSort">
                                <button type="button" data-sort="default" aria-pressed="true">Urutan awal</button>
                                <button type="button" data-sort="high" aria-pressed="false">
                                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
                                    Tertinggi
                                </button>
                                <button type="button" data-sort="low" aria-pressed="false">
                                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                                    Terendah
                                </button>
                            </div>
                        @endif
                    </div>

                    @if (collect($nakesByType)->isNotEmpty())
                        <p class="rp-sort-note" id="rpProfNote" aria-live="polite">
                            Diurutkan: <strong>urutan awal</strong>
                        </p>
                    @endif

                    <div class="rp-table-wrap cards">
                        <table class="rp-table">
                            <caption class="rp-sr">Jumlah peserta unik per profesi tahun {{ $year }}</caption>
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Jenis Profesi</th>
                                    <th scope="col">Peserta Terlatih</th>
                                </tr>
                            </thead>
                            <tbody id="rpProfBody" data-paginate="8" data-items="tr[data-row]" data-pager-after="closest:.rp-table-wrap">
                                @forelse ($nakesByType as $index => $profession)
                                    <tr data-row data-total="{{ (int) $profession->total }}">
                                        <td class="rp-number" data-label="No.">{{ $index + 1 }}</td>
                                        <td class="rp-participant" data-label="Profesi">{{ $profession->name }}</td>
                                        <td data-label="Peserta">
                                            <span class="rp-count">{{ number_format($profession->total, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="rp-empty">Belum ada data profesi pada tahun {{ $year }}.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>

        {{-- ================= SEBARAN WILAYAH ================= --}}
        <section class="rp-section rp-reveal" id="sebaran" aria-labelledby="rp-dist-title">
            <div class="rp-section-head">
                <h2 id="rp-dist-title">Distribusi wilayah</h2>
                <p>Sebaran jumlah pelatihan berdasarkan kabupaten, kota, mitra, atau mandiri.</p>
            </div>

            <div class="rp-panel">
                <div class="rp-panel-body">
                    <h3>Rekap Kabupaten/Kota/Mitra/Mandiri</h3>
                    <p>Jumlah pelatihan berdasarkan wilayah atau mitra pada tahun {{ $year }}.</p>

                    @if (collect($districtRecap)->isEmpty())
                        <div class="rp-panel-empty">Belum ada rekap wilayah atau mitra pada tahun {{ $year }}.</div>
                    @else
                        <ul class="rp-bars cols" data-paginate="8" data-items="li" data-pager-after="self">
                            @foreach ($districtRecap as $item)
                                <li>
                                    <div class="rp-bar-row">
                                        <span class="rp-bar-name">{{ $item->name }}</span>
                                        <span class="rp-bar-total">{{ number_format((int) $item->total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="rp-bar-track" aria-hidden="true">
                                        <div class="rp-bar-fill" style="width: {{ max(2, round(((int) $item->total / $districtMax) * 100)) }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </section>

        {{-- ================= DAFTAR PESERTA ================= --}}
        <section class="rp-section rp-reveal" id="peserta" aria-labelledby="rp-freq-title">
            <div class="rp-section-head">
                <h2 id="rp-freq-title">Frekuensi pelatihan per peserta</h2>
                <p>Jumlah keikutsertaan setiap peserta pada tahun {{ $year }}.</p>
            </div>

            <div class="rp-panel">
                <div class="rp-panel-top">
                    <h3>Daftar peserta</h3>

                    <form method="GET" action="{{ route('reports.export') }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="minimum" value="{{ $minimum }}">
                        <input type="hidden" name="search" value="{{ $search }}">

                        <button type="submit" class="rp-btn rp-btn-secondary">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                            Ekspor Excel
                        </button>
                    </form>
                </div>

                <p class="rp-note">
                    Hanya peserta yang memiliki riwayat pelatihan pada tahun terpilih
                    yang ditampilkan. Peserta dengan jumlah keikutsertaan nol tidak
                    dimasukkan ke dalam tabel.
                </p>

                <div class="rp-table-wrap cards">
                    <table class="rp-table" style="min-width:680px">
                        <caption class="rp-sr">Frekuensi keikutsertaan pelatihan per peserta</caption>
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama Peserta</th>
                                <th scope="col">NIP</th>
                                <th scope="col">Jumlah Keikutsertaan</th>
                                <th scope="col">Status Peninjauan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($participants as $participant)
                                <tr>
                                    <td class="rp-number" data-label="No.">{{ $participants->firstItem() + $loop->index }}</td>
                                    <td class="rp-participant" data-label="Nama Peserta">{{ $participant->name ?: '-' }}</td>
                                    <td class="rp-nip" data-label="NIP">{{ $participant->nip ?: '-' }}</td>
                                    <td data-label="Keikutsertaan">
                                        <span class="rp-count">{{ (int) $participant->participations_count }}</span>
                                    </td>
                                    <td data-label="Status">
                                        @if ($participant->participations_count >= $minimum)
                                            <span class="rp-status rp-status-review">
                                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/></svg>
                                                Perlu Ditinjau
                                            </span>
                                        @else
                                            <span class="rp-status rp-status-eligible">
                                                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                                Eligible
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="rp-empty">
                                        <strong>Tidak ada data peserta</strong>
                                        Belum ada peserta yang mengikuti pelatihan pada tahun {{ $year }}@if ($search !== '') dengan kata kunci "{{ $search }}"@endif.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="rp-pagination">
                    {{ $participants->links() }}
                </div>
            </div>
        </section>

    </div>
</div>

<script>
(function () {
    var page = document.getElementById('reportPage');
    if (!page) { return; }
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- 1. Pager Next/Sebelumnya ---------- */
    document.querySelectorAll('[data-paginate]').forEach(function (box) {
        var per = parseInt(box.getAttribute('data-paginate'), 10) || 8;
        var selector = box.getAttribute('data-items');
        function getItems() { return Array.prototype.slice.call(box.querySelectorAll(selector)); }

        var items = getItems();
        if (items.length <= per) { return; }

        var pages = Math.ceil(items.length / per), current = 1;

        var nav = document.createElement('div');
        nav.className = 'rp-pager';
        nav.innerHTML =
            '<button type="button" class="rp-btn rp-btn-secondary" data-prev>' +
                '<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>Sebelumnya</button>' +
            '<span class="rp-pager-info" aria-live="polite"></span>' +
            '<button type="button" class="rp-btn rp-btn-primary" data-next>Selanjutnya' +
                '<svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg></button>';

        var anchor = box, rule = box.getAttribute('data-pager-after') || 'self';
        if (rule.indexOf('closest:') === 0) { anchor = box.closest(rule.slice(8)) || box; }
        anchor.parentNode.insertBefore(nav, anchor.nextSibling);

        var prev = nav.querySelector('[data-prev]'),
            next = nav.querySelector('[data-next]'),
            info = nav.querySelector('.rp-pager-info');

        function show() {
            var s = (current - 1) * per, e = s + per;
            items.forEach(function (el, i) { el.hidden = !(i >= s && i < e); });
            info.textContent = 'Halaman ' + current + ' dari ' + pages +
                ' · ' + (s + 1) + '–' + Math.min(e, items.length) + ' dari ' + items.length;
            prev.disabled = current === 1;
            next.disabled = current === pages;
        }
        prev.addEventListener('click', function () { if (current > 1) { current--; show(); } });
        next.addEventListener('click', function () { if (current < pages) { current++; show(); } });

        // dipanggil setelah urutan baris berubah (filter tertinggi/terendah)
        box.addEventListener('rp:reorder', function () {
            items = getItems();
            current = 1;
            show();
        });

        show();
    });

    /* ---------- 1b. Filter urutan profesi: tertinggi / terendah ---------- */
    var sortBox  = document.getElementById('rpProfSort');
    var profBody = document.getElementById('rpProfBody');
    var profNote = document.getElementById('rpProfNote');

    if (sortBox && profBody) {
        var rows = Array.prototype.slice.call(profBody.querySelectorAll('tr[data-row]'));
        rows.forEach(function (r, i) { r.setAttribute('data-order', i); });

        var labels = {
            'default': 'urutan awal',
            'high': 'jumlah peserta tertinggi ke terendah',
            'low': 'jumlah peserta terendah ke tertinggi'
        };
        var buttons = Array.prototype.slice.call(sortBox.querySelectorAll('button[data-sort]'));

        function applySort(mode) {
            var sorted = rows.slice().sort(function (a, b) {
                var ta = Number(a.getAttribute('data-total')) || 0;
                var tb = Number(b.getAttribute('data-total')) || 0;
                if (mode === 'high' && ta !== tb) { return tb - ta; }
                if (mode === 'low' && ta !== tb) { return ta - tb; }
                return Number(a.getAttribute('data-order')) - Number(b.getAttribute('data-order'));
            });

            sorted.forEach(function (row, i) {
                profBody.appendChild(row);
                var num = row.querySelector('.rp-number');
                if (num) { num.textContent = i + 1; }
            });

            buttons.forEach(function (btn) {
                btn.setAttribute('aria-pressed', btn.getAttribute('data-sort') === mode ? 'true' : 'false');
            });
            if (profNote) { profNote.innerHTML = 'Diurutkan: <strong>' + labels[mode] + '</strong>'; }

            profBody.dispatchEvent(new CustomEvent('rp:reorder'));
        }

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () { applySort(btn.getAttribute('data-sort')); });
        });
    }

    /* ---------- 2. Counter angka ---------- */
    if (!reduce) {
        var fmt = new Intl.NumberFormat('id-ID');
        page.querySelectorAll('.rp-counter').forEach(function (el) {
            var target = parseInt(el.getAttribute('data-target'), 10) || 0;
            if (target < 1) { return; }
            var start = null, dur = 900;
            el.textContent = '0';
            requestAnimationFrame(function step(t) {
                if (start === null) { start = t; }
                var p = Math.min((t - start) / dur, 1);
                el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 3))));
                if (p < 1) { requestAnimationFrame(step); } else { el.textContent = fmt.format(target); }
            });
        });
    }

    /* ---------- 3. Scroll reveal ---------- */
    if (!reduce && 'IntersectionObserver' in window) {
        page.classList.add('rp-js');
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
            });
        }, { threshold: .08 });
        page.querySelectorAll('.rp-reveal').forEach(function (el) { io.observe(el); });
    }

    /* ---------- 4. Progress bar scroll ---------- */
    var bar = document.getElementById('rpProgress'), ticking = false;
    function updateBar() {
        var h = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.transform = 'scaleX(' + (h > 0 ? Math.min(window.scrollY / h, 1) : 0) + ')';
        ticking = false;
    }
    window.addEventListener('scroll', function () {
        if (!ticking) { ticking = true; requestAnimationFrame(updateBar); }
    }, { passive: true });
    updateBar();

    /* ---------- 5. Indikator section aktif ---------- */
    var links = Array.prototype.slice.call(document.querySelectorAll('#rpNav a'));
    if ('IntersectionObserver' in window) {
        var spy = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) {
                    links.forEach(function (a) {
                        a.classList.toggle('active', a.getAttribute('href') === '#' + en.target.id);
                    });
                }
            });
        }, { rootMargin: '-35% 0px -55% 0px' });
        links.forEach(function (a) {
            var s = document.querySelector(a.getAttribute('href'));
            if (s) { spy.observe(s); }
        });
    }
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('reportGenderChart');
    if (!canvas || typeof Chart === 'undefined') { return; }

    var male = Number(@json($maleCount));
    var female = Number(@json($femaleCount));
    var total = male + female;
    if (total <= 0) { return; }

    // Menampilkan persentase di dalam irisan
    var sliceLabels = {
        id: 'sliceLabels',
        afterDatasetsDraw: function (chart) {
            var ctx = chart.ctx;
            var meta = chart.getDatasetMeta(0);
            var data = chart.data.datasets[0].data;

            ctx.save();
            ctx.font = '700 16px "Sora", "Segoe UI", Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = '#FFFFFF';

            meta.data.forEach(function (arc, i) {
                if (!data[i]) { return; }
                var pct = (data[i] / total * 100).toFixed(1).replace('.', ',') + '%';
                var pos = arc.tooltipPosition();
                ctx.fillText(pct, pos.x, pos.y);
            });
            ctx.restore();
        }
    };

    new Chart(canvas, {
        type: 'pie',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [male, female],
                backgroundColor: ['#2457D6', '#0E8FB3'],
                borderColor: '#FFFFFF',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            var v = Number(ctx.raw) || 0;
                            var pct = (v / total * 100).toFixed(1);
                            return ctx.label + ': ' + v.toLocaleString('id-ID') + ' peserta (' + pct + '%)';
                        }
                    }
                }
            }
        },
        plugins: [sliceLabels]
    });
});
</script>
@endsection