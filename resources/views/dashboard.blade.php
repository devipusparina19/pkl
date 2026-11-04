
@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .dashboard-admin {
        --ink: #172b4d;
        --muted: #718096;
        --line: #e5ebf2;
        --blue: #2457a7;
        --teal: #087f8c;
        --pink: #e85d9e;
        font-family: 'Poppins', sans-serif;
        color: var(--ink);
        padding-bottom: 30px;
        font-size: 13px;
    }

    .dashboard-admin *,
    .dashboard-admin *::before,
    .dashboard-admin *::after {
        box-sizing: border-box;
    }

    .dashboard-admin .dashboard-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 32px;
        margin-bottom: 30px;
        color: #fff;
        background:
            radial-gradient(circle at 92% 8%, rgba(86,211,205,.2), transparent 27%),
            linear-gradient(118deg, #172b4d 0%, #204a7e 68%, #176b79 100%);
        border-radius: 17px;
        box-shadow: 0 10px 25px rgba(23,43,77,.1);
    }

    .dashboard-admin .heading-copy {
        max-width: 700px;
    }

    .dashboard-admin .heading-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 12px;
        color: #bce9e8;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
    }

    .dashboard-admin .heading-eyebrow::before {
        content: "";
        width: 22px;
        height: 2px;
        background: #67d5cd;
    }

    .dashboard-admin .dashboard-heading h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(25px, 3vw, 34px);
        font-weight: 800;
        line-height: 1.35;
    }

    .dashboard-admin .dashboard-heading p {
        max-width: 650px;
        margin: 12px 0 0;
        color: rgba(255,255,255,.85);
        font-size: 13px;
        line-height: 1.9;
    }

    .dashboard-admin .heading-mark {
        display: flex;
        flex: 0 0 70px;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 70px;
        color: #d4fbf5;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 19px;
    }

    .dashboard-admin svg {
        width: 20px;
        height: 20px;
    }

    .dashboard-admin .heading-mark svg {
        width: 36px;
        height: 36px;
    }

    .dashboard-admin .section-intro {
        margin-bottom: 18px;
    }

    .dashboard-admin .section-kicker {
        display: block;
        margin-bottom: 6px;
        color: var(--teal);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .dashboard-admin .section-intro h2,
    .dashboard-admin .panel-heading h2 {
        margin: 0;
        color: var(--ink);
        font-size: 19px;
        font-weight: 800;
        line-height: 1.5;
    }

    .dashboard-admin .section-intro p,
    .dashboard-admin .panel-heading p {
        margin: 7px 0 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.8;
    }

    /* KARTU RINGKASAN */
    .dashboard-admin .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
        margin-bottom: 22px;
    }

    .dashboard-admin .stat-card {
        min-width: 0;
        padding: 22px 23px;
        background: #fff;
        border: 1px solid var(--line);
        border-top: 3px solid var(--stat-accent, var(--blue));
        border-radius: 13px;
        box-shadow: 0 4px 15px rgba(23,43,77,.035);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .dashboard-admin .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(23,43,77,.07);
    }

    .dashboard-admin .stat-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 17px;
    }

    .dashboard-admin .stat-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        color: var(--stat-accent, var(--blue));
        background: var(--stat-soft, #eaf1fb);
        border-radius: 12px;
    }

    .dashboard-admin .stat-index {
        color: #9aa8ba;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .7px;
    }

    .dashboard-admin .stat-label {
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
        line-height: 1.7;
    }

    .dashboard-admin .stat-value {
        margin-top: 7px;
        color: var(--ink);
        font-size: 34px;
        font-weight: 800;
        line-height: 1.3;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-admin .stat-caption {
        margin-top: 13px;
        padding-top: 12px;
        color: #8492a6;
        border-top: 1px solid #edf1f6;
        font-size: 11px;
        line-height: 1.8;
    }

    /* DIAGRAM JENIS KELAMIN */
    .dashboard-admin .gender-card {
        margin-bottom: 30px;
        padding: 25px;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 15px;
        box-shadow: 0 4px 16px rgba(23,43,77,.035);
    }

    .dashboard-admin .gender-heading {
        margin-bottom: 20px;
    }

    .dashboard-admin .gender-heading h3 {
        margin: 0;
        color: var(--ink);
        font-size: 17px;
        font-weight: 800;
    }

    .dashboard-admin .gender-heading p {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.8;
    }

    .dashboard-admin .gender-chart-wrapper {
        position: relative;
        width: 100%;
        max-width: 520px;
        height: 300px;
        margin: 0 auto 20px;
    }

    .dashboard-admin .gender-chart-wrapper canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
    }

    .dashboard-admin .gender-legend {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px;
    }

    .dashboard-admin .gender-legend-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f7f9fc;
        border: 1px solid #e7edf4;
        border-radius: 10px;
    }

    .dashboard-admin .gender-dot {
        flex: 0 0 11px;
        width: 11px;
        height: 11px;
        border-radius: 50%;
    }

    .dashboard-admin .gender-legend-label {
        color: var(--muted);
        font-size: 11px;
    }

    .dashboard-admin .gender-legend-value {
        display: block;
        margin-top: 3px;
        color: var(--ink);
        font-size: 17px;
        font-weight: 800;
    }

    .dashboard-admin .gender-total {
        margin-top: 15px;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.8;
        text-align: center;
    }

    /* KARTU KONTEN */
    .dashboard-admin .content-card {
        min-width: 0;
        margin-bottom: 22px;
        padding: 25px;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 15px;
        box-shadow: 0 4px 16px rgba(23,43,77,.035);
    }

    .dashboard-admin .panel-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 20px;
    }

    .dashboard-admin .panel-number {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        padding: 8px 12px;
        color: #526984;
        background: #f2f6fb;
        border: 1px solid #e5edf5;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 700;
    }

    /* FILTER */
    .dashboard-admin .filter-form {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 13px;
        margin-bottom: 20px;
        padding: 17px;
        background: #f7f9fc;
        border: 1px solid #e7edf4;
        border-radius: 11px;
    }

    .dashboard-admin .filter-field {
        display: flex;
        flex: 1 1 190px;
        flex-direction: column;
        gap: 7px;
        min-width: 150px;
    }

    .dashboard-admin .filter-field label {
        color: #526984;
        font-size: 11px;
        font-weight: 700;
    }

    .dashboard-admin .filter-field select {
        width: 100%;
        min-height: 43px;
        padding: 9px 12px;
        color: var(--ink);
        background: #fff;
        border: 1px solid #d9e2ed;
        border-radius: 8px;
        font-family: inherit;
        font-size: 12px;
        outline: none;
    }

    .dashboard-admin .filter-field select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(36,87,167,.1);
    }

    .dashboard-admin .filter-button,
    .dashboard-admin .reset-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 43px;
        padding: 10px 16px;
        border-radius: 8px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .dashboard-admin .filter-button {
        color: #fff;
        background: var(--blue);
        border: 1px solid var(--blue);
    }

    .dashboard-admin .filter-button:hover {
        color: #fff;
        background: #193f7d;
    }

    .dashboard-admin .reset-button {
        color: #526984;
        background: #fff;
        border: 1px solid #d9e2ed;
    }

    .dashboard-admin .reset-button:hover {
        background: #eef3f8;
    }

    /* TABEL */
    .dashboard-admin .table-responsive {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e7edf4;
        border-radius: 10px;
    }

    .dashboard-admin table {
        width: 100%;
        min-width: 500px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        text-align: left;
    }

    .dashboard-admin thead th {
        padding: 14px 16px;
        color: #64758c;
        background: #f5f8fc;
        border-bottom: 1px solid #e2eaf2;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dashboard-admin tbody td {
        padding: 14px 16px;
        color: #43536b;
        border-bottom: 1px solid #edf1f6;
        font-size: 12px;
        line-height: 1.8;
        vertical-align: middle;
    }

    .dashboard-admin tbody tr:last-child td {
        border-bottom: 0;
    }

    .dashboard-admin tbody tr:hover {
        background: #f8fbff;
    }

    .dashboard-admin tbody td:first-child {
        color: #233a5b;
        font-weight: 600;
    }

    .dashboard-admin .number-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        min-height: 29px;
        padding: 5px 10px;
        color: var(--blue);
        background: #eaf1fc;
        border: 1px solid #dce8fa;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
    }

    .dashboard-admin .participant-name {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dashboard-admin .participant-icon {
        display: inline-flex;
        flex: 0 0 34px;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        color: var(--teal);
        background: #e6f6f5;
        border-radius: 9px;
    }

    .dashboard-admin .empty-state {
        padding: 28px 17px;
        color: #8391a5;
        text-align: center;
        font-size: 12px;
        line-height: 1.8;
    }

    /* PAGINATION TABEL */
    .dashboard-admin .table-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
    }

    .dashboard-admin .pagination-info {
        color: var(--muted);
        font-size: 11px;
    }

    .dashboard-admin .pagination-controls {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
    }

    .dashboard-admin .page-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        color: #526984;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 7px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
    }

    .dashboard-admin .page-button:hover:not(:disabled) {
        color: var(--blue);
        background: #edf3fc;
        border-color: #cbdaf0;
    }

    .dashboard-admin .page-button:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    .dashboard-admin .page-button.active {
        color: #fff;
        background: var(--blue);
        border-color: var(--blue);
    }

    /* CATATAN DAN PROFESI */
    .dashboard-admin .info-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 17px;
        padding: 14px 15px;
        color: #526984;
        background: #f1f7fc;
        border: 1px solid #e0ebf5;
        border-left: 3px solid #4b8cc8;
        border-radius: 8px;
        font-size: 11px;
        line-height: 1.8;
    }

    .dashboard-admin .info-note strong {
        color: #294967;
    }

    .dashboard-admin .profession-summary {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-bottom: 16px;
    }

    .dashboard-admin .profession-chip {
        display: inline-flex;
        align-items: center;
        padding: 8px 11px;
        color: #087f8c;
        background: #e6f6f5;
        border: 1px solid #ccecea;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    .dashboard-admin :focus-visible {
        outline: 3px solid rgba(8,127,140,.4);
        outline-offset: 3px;
    }

    /* TIDAK ADA DATA */
    .dashboard-admin .no-year-data {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 190px;
        margin-bottom: 25px;
        padding: 30px;
        background: #fff;
        border: 1px dashed #cbd7e5;
        border-radius: 13px;
        text-align: center;
    }

    .dashboard-admin .no-year-data .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        color: #7890ad;
        background: #edf3f9;
        border-radius: 13px;
    }

    .dashboard-admin .no-year-data h3 {
        margin: 0;
        color: var(--ink);
        font-size: 15px;
        font-weight: 700;
    }

    .dashboard-admin .no-year-data p {
        margin: 7px 0 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.8;
    }

    @media (max-width: 900px) {
        .dashboard-admin .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .dashboard-admin .dashboard-heading {
            align-items: flex-start;
            gap: 14px;
            padding: 23px 19px;
        }

        .dashboard-admin .dashboard-heading h1 {
            font-size: 24px;
        }

        .dashboard-admin .dashboard-heading p {
            font-size: 11px;
        }

        .dashboard-admin .heading-mark {
            flex-basis: 45px;
            width: 45px;
            height: 45px;
            border-radius: 11px;
        }

        .dashboard-admin .heading-mark svg {
            width: 24px;
            height: 24px;
        }

        .dashboard-admin .stats-grid {
            gap: 11px;
        }

        .dashboard-admin .stat-card {
            padding: 16px 14px;
        }

        .dashboard-admin .stat-value {
            font-size: 27px;
        }

        .dashboard-admin .stat-label {
            font-size: 11px;
        }

        .dashboard-admin .gender-card,
        .dashboard-admin .content-card {
            padding: 17px 14px;
        }

        .dashboard-admin .gender-chart-wrapper {
            height: 250px;
        }

        .dashboard-admin .gender-legend {
            grid-template-columns: 1fr;
        }

        .dashboard-admin .panel-heading {
            flex-direction: column;
            gap: 10px;
        }

        .dashboard-admin .panel-heading h2,
        .dashboard-admin .section-intro h2 {
            font-size: 16px;
        }

        .dashboard-admin .filter-form {
            align-items: stretch;
        }

        .dashboard-admin .filter-field {
            flex-basis: 100%;
        }

        .dashboard-admin .filter-button,
        .dashboard-admin .reset-button {
            flex: 1;
        }

        .dashboard-admin .table-pagination {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 380px) {
        .dashboard-admin .stats-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-admin .heading-mark {
            display: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .dashboard-admin *,
        .dashboard-admin *::before,
        .dashboard-admin *::after {
            transition-duration: .01ms !important;
        }
    }
</style>

@php
    $totalParticipants = (int) ($totalParticipants ?? 0);
    $totalTrainings = (int) ($totalTrainings ?? 0);

    $year = $year ?? now()->year;
    $years = $years ?? collect([$year]);
    $sort = $sort ?? 'desc';
    $selectedProfession = $selectedProfession ?? '';

    $frequentParticipants = $frequentParticipants ?? collect();
    $professionOptions = $professionOptions ?? collect();
    $trainedParticipants = $trainedParticipants ?? collect();

    $genderStats = $genderStats ?? ['L' => 0, 'P' => 0];

    $maleCount = (int) ($genderStats['L'] ?? 0);
    $femaleCount = (int) ($genderStats['P'] ?? 0);
    $genderTotal = $maleCount + $femaleCount;

    $malePercent = $genderTotal > 0
        ? round(($maleCount / $genderTotal) * 100, 1)
        : 0;

    $femalePercent = $genderTotal > 0
        ? round(($femaleCount / $genderTotal) * 100, 1)
        : 0;

    $hasYearData = $totalParticipants > 0 || $totalTrainings > 0;
@endphp

<div class="dashboard-admin">

    {{-- HEADER DASHBOARD --}}
    <header class="dashboard-heading">
        <div class="heading-copy">
            <div class="heading-eyebrow">Sistem Informasi Pelatihan</div>
            <h1>Dashboard Admin SIRAPIK</h1>
            <p>
                Pusat informasi pengelolaan peserta dan pelatihan.
                Pantau ringkasan data serta riwayat keikutsertaan peserta
                berdasarkan tahun dan jenis profesi.
            </p>
        </div>

        <div class="heading-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="5" rx="1.5"/>
                <rect x="14" y="12" width="7" height="9" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            </svg>
        </div>
    </header>

    {{-- RINGKASAN DATA --}}
    <section aria-labelledby="summary-title">
        <div class="section-intro">
            <span class="section-kicker">Ringkasan data</span>
            <h2 id="summary-title">Gambaran Umum SIRAPIK</h2>
            <p>Informasi peserta dan pelatihan pada tahun {{ $year }}.</p>
        </div>

        @if ($hasYearData)

            <div class="stats-grid">

                {{-- TOTAL PESERTA --}}
                <div class="stat-card"
                     style="--stat-accent:#2457a7;--stat-soft:#eaf1fc;">
                    <div class="stat-topline">
                        <span class="stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round">
                                <circle cx="10" cy="7" r="4"/>
                                <path d="M2 21v-2a8 8 0 0 1 16 0v2"/>
                                <path d="M19 8a3 3 0 0 1 0 6M22 21v-2a5 5 0 0 0-3-4.58"/>
                            </svg>
                        </span>
                        <span class="stat-index">01 / PESERTA</span>
                    </div>

                    <div class="stat-label">Peserta Terlatih Tahun {{ $year }}</div>
                    <div class="stat-value">
                        {{ number_format($totalParticipants, 0, ',', '.') }}
                    </div>
                    <div class="stat-caption">
                        Jumlah peserta unik yang mengikuti pelatihan pada tahun terpilih.
                    </div>
                </div>

                {{-- TOTAL PELATIHAN --}}
                <div class="stat-card"
                     style="--stat-accent:#087f8c;--stat-soft:#e5f6f4;">
                    <div class="stat-topline">
                        <span class="stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                                <path d="M8 7h8M8 11h7"/>
                            </svg>
                        </span>
                        <span class="stat-index">02 / PROGRAM</span>
                    </div>

                    <div class="stat-label">Total Pelatihan Tahun {{ $year }}</div>
                    <div class="stat-value">
                        {{ number_format($totalTrainings, 0, ',', '.') }}
                    </div>
                    <div class="stat-caption">
                        Jumlah program pelatihan yang dilaksanakan pada tahun terpilih.
                    </div>
                </div>

            </div>

            {{-- DIAGRAM JENIS KELAMIN --}}
            @if ($genderTotal > 0)
                <div class="gender-card">
                    <div class="gender-heading">
                        <span class="section-kicker">Komposisi peserta</span>
                        <h3>Persentase Jenis Kelamin Peserta Terlatih</h3>
                        <p>
                            Perbandingan jumlah peserta laki-laki dan perempuan
                            pada tahun {{ $year }}.
                        </p>
                    </div>

                    <div class="gender-chart-wrapper">
                        <canvas
                            id="genderPieChart"
                            role="img"
                            aria-label="Diagram lingkaran jenis kelamin peserta tahun {{ $year }}">
                        </canvas>
                    </div>

                    <div class="gender-legend">
                        <div class="gender-legend-item">
                            <span class="gender-dot" style="background:#2457a7;"></span>
                            <div>
                                <div class="gender-legend-label">Laki-laki</div>
                                <span class="gender-legend-value">
                                    {{ number_format($malePercent, 1, ',', '.') }}%
                                    ({{ number_format($maleCount, 0, ',', '.') }} peserta)
                                </span>
                            </div>
                        </div>

                        <div class="gender-legend-item">
                            <span class="gender-dot" style="background:#e85d9e;"></span>
                            <div>
                                <div class="gender-legend-label">Perempuan</div>
                                <span class="gender-legend-value">
                                    {{ number_format($femalePercent, 1, ',', '.') }}%
                                    ({{ number_format($femaleCount, 0, ',', '.') }} peserta)
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="gender-total">
                        Total peserta berdasarkan jenis kelamin:
                        <strong>{{ number_format($genderTotal, 0, ',', '.') }} peserta</strong>
                    </div>
                </div>
            @endif

        @else

            <div class="no-year-data">
                <div class="empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.7" stroke-linecap="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v4m0 4h.01"/>
                    </svg>
                </div>
                <h3>Data Tahun {{ $year }} Belum Tersedia</h3>
                <p>
                    Belum terdapat data peserta maupun pelatihan pada tahun ini.
                    Ringkasan dan diagram akan ditampilkan setelah data tersedia.
                </p>
            </div>

        @endif
    </section>

    {{-- JUMLAH KEIKUTSERTAAN PESERTA --}}
    <section class="content-card" aria-labelledby="frequent-title">
        <div class="panel-heading">
            <div>
                <span class="section-kicker">Pemantauan peserta</span>
                <h2 id="frequent-title">Jumlah Keikutsertaan Peserta dalam Pelatihan</h2>
                <p>
                    Lihat jumlah pelatihan yang diikuti setiap peserta
                    berdasarkan tahun yang dipilih.
                </p>
            </div>
            <div class="panel-number">Data peserta</div>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="filter-form">
            <div class="filter-field">
                <label for="participant-year">Tahun Pelatihan</label>
                <select id="participant-year" name="year">
                    @foreach ($years as $availableYear)
                        <option value="{{ $availableYear }}"
                            {{ (int) $year === (int) $availableYear ? 'selected' : '' }}>
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field">
                <label for="participant-sort">Urutan Jumlah Pelatihan</label>
                <select id="participant-sort" name="sort">
                    <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>
                        Jumlah Terendah
                    </option>
                    <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>
                        Jumlah Tertinggi
                    </option>
                </select>
            </div>

            <button type="submit" class="filter-button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round">
                    <path d="M3 4h18l-7 8v6l-4 2v-8L3 4Z"/>
                </svg>
                Terapkan
            </button>

            <a href="{{ url()->current() }}" class="reset-button">Reset</a>
        </form>

        <div class="table-responsive">
            <table class="paginated-table" id="participantTable">
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>NIP</th>
                        <th>Jumlah Pelatihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($frequentParticipants as $participant)
                        <tr>
                            <td>{{ $participant->name ?? '-' }}</td>
                            <td>{{ $participant->nip ?? '-' }}</td>
                            <td>
                                <span class="number-pill">
                                    {{ number_format((int) ($participant->participations_count ?? 0), 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">
                                Belum ada data peserta untuk tahun {{ $year }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-pagination" id="participantPagination">
            <div class="pagination-info"></div>
            <div class="pagination-controls"></div>
        </div>

        <div class="info-note">
            <span aria-hidden="true">ⓘ</span>
            <div>
                <strong>Catatan:</strong>
                Jumlah pelatihan dihitung berdasarkan riwayat keikutsertaan
                peserta pada tahun yang dipilih. Gunakan pilihan urutan untuk
                melihat jumlah terendah atau tertinggi.
            </div>
        </div>
    </section>

    {{-- PESERTA BERDASARKAN PROFESI --}}
    <section class="content-card" aria-labelledby="profession-title">
        <div class="panel-heading">
            <div>
                <span class="section-kicker">Data peserta terlatih</span>
                <h2 id="profession-title">Peserta Terlatih Berdasarkan Jenis Profesi</h2>
                <p>
                    Pilih tahun dan profesi untuk melihat daftar peserta
                    yang mengikuti pelatihan.
                </p>
            </div>
            <div class="panel-number">Data profesi</div>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="filter-form">
            <div class="filter-field">
                <label for="profession-year">Tahun Pelatihan</label>
                <select id="profession-year" name="year" required>
                    @foreach ($years as $availableYear)
                        <option value="{{ $availableYear }}"
                            {{ (int) $year === (int) $availableYear ? 'selected' : '' }}>
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field">
                <label for="profession">Jenis Profesi</label>
                <select id="profession" name="profession" required>
                    <option value="">-- Pilih Profesi --</option>
                    @foreach ($professionOptions as $professionOption)
                        <option value="{{ $professionOption }}"
                            {{ mb_strtolower(trim((string) $selectedProfession)) === mb_strtolower(trim((string) $professionOption)) ? 'selected' : '' }}>
                            {{ $professionOption }}
                        </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="sort" value="{{ $sort }}">

            <button type="submit" class="filter-button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round">
                    <path d="M3 4h18l-7 8v6l-4 2v-8L3 4Z"/>
                </svg>
                Tampilkan Peserta
            </button>

            <a href="{{ url()->current() }}" class="reset-button">Reset</a>
        </form>

        @if ($selectedProfession !== '')

            <div class="profession-summary">
                <span class="profession-chip">
                    Profesi: {{ $selectedProfession }}
                </span>
                <span class="profession-chip">
                    Tahun: {{ $year }}
                </span>
                <span class="profession-chip">
                    Jumlah peserta: {{ $trainedParticipants->count() }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="paginated-table" id="professionTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Peserta</th>
                            <th>NIP</th>
                            <th>Profesi</th>
                            <th>Jumlah Pelatihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trainedParticipants as $index => $participant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="participant-name">
                                        <span class="participant-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round">
                                                <circle cx="12" cy="8" r="4"/>
                                                <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                                            </svg>
                                        </span>
                                        {{ $participant->name ?? '-' }}
                                    </div>
                                </td>
                                <td>{{ $participant->nip ?? '-' }}</td>
                                <td>{{ $participant->profession ?? '-' }}</td>
                                <td>
                                    <span class="number-pill">
                                        {{ number_format((int) ($participant->participations_count ?? 0), 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    Tidak ditemukan peserta dengan profesi
                                    "{{ $selectedProfession }}" pada tahun {{ $year }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-pagination" id="professionPagination">
                <div class="pagination-info"></div>
                <div class="pagination-controls"></div>
            </div>

        @else

            <div class="empty-state">
                Pilih tahun dan jenis profesi, kemudian klik
                <strong>Tampilkan Peserta</strong> untuk melihat daftar peserta.
            </div>

        @endif
    </section>

</div>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* DIAGRAM PIE JENIS KELAMIN */
    const canvas = document.getElementById('genderPieChart');

    if (canvas && typeof Chart !== 'undefined') {
        const maleCount = Number(@json($maleCount));
        const femaleCount = Number(@json($femaleCount));
        const total = maleCount + femaleCount;

        new Chart(canvas, {
            // Menggunakan pie agar bagian tengah diagram tidak kosong.
            type: 'pie',

            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [maleCount, femaleCount],

                    // Biru untuk laki-laki dan pink untuk perempuan.
                    backgroundColor: [
                        '#2457a7',
                        '#e85d9e'
                    ],

                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 8
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    // Legenda bawaan dimatikan karena sudah ada
                    // keterangan khusus di bawah diagram.
                    legend: {
                        display: false
                    },

                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value = Number(context.raw) || 0;

                                const percentage = total > 0
                                    ? (value / total * 100).toLocaleString(
                                        'id-ID',
                                        { maximumFractionDigits: 1 }
                                    )
                                    : '0';

                                return context.label + ': '
                                    + value.toLocaleString('id-ID')
                                    + ' peserta ('
                                    + percentage
                                    + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    /* PAGINATION TABEL */
    function setupPagination(tableId, paginationId, pageSize = 8) {
        const table = document.getElementById(tableId);
        const pagination = document.getElementById(paginationId);

        if (!table || !pagination) return;

        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const info = pagination.querySelector('.pagination-info');
        const controls = pagination.querySelector('.pagination-controls');

        // Baris pesan kosong tidak perlu pagination.
        if (
            rows.length === 0 ||
            (rows.length === 1 && rows[0].querySelector('.empty-state'))
        ) {
            pagination.style.display = 'none';
            return;
        }

        let currentPage = 1;
        const totalPages = Math.ceil(rows.length / pageSize);

        function renderPage() {
            const start = (currentPage - 1) * pageSize;
            const end = Math.min(start + pageSize, rows.length);

            rows.forEach((row, index) => {
                row.style.display = index >= start && index < end ? '' : 'none';
            });

            info.textContent =
                'Menampilkan ' + (start + 1) + '–' + end +
                ' dari ' + rows.length + ' peserta';

            controls.innerHTML = '';

            const previous = document.createElement('button');
            previous.type = 'button';
            previous.className = 'page-button';
            previous.textContent = 'Sebelumnya';
            previous.disabled = currentPage === 1;

            previous.addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    renderPage();
                }
            });

            controls.appendChild(previous);

            for (let page = 1; page <= totalPages; page++) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'page-button' +
                    (page === currentPage ? ' active' : '');
                button.textContent = page;

                button.addEventListener('click', function () {
                    currentPage = page;
                    renderPage();
                });

                controls.appendChild(button);
            }

            const next = document.createElement('button');
            next.type = 'button';
            next.className = 'page-button';
            next.textContent = 'Berikutnya';
            next.disabled = currentPage === totalPages;

            next.addEventListener('click', function () {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderPage();
                }
            });

            controls.appendChild(next);
        }

        if (rows.length <= pageSize) {
            pagination.style.display = 'none';
            return;
        }

        renderPage();
    }

    setupPagination('participantTable', 'participantPagination', 8);
    setupPagination('professionTable', 'professionPagination', 8);

});
</script>
@endsection