@extends('layouts.app')

@section('title', 'Laporan Pelatihan')

@section('content')

<style>
    /* ==========================================
       SIRAPIK - TRAINING REPORT
       Institutional Blue Executive Design
    ========================================== */

    .report-page {
        --report-navy: #102b50;
        --report-blue: #1765c1;
        --report-blue-dark: #104c98;
        --report-cyan: #32a9d6;
        --report-bg: #f3f7fc;
        --report-border: #e1eaf4;
        --report-text: #23364d;
        --report-muted: #718198;

        width: 100%;
        max-width: none;
        color: var(--report-text);
        font-family: inherit;
        animation: reportAppear .45s ease both;
    }

    .report-page *,
    .report-page *::before,
    .report-page *::after {
        box-sizing: border-box;
    }

    @keyframes reportAppear {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .report-page button,
    .report-page input,
    .report-page a {
        font: inherit;
    }

    /* HEADER */

    .report-header {
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        padding: 36px 40px;
        margin-bottom: 25px;
        border-radius: 20px;
        background: var(--report-navy);
        color: white;
        isolation: isolate;
    }

    .report-header::before {
        content: "";
        position: absolute;
        z-index: -1;
        width: 340px;
        height: 340px;
        right: 8%;
        top: -210px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        box-shadow:
            0 0 0 35px rgba(255,255,255,.035),
            0 0 0 75px rgba(255,255,255,.025);
    }

    .report-header::after {
        content: "";
        position: absolute;
        z-index: -1;
        width: 220px;
        height: 220px;
        right: -70px;
        bottom: -150px;
        border-radius: 50%;
        background: rgba(50,169,214,.16);
    }

    .report-eyebrow {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 13px;
        color: #91c9f4;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .report-eyebrow-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #51c7ed;
        box-shadow: 0 0 12px rgba(81,199,237,.7);
    }

    .report-header h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(27px, 3.3vw, 39px);
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -.8px;
    }

    .report-header p {
        max-width: 650px;
        margin: 12px 0 0;
        color: #c4d5e9;
        font-size: 16px;
        line-height: 1.7;
    }

    .report-header-icon {
        display: flex;
        flex-shrink: 0;
        justify-content: center;
        align-items: center;
        width: 86px;
        height: 86px;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 22px;
        background: rgba(255,255,255,.09);
        color: white;
    }

    .report-header-icon svg {
        width: 43px;
        height: 43px;
    }

    /* STATISTICS */

    .report-stats {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(0, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .report-stat {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        min-height: 165px;
        padding: 27px 30px;
        border: 1px solid var(--report-border);
        border-radius: 18px;
        background: #fff;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .report-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(26,67,112,.09);
    }

    .report-stat-primary {
        border: none;
        background: #1765c1;
        color: white;
    }

    .report-stat-primary::after {
        content: "";
        position: absolute;
        width: 190px;
        height: 190px;
        right: -55px;
        bottom: -125px;
        border: 30px solid rgba(255,255,255,.09);
        border-radius: 50%;
    }

    .report-stat-label {
        margin-bottom: 13px;
        color: var(--report-muted);
        font-size: 14px;
        font-weight: 700;
    }

    .report-stat-primary .report-stat-label {
        color: #d7e9ff;
    }

    .report-stat-value {
        color: var(--report-navy);
        font-size: clamp(35px, 4vw, 48px);
        line-height: 1;
        font-weight: 850;
        letter-spacing: -1.5px;
    }

    .report-stat-primary .report-stat-value {
        color: white;
    }

    .report-stat-note {
        margin-top: 12px;
        color: var(--report-muted);
        font-size: 13px;
    }

    .report-stat-primary .report-stat-note {
        color: #d7e9ff;
    }

    .report-stat-icon {
        position: relative;
        z-index: 1;
        display: flex;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        width: 62px;
        height: 62px;
        border-radius: 17px;
        background: #eaf3ff;
        color: var(--report-blue);
    }

    .report-stat-primary .report-stat-icon {
        background: rgba(255,255,255,.14);
        color: white;
    }

    .report-stat-icon svg {
        width: 29px;
        height: 29px;
    }

    /* GENERAL PANELS */

    .report-panel {
        width: 100%;
        margin-bottom: 24px;
        padding: 28px 30px;
        border: 1px solid var(--report-border);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(27,57,91,.035);
    }

    .report-panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 23px;
    }

    .report-panel-heading h2 {
        margin: 0;
        color: var(--report-navy);
        font-size: 21px;
        line-height: 1.4;
        font-weight: 800;
    }

    .report-panel-heading p {
        margin: 7px 0 0;
        color: var(--report-muted);
        font-size: 14px;
        line-height: 1.7;
    }

    .report-section-index {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 39px;
        height: 39px;
        border-radius: 12px;
        background: #eaf3ff;
        color: var(--report-blue);
        font-size: 15px;
        font-weight: 800;
    }

    /* FILTER */

    .report-filter-grid {
        display: grid;
        grid-template-columns: .8fr .8fr 1.5fr;
        gap: 17px;
        align-items: end;
    }

    .report-field {
        min-width: 0;
    }

    .report-field label {
        display: block;
        margin-bottom: 9px;
        color: #31445d;
        font-size: 14px;
        font-weight: 750;
    }

    .report-input-wrap {
        position: relative;
    }

    .report-input-wrap svg {
        position: absolute;
        top: 50%;
        left: 15px;
        width: 18px;
        height: 18px;
        color: #8495aa;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .report-field input {
        display: block;
        width: 100%;
        min-width: 0;
        height: 49px;
        padding: 0 14px;
        border: 1px solid #dce6f1;
        border-radius: 11px;
        outline: none;
        background: #f9fbfe;
        color: var(--report-navy);
        font-size: 15px;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }

    .report-input-wrap input {
        padding-left: 43px;
    }

    .report-field input:focus {
        border-color: var(--report-blue);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(23,101,193,.10);
    }

    .report-filter-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 11px;
        margin-top: 22px;
    }

    .report-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 9px;
        min-height: 47px;
        padding: 0 21px;
        border: 1px solid transparent;
        border-radius: 10px;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 750;
        transition: background .2s, transform .2s, box-shadow .2s;
    }

    .report-btn:hover {
        transform: translateY(-2px);
    }

    .report-btn svg {
        width: 18px;
        height: 18px;
    }

    .report-btn-primary {
        background: var(--report-blue);
        color: #fff;
        box-shadow: 0 5px 12px rgba(23,101,193,.15);
    }

    .report-btn-primary:hover {
        background: var(--report-blue-dark);
        box-shadow: 0 8px 18px rgba(23,101,193,.2);
    }

    .report-btn-light {
        border-color: #dce6f1;
        background: #fff;
        color: #43556d;
    }

    .report-btn-light:hover {
        background: #f1f6fc;
    }

    .report-btn-export {
        background: #eaf6ff;
        color: #125ba8;
        border-color: #d2e9fc;
    }

    .report-btn-export:hover {
        background: #dcefff;
    }

    /* TABLE */

    .report-table-description {
        max-width: 900px;
        margin: -9px 0 23px;
        color: var(--report-muted);
        font-size: 14px;
        line-height: 1.8;
    }

    .report-table-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 15px;
    }

    .report-table-caption {
        color: #64758b;
        font-size: 13px;
        font-weight: 650;
    }

    .report-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid var(--report-border);
        border-radius: 13px;
        scrollbar-width: thin;
    }

    .report-table {
        width: 100%;
        min-width: 690px;
        border-collapse: separate;
        border-spacing: 0;
        text-align: left;
    }

    .report-table thead th {
        padding: 17px 18px;
        border-bottom: 1px solid #dfe9f4;
        background: #f1f6fc;
        color: #52667f;
        font-size: 12px;
        font-weight: 850;
        letter-spacing: .65px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .report-table tbody td {
        padding: 17px 18px;
        border-bottom: 1px solid #edf1f6;
        color: #344860;
        font-size: 14px;
        vertical-align: middle;
    }

    .report-table tbody tr:last-child td {
        border-bottom: none;
    }

    .report-table tbody tr {
        transition: background .18s ease;
    }

    .report-table tbody tr:hover {
        background: #f6faff;
    }

    .report-number {
        color: #8190a3 !important;
        font-weight: 700;
    }

    .report-participant-name {
        color: var(--report-navy) !important;
        font-weight: 800;
    }

    .report-nip {
        color: #718198 !important;
        font-variant-numeric: tabular-nums;
    }

    .report-count {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-width: 43px;
        height: 34px;
        padding: 0 10px;
        border-radius: 9px;
        background: #edf5ff;
        color: #1765c1;
        font-size: 15px;
        font-weight: 850;
        font-variant-numeric: tabular-nums;
    }

    .report-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .report-status::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .report-status-limit {
        background: #fff4e7;
        color: #a65a08;
    }

    .report-status-eligible {
        background: #e8f7ef;
        color: #18804b;
    }

    .report-empty {
        padding: 48px 20px !important;
        color: #8090a4 !important;
        text-align: center;
    }

    .report-empty-icon {
        display: flex;
        justify-content: center;
        margin-bottom: 13px;
        color: #9aacc1;
    }

    .report-empty-icon svg {
        width: 36px;
        height: 36px;
    }

    /* PAGINATION */

    .report-pagination {
        display: flex;
        justify-content: flex-end;
        margin-top: 22px;
    }

    .report-pagination nav {
        width: 100%;
    }

    .report-pagination svg {
        width: 18px;
        height: 18px;
    }

    .report-pagination a,
    .report-pagination span {
        transition: background .2s, color .2s;
    }

    /* RESPONSIVE */

    @media (max-width: 1000px) {
        .report-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .report-field-search {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 700px) {
        .report-header {
            padding: 27px 23px;
            border-radius: 15px;
        }

        .report-header-icon {
            width: 59px;
            height: 59px;
            border-radius: 15px;
        }

        .report-header-icon svg {
            width: 30px;
            height: 30px;
        }

        .report-header p {
            font-size: 14px;
        }

        .report-stats {
            grid-template-columns: 1fr;
            gap: 13px;
        }

        .report-stat {
            min-height: 135px;
            padding: 23px;
        }

        .report-panel {
            padding: 22px 17px;
            border-radius: 14px;
        }

        .report-panel-heading h2 {
            font-size: 19px;
        }

        .report-filter-grid {
            grid-template-columns: 1fr;
        }

        .report-field-search {
            grid-column: auto;
        }

        .report-filter-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .report-filter-actions .report-btn {
            width: 100%;
            padding: 0 10px;
        }

        .report-table-topline {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 420px) {
        .report-header {
            padding: 23px 18px;
        }

        .report-header-icon {
            display: none;
        }

        .report-filter-actions {
            grid-template-columns: 1fr;
        }

        .report-stat-icon {
            width: 49px;
            height: 49px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .report-page *,
        .report-page *::before,
        .report-page *::after {
            animation-duration: .01ms !important;
            transition-duration: .01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>

<div class="report-page">

    {{-- =====================================
         HEADER
    ====================================== --}}

    <section class="report-header">
        <div>
            <div class="report-eyebrow">
                <span class="report-eyebrow-dot"></span>
                SIRAPIK / PUSAT LAPORAN
            </div>

            <h1>Laporan Riwayat Pelatihan</h1>

            <p>
                Pantau frekuensi keikutsertaan, distribusi kesempatan,
                dan aktivitas pelatihan peserta berdasarkan tahun.
            </p>
        </div>

        <div class="report-header-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                <path d="M8 7h8"/>
                <path d="M8 11h8"/>
                <path d="M8 15h5"/>
            </svg>
        </div>
    </section>


    {{-- =====================================
         STATISTICS
    ====================================== --}}

    <section class="report-stats">

        <article class="report-stat report-stat-primary">
            <div>
                <div class="report-stat-label">
                    Total Keikutsertaan {{ $year }}
                </div>

                <div class="report-stat-value">
                    {{ number_format((int) $totalParticipations, 0, ',', '.') }}
                </div>

                <div class="report-stat-note">
                    Jumlah seluruh riwayat keikutsertaan pada tahun terpilih
                </div>
            </div>

            <div class="report-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.7"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="10" cy="7" r="4"/>
                    <path d="M20 8v6"/>
                    <path d="M23 11h-6"/>
                </svg>
            </div>
        </article>

        <article class="report-stat">
            <div>
                <div class="report-stat-label">
                    Peserta Perlu Ditinjau
                </div>

                <div class="report-stat-value">
                    {{ number_format((int) $participantsToReview, 0, ',', '.') }}
                </div>

                <div class="report-stat-note">
                    Batas indikator: {{ $minimum }} pelatihan per tahun
                </div>
            </div>

            <div class="report-stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.7"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>
                    <path d="M10.3 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                </svg>
            </div>
        </article>

    </section>


    {{-- =====================================
         FILTER
    ====================================== --}}

    <section class="report-panel">

        <div class="report-panel-heading">
            <div>
                <h2>Filter Laporan</h2>
                <p>
                    Sesuaikan periode dan pencarian untuk menemukan
                    data peserta yang dibutuhkan.
                </p>
            </div>

            <div class="report-section-index">01</div>
        </div>

        <form method="GET" action="{{ route('reports.index') }}">

            <div class="report-filter-grid">

                <div class="report-field">
                    <label for="year">Tahun Pelatihan</label>

                    <div class="report-input-wrap">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>

                        <input
                            id="year"
                            type="number"
                            name="year"
                            min="2000"
                            max="2100"
                            value="{{ $year }}"
                            required
                        >
                    </div>
                </div>

                <div class="report-field">
                    <label for="minimum">Batas Peninjauan</label>

                    <div class="report-input-wrap">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3v18"/>
                            <path d="M5 8h14"/>
                            <path d="M5 16h14"/>
                        </svg>

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
                </div>

                <div class="report-field report-field-search">
                    <label for="search">Pencarian Peserta</label>

                    <div class="report-input-wrap">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>

                        <input
                            id="search"
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama atau NIP..."
                        >
                    </div>
                </div>

            </div>

            <div class="report-filter-actions">

                <button type="submit" class="report-btn report-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16l-6 7v6l-4 3v-9L4 4Z"/>
                    </svg>
                    Terapkan Filter
                </button>

                <a href="{{ route('reports.index') }}"
                   class="report-btn report-btn-light">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>
                    Reset Filter
                </a>

            </div>
        </form>

    </section>


    {{-- =====================================
         PARTICIPANT REPORT
    ====================================== --}}

    <section class="report-panel">

        <div class="report-panel-heading">
            <div>
                <h2>Frekuensi Pelatihan per Peserta</h2>

                <p>
                    Rekapitulasi jumlah keikutsertaan setiap peserta
                    pada tahun {{ $year }}.
                </p>
            </div>

            <div class="report-section-index">02</div>
        </div>

        <p class="report-table-description">
            Status peninjauan merupakan indikator awal untuk membantu
            staf mengevaluasi pemerataan kesempatan pelatihan.
            Angka ini bukan bukti bahwa peserta memiliki beban kerja berlebih.
        </p>

        <div class="report-table-topline">
            <div class="report-table-caption">
                DATA KEIKUTSERTAAN PESERTA
            </div>

            <form method="GET" action="{{ route('reports.export') }}">
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="minimum" value="{{ $minimum }}">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <button type="submit"
                        class="report-btn report-btn-export">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3v12"/>
                        <path d="m7 10 5 5 5-5"/>
                        <path d="M4 17v4h16v-4"/>
                    </svg>
                    Ekspor Excel
                </button>
            </form>
        </div>

        <div class="report-table-wrap">

            <table class="report-table">

                <thead>
                    <tr>
                        <th style="width:75px;">No.</th>
                        <th>Nama Peserta</th>
                        <th>NIP</th>
                        <th>Jumlah Keikutsertaan</th>
                        <th>Status Peninjauan</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($participants as $participant)

                        <tr>
                            <td class="report-number">
                                {{ $participants->firstItem() + $loop->index }}
                            </td>

                            <td class="report-participant-name">
                                {{ $participant->name ?: '-' }}
                            </td>

                            <td class="report-nip">
                                {{ $participant->nip ?: '-' }}
                            </td>

                            <td>
                                <span class="report-count">
                                    {{ $participant->participations_count }}
                                </span>
                            </td>

                            <td>
                                @if ($participant->participations_count >= $minimum)

                                    <span class="report-status report-status-limit">
                                        Limit Reached
                                    </span>

                                @else

                                    <span class="report-status report-status-eligible">
                                        Eligible
                                    </span>

                                @endif
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="report-empty">
                                <div class="report-empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.5"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"/>
                                        <path d="m21 21-4.3-4.3"/>
                                        <path d="M8 11h6"/>
                                    </svg>
                                </div>

                                <strong>Tidak ada data peserta</strong>

                                <div style="margin-top:7px;">
                                    Belum ada data yang sesuai dengan filter pencarian.
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="report-pagination">
            {{ $participants->appends(request()->query())->links() }}
        </div>

    </section>

</div>

@endsection