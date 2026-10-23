@extends('layouts.app')

@section('content')

<style>
    /* ==========================================
       SIRAPIK — DASHBOARD PIMPINAN
       Selaras dengan Dashboard Admin
       ========================================== */

    .leader-dashboard {
        --ld-navy: #172b4d;
        --ld-blue: #2457a7;
        --ld-teal: #087f8c;
        --ld-text: #263b59;
        --ld-muted: #718097;
        --ld-border: #e3eaf2;
        --ld-bg: #f5f8fc;
        --ld-white: #ffffff;

        color: var(--ld-text);
        font-family: inherit;
        padding-bottom: 28px;
    }

    .leader-dashboard * {
        box-sizing: border-box;
    }

    /* Header */

    .leader-dashboard .page-heading {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        overflow: hidden;
        padding: 31px 32px;
        margin-bottom: 25px;
        color: #fff;
        background:
            radial-gradient(
                circle at 92% 12%,
                rgba(100, 218, 208, .20),
                transparent 27%
            ),
            linear-gradient(
                118deg,
                #172b4d 0%,
                #204a7e 66%,
                #176b79 100%
            );
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(23, 43, 77, .12);
    }

    .leader-dashboard .page-heading::after {
        content: "";
        position: absolute;
        right: -36px;
        bottom: -115px;
        width: 250px;
        height: 250px;
        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 50%;
        box-shadow:
            0 0 0 26px rgba(255, 255, 255, .035),
            0 0 0 54px rgba(255, 255, 255, .025);
        pointer-events: none;
    }

    .leader-dashboard .heading-copy {
        position: relative;
        z-index: 1;
        max-width: 720px;
    }

    .leader-dashboard .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 12px;
        color: #bce9e8;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .leader-dashboard .eyebrow::before {
        content: "";
        width: 23px;
        height: 2px;
        background: #67d5cd;
        border-radius: 10px;
    }

    .leader-dashboard .page-heading h1 {
        margin: 0;
        color: #fff;
        font-size: clamp(26px, 3vw, 35px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.7px;
    }

    .leader-dashboard .page-heading p {
        max-width: 590px;
        margin: 12px 0 0;
        color: rgba(255, 255, 255, .80);
        font-size: 13px;
        line-height: 1.9;
    }

    .leader-dashboard .heading-icon {
        position: relative;
        z-index: 1;
        display: flex;
        flex: 0 0 76px;
        align-items: center;
        justify-content: center;
        width: 76px;
        height: 76px;
        color: #d4fbf5;
        background: rgba(255, 255, 255, .10);
        border: 1px solid rgba(255, 255, 255, .19);
        border-radius: 22px;
    }

    .leader-dashboard .heading-icon svg {
        width: 38px;
        height: 38px;
    }

    /* Intro section */

    .leader-dashboard .section-intro {
        margin-bottom: 15px;
    }

    .leader-dashboard .section-kicker {
        display: block;
        margin-bottom: 5px;
        color: var(--ld-teal);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.7px;
        text-transform: uppercase;
    }

    .leader-dashboard .section-intro h2 {
        margin: 0;
        color: var(--ld-navy);
        font-size: 19px;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .leader-dashboard .section-intro p {
        margin: 6px 0 0;
        color: var(--ld-muted);
        font-size: 12px;
        line-height: 1.8;
    }

    /* Kartu statistik */

    .leader-dashboard .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .leader-dashboard .stat-card {
        position: relative;
        min-width: 0;
        overflow: hidden;
        padding: 21px 20px 18px;
        background: var(--ld-white);
        border: 1px solid var(--ld-border);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, .035);
        transition:
            transform .22s ease,
            box-shadow .22s ease,
            border-color .22s ease;
    }

    .leader-dashboard .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--stat-accent, var(--ld-blue));
    }

    .leader-dashboard .stat-card:hover {
        transform: translateY(-4px);
        border-color: #c8d8e8;
        box-shadow: 0 12px 24px rgba(23, 43, 77, .08);
    }

    .leader-dashboard .stat-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 18px;
    }

    .leader-dashboard .stat-icon {
        display: flex;
        flex: 0 0 43px;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        color: var(--stat-accent, var(--ld-blue));
        background: var(--stat-soft, #eaf1fc);
        border-radius: 13px;
    }

    .leader-dashboard .stat-icon svg {
        width: 22px;
        height: 22px;
    }

    .leader-dashboard .stat-index {
        color: #a0adbe;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .8px;
    }

    .leader-dashboard .stat-label {
        min-height: 38px;
        color: var(--ld-muted);
        font-size: 12px;
        font-weight: 700;
        line-height: 1.7;
    }

    .leader-dashboard .stat-number {
        margin-top: 5px;
        color: var(--ld-navy);
        font-size: clamp(27px, 2.5vw, 35px);
        font-weight: 850;
        line-height: 1.25;
        letter-spacing: -1px;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
    }

    .leader-dashboard .stat-note {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        margin-top: 13px;
        padding-top: 12px;
        color: #8290a4;
        border-top: 1px solid #edf1f6;
        font-size: 10px;
        line-height: 1.7;
    }

    .leader-dashboard .stat-note::before {
        content: "";
        flex: 0 0 6px;
        width: 6px;
        height: 6px;
        margin-top: 5px;
        background: var(--stat-accent, var(--ld-blue));
        border-radius: 50%;
    }

    /* Panel */

    .leader-dashboard .panel {
        margin-bottom: 22px;
        padding: 25px;
        background: var(--ld-white);
        border: 1px solid var(--ld-border);
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(23, 43, 77, .035);
    }

    .leader-dashboard .panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 22px;
    }

    .leader-dashboard .panel-header h2 {
        margin: 0;
        color: var(--ld-navy);
        font-size: 17px;
        font-weight: 800;
        line-height: 1.5;
        letter-spacing: -.25px;
    }

    .leader-dashboard .panel-header p {
        max-width: 680px;
        margin: 7px 0 0;
        color: var(--ld-muted);
        font-size: 12px;
        line-height: 1.8;
    }

    .leader-dashboard .panel-tag {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        gap: 7px;
        padding: 8px 11px;
        color: #526984;
        background: #f2f6fb;
        border: 1px solid #e5edf5;
        border-radius: 9px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .6px;
        text-transform: uppercase;
    }

    .leader-dashboard .panel-tag svg {
        width: 15px;
        height: 15px;
    }

    /* Distribusi bidang */

    .leader-dashboard .field-stat {
        padding: 17px 0;
        border-bottom: 1px solid #edf1f6;
    }

    .leader-dashboard .field-stat:first-child {
        padding-top: 0;
    }

    .leader-dashboard .field-stat:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .leader-dashboard .field-stat-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 10px;
    }

    .leader-dashboard .field-stat-line span {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #40536d;
        font-size: 13px;
        font-weight: 650;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .leader-dashboard .field-stat-line span::before {
        content: "";
        flex: 0 0 8px;
        width: 8px;
        height: 8px;
        background: #168d98;
        border-radius: 3px;
        transform: rotate(45deg);
    }

    .leader-dashboard .field-stat-line strong {
        flex: 0 0 auto;
        min-width: 38px;
        padding: 5px 10px;
        color: var(--ld-blue);
        background: #eaf1fc;
        border: 1px solid #dce8fa;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        text-align: center;
        font-variant-numeric: tabular-nums;
    }

    .leader-dashboard .progress-track {
        width: 100%;
        height: 9px;
        overflow: hidden;
        background: #edf2f7;
        border-radius: 20px;
    }

    .leader-dashboard .progress-fill {
        height: 100%;
        min-width: 0;
        background: linear-gradient(90deg, #2457a7 0%, #168d98 100%);
        border-radius: 20px;
        transition: width .45s ease;
    }

    /* Tampilan data kosong */

    .leader-dashboard .empty-state {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 19px 17px;
        color: #74849a;
        background: #f7f9fc;
        border: 1px dashed #d7e1ec;
        border-radius: 10px;
        font-size: 12px;
        line-height: 1.8;
    }

    .leader-dashboard .empty-state::before {
        content: "i";
        display: flex;
        flex: 0 0 27px;
        align-items: center;
        justify-content: center;
        width: 27px;
        height: 27px;
        color: #357bb8;
        background: #e7f1fb;
        border-radius: 50%;
        font-size: 14px;
        font-weight: 800;
        font-style: normal;
    }

    /* Panel informasi */

    .leader-dashboard .monitoring-panel {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(120deg, #fff 0%, #f6fbfd 100%);
    }

    .leader-dashboard .monitoring-content {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .leader-dashboard .monitoring-icon {
        display: flex;
        flex: 0 0 47px;
        align-items: center;
        justify-content: center;
        width: 47px;
        height: 47px;
        color: var(--ld-teal);
        background: #e4f5f3;
        border: 1px solid #d2eeeb;
        border-radius: 14px;
    }

    .leader-dashboard .monitoring-icon svg {
        width: 23px;
        height: 23px;
    }

    .leader-dashboard .monitoring-copy {
        min-width: 0;
    }

    .leader-dashboard .monitoring-copy h2 {
        margin: 0;
        color: var(--ld-navy);
        font-size: 17px;
        font-weight: 800;
        line-height: 1.5;
    }

    .leader-dashboard .monitoring-copy p {
        margin: 8px 0 0;
        color: var(--ld-muted);
        font-size: 12px;
        line-height: 1.9;
    }

    .leader-dashboard .monitoring-copy strong {
        color: #2457a7;
        font-weight: 800;
    }

    .leader-dashboard :focus-visible {
        outline: 3px solid rgba(8, 127, 140, .55);
        outline-offset: 3px;
    }

    /* Tablet */

    @media (max-width: 1100px) {
        .leader-dashboard .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* Ponsel */

    @media (max-width: 640px) {
        .leader-dashboard {
            padding-bottom: 16px;
        }

        .leader-dashboard .page-heading {
            align-items: flex-start;
            gap: 15px;
            padding: 23px 20px;
            margin-bottom: 22px;
            border-radius: 14px;
        }

        .leader-dashboard .page-heading h1 {
            font-size: 25px;
        }

        .leader-dashboard .page-heading p {
            font-size: 12px;
        }

        .leader-dashboard .eyebrow {
            font-size: 9px;
            letter-spacing: 1.3px;
        }

        .leader-dashboard .heading-icon {
            flex-basis: 46px;
            width: 46px;
            height: 46px;
            border-radius: 13px;
        }

        .leader-dashboard .heading-icon svg {
            width: 25px;
            height: 25px;
        }

        .leader-dashboard .stats-grid {
            gap: 11px;
            margin-bottom: 23px;
        }

        .leader-dashboard .stat-card {
            padding: 16px 14px;
            border-radius: 12px;
        }

        .leader-dashboard .stat-topline {
            margin-bottom: 14px;
        }

        .leader-dashboard .stat-icon {
            flex-basis: 37px;
            width: 37px;
            height: 37px;
            border-radius: 11px;
        }

        .leader-dashboard .stat-icon svg {
            width: 19px;
            height: 19px;
        }

        .leader-dashboard .stat-index {
            font-size: 9px;
        }

        .leader-dashboard .stat-label {
            min-height: 39px;
            font-size: 11px;
        }

        .leader-dashboard .stat-number {
            font-size: 27px;
        }

        .leader-dashboard .stat-note {
            font-size: 10px;
        }

        .leader-dashboard .panel {
            padding: 18px 15px;
            margin-bottom: 16px;
            border-radius: 13px;
        }

        .leader-dashboard .panel-header {
            gap: 10px;
            margin-bottom: 19px;
        }

        .leader-dashboard .panel-header h2,
        .leader-dashboard .monitoring-copy h2 {
            font-size: 15px;
        }

        .leader-dashboard .panel-header p,
        .leader-dashboard .monitoring-copy p {
            font-size: 11px;
        }

        .leader-dashboard .panel-tag {
            padding: 7px;
            font-size: 9px;
        }

        .leader-dashboard .field-stat-line span {
            font-size: 12px;
        }

        .leader-dashboard .monitoring-content {
            gap: 12px;
        }

        .leader-dashboard .monitoring-icon {
            flex-basis: 39px;
            width: 39px;
            height: 39px;
            border-radius: 11px;
        }

        .leader-dashboard .monitoring-icon svg {
            width: 20px;
            height: 20px;
        }
    }

    @media (max-width: 380px) {
        .leader-dashboard .stats-grid {
            grid-template-columns: 1fr;
        }

        .leader-dashboard .stat-label {
            min-height: 0;
        }

        .leader-dashboard .heading-icon {
            display: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .leader-dashboard *,
        .leader-dashboard *::before,
        .leader-dashboard *::after {
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
        }
    }
</style>

<div class="leader-dashboard">

    {{-- HEADER DASHBOARD PIMPINAN --}}
    <header class="page-heading">
        <div class="heading-copy">
            <span class="eyebrow">DASHBOARD PIMPINAN</span>

            <h1>Ringkasan Pelatihan</h1>

            <p>
                Informasi agregat untuk memantau pelaksanaan pelatihan,
                melihat distribusi program, dan mendukung evaluasi
                berdasarkan data yang tercatat di SIRAPIK.
            </p>
        </div>

        <div class="heading-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.6"
                 stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="5" rx="1.5"/>
                <rect x="14" y="12" width="7" height="9" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            </svg>
        </div>
    </header>

    {{-- RINGKASAN STATISTIK --}}
    <section aria-labelledby="leader-summary-title">
        <div class="section-intro">
            <span class="section-kicker">Ikhtisar sistem</span>
            <h2 id="leader-summary-title">Gambaran Umum Pelatihan</h2>
            <p>
                Ringkasan angka utama sebagai gambaran kondisi data pelatihan.
            </p>
        </div>

        <div class="stats-grid">

            {{-- TOTAL PESERTA --}}
            <div class="stat-card"
                 style="--stat-accent:#2457a7;--stat-soft:#eaf1fc;">
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

                <div class="stat-label">Total Peserta</div>

                <div class="stat-number">
                    {{ number_format($totalParticipants) }}
                </div>

                <div class="stat-note">
                    Peserta terdaftar di sistem
                </div>
            </div>

            {{-- TOTAL PELATIHAN --}}
            <div class="stat-card"
                 style="--stat-accent:#087f8c;--stat-soft:#e5f6f4;">
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

                <div class="stat-label">Total Pelatihan</div>

                <div class="stat-number">
                    {{ number_format($totalTrainings) }}
                </div>

                <div class="stat-note">
                    Pelatihan tercatat di sistem
                </div>
            </div>

            {{-- TOTAL KEIKUTSERTAAN --}}
            <div class="stat-card"
                 style="--stat-accent:#8b65bd;--stat-soft:#f1eafb;">
                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 2h8l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"/>
                            <path d="M16 2v5h5"/>
                            <path d="M8 12h8M8 16h8M8 8h3"/>
                        </svg>
                    </div>
                    <span class="stat-index">03 / RIWAYAT</span>
                </div>

                <div class="stat-label">Total Keikutsertaan</div>

                <div class="stat-number">
                    {{ number_format($totalParticipations) }}
                </div>

                <div class="stat-note">
                    Riwayat keikutsertaan peserta
                </div>
            </div>

            {{-- KEIKUTSERTAAN TAHUN INI --}}
            <div class="stat-card"
                 style="--stat-accent:#bd8530;--stat-soft:#fbf2e2;">
                <div class="stat-topline">
                    <div class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="16" rx="2"/>
                            <path d="M16 3v4M8 3v4M3 10h18"/>
                            <path d="m9 15 2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="stat-index">04 / TAHUN</span>
                </div>

                <div class="stat-label">
                    Keikutsertaan Tahun {{ $year }}
                </div>

                <div class="stat-number">
                    {{ number_format($participationsThisYear) }}
                </div>

                <div class="stat-note">
                    Berdasarkan tanggal mulai pelatihan
                </div>
            </div>

        </div>
    </section>

    {{-- DISTRIBUSI BIDANG PELATIHAN --}}
    <section class="panel" aria-labelledby="field-distribution-title">
        <div class="panel-header">
            <div>
                <span class="section-kicker">Komposisi program</span>

                <h2 id="field-distribution-title">
                    Distribusi Bidang Pelatihan
                </h2>

                <p>
                    Perbandingan jumlah pelatihan per bidang untuk membantu
                    pimpinan melihat persebaran program yang tercatat.
                    Batang yang lebih panjang menunjukkan jumlah yang lebih besar.
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
            $maxFieldTotal = max(
                1,
                (int) $fieldStats->max('total')
            );
        @endphp

        @forelse ($fieldStats as $stat)
            <div class="field-stat">
                <div class="field-stat-line">
                    <span>{{ $stat->field }}</span>

                    <strong>{{ number_format($stat->total) }}</strong>
                </div>

                <div class="progress-track">
                    <div
                        class="progress-fill"
                        style="width: {{ ($stat->total / $maxFieldTotal) * 100 }}%"
                    ></div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                Belum ada data bidang pelatihan yang dapat ditampilkan.
            </div>
        @endforelse
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
                    Gunakan menu <strong>Laporan Pelatihan</strong> pada sidebar
                    untuk melihat rincian laporan dan memantau pelaksanaan
                    pelatihan berdasarkan data yang tercatat di SIRAPIK.
                </p>
            </div>
        </div>
    </section>

</div>
@endsection