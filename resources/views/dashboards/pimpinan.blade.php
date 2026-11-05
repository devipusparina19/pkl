
@extends('layouts.app')

@section('content')

<style>
    /* =========================================================
       SIRAPIK — EXECUTIVE DASHBOARD
       Concept: Executive Data / Editorial Blue
       ========================================================= */

    .leader-dashboard {
        --ld-navy: #102b4e;
        --ld-navy-2: #173d68;
        --ld-blue: #2864ad;
        --ld-blue-light: #eaf2fc;
        --ld-teal: #168b91;
        --ld-teal-light: #e8f5f4;
        --ld-gold: #c18a3b;
        --ld-gold-light: #fbf3e7;
        --ld-purple: #8267b5;
        --ld-purple-light: #f1edfa;

        --ld-text: #253b56;
        --ld-muted: #718198;
        --ld-border: #e3eaf2;
        --ld-surface: #ffffff;
        --ld-background: #f4f7fb;

        width: 100%;
        padding-bottom: 34px;
        color: var(--ld-text);
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

    /* =========================================================
       PAGE HEADER
       ========================================================= */

    .leader-dashboard .page-heading {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 220px;
        align-items: center;
        gap: 30px;
        min-height: 245px;
        margin-bottom: 36px;
        padding: 42px 45px;
        overflow: hidden;
        isolation: isolate;
        color: #fff;
        background: var(--ld-navy);
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
        background: var(--ld-blue);
        border-radius: 48% 52% 43% 57%;
        opacity: .28;
        transform: rotate(-25deg);
    }

    .leader-dashboard .heading-copy {
        position: relative;
        z-index: 2;
        max-width: 760px;
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
        font-size: 16px;
        line-height: 1.85;
    }

    .leader-dashboard .heading-visual {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 174px;
        height: 174px;
        margin: auto;
        color: #c6f1ee;
        background: rgba(255,255,255,.075);
        border: 1px solid rgba(255,255,255,.17);
        border-radius: 42px;
        transform: rotate(5deg);
        transition: transform .35s ease, background .35s ease;
    }

    .leader-dashboard .page-heading:hover .heading-visual {
        background: rgba(255,255,255,.12);
        transform: rotate(0deg) translateY(-4px);
    }

    .leader-dashboard .heading-visual svg {
        width: 76px;
        height: 76px;
        transform: rotate(-5deg);
    }

    .leader-dashboard .heading-visual::after {
        content: "";
        position: absolute;
        right: -14px;
        bottom: -14px;
        width: 48px;
        height: 48px;
        background: var(--ld-gold);
        border: 7px solid var(--ld-navy);
        border-radius: 17px;
    }

    /* =========================================================
       SECTION INTRO
       ========================================================= */

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
        color: var(--ld-teal);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .leader-dashboard .section-intro h2 {
        margin: 0;
        color: var(--ld-navy);
        font-family: 'Manrope', sans-serif;
        font-size: clamp(23px, 2.5vw, 29px);
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: -.8px;
    }

    .leader-dashboard .section-intro p {
        max-width: 540px;
        margin: 8px 0 0;
        color: var(--ld-muted);
        font-size: 15px;
        line-height: 1.8;
    }

    .leader-dashboard .section-number {
        flex-shrink: 0;
        color: #aab7c7;
        font-family: 'Manrope', sans-serif;
        font-size: 38px;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -2px;
    }

    /* =========================================================
       STATISTIC CARDS
       ========================================================= */

    .leader-dashboard .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 17px;
        margin-bottom: 42px;
    }

    .leader-dashboard .stat-card {
        --stat-accent: var(--ld-blue);
        --stat-soft: var(--ld-blue-light);

        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        min-height: 220px;
        padding: 23px 22px 20px;
        overflow: hidden;
        background: var(--ld-surface);
        border: 1px solid var(--ld-border);
        border-radius: 17px;
        box-shadow: 0 5px 18px rgba(16,43,78,.035);
        transition:
            transform .25s ease,
            box-shadow .25s ease,
            border-color .25s ease;
    }

    .leader-dashboard .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--stat-accent);
    }

    .leader-dashboard .stat-card::after {
        content: "";
        position: absolute;
        right: -40px;
        bottom: -65px;
        width: 135px;
        height: 135px;
        background: var(--stat-soft);
        border-radius: 50%;
        opacity: .55;
        transition: transform .35s ease;
        pointer-events: none;
    }

    .leader-dashboard .stat-card:hover {
        z-index: 1;
        border-color: color-mix(in srgb, var(--stat-accent), white 65%);
        box-shadow: 0 15px 30px rgba(16,43,78,.09);
        transform: translateY(-5px);
    }

    .leader-dashboard .stat-card:hover::after {
        transform: scale(1.35);
    }

    .leader-dashboard .stat-topline {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 22px;
    }

    .leader-dashboard .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 49px;
        width: 49px;
        height: 49px;
        color: var(--stat-accent);
        background: var(--stat-soft);
        border-radius: 15px;
        transition: transform .25s ease;
    }

    .leader-dashboard .stat-card:hover .stat-icon {
        transform: rotate(-7deg) scale(1.06);
    }

    .leader-dashboard .stat-icon svg {
        width: 25px;
        height: 25px;
    }

    .leader-dashboard .stat-index {
        color: #9aa9bb;
        font-family: 'Manrope', sans-serif;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .leader-dashboard .stat-label {
        position: relative;
        z-index: 1;
        min-height: 28px;
        color: #65768c;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.6;
    }

    .leader-dashboard .stat-number {
        position: relative;
        z-index: 1;
        margin-top: 4px;
        color: var(--ld-navy);
        font-family: 'Manrope', sans-serif;
        font-size: clamp(30px, 3vw, 39px);
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -1.7px;
        font-variant-numeric: tabular-nums;
        overflow-wrap: anywhere;
    }

    .leader-dashboard .stat-note {
        position: relative;
        z-index: 1;
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
        flex: 0 0 7px;
        width: 7px;
        height: 7px;
        background: var(--stat-accent);
        border-radius: 50%;
    }

    /* =========================================================
       PANELS
       ========================================================= */

    .leader-dashboard .panel {
        margin-bottom: 25px;
        padding: 30px;
        background: var(--ld-surface);
        border: 1px solid var(--ld-border);
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

    .leader-dashboard .panel-heading {
        min-width: 0;
    }

    .leader-dashboard .panel-header h2 {
        margin: 0;
        color: var(--ld-navy);
        font-family: 'Manrope', sans-serif;
        font-size: 23px;
        font-weight: 800;
        line-height: 1.4;
        letter-spacing: -.6px;
    }

    .leader-dashboard .panel-header p {
        max-width: 690px;
        margin: 9px 0 0;
        color: var(--ld-muted);
        font-size: 14px;
        line-height: 1.85;
    }

    .leader-dashboard .panel-tag {
        display: inline-flex;
        flex-shrink: 0;
        align-items: center;
        gap: 9px;
        padding: 11px 15px;
        color: var(--ld-blue);
        background: var(--ld-blue-light);
        border: 1px solid #dce8f8;
        border-radius: 11px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .leader-dashboard .panel-tag svg {
        width: 18px;
        height: 18px;
    }

    /* =========================================================
       DISTRIBUTION
       ========================================================= */

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

    .leader-dashboard .field-stat:nth-last-child(-n+2) {
        border-bottom-color: transparent;
    }

    .leader-dashboard .field-stat:first-child,
    .leader-dashboard .field-stat:nth-child(2) {
        padding-top: 0;
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
        font-size: 15px;
        font-weight: 700;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .leader-dashboard .field-stat-line span::before {
        content: "";
        flex: 0 0 9px;
        width: 9px;
        height: 9px;
        background: var(--ld-teal);
        border-radius: 3px;
        transform: rotate(45deg);
    }

    .leader-dashboard .field-stat-line strong {
        flex-shrink: 0;
        min-width: 47px;
        padding: 6px 10px;
        color: var(--ld-blue);
        background: var(--ld-blue-light);
        border: 1px solid #dce8f8;
        border-radius: 8px;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        font-weight: 800;
        text-align: center;
        font-variant-numeric: tabular-nums;
    }

    .leader-dashboard .progress-track {
        position: relative;
        width: 100%;
        height: 11px;
        overflow: hidden;
        background: #edf2f7;
        border-radius: 20px;
    }

    .leader-dashboard .progress-fill {
        position: relative;
        height: 100%;
        min-width: 0;
        background: linear-gradient(90deg, #2864ad 0%, #168b91 100%);
        border-radius: 20px;
        transition: width .7s cubic-bezier(.2,.7,.2,1);
    }

    .leader-dashboard .progress-fill::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 30px;
        height: 100%;
        background: rgba(255,255,255,.24);
        border-radius: inherit;
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */

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
        color: var(--ld-blue);
        background: var(--ld-blue-light);
        border-radius: 12px;
    }

    .leader-dashboard .empty-icon svg {
        width: 21px;
        height: 21px;
    }

    /* =========================================================
       MONITORING / INFORMATION PANEL
       ========================================================= */

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

    .leader-dashboard .monitoring-panel::after {
        content: "";
        position: absolute;
        top: -100px;
        right: 100px;
        width: 240px;
        height: 240px;
        border: 1px solid rgba(40,100,173,.12);
        border-radius: 50%;
        box-shadow: 0 0 0 28px rgba(40,100,173,.035);
        pointer-events: none;
    }

    .leader-dashboard .monitoring-content {
        position: relative;
        z-index: 1;
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
        color: var(--ld-teal);
        background: #fff;
        border: 1px solid #dcebe9;
        border-radius: 16px;
        box-shadow: 0 5px 14px rgba(16,43,78,.05);
    }

    .leader-dashboard .monitoring-icon svg {
        width: 27px;
        height: 27px;
    }

    .leader-dashboard .monitoring-copy {
        min-width: 0;
    }

    .leader-dashboard .monitoring-copy h2 {
        margin: 0;
        color: var(--ld-navy);
        font-family: 'Manrope', sans-serif;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.4;
    }

    .leader-dashboard .monitoring-copy p {
        max-width: 760px;
        margin: 9px 0 0;
        color: #64778e;
        font-size: 14px;
        line-height: 1.9;
    }

    .leader-dashboard .monitoring-copy strong {
        color: var(--ld-blue);
        font-weight: 800;
    }

    .leader-dashboard .monitoring-decoration {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 76px;
        width: 76px;
        height: 76px;
        color: #fff;
        background: var(--ld-navy);
        border-radius: 23px;
        transform: rotate(-7deg);
    }

    .leader-dashboard .monitoring-decoration svg {
        width: 34px;
        height: 34px;
        transform: rotate(7deg);
    }

    /* =========================================================
       ACCESSIBILITY
       ========================================================= */

    .leader-dashboard :focus-visible {
        outline: 3px solid rgba(22,139,145,.65);
        outline-offset: 4px;
    }

    /* =========================================================
       RESPONSIVE — TABLET
       ========================================================= */

    @media (max-width: 1100px) {
        .leader-dashboard .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .leader-dashboard .page-heading {
            grid-template-columns: minmax(0, 1fr) 160px;
        }

        .leader-dashboard .heading-visual {
            width: 140px;
            height: 140px;
        }

        .leader-dashboard .distribution-list {
            column-gap: 25px;
        }
    }

    /* =========================================================
       RESPONSIVE — MOBILE
       ========================================================= */

    @media (max-width: 640px) {
        .leader-dashboard {
            padding-bottom: 20px;
        }

        .leader-dashboard .page-heading {
            grid-template-columns: minmax(0, 1fr);
            min-height: auto;
            padding: 29px 24px;
            margin-bottom: 29px;
            border-radius: 17px;
        }

        .leader-dashboard .page-heading::before {
            right: -160px;
        }

        .leader-dashboard .page-heading h1 {
            font-size: clamp(31px, 8vw, 38px);
            letter-spacing: -1.2px;
        }

        .leader-dashboard .page-heading p {
            margin-top: 13px;
            font-size: 14px;
            line-height: 1.8;
        }

        .leader-dashboard .eyebrow {
            margin-bottom: 14px;
            font-size: 10px;
            letter-spacing: 1.8px;
        }

        .leader-dashboard .heading-visual {
            position: absolute;
            right: 17px;
            bottom: 15px;
            width: 66px;
            height: 66px;
            border-radius: 19px;
            opacity: .5;
        }

        .leader-dashboard .heading-visual svg {
            width: 32px;
            height: 32px;
        }

        .leader-dashboard .heading-visual::after {
            width: 22px;
            height: 22px;
            right: -6px;
            bottom: -6px;
            border-width: 4px;
            border-radius: 8px;
        }

        .leader-dashboard .section-intro {
            align-items: flex-start;
            margin-bottom: 17px;
        }

        .leader-dashboard .section-intro h2 {
            font-size: 23px;
        }

        .leader-dashboard .section-intro p {
            font-size: 13px;
        }

        .leader-dashboard .section-number {
            font-size: 29px;
        }

        .leader-dashboard .stats-grid {
            gap: 12px;
            margin-bottom: 30px;
        }

        .leader-dashboard .stat-card {
            min-height: 195px;
            padding: 18px 16px;
            border-radius: 14px;
        }

        .leader-dashboard .stat-topline {
            margin-bottom: 16px;
        }

        .leader-dashboard .stat-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            border-radius: 12px;
        }

        .leader-dashboard .stat-icon svg {
            width: 22px;
            height: 22px;
        }

        .leader-dashboard .stat-index {
            font-size: 9px;
            letter-spacing: .5px;
        }

        .leader-dashboard .stat-label {
            min-height: 39px;
            font-size: 13px;
        }

        .leader-dashboard .stat-number {
            font-size: clamp(27px, 7vw, 34px);
        }

        .leader-dashboard .stat-note {
            padding-top: 11px;
            font-size: 11px;
        }

        .leader-dashboard .panel {
            padding: 22px 18px;
            margin-bottom: 17px;
            border-radius: 15px;
        }

        .leader-dashboard .panel-header {
            flex-direction: column;
            gap: 15px;
            margin-bottom: 23px;
        }

        .leader-dashboard .panel-header h2 {
            font-size: 20px;
        }

        .leader-dashboard .panel-header p {
            font-size: 13px;
        }

        .leader-dashboard .panel-tag {
            padding: 9px 12px;
            font-size: 11px;
        }

        .leader-dashboard .distribution-list {
            grid-template-columns: 1fr;
        }

        .leader-dashboard .field-stat {
            padding: 16px 0;
            border-bottom: 1px solid #edf1f6 !important;
        }

        .leader-dashboard .field-stat:first-child {
            padding-top: 0;
        }

        .leader-dashboard .field-stat:last-child {
            padding-bottom: 0;
            border-bottom: 0 !important;
        }

        .leader-dashboard .field-stat-line span {
            font-size: 14px;
        }

        .leader-dashboard .monitoring-panel {
            grid-template-columns: 1fr;
            padding: 22px 18px;
        }

        .leader-dashboard .monitoring-content {
            gap: 13px;
        }

        .leader-dashboard .monitoring-icon {
            flex-basis: 43px;
            width: 43px;
            height: 43px;
            border-radius: 12px;
        }

        .leader-dashboard .monitoring-icon svg {
            width: 22px;
            height: 22px;
        }

        .leader-dashboard .monitoring-copy h2 {
            font-size: 19px;
        }

        .leader-dashboard .monitoring-copy p {
            font-size: 13px;
        }

        .leader-dashboard .monitoring-decoration {
            display: none;
        }
    }

    @media (max-width: 380px) {
        .leader-dashboard .stats-grid {
            grid-template-columns: 1fr;
        }

        .leader-dashboard .stat-card {
            min-height: 180px;
        }

        .leader-dashboard .stat-label {
            min-height: 0;
        }

        .leader-dashboard .stat-number {
            font-size: 34px;
        }
    }

    /* =========================================================
       REDUCED MOTION
       ========================================================= */

    @media (prefers-reduced-motion: reduce) {
        .leader-dashboard *,
        .leader-dashboard *::before,
        .leader-dashboard *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

{{-- FONT --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="leader-dashboard">

    {{-- =====================================================
         HEADER
         ===================================================== --}}

    <header class="page-heading">

        <div class="heading-copy">

            <span class="eyebrow">
                Dashboard Pimpinan
            </span>

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


    {{-- =====================================================
         STATISTIK UTAMA
         ===================================================== --}}

    <section aria-labelledby="leader-summary-title">

        <div class="section-intro">

            <div>

                <span class="section-kicker">
                    Ikhtisar sistem
                </span>

                <h2 id="leader-summary-title">
                    Gambaran Umum Pelatihan
                </h2>

                <p>
                    Indikator utama untuk memberikan gambaran kondisi
                    pelatihan dan keikutsertaan peserta.
                </p>

            </div>

            <span class="section-number" aria-hidden="true">
                01
            </span>

        </div>


        <div class="stats-grid">

            {{-- TOTAL PESERTA --}}
            <article class="stat-card"
                     style="--stat-accent:#2864ad;--stat-soft:#eaf2fc;">

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
                    Total Peserta
                </div>

                <div class="stat-number">
                    {{ number_format($totalParticipants) }}
                </div>

                <div class="stat-note">
                    Peserta terdaftar di sistem
                </div>

            </article>


            {{-- TOTAL PELATIHAN --}}
            <article class="stat-card"
                     style="--stat-accent:#168b91;--stat-soft:#e8f5f4;">

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
                    Pelatihan tercatat di sistem
                </div>

            </article>


            {{-- TOTAL KEIKUTSERTAAN --}}
            <article class="stat-card"
                     style="--stat-accent:#8267b5;--stat-soft:#f1edfa;">

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

                <div class="stat-label">
                    Total Keikutsertaan
                </div>

                <div class="stat-number">
                    {{ number_format($totalParticipations) }}
                </div>

                <div class="stat-note">
                    Riwayat keikutsertaan peserta
                </div>

            </article>


            {{-- KEIKUTSERTAAN TAHUN INI --}}
            <article class="stat-card"
                     style="--stat-accent:#bd8530;--stat-soft:#fbf3e7;">

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

            </article>

        </div>

    </section>


    {{-- =====================================================
         DISTRIBUSI BIDANG PELATIHAN
         ===================================================== --}}

    <section class="panel"
             aria-labelledby="field-distribution-title">

        <div class="panel-header">

            <div class="panel-heading">

                <span class="section-kicker">
                    Komposisi program
                </span>

                <h2 id="field-distribution-title">
                    Distribusi Bidang Pelatihan
                </h2>

                <p>
                    Perbandingan jumlah pelatihan berdasarkan bidang.
                    Gunakan visualisasi ini untuk melihat persebaran
                    program yang telah tercatat di dalam sistem.
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
            // Alias dari query controller adalah name dan total.
            $maxFieldTotal = max(
                1,
                (int) $fieldStats->max('total')
            );
        @endphp

        <div class="distribution-list">

            @forelse ($fieldStats as $stat)

                @php
                    $fieldName = $stat->name ?? 'Bidang tidak diketahui';
                    $total = (int) ($stat->total ?? 0);

                    $percentage = min(
                        100,
                        max(0, ($total / $maxFieldTotal) * 100)
                    );
                @endphp

                <div class="field-stat">

                    <div class="field-stat-line">

                        <span>
                            {{ $fieldName }}
                        </span>

                        <strong>
                            {{ number_format($total) }}
                        </strong>

                    </div>

                    <div
                        class="progress-track"
                        role="progressbar"
                        aria-label="Jumlah pelatihan {{ $fieldName }}"
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
                        Belum ada data bidang pelatihan yang dapat ditampilkan.
                    </div>

                </div>

            @endforelse

        </div>

    </section>


    {{-- =====================================================
         CATATAN PEMANTAUAN
         ===================================================== --}}

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

                <span class="section-kicker">
                    Informasi lanjutan
                </span>

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
