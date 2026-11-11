
@extends('layouts.app')

@section('content')

<style>
    .leader-dashboard {
        --navy: #102b4e;
        --blue: #2864ad;
        --teal: #168b91;
        --text: #253b56;
        --muted: #718198;
        --border: #e3eaf2;
        width: 100%;
        padding-bottom: 34px;
        color: var(--text);
        font-family: 'DM Sans', 'Segoe UI', sans-serif;
        font-size: 15px;
    }

    .leader-dashboard *,
    .leader-dashboard *::before,
    .leader-dashboard *::after {
        box-sizing: border-box;
    }

    .leader-dashboard h1,
    .leader-dashboard h2,
    .leader-dashboard h3,
    .leader-dashboard p {
        margin-top: 0;
    }

    .leader-dashboard .page-heading {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 180px;
        align-items: center;
        gap: 30px;
        min-height: 235px;
        margin-bottom: 24px;
        padding: 40px 45px;
        overflow: hidden;
        isolation: isolate;
        color: #fff;
        background: var(--navy);
        border-radius: 22px;
        box-shadow: 0 18px 42px rgba(16, 43, 78, .13);
    }

    .leader-dashboard .page-heading::before {
        content: "";
        position: absolute;
        z-index: -1;
        top: -180px;
        right: 120px;
        width: 450px;
        height: 450px;
        border: 1px solid rgba(255,255,255,.10);
        border-radius: 50%;
        box-shadow:
            0 0 0 45px rgba(255,255,255,.025),
            0 0 0 95px rgba(255,255,255,.02);
    }

    .leader-dashboard .page-heading::after {
        content: "";
        position: absolute;
        z-index: -1;
        right: -65px;
        bottom: -130px;
        width: 340px;
        height: 340px;
        background: var(--blue);
        border-radius: 48% 52% 43% 57%;
        opacity: .28;
        transform: rotate(-25deg);
    }

    .leader-dashboard .heading-copy {
        position: relative;
        z-index: 2;
    }

    .leader-dashboard .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 19px;
        color: #9ddbd9;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
    }

    .leader-dashboard .eyebrow::before {
        content: "";
        width: 30px;
        height: 3px;
        background: #69c9c5;
        border-radius: 10px;
    }

    .leader-dashboard .page-heading h1 {
        margin: 0;
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-size: clamp(34px, 4vw, 48px);
        font-weight: 800;
        line-height: 1.13;
        letter-spacing: -1.8px;
    }

    .leader-dashboard .page-heading p {
        max-width: 650px;
        margin: 17px 0 0;
        color: rgba(255,255,255,.77);
        font-size: 15px;
        line-height: 1.85;
    }

    .leader-dashboard .heading-visual {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 150px;
        height: 150px;
        margin: auto;
        color: #c6f1ee;
        background: rgba(255,255,255,.075);
        border: 1px solid rgba(255,255,255,.17);
        border-radius: 38px;
        transform: rotate(5deg);
    }

    .leader-dashboard .heading-visual svg {
        width: 70px;
        height: 70px;
        transform: rotate(-5deg);
    }

    .leader-dashboard .filter-panel {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 22px;
        margin-bottom: 38px;
        padding: 22px 25px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 17px;
        box-shadow: 0 5px 18px rgba(16,43,78,.035);
    }

    .leader-dashboard .filter-copy {
        min-width: 0;
    }

    .leader-dashboard .filter-copy strong {
        display: block;
        margin-bottom: 5px;
        color: var(--navy);
        font-family: 'Manrope', sans-serif;
        font-size: 16px;
        font-weight: 800;
    }

    .leader-dashboard .filter-copy span {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .leader-dashboard .year-filter {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .leader-dashboard .year-filter label {
        color: #526780;
        font-size: 13px;
        font-weight: 700;
    }

    .leader-dashboard .year-filter select {
        min-width: 145px;
        min-height: 44px;
        padding: 0 35px 0 13px;
        color: var(--navy);
        background: #f8fafc;
        border: 1px solid #dce5ef;
        border-radius: 10px;
        font: inherit;
        font-size: 14px;
        font-weight: 700;
    }

    .leader-dashboard .filter-button {
        min-height: 44px;
        padding: 0 20px;
        color: #fff;
        background: var(--blue);
        border: 0;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }

    .leader-dashboard .filter-button:hover {
        background: var(--navy);
        transform: translateY(-1px);
    }

    .leader-dashboard .filter-empty {
        width: 100%;
        padding: 12px 15px;
        color: #8a5b18;
        background: #fff8e9;
        border: 1px solid #f0dfbd;
        border-radius: 10px;
        font-size: 13px;
    }

    .leader-dashboard .section-intro {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .leader-dashboard .section-kicker {
        display: block;
        margin-bottom: 8px;
        color: var(--teal);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .leader-dashboard .section-intro h2 {
        margin: 0;
        color: var(--navy);
        font-family: 'Manrope', sans-serif;
        font-size: clamp(23px, 2.5vw, 29px);
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: -.8px;
    }

    .leader-dashboard .section-intro p {
        max-width: 540px;
        margin: 8px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.8;
    }

    .leader-dashboard .section-number {
        flex-shrink: 0;
        color: #aab7c7;
        font-family: 'Manrope', sans-serif;
        font-size: 38px;
        font-weight: 800;
    }

    .leader-dashboard .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
        margin-bottom: 42px;
    }

    .leader-dashboard .stat-card {
        --accent: var(--blue);
        --soft: #eaf2fc;
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        min-height: 210px;
        padding: 23px 22px 20px;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 17px;
        box-shadow: 0 5px 18px rgba(16,43,78,.035);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .leader-dashboard .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--accent);
    }

    .leader-dashboard .stat-card:hover {
        box-shadow: 0 15px 30px rgba(16,43,78,.09);
        transform: translateY(-4px);
    }

    .leader-dashboard .stat-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .leader-dashboard .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 49px;
        height: 49px;
        color: var(--accent);
        background: var(--soft);
        border-radius: 15px;
    }

    .leader-dashboard .stat-icon svg {
        width: 25px;
        height: 25px;
    }

    .leader-dashboard .stat-index {
        color: #9aa9bb;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .leader-dashboard .stat-label {
        color: #65768c;
        font-size: 14px;
        font-weight: 700;
    }

    .leader-dashboard .stat-number {
        margin-top: 5px;
        color: var(--navy);
        font-family: 'Manrope', sans-serif;
        font-size: clamp(30px, 3vw, 39px);
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -1.7px;
        font-variant-numeric: tabular-nums;
    }

    .leader-dashboard .stat-note {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: auto;
        padding-top: 15px;
        color: #8290a2;
        border-top: 1px solid #edf1f6;
        font-size: 12px;
        line-height: 1.6;
    }

    .leader-dashboard .stat-note::before {
        content: "";
        width: 7px;
        height: 7px;
        flex: 0 0 7px;
        background: var(--accent);
        border-radius: 50%;
    }

    .leader-dashboard .panel {
        margin-bottom: 25px;
        padding: 30px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 19px;
        box-shadow: 0 5px 20px rgba(16,43,78,.035);
    }

    .leader-dashboard .panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .leader-dashboard .panel-header h2 {
        margin: 0;
        color: var(--navy);
        font-family: 'Manrope', sans-serif;
        font-size: 23px;
        font-weight: 800;
        line-height: 1.4;
    }

    .leader-dashboard .panel-header p {
        max-width: 690px;
        margin: 9px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.85;
    }

    .leader-dashboard .panel-tag {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 11px 15px;
        color: var(--blue);
        background: #eaf2fc;
        border: 1px solid #dce8f8;
        border-radius: 11px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .leader-dashboard .panel-tag svg {
        width: 18px;
        height: 18px;
    }

    .leader-dashboard .distribution-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 42px;
    }

    .leader-dashboard .field-stat {
        min-width: 0;
        padding: 19px 0;
        border-bottom: 1px solid #edf1f6;
    }

    .leader-dashboard .field-stat-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 12px;
    }

    .leader-dashboard .field-stat-line span {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
        color: #3d526d;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .leader-dashboard .field-stat-line span::before {
        content: "";
        width: 9px;
        height: 9px;
        flex: 0 0 9px;
        background: var(--teal);
        border-radius: 3px;
        transform: rotate(45deg);
    }

    .leader-dashboard .field-stat-line strong {
        flex-shrink: 0;
        min-width: 47px;
        padding: 6px 10px;
        color: var(--blue);
        background: #eaf2fc;
        border: 1px solid #dce8f8;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 800;
        text-align: center;
    }

    .leader-dashboard .progress-track {
        width: 100%;
        height: 11px;
        overflow: hidden;
        background: #edf2f7;
        border-radius: 20px;
    }

    .leader-dashboard .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #2864ad 0%, #168b91 100%);
        border-radius: 20px;
    }

    .leader-dashboard .empty-state {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 23px;
        color: #708198;
        background: #f7f9fc;
        border: 1px dashed #d5e0ec;
        border-radius: 13px;
        font-size: 14px;
        line-height: 1.8;
    }

    .leader-dashboard .empty-icon {
        display: flex;
        flex: 0 0 42px;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        color: var(--blue);
        background: #eaf2fc;
        border-radius: 12px;
    }

    .leader-dashboard .empty-icon svg {
        width: 21px;
        height: 21px;
    }

    .leader-dashboard .monitoring-panel {
        position: relative;
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 25px;
        overflow: hidden;
        background: #edf4fb;
        border: 1px solid #dce8f5;
    }

    .leader-dashboard .monitoring-content {
        display: flex;
        align-items: flex-start;
        gap: 19px;
        min-width: 0;
    }

    .leader-dashboard .monitoring-icon {
        display: flex;
        flex: 0 0 55px;
        align-items: center;
        justify-content: center;
        width: 55px;
        height: 55px;
        color: var(--teal);
        background: #fff;
        border: 1px solid #dcebe9;
        border-radius: 16px;
    }

    .leader-dashboard .monitoring-icon svg {
        width: 27px;
        height: 27px;
    }

    .leader-dashboard .monitoring-copy h2 {
        margin: 0;
        color: var(--navy);
        font-family: 'Manrope', sans-serif;
        font-size: 22px;
        font-weight: 800;
    }

    .leader-dashboard .monitoring-copy p {
        max-width: 760px;
        margin: 9px 0 0;
        color: #64778e;
        font-size: 14px;
        line-height: 1.9;
    }

    .leader-dashboard .monitoring-copy strong {
        color: var(--blue);
    }

    .leader-dashboard .monitoring-decoration {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 76px;
        height: 76px;
        color: #fff;
        background: var(--navy);
        border-radius: 23px;
        transform: rotate(-7deg);
    }

    .leader-dashboard .monitoring-decoration svg {
        width: 34px;
        height: 34px;
    }

    .leader-dashboard :focus-visible {
        outline: 3px solid rgba(22,139,145,.65);
        outline-offset: 4px;
    }

    @media (max-width: 900px) {
        .leader-dashboard .filter-panel {
            align-items: flex-start;
            flex-direction: column;
        }

        .leader-dashboard .year-filter {
            width: 100%;
            flex-wrap: wrap;
        }

        .leader-dashboard .distribution-list {
            column-gap: 24px;
        }
    }

    @media (max-width: 640px) {
        .leader-dashboard {
            padding-bottom: 20px;
        }

        .leader-dashboard .page-heading {
            grid-template-columns: 1fr;
            min-height: auto;
            padding: 29px 24px;
            margin-bottom: 20px;
            border-radius: 17px;
        }

        .leader-dashboard .page-heading h1 {
            font-size: clamp(31px, 8vw, 38px);
            letter-spacing: -1.2px;
        }

        .leader-dashboard .page-heading p {
            font-size: 14px;
        }

        .leader-dashboard .heading-visual {
            position: absolute;
            right: 18px;
            bottom: 15px;
            width: 65px;
            height: 65px;
            opacity: .5;
        }

        .leader-dashboard .heading-visual svg {
            width: 32px;
            height: 32px;
        }

        .leader-dashboard .filter-panel {
            padding: 19px;
            margin-bottom: 29px;
        }

        .leader-dashboard .year-filter {
            align-items: stretch;
            flex-direction: column;
        }

        .leader-dashboard .year-filter label {
            margin-bottom: 2px;
        }

        .leader-dashboard .year-filter select,
        .leader-dashboard .filter-button {
            width: 100%;
        }

        .leader-dashboard .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 30px;
        }

        .leader-dashboard .stat-card {
            min-height: 190px;
            padding: 18px 15px;
            border-radius: 14px;
        }

        .leader-dashboard .stat-number {
            font-size: clamp(27px, 7vw, 34px);
        }

        .leader-dashboard .stat-label {
            font-size: 13px;
        }

        .leader-dashboard .panel {
            padding: 22px 18px;
            margin-bottom: 17px;
            border-radius: 15px;
        }

        .leader-dashboard .panel-header {
            flex-direction: column;
            gap: 15px;
        }

        .leader-dashboard .panel-header h2 {
            font-size: 20px;
        }

        .leader-dashboard .distribution-list {
            grid-template-columns: 1fr;
        }

        .leader-dashboard .monitoring-panel {
            grid-template-columns: 1fr;
            padding: 22px 18px;
        }

        .leader-dashboard .monitoring-content {
            gap: 13px;
        }

        .leader-dashboard .monitoring-decoration {
            display: none;
        }
    }

    @media (max-width: 380px) {
        .leader-dashboard .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .leader-dashboard *,
        .leader-dashboard *::before,
        .leader-dashboard *::after {
            transition-duration: .01ms !important;
        }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="leader-dashboard">

    {{-- HEADER --}}
    <header class="page-heading">
        <div class="heading-copy">
            <span class="eyebrow">Dashboard Pimpinan</span>

            <h1>
                Ringkasan<br>
                Pelatihan
            </h1>

            <p>
                Pusat informasi untuk memantau pelaksanaan pelatihan,
                memahami distribusi program, dan melihat perkembangan
                keikutsertaan peserta melalui data SIRAPIK.
            </p>
        </div>

        <div class="heading-visual" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.35"
                 stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="5" rx="1.5"/>
                <rect x="14" y="12" width="7" height="9" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            </svg>
        </div>
    </header>

    {{-- FILTER TAHUN --}}
    <section class="filter-panel" aria-label="Filter tahun dashboard">

        <div class="filter-copy">
            <strong>Filter Periode Pelatihan</strong>
            <span>
                Pilih tahun untuk melihat ringkasan data pelatihan pada periode tersebut.
            </span>
        </div>

        @if ($years->isNotEmpty())
            <form
                action="{{ route('dashboard') }}"
                method="GET"
                class="year-filter"
            >
                <label for="dashboard-year">Tahun Pelatihan</label>

                <select
                    name="year"
                    id="dashboard-year"
                    required
                >
                    @foreach ($years as $availableYear)
                        <option
                            value="{{ $availableYear }}"
                            {{ (int) $year === (int) $availableYear ? 'selected' : '' }}
                        >
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="filter-button">
                    Tampilkan Data
                </button>
            </form>
        @else
            <div class="filter-empty">
                Belum ada tahun aktif. Silakan Super Admin menambahkan dan mengaktifkan
                tahun pada menu Master Data Tahun Pelatihan.
            </div>
        @endif
    </section>

    {{-- STATISTIK UTAMA --}}
    <section aria-labelledby="leader-summary-title">

        <div class="section-intro">
            <div>
                <span class="section-kicker">Ikhtisar sistem</span>

                <h2 id="leader-summary-title">
                    Gambaran Umum Pelatihan Tahun {{ $year }}
                </h2>

                <p>
                    Indikator utama pelatihan dan keikutsertaan peserta
                    pada tahun {{ $year }}.
                </p>
            </div>

            <span class="section-number" aria-hidden="true">01</span>
        </div>

        <div class="stats-grid">

            {{-- TOTAL PESERTA --}}
            <article class="stat-card"
                     style="--accent:#2864ad;--soft:#eaf2fc;">

                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>

                    <span class="stat-index">01 / SDM</span>
                </div>

                <div class="stat-label">
                    Total Peserta Unik
                </div>

                <div class="stat-number">
                    {{ number_format($totalParticipants) }}
                </div>

                <div class="stat-note">
                    Peserta yang mengikuti pelatihan pada tahun {{ $year }}
                </div>
            </article>

            {{-- TOTAL PELATIHAN --}}
            <article class="stat-card"
                     style="--accent:#168b91;--soft:#e8f5f4;">

                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                            <path d="M8 7h8M8 11h7"/>
                        </svg>
                    </div>

                    <span class="stat-index">02 / PROGRAM</span>
                </div>

                <div class="stat-label">
                    Total Pelatihan
                </div>

                <div class="stat-number">
                    {{ number_format($totalTrainings) }}
                </div>

                <div class="stat-note">
                    Jumlah pelaksanaan pelatihan pada tahun {{ $year }}
                </div>
            </article>

        </div>
    </section>

    {{-- DISTRIBUSI KATEGORI PELATIHAN --}}
    <section class="panel" aria-labelledby="field-distribution-title">

        <div class="panel-header">
            <div>
                <span class="section-kicker">Komposisi program</span>

                <h2 id="field-distribution-title">
                    Distribusi Kategori Pelatihan
                </h2>

                <p>
                    Perbandingan jumlah pelaksanaan pelatihan berdasarkan
                    kategori pada tahun {{ $year }}.
                </p>
            </div>

            <div class="panel-tag">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"/>
                    <path d="M7 14h3v4H7zM12 9h3v9h-3zM17 5h3v13h-3z"/>
                </svg>
                Distribusi
            </div>
        </div>

        @php
            $maxFieldTotal = max(1, (int) $fieldStats->max('total'));
        @endphp

        <div class="distribution-list">

            @forelse ($fieldStats as $stat)

                @php
                    $fieldName = $stat->name ?? 'Kategori tidak diketahui';
                    $total = (int) ($stat->total ?? 0);

                    $percentage = min(
                        100,
                        max(0, ($total / $maxFieldTotal) * 100)
                    );
                @endphp

                <div class="field-stat">

                    <div class="field-stat-line">
                        <span>{{ $fieldName }}</span>
                        <strong>{{ number_format($total) }}</strong>
                    </div>

                    <div
                        class="progress-track"
                        role="progressbar"
                        aria-label="Jumlah pelatihan kategori {{ $fieldName }}"
                        aria-valuenow="{{ $total }}"
                        aria-valuemin="0"
                        aria-valuemax="{{ $maxFieldTotal }}"
                    >
                        <div
                            class="progress-fill"
                            style="width: {{ $percentage }}%;"
                        ></div>
                    </div>

                </div>

            @empty

                <div class="empty-state">
                    <div class="empty-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 11v5"/>
                            <path d="M12 8h.01"/>
                        </svg>
                    </div>

                    <div>
                        Belum ada data kategori pelatihan pada tahun {{ $year }}.
                    </div>
                </div>

            @endforelse

        </div>
    </section>

    {{-- CATATAN PEMANTAUAN --}}
    <section class="panel monitoring-panel"
             aria-labelledby="monitoring-title">

        <div class="monitoring-content">

            <div class="monitoring-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"/>
                    <path d="m7 14 4-4 3 3 5-7"/>
                    <path d="M15 6h4v4"/>
                </svg>
            </div>

            <div class="monitoring-copy">
                <span class="section-kicker">Informasi lanjutan</span>

                <h2 id="monitoring-title">
                    Pemantauan Pelaksanaan Pelatihan
                </h2>

                <p>
                    Dashboard ini sedang menampilkan data tahun
                    <strong>{{ $year }}</strong>.
                    Gunakan menu <strong>Laporan Pelatihan</strong> pada sidebar
                    untuk melihat rincian laporan dan memantau pelaksanaan
                    pelatihan berdasarkan data yang tercatat di SIRAPIK.
                </p>
            </div>
        </div>

        <div class="monitoring-decoration" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.6"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19V5"/>
                <path d="M4 19h16"/>
                <path d="m7 15 4-4 3 3 5-7"/>
                <path d="M15 7h4v4"/>
            </svg>
        </div>
    </section>

</div>

@endsection
