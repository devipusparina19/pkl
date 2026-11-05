@extends('layouts.app')

@section('title', 'Dashboard Admin SIRAPIK')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* =========================================================
       SIRAPIK ADMIN DASHBOARD
       Editorial Data Dashboard
    ========================================================= */

    .dashboard-admin {
        --da-navy: #102d50;
        --da-navy-2: #173e69;
        --da-blue: #2463a8;
        --da-blue-light: #eaf2fb;
        --da-teal: #087f83;
        --da-teal-light: #e8f5f3;
        --da-coral: #d96b79;
        --da-coral-light: #fff0f1;
        --da-gold: #d6ad62;

        --da-ink: #1b3049;
        --da-text: #40536b;
        --da-muted: #728197;
        --da-subtle: #93a0b1;

        --da-line: #e3eaf1;
        --da-line-soft: #edf1f5;
        --da-surface: #ffffff;
        --da-canvas: #f4f7fa;

        width: 100%;
        padding: 10px 0 42px;
        color: var(--da-ink);
        font-family: 'DM Sans', sans-serif;
        font-size: 15px;
        line-height: 1.65;
    }

    .dashboard-admin *,
    .dashboard-admin *::before,
    .dashboard-admin *::after {
        box-sizing: border-box;
    }

    .dashboard-admin button,
    .dashboard-admin input,
    .dashboard-admin select {
        font: inherit;
    }

    .dashboard-admin svg {
        display: block;
        width: 21px;
        height: 21px;
        flex-shrink: 0;
    }

    .dashboard-admin a {
        text-decoration: none;
    }

    .dashboard-admin button,
    .dashboard-admin a,
    .dashboard-admin select {
        -webkit-tap-highlight-color: transparent;
    }

    /* =========================================================
       HEADER / INTRO
    ========================================================= */

    .dashboard-admin .dashboard-heading {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 30px;
        min-height: 245px;
        margin-bottom: 38px;
        padding: 42px 46px;
        overflow: hidden;
        color: #fff;
        background: var(--da-navy);
        border-radius: 22px;
        box-shadow: 0 16px 40px rgba(16, 45, 80, .13);
        isolation: isolate;
    }

    .dashboard-admin .dashboard-heading::before {
        position: absolute;
        z-index: -1;
        top: -120px;
        right: 100px;
        width: 390px;
        height: 390px;
        content: "";
        border: 1px solid rgba(255,255,255,.11);
        border-radius: 50%;
        box-shadow:
            0 0 0 35px rgba(255,255,255,.025),
            0 0 0 75px rgba(255,255,255,.018);
    }

    .dashboard-admin .dashboard-heading::after {
        position: absolute;
        z-index: -1;
        right: 0;
        bottom: 0;
        width: 42%;
        height: 5px;
        content: "";
        background: linear-gradient(
            90deg,
            transparent,
            var(--da-teal),
            #65b9b2
        );
    }

    .dashboard-admin .heading-copy {
        max-width: 760px;
    }

    .dashboard-admin .heading-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 17px;
        color: #9ed7d2;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .dashboard-admin .heading-eyebrow::before {
        width: 29px;
        height: 2px;
        content: "";
        background: var(--da-gold);
    }

    .dashboard-admin .dashboard-heading h1 {
        max-width: 700px;
        margin: 0;
        color: #fff;
        font-family: 'Manrope', sans-serif;
        font-size: clamp(30px, 4vw, 43px);
        font-weight: 800;
        letter-spacing: -1.8px;
        line-height: 1.2;
    }

    .dashboard-admin .dashboard-heading p {
        max-width: 650px;
        margin: 17px 0 0;
        color: rgba(255,255,255,.77);
        font-size: 16px;
        line-height: 1.85;
    }

    .dashboard-admin .heading-mark {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 112px;
        height: 112px;
        margin-right: 18px;
        color: #c6eeea;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 29px;
        transform: rotate(-5deg);
    }

    .dashboard-admin .heading-mark::before,
    .dashboard-admin .heading-mark::after {
        position: absolute;
        content: "";
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 22px;
    }

    .dashboard-admin .heading-mark::before {
        inset: -11px;
    }

    .dashboard-admin .heading-mark::after {
        inset: 11px;
        border-color: rgba(255,255,255,.1);
    }

    .dashboard-admin .heading-mark svg {
        width: 47px;
        height: 47px;
        transform: rotate(5deg);
    }

    /* =========================================================
       SECTION HEADING
    ========================================================= */

    .dashboard-admin .section-intro {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 19px;
    }

    .dashboard-admin .section-kicker {
        display: block;
        margin-bottom: 7px;
        color: var(--da-teal);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.7px;
        text-transform: uppercase;
    }

    .dashboard-admin .section-intro h2,
    .dashboard-admin .panel-heading h2 {
        margin: 0;
        color: var(--da-ink);
        font-family: 'Manrope', sans-serif;
        font-size: clamp(21px, 2.3vw, 26px);
        font-weight: 800;
        letter-spacing: -.7px;
        line-height: 1.35;
    }

    .dashboard-admin .section-intro p,
    .dashboard-admin .panel-heading p {
        margin: 8px 0 0;
        color: var(--da-muted);
        font-size: 15px;
        line-height: 1.75;
    }

    .dashboard-admin .section-year {
        flex-shrink: 0;
        padding: 9px 14px;
        color: var(--da-blue);
        background: var(--da-blue-light);
        border: 1px solid #d9e7f7;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 800;
    }

    /* =========================================================
       STATISTICS
    ========================================================= */

    .dashboard-admin .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 23px;
    }

    .dashboard-admin .stat-card {
        position: relative;
        min-width: 0;
        min-height: 215px;
        padding: 26px 28px 23px;
        overflow: hidden;
        background: var(--da-surface);
        border: 1px solid var(--da-line);
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(23, 48, 73, .035);
        transition:
            transform .25s ease,
            box-shadow .25s ease,
            border-color .25s ease;
    }

    .dashboard-admin .stat-card:hover {
        border-color: #cbdbea;
        box-shadow: 0 14px 32px rgba(23, 48, 73, .08);
        transform: translateY(-4px);
    }

    .dashboard-admin .stat-card::after {
        position: absolute;
        right: -42px;
        bottom: -67px;
        width: 175px;
        height: 175px;
        content: "";
        background: var(--stat-soft, var(--da-blue-light));
        border-radius: 50%;
        opacity: .75;
        transition: transform .35s ease;
    }

    .dashboard-admin .stat-card:hover::after {
        transform: scale(1.15);
    }

    .dashboard-admin .stat-topline {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .dashboard-admin .stat-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 49px;
        height: 49px;
        color: var(--stat-accent, var(--da-blue));
        background: var(--stat-soft, var(--da-blue-light));
        border: 1px solid rgba(36,99,168,.08);
        border-radius: 14px;
    }

    .dashboard-admin .stat-icon svg {
        width: 25px;
        height: 25px;
    }

    .dashboard-admin .stat-index {
        color: #9aa8b8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.1px;
    }

    .dashboard-admin .stat-label {
        position: relative;
        z-index: 1;
        color: var(--da-muted);
        font-size: 15px;
        font-weight: 600;
    }

    .dashboard-admin .stat-value {
        position: relative;
        z-index: 1;
        margin-top: 3px;
        color: var(--da-ink);
        font-family: 'Manrope', sans-serif;
        font-size: clamp(36px, 4vw, 47px);
        font-weight: 800;
        letter-spacing: -1.8px;
        line-height: 1.35;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-admin .stat-caption {
        position: relative;
        z-index: 1;
        max-width: 440px;
        margin-top: 13px;
        padding-top: 12px;
        color: #8290a2;
        border-top: 1px solid var(--da-line-soft);
        font-size: 13px;
        line-height: 1.7;
    }

    /* =========================================================
       GENDER CHART
    ========================================================= */

    .dashboard-admin .gender-card {
        display: grid;
        grid-template-columns: minmax(220px, .8fr) minmax(0, 1.2fr);
        align-items: center;
        gap: 35px;
        margin-bottom: 36px;
        padding: 30px;
        background: #fff;
        border: 1px solid var(--da-line);
        border-radius: 17px;
        box-shadow: 0 5px 20px rgba(23, 48, 73, .035);
    }

    .dashboard-admin .gender-heading {
        align-self: start;
    }

    .dashboard-admin .gender-heading h3 {
        max-width: 350px;
        margin: 0;
        color: var(--da-ink);
        font-family: 'Manrope', sans-serif;
        font-size: 23px;
        font-weight: 800;
        letter-spacing: -.6px;
        line-height: 1.4;
    }

    .dashboard-admin .gender-heading p {
        max-width: 370px;
        margin: 10px 0 0;
        color: var(--da-muted);
        font-size: 15px;
        line-height: 1.8;
    }

    .dashboard-admin .gender-total {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 22px;
        padding: 10px 13px;
        color: var(--da-navy-2);
        background: #f0f5fa;
        border: 1px solid #e2eaf2;
        border-radius: 9px;
        font-size: 13px;
    }

    .dashboard-admin .gender-total strong {
        font-weight: 800;
    }

    .dashboard-admin .gender-chart-area {
        min-width: 0;
    }

    .dashboard-admin .gender-chart-wrapper {
        position: relative;
        width: 100%;
        max-width: 440px;
        height: 260px;
        margin: 0 auto 18px;
    }

    .dashboard-admin .gender-chart-wrapper canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
    }

    .dashboard-admin .gender-legend {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .dashboard-admin .gender-legend-item {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        padding: 15px;
        background: #f8fafc;
        border: 1px solid var(--da-line);
        border-radius: 11px;
    }

    .dashboard-admin .gender-dot {
        flex: 0 0 12px;
        width: 12px;
        height: 12px;
        border-radius: 4px;
    }

    .dashboard-admin .gender-legend-label {
        color: var(--da-muted);
        font-size: 13px;
        font-weight: 600;
    }

    .dashboard-admin .gender-legend-value {
        display: block;
        margin-top: 3px;
        color: var(--da-ink);
        font-family: 'Manrope', sans-serif;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.45;
    }

    /* =========================================================
       CONTENT PANELS
    ========================================================= */

    .dashboard-admin .content-card {
        min-width: 0;
        margin-bottom: 25px;
        padding: 30px;
        background: #fff;
        border: 1px solid var(--da-line);
        border-radius: 17px;
        box-shadow: 0 5px 20px rgba(23, 48, 73, .035);
    }

    .dashboard-admin .panel-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 23px;
    }

    .dashboard-admin .panel-heading-copy {
        max-width: 760px;
    }

    .dashboard-admin .panel-number {
        display: inline-flex;
        flex-shrink: 0;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        color: #536a83;
        background: #f2f6fa;
        border: 1px solid #e4ebf2;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .dashboard-admin .panel-number::before {
        width: 7px;
        height: 7px;
        content: "";
        background: var(--da-teal);
        border-radius: 50%;
    }

    /* =========================================================
       FILTER FORM
    ========================================================= */

    .dashboard-admin .filter-form {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 22px;
        padding: 20px;
        background: #f6f9fc;
        border: 1px solid #e5edf4;
        border-radius: 13px;
    }

    .dashboard-admin .filter-field {
        display: flex;
        flex: 1 1 210px;
        flex-direction: column;
        gap: 8px;
        min-width: 160px;
    }

    .dashboard-admin .filter-field label {
        color: #405771;
        font-size: 13px;
        font-weight: 800;
    }

    .dashboard-admin .filter-field select {
        width: 100%;
        min-height: 48px;
        padding: 10px 40px 10px 14px;
        color: var(--da-ink);
        background-color: #fff;
        border: 1px solid #d6e1ec;
        border-radius: 9px;
        font-family: inherit;
        font-size: 14px;
        outline: none;
        cursor: pointer;
        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .dashboard-admin .filter-field select:hover {
        border-color: #9fb7d0;
    }

    .dashboard-admin .filter-field select:focus {
        border-color: var(--da-blue);
        box-shadow: 0 0 0 4px rgba(36, 99, 168, .11);
    }

    .dashboard-admin .filter-button,
    .dashboard-admin .reset-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 48px;
        padding: 11px 19px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition:
            background .2s ease,
            color .2s ease,
            border-color .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .dashboard-admin .filter-button {
        color: #fff;
        background: var(--da-blue);
        border-color: var(--da-blue);
        box-shadow: 0 4px 10px rgba(36, 99, 168, .14);
    }

    .dashboard-admin .filter-button svg {
        width: 18px;
        height: 18px;
    }

    .dashboard-admin .filter-button:hover {
        color: #fff;
        background: #194d86;
        border-color: #194d86;
        box-shadow: 0 7px 16px rgba(36, 99, 168, .2);
        transform: translateY(-2px);
    }

    .dashboard-admin .filter-button:active,
    .dashboard-admin .reset-button:active {
        transform: translateY(0);
    }

    .dashboard-admin .reset-button {
        color: #536a83;
        background: #fff;
        border-color: #d7e1eb;
    }

    .dashboard-admin .reset-button:hover {
        color: var(--da-blue);
        background: #edf4fb;
        border-color: #c6d8eb;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .dashboard-admin .table-responsive {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e3eaf1;
        border-radius: 12px;
        scrollbar-width: thin;
        scrollbar-color: #b8c8d9 #f1f5f8;
    }

    .dashboard-admin .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .dashboard-admin .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f8;
    }

    .dashboard-admin .table-responsive::-webkit-scrollbar-thumb {
        background: #b8c8d9;
        border-radius: 20px;
    }

    .dashboard-admin table {
        width: 100%;
        min-width: 540px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        text-align: left;
    }

    .dashboard-admin thead th {
        padding: 16px 18px;
        color: #62758c;
        background: #f3f7fb;
        border-bottom: 1px solid #e2eaf2;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .6px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dashboard-admin tbody td {
        padding: 16px 18px;
        color: var(--da-text);
        border-bottom: 1px solid #edf1f5;
        font-size: 14px;
        line-height: 1.7;
        vertical-align: middle;
    }

    .dashboard-admin tbody tr {
        transition: background .18s ease;
    }

    .dashboard-admin tbody tr:hover {
        background: #f7faff;
    }

    .dashboard-admin tbody tr:last-child td {
        border-bottom: 0;
    }

    .dashboard-admin tbody td:first-child {
        color: #263e5b;
        font-weight: 700;
    }

    .dashboard-admin .number-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 43px;
        min-height: 32px;
        padding: 5px 11px;
        color: #245d9b;
        background: #eaf2fb;
        border: 1px solid #dce9f7;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-admin .participant-name {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 200px;
        color: var(--da-ink);
        font-weight: 700;
    }

    .dashboard-admin .participant-icon {
        display: inline-flex;
        flex: 0 0 38px;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        color: var(--da-teal);
        background: var(--da-teal-light);
        border: 1px solid #d6eeea;
        border-radius: 11px;
    }

    .dashboard-admin .participant-icon svg {
        width: 20px;
        height: 20px;
    }

    .dashboard-admin .empty-state {
        padding: 32px 18px;
        color: #78889c;
        text-align: center;
        font-size: 14px;
        line-height: 1.8;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .dashboard-admin .laravel-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 20px;
    }

    .dashboard-admin .laravel-pagination nav {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 6px;
    }

    .dashboard-admin .laravel-pagination p {
        margin: 0;
        color: var(--da-muted);
        font-size: 13px;
    }

    .dashboard-admin .laravel-pagination a,
    .dashboard-admin .laravel-pagination span[aria-current="page"] span,
    .dashboard-admin .laravel-pagination span[aria-disabled="true"] span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 37px;
        min-height: 37px;
        padding: 6px 12px;
        color: #536a83;
        background: #fff;
        border: 1px solid #dce5ee;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
    }

    .dashboard-admin .laravel-pagination a:hover {
        color: var(--da-blue);
        background: #edf4fb;
        border-color: #cbdced;
    }

    .dashboard-admin .laravel-pagination span[aria-current="page"] span {
        color: #fff;
        background: var(--da-blue);
        border-color: var(--da-blue);
    }

    .dashboard-admin .laravel-pagination span[aria-disabled="true"] span {
        opacity: .45;
    }

    /* =========================================================
       NOTE / PROFESSION SUMMARY
    ========================================================= */

    .dashboard-admin .info-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 20px;
        padding: 16px 18px;
        color: #526b84;
        background: #f0f6fb;
        border: 1px solid #dfebf5;
        border-left: 4px solid #4d91bd;
        border-radius: 9px;
        font-size: 13px;
        line-height: 1.8;
    }

    .dashboard-admin .info-note-icon {
        display: flex;
        flex: 0 0 23px;
        align-items: center;
        justify-content: center;
        width: 23px;
        height: 23px;
        color: #397da9;
    }

    .dashboard-admin .info-note-icon svg {
        width: 19px;
        height: 19px;
    }

    .dashboard-admin .info-note strong {
        color: #294967;
    }

    .dashboard-admin .profession-summary {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 19px;
    }

    .dashboard-admin .profession-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 13px;
        color: #087a7e;
        background: #e8f5f3;
        border: 1px solid #d2ebe7;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
    }

    .dashboard-admin .profession-chip strong {
        color: #075f63;
        font-weight: 800;
    }

    /* =========================================================
       EMPTY YEAR STATE
    ========================================================= */

    .dashboard-admin .no-year-data {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 235px;
        margin-bottom: 30px;
        padding: 35px 25px;
        background: #fff;
        border: 1px dashed #c8d6e4;
        border-radius: 16px;
        text-align: center;
    }

    .dashboard-admin .no-year-data .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 57px;
        height: 57px;
        margin-bottom: 15px;
        color: #6585a7;
        background: #edf3f9;
        border: 1px solid #e0eaf3;
        border-radius: 16px;
    }

    .dashboard-admin .no-year-data .empty-icon svg {
        width: 26px;
        height: 26px;
    }

    .dashboard-admin .no-year-data h3 {
        margin: 0;
        color: var(--da-ink);
        font-family: 'Manrope', sans-serif;
        font-size: 20px;
        font-weight: 800;
    }

    .dashboard-admin .no-year-data p {
        max-width: 520px;
        margin: 9px 0 0;
        color: var(--da-muted);
        font-size: 14px;
        line-height: 1.8;
    }

    /* =========================================================
       ACCESSIBILITY
    ========================================================= */

    .dashboard-admin :focus-visible {
        outline: 3px solid rgba(8, 127, 131, .42);
        outline-offset: 3px;
    }

    .dashboard-admin .filter-button:focus-visible,
    .dashboard-admin .reset-button:focus-visible {
        outline-offset: 4px;
    }

    /* =========================================================
       RESPONSIVE TABLET
    ========================================================= */

    @media (max-width: 900px) {
        .dashboard-admin .dashboard-heading {
            padding: 35px;
        }

        .dashboard-admin .gender-card {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .dashboard-admin .gender-heading h3,
        .dashboard-admin .gender-heading p {
            max-width: 650px;
        }

        .dashboard-admin .gender-total {
            margin-top: 15px;
        }

        .dashboard-admin .gender-chart-wrapper {
            max-width: 500px;
        }
    }

    /* =========================================================
       RESPONSIVE MOBILE
    ========================================================= */

    @media (max-width: 640px) {
        .dashboard-admin {
            padding-top: 3px;
            padding-bottom: 25px;
        }

        .dashboard-admin .dashboard-heading {
            grid-template-columns: minmax(0, 1fr);
            gap: 18px;
            min-height: auto;
            margin-bottom: 29px;
            padding: 29px 23px 32px;
            border-radius: 17px;
        }

        .dashboard-admin .dashboard-heading::before {
            top: auto;
            right: -130px;
            bottom: -200px;
            width: 330px;
            height: 330px;
        }

        .dashboard-admin .dashboard-heading::after {
            width: 65%;
        }

        .dashboard-admin .heading-eyebrow {
            margin-bottom: 13px;
            font-size: 10px;
            letter-spacing: 1.5px;
        }

        .dashboard-admin .dashboard-heading h1 {
            font-size: clamp(27px, 7vw, 34px);
            letter-spacing: -1.2px;
        }

        .dashboard-admin .dashboard-heading p {
            margin-top: 12px;
            font-size: 14px;
            line-height: 1.8;
        }

        .dashboard-admin .heading-mark {
            position: absolute;
            right: 24px;
            bottom: 24px;
            width: 60px;
            height: 60px;
            margin: 0;
            border-radius: 16px;
            opacity: .7;
        }

        .dashboard-admin .heading-mark::before {
            inset: -6px;
            border-radius: 13px;
        }

        .dashboard-admin .heading-mark::after {
            inset: 7px;
            border-radius: 10px;
        }

        .dashboard-admin .heading-mark svg {
            width: 28px;
            height: 28px;
        }

        .dashboard-admin .section-intro {
            align-items: flex-start;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 16px;
        }

        .dashboard-admin .section-intro h2,
        .dashboard-admin .panel-heading h2 {
            font-size: 21px;
        }

        .dashboard-admin .section-intro p,
        .dashboard-admin .panel-heading p {
            font-size: 14px;
        }

        .dashboard-admin .section-year {
            font-size: 12px;
        }

        .dashboard-admin .stats-grid {
            grid-template-columns: 1fr;
            gap: 12px;
            margin-bottom: 18px;
        }

        .dashboard-admin .stat-card {
            min-height: 190px;
            padding: 22px;
            border-radius: 14px;
        }

        .dashboard-admin .stat-topline {
            margin-bottom: 17px;
        }

        .dashboard-admin .stat-value {
            font-size: 39px;
        }

        .dashboard-admin .stat-caption {
            max-width: 90%;
            font-size: 13px;
        }

        .dashboard-admin .gender-card {
            gap: 19px;
            margin-bottom: 27px;
            padding: 22px 17px;
            border-radius: 14px;
        }

        .dashboard-admin .gender-heading h3 {
            font-size: 21px;
        }

        .dashboard-admin .gender-heading p {
            font-size: 14px;
        }

        .dashboard-admin .gender-chart-wrapper {
            height: 235px;
            margin-bottom: 15px;
        }

        .dashboard-admin .gender-legend {
            grid-template-columns: 1fr;
            gap: 9px;
        }

        .dashboard-admin .gender-legend-item {
            padding: 13px;
        }

        .dashboard-admin .gender-total {
            align-items: flex-start;
            flex-wrap: wrap;
            font-size: 12px;
        }

        .dashboard-admin .content-card {
            margin-bottom: 19px;
            padding: 22px 16px;
            border-radius: 14px;
        }

        .dashboard-admin .panel-heading {
            flex-direction: column;
            gap: 12px;
            margin-bottom: 19px;
        }

        .dashboard-admin .panel-number {
            font-size: 11px;
        }

        .dashboard-admin .filter-form {
            align-items: stretch;
            flex-direction: column;
            gap: 13px;
            padding: 15px;
        }

        .dashboard-admin .filter-field {
            flex: 1 1 auto;
            min-width: 100%;
        }

        .dashboard-admin .filter-field label {
            font-size: 13px;
        }

        .dashboard-admin .filter-field select {
            min-height: 47px;
            font-size: 14px;
        }

        .dashboard-admin .filter-button,
        .dashboard-admin .reset-button {
            width: 100%;
            min-height: 47px;
            font-size: 14px;
        }

        .dashboard-admin .table-responsive {
            border-radius: 10px;
        }

        .dashboard-admin thead th {
            padding: 14px;
            font-size: 11px;
        }

        .dashboard-admin tbody td {
            padding: 14px;
            font-size: 13px;
        }

        .dashboard-admin .profession-summary {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

        .dashboard-admin .profession-chip {
            max-width: 100%;
            font-size: 12px;
        }

        .dashboard-admin .info-note {
            padding: 14px;
            font-size: 12px;
        }

        .dashboard-admin .no-year-data {
            min-height: 210px;
            padding: 27px 18px;
        }

        .dashboard-admin .no-year-data h3 {
            font-size: 18px;
        }

        .dashboard-admin .no-year-data p {
            font-size: 13px;
        }

        .dashboard-admin .laravel-pagination {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-admin .laravel-pagination nav {
            justify-content: flex-start;
        }
    }

    @media (max-width: 380px) {
        .dashboard-admin .dashboard-heading {
            padding: 25px 18px 29px;
        }

        .dashboard-admin .dashboard-heading h1 {
            font-size: 26px;
        }

        .dashboard-admin .heading-mark {
            display: none;
        }

        .dashboard-admin .stat-value {
            font-size: 35px;
        }

        .dashboard-admin .content-card {
            padding: 19px 13px;
        }

        .dashboard-admin .gender-card {
            padding: 19px 13px;
        }
    }

    /* =========================================================
       MOTION PREFERENCES
    ========================================================= */

    @media (prefers-reduced-motion: reduce) {
        .dashboard-admin *,
        .dashboard-admin *::before,
        .dashboard-admin *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

@php
    /*
    |--------------------------------------------------------------------------
    | DATA DEFAULTS
    |--------------------------------------------------------------------------
    | Menjaga halaman tetap aman jika sebagian data belum dikirim controller.
    */

    $totalParticipants = (int) ($totalParticipants ?? 0);
    $totalTrainings = (int) ($totalTrainings ?? 0);

    $year = $year ?? now()->year;
    $years = $years ?? collect([$year]);

    $sort = $sort ?? 'desc';
    $selectedProfession = $selectedProfession ?? '';

    $frequentParticipants = $frequentParticipants ?? collect();
    $professionOptions = $professionOptions ?? collect();
    $trainedParticipants = $trainedParticipants ?? collect();

    $genderStats = $genderStats ?? [
        'L' => 0,
        'P' => 0,
    ];

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

    /*
     * Gunakan LengthAwarePaginator untuk data yang memiliki total().
     * Collection biasa tetap bisa ditampilkan tanpa pagination.
     */
    $isFrequentPaginated = $frequentParticipants instanceof
        \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    $isTrainedPaginated = $trainedParticipants instanceof
        \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    $trainedParticipantTotal = $isTrainedPaginated
        ? $trainedParticipants->total()
        : $trainedParticipants->count();

    $trainedParticipantStart = method_exists($trainedParticipants, 'firstItem')
        ? ($trainedParticipants->firstItem() ?? 1)
        : 1;
@endphp

<div class="dashboard-admin">

    {{-- =====================================================
         HEADER DASHBOARD
    ====================================================== --}}

    <header class="dashboard-heading">
        <div class="heading-copy">
            <div class="heading-eyebrow">
                Sistem Informasi Pelatihan
            </div>

            <h1>Dashboard Admin SIRAPIK</h1>

            <p>
                Pusat kendali data peserta dan program pelatihan.
                Pantau perkembangan, evaluasi keikutsertaan, serta
                telusuri data peserta berdasarkan tahun dan jenis profesi.
            </p>
        </div>

        <div class="heading-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="5" rx="1.5"/>
                <rect x="14" y="12" width="7" height="9" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            </svg>
        </div>
    </header>


    {{-- =====================================================
         RINGKASAN DATA
    ====================================================== --}}

    <section aria-labelledby="summary-title">

        <div class="section-intro">
            <div>
                <span class="section-kicker">Ringkasan data</span>
                <h2 id="summary-title">Gambaran Umum SIRAPIK</h2>
                <p>
                    Informasi peserta dan pelatihan pada tahun {{ $year }}.
                </p>
            </div>

            <div class="section-year">
                Periode {{ $year }}
            </div>
        </div>

        @if ($hasYearData)

            <div class="stats-grid">

                {{-- TOTAL PESERTA --}}
                <article class="stat-card"
                         style="--stat-accent:#2463a8;--stat-soft:#eaf2fb;">

                    <div class="stat-topline">
                        <span class="stat-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="10" cy="7" r="4"/>
                                <path d="M2 21v-2a8 8 0 0 1 16 0v2"/>
                                <path d="M19 8a3 3 0 0 1 0 6"/>
                                <path d="M22 21v-2a5 5 0 0 0-3-4.58"/>
                            </svg>
                        </span>

                        <span class="stat-index">01 / PESERTA</span>
                    </div>

                    <div class="stat-label">
                        Peserta Terlatih Tahun {{ $year }}
                    </div>

                    <div class="stat-value">
                        {{ number_format($totalParticipants, 0, ',', '.') }}
                    </div>

                    <div class="stat-caption">
                        Jumlah peserta unik yang mengikuti pelatihan
                        pada tahun terpilih.
                    </div>
                </article>


                {{-- TOTAL PELATIHAN --}}
                <article class="stat-card"
                         style="--stat-accent:#087f83;--stat-soft:#e8f5f3;">

                    <div class="stat-topline">
                        <span class="stat-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                                <path d="M8 7h8M8 11h7"/>
                            </svg>
                        </span>

                        <span class="stat-index">02 / PROGRAM</span>
                    </div>

                    <div class="stat-label">
                        Total Pelatihan Tahun {{ $year }}
                    </div>

                    <div class="stat-value">
                        {{ number_format($totalTrainings, 0, ',', '.') }}
                    </div>

                    <div class="stat-caption">
                        Jumlah program pelatihan yang dilaksanakan
                        pada tahun terpilih.
                    </div>
                </article>

            </div>


            {{-- =================================================
                 DIAGRAM JENIS KELAMIN
            ================================================== --}}

            @if ($genderTotal > 0)

                <section class="gender-card" aria-labelledby="gender-title">

                    <div class="gender-heading">
                        <span class="section-kicker">Komposisi peserta</span>

                        <h3 id="gender-title">
                            Profil Peserta Berdasarkan Jenis Kelamin
                        </h3>

                        <p>
                            Perbandingan jumlah peserta laki-laki dan
                            perempuan yang mengikuti pelatihan pada
                            tahun {{ $year }}.
                        </p>

                        <div class="gender-total">
                            Total peserta:
                            <strong>
                                {{ number_format($genderTotal, 0, ',', '.') }}
                                orang
                            </strong>
                        </div>
                    </div>


                    <div class="gender-chart-area">

                        <div class="gender-chart-wrapper">
                            <canvas
                                id="genderPieChart"
                                role="img"
                                aria-label="Diagram lingkaran jenis kelamin peserta tahun {{ $year }}">
                            </canvas>
                        </div>

                        <div class="gender-legend">

                            <div class="gender-legend-item">
                                <span class="gender-dot"
                                      style="background:#2463a8;"></span>

                                <div>
                                    <div class="gender-legend-label">
                                        Laki-laki
                                    </div>

                                    <span class="gender-legend-value">
                                        {{ number_format($malePercent, 1, ',', '.') }}%
                                        ({{ number_format($maleCount, 0, ',', '.') }})
                                    </span>
                                </div>
                            </div>


                            <div class="gender-legend-item">
                                <span class="gender-dot"
                                      style="background:#d96b79;"></span>

                                <div>
                                    <div class="gender-legend-label">
                                        Perempuan
                                    </div>

                                    <span class="gender-legend-value">
                                        {{ number_format($femalePercent, 1, ',', '.') }}%
                                        ({{ number_format($femaleCount, 0, ',', '.') }})
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                </section>

            @endif

        @else

            {{-- EMPTY STATE DATA TAHUN --}}
            <div class="no-year-data">

                <div class="empty-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round">
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


    {{-- =====================================================
         JUMLAH KEIKUTSERTAAN PESERTA
    ====================================================== --}}

    <section class="content-card" aria-labelledby="frequent-title">

        <div class="panel-heading">

            <div class="panel-heading-copy">
                <span class="section-kicker">Pemantauan peserta</span>

                <h2 id="frequent-title">
                    Jumlah Keikutsertaan Peserta dalam Pelatihan
                </h2>

                <p>
                    Telusuri jumlah pelatihan yang diikuti setiap peserta
                    berdasarkan tahun dan urutan jumlah keikutsertaan.
                </p>
            </div>

            <div class="panel-number">
                Data peserta
            </div>

        </div>


        {{-- FILTER KEIKUTSERTAAN --}}
        <form method="GET"
              action="{{ url()->current() }}"
              class="filter-form">

            <div class="filter-field">
                <label for="participant-year">
                    Tahun Pelatihan
                </label>

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
                <label for="participant-sort">
                    Urutan Jumlah Pelatihan
                </label>

                <select id="participant-sort" name="sort">
                    <option value="asc"
                        {{ $sort === 'asc' ? 'selected' : '' }}>
                        Jumlah Terendah
                    </option>

                    <option value="desc"
                        {{ $sort === 'desc' ? 'selected' : '' }}>
                        Jumlah Tertinggi
                    </option>
                </select>
            </div>


            <button type="submit" class="filter-button">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M3 4h18l-7 8v6l-4 2v-8L3 4Z"/>
                </svg>

                Terapkan Filter
            </button>


            <a href="{{ url()->current() }}" class="reset-button">
                Reset
            </a>

        </form>


        {{-- TABEL PESERTA --}}
        <div class="table-responsive">

            <table id="participantTable">

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
                            <td>
                                {{ $participant->name ?? '-' }}
                            </td>

                            <td>
                                {{ $participant->nip ?? '-' }}
                            </td>

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


        {{-- PAGINATION KEIKUTSERTAAN --}}
        @if ($isFrequentPaginated)

            <div class="laravel-pagination">

                <p>
                    Menampilkan
                    {{ $frequentParticipants->firstItem() ?? 0 }}
                    sampai
                    {{ $frequentParticipants->lastItem() ?? 0 }}
                    dari
                    {{ $frequentParticipants->total() }}
                    peserta
                </p>

                {{ $frequentParticipants->onEachSide(1)->links() }}

            </div>

        @endif


        {{-- CATATAN --}}
        <div class="info-note">

            <span class="info-note-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 11v5m0-8h.01"/>
                </svg>
            </span>

            <div>
                <strong>Catatan:</strong>
                Jumlah pelatihan dihitung berdasarkan riwayat keikutsertaan
                peserta pada tahun yang dipilih. Gunakan pilihan urutan untuk
                melihat peserta dengan jumlah pelatihan terendah atau tertinggi.
            </div>

        </div>

    </section>


    {{-- =====================================================
         PESERTA BERDASARKAN PROFESI
    ====================================================== --}}

    <section class="content-card" aria-labelledby="profession-title">

        <div class="panel-heading">

            <div class="panel-heading-copy">
                <span class="section-kicker">Data peserta terlatih</span>

                <h2 id="profession-title">
                    Peserta Terlatih Berdasarkan Jenis Profesi
                </h2>

                <p>
                    Temukan daftar peserta berdasarkan kategori profesi
                    dan tahun pelaksanaan pelatihan.
                </p>
            </div>

            <div class="panel-number">
                Data profesi
            </div>

        </div>


        {{-- FILTER PROFESI --}}
        <form method="GET"
              action="{{ url()->current() }}"
              class="filter-form">

            <div class="filter-field">
                <label for="profession-year">
                    Tahun Pelatihan
                </label>

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
                <label for="profession">
                    Jenis Profesi
                </label>

                <select id="profession" name="profession" required>

                    <option value="">
                        -- Pilih Profesi --
                    </option>

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

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M3 4h18l-7 8v6l-4 2v-8L3 4Z"/>
                </svg>

                Tampilkan Peserta

            </button>


            <a href="{{ url()->current() }}" class="reset-button">
                Reset
            </a>

        </form>


        @if ($selectedProfession !== '')

            {{-- RINGKASAN FILTER AKTIF --}}
            <div class="profession-summary">

                <span class="profession-chip">
                    Profesi:
                    <strong>{{ $selectedProfession }}</strong>
                </span>

                <span class="profession-chip">
                    Tahun:
                    <strong>{{ $year }}</strong>
                </span>

                <span class="profession-chip">
                    Jumlah peserta:
                    <strong>
                        {{ number_format($trainedParticipantTotal, 0, ',', '.') }}
                    </strong>
                </span>

            </div>


            {{-- TABEL PESERTA PROFESI --}}
            <div class="table-responsive">

                <table id="professionTable">

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

                                <td>
                                    {{ $trainedParticipantStart + $index }}
                                </td>


                                <td>
                                    <div class="participant-name">

                                        <span class="participant-icon"
                                              aria-hidden="true">

                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="1.8"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <circle cx="12" cy="8" r="4"/>
                                                <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                                            </svg>

                                        </span>

                                        {{ $participant->name ?? '-' }}

                                    </div>
                                </td>


                                <td>
                                    {{ $participant->nip ?? '-' }}
                                </td>


                                <td>
                                    {{ $participant->profession ?? '-' }}
                                </td>


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
                                    "{{ $selectedProfession }}"
                                    pada tahun {{ $year }}.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION PROFESI --}}
            @if ($isTrainedPaginated)

                <div class="laravel-pagination">

                    <p>
                        Menampilkan
                        {{ $trainedParticipants->firstItem() ?? 0 }}
                        sampai
                        {{ $trainedParticipants->lastItem() ?? 0 }}
                        dari
                        {{ $trainedParticipants->total() }}
                        peserta
                    </p>

                    {{ $trainedParticipants->onEachSide(1)->links() }}

                </div>

            @endif

        @else

            {{-- EMPTY STATE SEBELUM PROFESI DIPILIH --}}
            <div class="empty-state">
                Pilih tahun dan jenis profesi, kemudian klik
                <strong>Tampilkan Peserta</strong>
                untuk melihat daftar peserta.
            </div>

        @endif

    </section>

</div>


{{-- =========================================================
     CHART.JS
========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('genderPieChart');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    const maleCount = Number(@json($maleCount));
    const femaleCount = Number(@json($femaleCount));
    const total = maleCount + femaleCount;

    new Chart(canvas, {
        type: 'doughnut',

        data: {
            labels: ['Laki-laki', 'Perempuan'],

            datasets: [{
                data: [maleCount, femaleCount],

                backgroundColor: [
                    '#2463a8',
                    '#d96b79'
                ],

                borderColor: '#ffffff',
                borderWidth: 5,
                hoverOffset: 10,
                borderRadius: 5,
                spacing: 3
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '64%',

            animation: {
                duration: 850,
                easing: 'easeOutQuart',
                animateRotate: true,
                animateScale: true
            },

            interaction: {
                intersect: false
            },

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    backgroundColor: '#102d50',
                    titleFont: {
                        family: 'DM Sans',
                        size: 14,
                        weight: '700'
                    },
                    bodyFont: {
                        family: 'DM Sans',
                        size: 13
                    },
                    padding: 13,
                    cornerRadius: 9,
                    displayColors: true,

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

});
</script>

@endsection