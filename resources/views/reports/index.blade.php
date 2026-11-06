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

        width: 100%; color: var(--text);
        font-family: var(--font-body); font-size: 15px; line-height: 1.65;
    }
    .report-page *, .report-page *::before, .report-page *::after { box-sizing: border-box; }
    .report-page button, .report-page input, .report-page select { font: inherit; }
    .report-page svg.i {
        width: 1em; height: 1em; flex: none;
        fill: none; stroke: currentColor;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
    .report-page :focus-visible { outline: 3px solid var(--cyan); outline-offset: 3px; border-radius: 6px; }
    .rp-shell { width: min(100%, 1500px); margin: 0 auto; }

    /* ---------- Hero ---------- */
    .rp-hero {
        position: relative; isolation: isolate; overflow: hidden;
        display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(0, .75fr);
        align-items: end; gap: clamp(24px, 4vw, 64px);
        padding: clamp(30px, 4.5vw, 60px); border-radius: 26px; color: #fff;
        background: linear-gradient(120deg, var(--navy) 0%, var(--midnight) 60%, #173d86 100%);
        animation: rp-rise .8s var(--ease) both;
    }
    .rp-hero::before {
        content: ""; position: absolute; z-index: -1; right: -90px; top: -110px;
        width: 380px; height: 380px; border-radius: 50%;
        background: var(--royal); opacity: .45; filter: blur(60px);
        animation: rp-drift 24s ease-in-out infinite alternate;
    }
    .rp-hero::after {
        content: ""; position: absolute; z-index: -1; left: 30%; bottom: -120px;
        width: 260px; height: 260px; border-radius: 50%;
        background: var(--cyan); opacity: .14; filter: blur(60px);
    }
    .rp-hero h1 {
        margin: 0 0 16px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.3rem, 5.6vw, 4.4rem); line-height: 1.04; letter-spacing: -.035em;
    }
    .rp-hero h1 span { display: block; color: var(--cyan); }
    .rp-hero p { max-width: 560px; margin: 0 0 22px; font-size: 15px; line-height: 1.8; color: rgba(255,255,255,.82); }
    .rp-chips { display: flex; flex-wrap: wrap; gap: 10px; }
    .rp-chip {
        display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px;
        border: 1px solid rgba(255,255,255,.2); border-radius: 999px;
        background: rgba(255,255,255,.08); font-size: 13px; font-weight: 600; color: #E3EEFB;
    }
    .rp-chip .i { font-size: 15px; color: var(--cyan); }

    /* Statistik di hero: dua angka besar, bukan kartu */
    .rp-hero-stats { display: grid; gap: 0; border-left: 1px solid rgba(255,255,255,.2); padding-left: clamp(20px, 3vw, 40px); }
    .rp-hero-stat { padding: 16px 0; }
    .rp-hero-stat + .rp-hero-stat { border-top: 1px solid rgba(255,255,255,.16); }
    .rp-hero-stat small { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: rgba(255,255,255,.78); }
    .rp-hero-stat small .i { font-size: 17px; color: var(--cyan); }
    .rp-hero-stat strong {
        display: block; margin: 4px 0 2px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.4rem, 5vw, 3.8rem); line-height: 1; letter-spacing: -.04em; font-variant-numeric: tabular-nums;
    }
    .rp-hero-stat span { font-size: 12.5px; color: rgba(255,255,255,.68); }

    /* ---------- Sections ---------- */
    .rp-section { padding-top: clamp(32px, 4.5vw, 52px); }
    .rp-section-head { margin-bottom: 18px; }
    .rp-section-head h2 {
        margin: 0 0 6px; font-family: var(--font-display); font-weight: 700;
        font-size: clamp(1.45rem, 3vw, 2rem); letter-spacing: -.025em; color: var(--navy);
    }
    .rp-section-head p { margin: 0; max-width: 680px; font-size: 14.5px; color: var(--slate); }

    /* ---------- Filter ---------- */
    .rp-filter {
        padding: clamp(18px, 2.6vw, 26px); border: 1px solid var(--border);
        border-radius: 20px; background: #fff; box-shadow: 0 14px 36px rgba(8,27,51,.05);
    }
    .rp-filter-grid { display: grid; grid-template-columns: .8fr .8fr 1.6fr auto; gap: 14px; align-items: end; }
    .rp-field label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: var(--navy); }
    .rp-field input, .rp-field select {
        display: block; width: 100%; min-width: 0; height: 50px; padding: 0 14px;
        color: var(--text); background: var(--ice); border: 1.5px solid var(--border); border-radius: 12px;
        font-size: 14px; transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .rp-field input::placeholder { color: #8796aa; }
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

    /* ---------- Panel & table ---------- */
    .rp-panel {
        min-width: 0; overflow: hidden; border: 1px solid var(--border); border-radius: 22px;
        background: #fff; box-shadow: 0 18px 44px rgba(8,27,51,.07);
    }
    .rp-panel-top {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
        padding: 18px clamp(18px, 2.6vw, 26px); border-bottom: 1px solid var(--border);
    }
    .rp-panel-top h3 { margin: 0; font-family: var(--font-display); font-size: 16px; font-weight: 600; color: var(--navy); }
    .rp-panel-top form { margin: 0; }
    .rp-note {
        margin: 0; padding: 14px clamp(18px, 2.6vw, 26px); border-bottom: 1px solid var(--border);
        background: var(--ice); font-size: 13.5px; line-height: 1.75; color: var(--slate);
    }
    .rp-table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: thin; }
    .rp-table { width: 100%; min-width: 680px; border-collapse: collapse; text-align: left; }
    .rp-table thead th {
        padding: 14px 18px; background: var(--navy); color: #fff;
        font-size: 12px; font-weight: 700; letter-spacing: .06em; white-space: nowrap;
    }
    .rp-table tbody td { padding: 15px 18px; border-bottom: 1px solid #EBF0F6; font-size: 14px; color: #33465C; vertical-align: middle; }
    .rp-table tbody tr { transition: background .2s; }
    .rp-table tbody tr:hover { background: #F5F9FF; }
    .rp-table tbody tr:last-child td { border-bottom: 0; }
    .rp-number { width: 64px; font-weight: 700; color: #7C8DA3 !important; font-variant-numeric: tabular-nums; }
    .rp-participant { font-weight: 700; color: var(--navy) !important; }
    .rp-nip { color: var(--slate) !important; font-variant-numeric: tabular-nums; }
    .rp-count {
        display: inline-grid; place-items: center; min-width: 40px; height: 32px; padding: 0 10px;
        border-radius: 10px; background: var(--soft); color: var(--royal);
        font-family: var(--font-display); font-weight: 700; font-variant-numeric: tabular-nums;
    }
    .rp-status {
        display: inline-flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 999px;
        font-size: 12.5px; font-weight: 700; white-space: nowrap;
    }
    .rp-status .i { font-size: 14px; }
    .rp-status-review { color: var(--warn); background: var(--warn-bg); }
    .rp-status-eligible { color: var(--ok); background: var(--ok-bg); }
    .rp-empty { padding: 44px 18px !important; text-align: center; color: var(--slate) !important; }
    .rp-empty strong { display: block; margin-bottom: 4px; font-family: var(--font-display); font-size: 16px; color: var(--navy); }
    .rp-pagination { padding: 16px clamp(18px, 2.6vw, 26px); border-top: 1px solid var(--border); overflow-x: auto; }
    .rp-pagination:empty { display: none; }
    .rp-pagination svg { width: 17px; height: 17px; }

    /* ---------- Distribusi ---------- */
    .rp-two-columns { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.15fr); gap: 20px; align-items: start; }
    .rp-panel-body { padding: clamp(18px, 2.6vw, 26px); }
    .rp-panel-body h3 { margin: 0 0 4px; font-family: var(--font-display); font-size: 17px; font-weight: 600; color: var(--navy); }
    .rp-panel-body > p { margin: 0 0 18px; font-size: 13.5px; color: var(--slate); }

    .rp-bars { display: flex; flex-direction: column; margin: 0; padding: 0; list-style: none; }
    .rp-bars li { padding: 11px 0; border-top: 1px solid var(--border); }
    .rp-bar-row { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin-bottom: 7px; font-size: 14px; }
    .rp-bar-name { min-width: 0; color: #33465C; overflow-wrap: anywhere; }
    .rp-bar-total { flex: none; font-family: var(--font-display); font-weight: 700; color: var(--navy); font-variant-numeric: tabular-nums; }
    .rp-bar-total small { font-family: var(--font-body); font-weight: 500; font-size: 12px; color: var(--slate); }
    .rp-bar-track { height: 6px; overflow: hidden; border-radius: 999px; background: var(--soft); }
    .rp-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--royal), var(--cyan)); transform-origin: left; animation: rp-grow .9s var(--ease) both; }
    .rp-bar-fill.low { background: linear-gradient(90deg, #E39B3B, #F2C46B); }
    .rp-rank { display: inline-grid; place-items: center; width: 22px; height: 22px; margin-right: 8px; border-radius: 7px; background: var(--soft); color: var(--royal); font-size: 12px; font-weight: 700; }

    .rp-rank-heading { display: flex; align-items: center; gap: 10px; margin: 0 0 4px; font-family: var(--font-display); font-size: 15px; font-weight: 600; color: var(--navy); }
    .rp-rank-heading .ic { display: grid; place-items: center; width: 34px; height: 34px; border-radius: 10px; font-size: 18px; }
    .rp-rank-heading .up { background: var(--ok-bg); color: var(--ok); }
    .rp-rank-heading .down { background: var(--warn-bg); color: var(--warn); }
    .rp-rank-sub { margin: 0 0 6px 44px; font-size: 12.5px; color: var(--slate); }
    .rp-rank-block + .rp-rank-block { margin-top: 26px; }
    .rp-panel-empty { padding: 22px 0; font-size: 13.5px; color: var(--slate); text-align: center; }

    @keyframes rp-rise { from { opacity: 0; transform: translate3d(0, 18px, 0); } to { opacity: 1; transform: none; } }
    @keyframes rp-drift { to { transform: translate3d(-40px, 40px, 0); } }
    @keyframes rp-grow { from { transform: scaleX(0); } to { transform: scaleX(1); } }

    /* ---------- Responsive ---------- */
    @media (max-width: 1100px) {
        .rp-hero { grid-template-columns: 1fr; align-items: start; }
        .rp-hero-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); border-left: 0; padding-left: 0; border-top: 1px solid rgba(255,255,255,.2); padding-top: 8px; }
        .rp-hero-stat + .rp-hero-stat { border-top: 0; padding-left: 24px; border-left: 1px solid rgba(255,255,255,.16); }
        .rp-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .rp-field-search { grid-column: 1 / -1; }
        .rp-filter-actions { grid-column: 1 / -1; }
        .rp-two-columns { grid-template-columns: 1fr; }
    }
    @media (max-width: 700px) {
        .rp-hero-stats { grid-template-columns: 1fr; }
        .rp-hero-stat + .rp-hero-stat { padding-left: 0; border-left: 0; border-top: 1px solid rgba(255,255,255,.16); }
        .rp-filter-grid { grid-template-columns: 1fr; }
        .rp-filter-actions { display: grid; grid-template-columns: 1fr; }
        .rp-btn { width: 100%; }

        /* tabel peserta menjadi kartu per baris */
        .rp-table-wrap.cards { overflow: visible; }
        .rp-table-wrap.cards .rp-table { display: block; min-width: 0; }
        .rp-table-wrap.cards thead { display: none; }
        .rp-table-wrap.cards tbody { display: grid; gap: 12px; padding: 12px; }
        .rp-table-wrap.cards tbody tr { display: block; overflow: hidden; border: 1px solid var(--border); border-radius: 14px; }
        .rp-table-wrap.cards tbody td {
            display: grid; grid-template-columns: 118px minmax(0, 1fr); gap: 10px;
            padding: 11px 14px; font-size: 14px; border-bottom: 1px solid #EDF1F5;
        }
        .rp-table-wrap.cards tbody td[data-label]::before { content: attr(data-label); font-size: 13px; font-weight: 600; color: var(--slate); }
        .rp-table-wrap.cards tbody tr td:last-child { border-bottom: 0; }
        .rp-table-wrap.cards .rp-number { width: auto; }
        .rp-table-wrap.cards td.rp-empty { display: block; }
    }
    @media (prefers-reduced-motion: reduce) {
        .report-page *, .report-page *::before, .report-page *::after { animation: none !important; transition-duration: .01ms !important; scroll-behavior: auto !important; }
    }
</style>

@php
    $districtMax = max(1, (int) collect($districtRecap)->max('total'));
    $topMax = max(1, (int) collect($topNakes)->max('total'));
    $bottomMax = max(1, (int) collect($bottomNakes)->max('total'));
@endphp

<div class="report-page">
    <div class="rp-shell">

        {{-- HERO --}}
        <section class="rp-hero" aria-labelledby="rp-title">
            <div>
                <h1 id="rp-title">Laporan riwayat <span>pelatihan.</span></h1>

                <p>
                    Pantau frekuensi keikutsertaan, distribusi wilayah, dan
                    partisipasi tenaga kesehatan berdasarkan tahun pelatihan.
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
            </div>
        </section>

        {{-- FILTER --}}
        <section class="rp-section" aria-labelledby="rp-filter-title">
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
                                    <option value="{{ $year }}" selected>
                                        {{ $year }}
                                    </option>
                                @endforelse
                            </select>
                        </div>

                        <div class="rp-field">
                            <label for="minimum">Batas Peninjauan</label>
                            <input
                                id="minimum"
                                type="number"
                                name="minimum"
                                min="1"
                                max="100"
                                value="{{ $minimum }}"
                                required
                            >
                        </div>

                        <div class="rp-field rp-field-search">
                            <label for="search">Pencarian Peserta</label>
                            <input
                                id="search"
                                type="search"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Cari nama, NIP, atau NIK"
                            >
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

        {{-- FREKUENSI PESERTA --}}
        <section class="rp-section" aria-labelledby="rp-freq-title">
            <div class="rp-section-head">
                <h2 id="rp-freq-title">Frekuensi pelatihan</h2>
                <p>Jumlah keikutsertaan setiap peserta pada tahun {{ $year }}.</p>
            </div>

            <div class="rp-panel">
                <div class="rp-panel-top">
                    <h3>Frekuensi Pelatihan per Peserta</h3>

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
                    <table class="rp-table">
                        <caption style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">
                            Frekuensi keikutsertaan pelatihan per peserta
                        </caption>
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
                                    <td class="rp-number" data-label="No.">
                                        {{ $participants->firstItem() + $loop->index }}
                                    </td>

                                    <td class="rp-participant" data-label="Nama Peserta">
                                        {{ $participant->name ?: '-' }}
                                    </td>

                                    <td class="rp-nip" data-label="NIP">
                                        {{ $participant->nip ?: '-' }}
                                    </td>

                                    <td data-label="Keikutsertaan">
                                        <span class="rp-count">
                                            {{ (int) $participant->participations_count }}
                                        </span>
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
                                        Belum ada peserta yang mengikuti pelatihan
                                        pada tahun {{ $year }}
                                        @if ($search !== '')
                                            dengan kata kunci "{{ $search }}"
                                        @endif
                                        .
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

        {{-- DISTRIBUSI --}}
        <section class="rp-section" aria-labelledby="rp-dist-title">
            <div class="rp-section-head">
                <h2 id="rp-dist-title">Distribusi dan partisipasi</h2>
                <p>Ringkasan sebaran pelatihan dan partisipasi tenaga kesehatan.</p>
            </div>

            <div class="rp-two-columns">

                {{-- REKAP WILAYAH --}}
                <div class="rp-panel">
                    <div class="rp-panel-body">
                        <h3>Rekap Kabupaten/Kota/Mitra/Mandiri</h3>
                        <p>
                            Jumlah pelatihan berdasarkan wilayah atau mitra
                            pada tahun {{ $year }}.
                        </p>

                        @if (collect($districtRecap)->isEmpty())
                            <div class="rp-panel-empty">
                                Belum ada rekap wilayah atau mitra pada tahun {{ $year }}.
                            </div>
                        @else
                            <ul class="rp-bars">
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

                {{-- PARTISIPASI TENAGA KESEHATAN --}}
                <div class="rp-panel">
                    <div class="rp-panel-body">
                        <h3>Partisipasi Tenaga Kesehatan</h3>
                        <p>
                            Peringkat profesi berdasarkan jumlah peserta unik
                            yang mengikuti pelatihan pada tahun {{ $year }}.
                        </p>

                        <div class="rp-rank-block">
                            <div class="rp-rank-heading">
                                <span class="ic up" aria-hidden="true">
                                    <svg class="i" viewBox="0 0 24 24"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
                                </span>
                                5 Tertinggi
                            </div>
                            <p class="rp-rank-sub">Peserta terbanyak</p>

                            @forelse ($topNakes as $item)
                                @if ($loop->first)<ul class="rp-bars">@endif
                                    <li>
                                        <div class="rp-bar-row">
                                            <span class="rp-bar-name">
                                                <span class="rp-rank">{{ $loop->iteration }}</span>{{ $item->name }}
                                            </span>
                                            <span class="rp-bar-total">
                                                {{ number_format((int) $item->total, 0, ',', '.') }}
                                                <small>org</small>
                                            </span>
                                        </div>
                                        <div class="rp-bar-track" aria-hidden="true">
                                            <div class="rp-bar-fill" style="width: {{ max(2, round(((int) $item->total / $topMax) * 100)) }}%"></div>
                                        </div>
                                    </li>
                                @if ($loop->last)</ul>@endif
                            @empty
                                <div class="rp-panel-empty">Belum ada data tenaga kesehatan.</div>
                            @endforelse
                        </div>

                        <div class="rp-rank-block">
                            <div class="rp-rank-heading">
                                <span class="ic down" aria-hidden="true">
                                    <svg class="i" viewBox="0 0 24 24"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                                </span>
                                5 Terendah
                            </div>
                            <p class="rp-rank-sub">Peserta paling sedikit</p>

                            @forelse ($bottomNakes as $item)
                                @if ($loop->first)<ul class="rp-bars">@endif
                                    <li>
                                        <div class="rp-bar-row">
                                            <span class="rp-bar-name">
                                                <span class="rp-rank">{{ $loop->iteration }}</span>{{ $item->name }}
                                            </span>
                                            <span class="rp-bar-total">
                                                {{ number_format((int) $item->total, 0, ',', '.') }}
                                                <small>org</small>
                                            </span>
                                        </div>
                                        <div class="rp-bar-track" aria-hidden="true">
                                            <div class="rp-bar-fill low" style="width: {{ max(2, round(((int) $item->total / $bottomMax) * 100)) }}%"></div>
                                        </div>
                                    </li>
                                @if ($loop->last)</ul>@endif
                            @empty
                                <div class="rp-panel-empty">Belum ada data tenaga kesehatan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>
</div>

<script>
    (function () {
        // Counter angka statistik. Teks awal sudah berisi nilai asli dari server.
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }

        document.querySelectorAll('.report-page .rp-counter').forEach(function (el) {
            var target = parseInt(el.getAttribute('data-target'), 10) || 0;
            if (target < 1) { return; }

            var fmt = new Intl.NumberFormat('id-ID');
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