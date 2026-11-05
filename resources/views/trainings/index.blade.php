@extends('layouts.app')

@section('title', 'Data Pelatihan')

@section('content')

<style>
    /* =====================================================
       EXECUTIVE BLUE — TRAINING MANAGEMENT
       ===================================================== */

    .training-page {
        --tp-navy: #102b50;
        --tp-blue: #2463a6;
        --tp-blue-dark: #194d86;
        --tp-blue-soft: #eaf2fc;
        --tp-ink: #1b304b;
        --tp-muted: #718198;
        --tp-line: #e3eaf2;
        --tp-surface: #ffffff;
        --tp-bg: #f5f8fc;
        --tp-green: #16845b;
        --tp-red: #d94b55;

        width: 100%;
        color: var(--tp-ink);
        font-family: 'Inter', 'Segoe UI', sans-serif;
        font-size: 15px;
        animation: tpPageEnter .55s ease both;
    }

    .training-page *,
    .training-page *::before,
    .training-page *::after {
        box-sizing: border-box;
    }

    .training-page button,
    .training-page input,
    .training-page select {
        font: inherit;
    }

    .training-page a,
    .training-page button {
        -webkit-tap-highlight-color: transparent;
    }

    @keyframes tpPageEnter {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =====================================================
       PAGE HEADER
       ===================================================== */

    .training-page .page-heading {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 24px;
        margin-bottom: 30px;
        padding: 5px 0 25px;
        border-bottom: 1px solid var(--tp-line);
    }

    .training-page .heading-content {
        min-width: 0;
    }

    .training-page .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 13px;
        color: var(--tp-blue);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .training-page .eyebrow::before {
        content: '';
        width: 27px;
        height: 3px;
        border-radius: 10px;
        background: var(--tp-blue);
    }

    .training-page .page-heading h1 {
        margin: 0;
        color: var(--tp-navy);
        font-size: clamp(30px, 3.2vw, 42px);
        font-weight: 850;
        line-height: 1.2;
        letter-spacing: -1.7px;
    }

    .training-page .page-heading p {
        max-width: 550px;
        margin: 12px 0 0;
        color: var(--tp-muted);
        font-size: 15px;
        line-height: 1.8;
    }

    .training-page .heading-decoration {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        gap: 12px;
        padding: 13px 17px;
        color: var(--tp-blue);
        background: var(--tp-blue-soft);
        border: 1px solid #d8e7f8;
        border-radius: 12px;
    }

    .training-page .heading-decoration svg {
        width: 24px;
        height: 24px;
    }

    .training-page .heading-decoration span {
        color: var(--tp-navy);
        font-size: 13px;
        font-weight: 750;
    }

    /* =====================================================
       NOTIFICATIONS
       ===================================================== */

    .training-page .notice {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
        padding: 17px 19px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.8;
        animation: tpPageEnter .3s ease both;
    }

    .training-page .notice svg {
        flex-shrink: 0;
        width: 21px;
        height: 21px;
        margin-top: 1px;
    }

    .training-page .notice-success {
        color: #176747;
        background: #edfaf4;
        border: 1px solid #c8efdc;
    }

    .training-page .notice-error {
        color: #a12f3b;
        background: #fff1f2;
        border: 1px solid #f7d1d5;
    }

    .training-page .notice ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    /* =====================================================
       YEAR FILTER
       ===================================================== */

    .training-page .year-filter {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 25px;
        margin-bottom: 22px;
        padding: 23px 25px;
        overflow: hidden;
        background: var(--tp-surface);
        border: 1px solid var(--tp-line);
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(21, 47, 81, .035);
    }

    .training-page .year-filter::after {
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--tp-blue);
        content: '';
    }

    .training-page .filter-intro {
        min-width: 170px;
    }

    .training-page .filter-title {
        display: block;
        margin-bottom: 5px;
        color: var(--tp-navy);
        font-size: 16px;
        font-weight: 800;
    }

    .training-page .filter-description {
        color: var(--tp-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .training-page .year-filter-form {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 10px;
    }

    .training-page .year-select-wrap {
        width: 220px;
    }

    .training-page .year-select,
    .training-page .search-input {
        display: block;
        width: 100%;
        height: 49px;
        padding: 0 15px;
        color: var(--tp-navy);
        background: #f9fbfe;
        border: 1px solid #dce5ef;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: border .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .training-page .year-select {
        cursor: pointer;
    }

    .training-page .year-select:focus,
    .training-page .search-input:focus {
        background: #fff;
        border-color: var(--tp-blue);
        box-shadow: 0 0 0 4px rgba(36, 99, 166, .10);
    }

    .training-page .search-input::placeholder {
        color: #98a6b8;
    }

    /* =====================================================
       BUTTONS
       ===================================================== */

    .training-page .action-button {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 9px;
        min-height: 46px;
        padding: 11px 17px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 750;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition:
            transform .2s ease,
            background .2s ease,
            color .2s ease,
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .training-page .action-button svg {
        width: 17px;
        height: 17px;
        flex-shrink: 0;
    }

    .training-page .action-button:active {
        transform: scale(.97);
    }

    .training-page .btn-primary {
        color: #fff;
        background: var(--tp-blue);
        border-color: var(--tp-blue);
        box-shadow: 0 4px 10px rgba(36, 99, 166, .14);
    }

    .training-page .btn-primary:hover {
        color: #fff;
        background: var(--tp-blue-dark);
        border-color: var(--tp-blue-dark);
        box-shadow: 0 6px 16px rgba(36, 99, 166, .2);
        transform: translateY(-2px);
    }

    .training-page .btn-light {
        color: var(--tp-blue-dark);
        background: #f0f5fb;
        border-color: #e1eaf5;
    }

    .training-page .btn-light:hover {
        color: var(--tp-navy);
        background: #e3edf9;
        border-color: #cfdef0;
        transform: translateY(-2px);
    }

    .training-page .delete-button {
        color: #c13d49;
        background: #fff2f3;
        border-color: #f8dfe2;
    }

    .training-page .delete-button:hover {
        color: #fff;
        background: var(--tp-red);
        border-color: var(--tp-red);
        transform: translateY(-2px);
    }

    /* =====================================================
       MAIN DATA PANEL
       ===================================================== */

    .training-page .panel {
        width: 100%;
        min-width: 0;
        padding: 25px;
        background: var(--tp-surface);
        border: 1px solid var(--tp-line);
        border-radius: 16px;
        box-shadow: 0 5px 24px rgba(21, 47, 81, .045);
    }

    .training-page .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        margin-bottom: 22px;
    }

    .training-page .panel-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
    }

    .training-page .panel-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 47px;
        height: 47px;
        flex-shrink: 0;
        color: var(--tp-blue);
        background: var(--tp-blue-soft);
        border-radius: 12px;
    }

    .training-page .panel-icon svg {
        width: 23px;
        height: 23px;
    }

    .training-page .panel-heading h2 {
        margin: 0;
        color: var(--tp-navy);
        font-size: 20px;
        font-weight: 850;
        letter-spacing: -.5px;
    }

    .training-page .panel-heading p {
        margin: 5px 0 0;
        color: var(--tp-muted);
        font-size: 13px;
    }

    .training-page .panel-indicator {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        color: #24704f;
        background: #eef9f3;
        border: 1px solid #d8efe2;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .training-page .indicator-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #2da878;
        box-shadow: 0 0 0 3px rgba(45, 168, 120, .12);
    }

    /* =====================================================
       FILTER STATUS
       ===================================================== */

    .training-page .filter-information {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 19px;
        padding: 13px 15px;
        background: #f7f9fc;
        border: 1px solid #eaf0f6;
        border-radius: 10px;
    }

    .training-page .filter-label {
        color: var(--tp-muted);
        font-size: 13px;
        font-weight: 600;
    }

    .training-page .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        color: var(--tp-blue-dark);
        background: #e9f2fd;
        border: 1px solid #d7e6f8;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 750;
    }

    .training-page .filter-chip svg {
        width: 14px;
        height: 14px;
    }

    /* =====================================================
       SEARCH
       ===================================================== */

    .training-page .search-section {
        margin-bottom: 22px;
    }

    .training-page .search-title {
        display: block;
        margin-bottom: 10px;
        color: var(--tp-navy);
        font-size: 14px;
        font-weight: 800;
    }

    .training-page .search-form {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .training-page .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .training-page .search-input-wrap svg {
        position: absolute;
        top: 50%;
        left: 15px;
        width: 19px;
        height: 19px;
        color: #8294aa;
        pointer-events: none;
        transform: translateY(-50%);
    }

    .training-page .search-input {
        height: 49px;
        padding-left: 44px;
    }

    .training-page .search-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    /* =====================================================
       TABLE
       ===================================================== */

    .training-page .table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .training-page .table-heading h3 {
        margin: 0;
        color: var(--tp-navy);
        font-size: 15px;
        font-weight: 850;
    }

    .training-page .table-heading span {
        color: var(--tp-muted);
        font-size: 12px;
    }

    .training-page .table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e2eaf3;
        border-radius: 12px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #bdcde0 #f2f5f9;
    }

    .training-page table {
        width: 100%;
        min-width: 1280px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
    }

    .training-page thead th {
        padding: 17px 15px;
        color: #fff;
        background: var(--tp-navy);
        border-bottom: 3px solid #397bb9;
        font-size: 11px;
        font-weight: 800;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
        letter-spacing: .8px;
        text-transform: uppercase;
    }

    .training-page thead th:first-child {
        padding-left: 19px;
    }

    .training-page tbody td {
        padding: 19px 15px;
        color: #52647a;
        border-bottom: 1px solid #edf1f6;
        font-size: 13px;
        line-height: 1.75;
        vertical-align: middle;
        transition: background .2s ease;
    }

    .training-page tbody tr {
        transition: background .2s ease;
    }

    .training-page tbody tr:nth-child(even) {
        background: #f8fafd;
    }

    .training-page tbody tr:hover {
        background: #edf5ff;
    }

    .training-page tbody tr:last-child td {
        border-bottom: none;
    }

    .training-page tbody td:first-child {
        padding-left: 19px;
        color: var(--tp-blue);
        font-weight: 800;
    }

    .training-page tbody td:nth-child(2) {
        min-width: 235px;
        color: var(--tp-navy);
        font-weight: 750;
    }

    .training-page tbody td:nth-child(3),
    .training-page tbody td:nth-child(4) {
        min-width: 130px;
    }

    /* =====================================================
       ACTION GROUP
       ===================================================== */

    .training-page .action-group {
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
    }

    .training-page .action-group .action-button {
        min-height: 35px;
        padding: 8px 12px;
        font-size: 12px;
    }

    /* =====================================================
       EMPTY STATE
       ===================================================== */

    .training-page .empty-state {
        padding: 65px 20px !important;
        color: var(--tp-muted) !important;
        font-size: 14px !important;
        line-height: 1.9 !important;
        text-align: center;
    }

    .training-page .empty-state-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 64px;
        height: 64px;
        margin: 0 auto 17px;
        color: var(--tp-blue);
        background: var(--tp-blue-soft);
        border-radius: 18px;
    }

    .training-page .empty-state-icon svg {
        width: 30px;
        height: 30px;
    }

    .training-page .empty-state strong {
        display: block;
        margin-bottom: 5px;
        color: var(--tp-navy);
        font-size: 17px;
    }

    /* =====================================================
       PAGINATION
       ===================================================== */

    .training-page .pagination-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 22px;
        padding-top: 19px;
        border-top: 1px solid var(--tp-line);
        overflow-x: auto;
    }

    .training-page .pagination-wrap p {
        margin: 0;
        color: var(--tp-muted);
        font-size: 13px;
    }

    .training-page .pagination-wrap nav {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
    }

    .training-page .pagination-wrap nav > div:first-child {
        display: none;
    }

    .training-page .pagination-wrap nav > div:last-child {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
    }

    .training-page .pagination-wrap a,
    .training-page .pagination-wrap span {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-width: 36px;
        min-height: 36px;
        padding: 6px 10px;
        color: var(--tp-blue);
        background: #f3f7fc;
        border: 1px solid #e5edf6;
        border-radius: 8px;
        font-size: 13px;
        text-decoration: none;
        transition: .2s ease;
    }

    .training-page .pagination-wrap a:hover {
        color: #fff;
        background: var(--tp-blue);
        border-color: var(--tp-blue);
    }

    .training-page .pagination-wrap span[aria-current="page"] span {
        color: #fff;
        background: var(--tp-blue);
        border-color: var(--tp-blue);
    }

    .training-page .pagination-wrap svg {
        width: 17px;
        height: 17px;
    }

    /* =====================================================
       ACCESSIBILITY
       ===================================================== */

    .training-page :focus-visible {
        outline: 3px solid rgba(36, 99, 166, .35);
        outline-offset: 3px;
    }

    @media (prefers-reduced-motion: reduce) {
        .training-page *,
        .training-page *::before,
        .training-page *::after {
            animation-duration: .01ms !important;
            transition-duration: .01ms !important;
            scroll-behavior: auto !important;
        }
    }

    /* =====================================================
       TABLET
       ===================================================== */

    @media (max-width: 992px) {
        .training-page .year-filter {
            align-items: flex-start;
            flex-direction: column;
        }

        .training-page .year-filter-form {
            justify-content: flex-start;
            width: 100%;
        }

        .training-page .panel {
            padding: 21px;
        }
    }

    /* =====================================================
       MOBILE
       ===================================================== */

    @media (max-width: 768px) {
        .training-page {
            font-size: 14px;
        }

        .training-page .page-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 22px;
            padding-bottom: 20px;
        }

        .training-page .page-heading h1 {
            font-size: 32px;
            letter-spacing: -1.2px;
        }

        .training-page .page-heading p {
            margin-top: 9px;
            font-size: 14px;
        }

        .training-page .heading-decoration {
            padding: 10px 13px;
        }

        .training-page .year-filter {
            gap: 17px;
            padding: 19px;
            margin-bottom: 17px;
        }

        .training-page .filter-intro {
            width: 100%;
        }

        .training-page .year-filter-form {
            align-items: stretch;
            flex-direction: column;
        }

        .training-page .year-select-wrap {
            width: 100%;
        }

        .training-page .year-filter-form .action-button {
            width: 100%;
        }

        .training-page .panel {
            padding: 17px;
            border-radius: 13px;
        }

        .training-page .panel-header {
            align-items: flex-start;
            flex-direction: column;
            margin-bottom: 17px;
        }

        .training-page .panel-heading h2 {
            font-size: 18px;
        }

        .training-page .panel-indicator {
            margin-left: 60px;
        }

        .training-page .filter-information {
            align-items: flex-start;
            flex-direction: column;
        }

        .training-page .search-form {
            align-items: stretch;
            flex-direction: column;
        }

        .training-page .search-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .training-page .search-actions .action-button {
            width: 100%;
        }

        .training-page .table-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .training-page .table-wrap {
            border-radius: 9px;
        }

        .training-page table {
            min-width: 1280px;
        }

        .training-page thead th {
            padding: 15px 12px;
        }

        .training-page tbody td {
            padding: 16px 12px;
        }

        .training-page .pagination-wrap {
            align-items: flex-start;
            flex-direction: column;
        }

        .training-page .pagination-wrap nav {
            max-width: 100%;
        }
    }

    @media (max-width: 420px) {
        .training-page .page-heading h1 {
            font-size: 29px;
        }

        .training-page .eyebrow {
            font-size: 11px;
        }

        .training-page .panel {
            padding: 14px;
        }

        .training-page .panel-icon {
            width: 42px;
            height: 42px;
        }

        .training-page .panel-indicator {
            margin-left: 55px;
        }

        .training-page .filter-title {
            font-size: 15px;
        }

        .training-page .notice {
            padding: 14px;
        }
    }
</style>

@php
    $search = $search ?? '';
    $years = $years ?? collect();
    $year = $year ?? '';
@endphp

<div class="training-page">

    {{-- ==================================================
         PAGE HEADER
    ================================================== --}}

    <div class="page-heading">

        <div class="heading-content">

            <div class="eyebrow">
                DATA MASTER
            </div>

            <h1>Data Pelatihan</h1>

            <p>
                Kelola dan pantau seluruh informasi program pelatihan
                yang telah terdaftar dalam sistem.
            </p>

        </div>

        <div class="heading-decoration">

            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                <path d="M8 7h8M8 11h8"/>
            </svg>

            <span>Manajemen Pelatihan</span>

        </div>

    </div>


    {{-- ==================================================
         NOTIFICATIONS
    ================================================== --}}

    @if (session('success'))

        <div class="notice notice-success" role="status">

            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <path d="m9 11 3 3L22 4"/>
            </svg>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    @if (session('error'))

        <div class="notice notice-error" role="alert">

            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4m0 4h.01"/>
            </svg>

            <div>
                {{ session('error') }}
            </div>

        </div>

    @endif


    @if ($errors->any())

        <div class="notice notice-error" role="alert">

            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4m0 4h.01"/>
            </svg>

            <div>

                <strong>Terjadi kesalahan.</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- ==================================================
         YEAR FILTER
    ================================================== --}}

    <section class="year-filter">

        <div class="filter-intro">

            <label for="year" class="filter-title">
                Filter Tahun Pelatihan
            </label>

            <div class="filter-description">
                Pilih tahun untuk melihat data pelatihan tertentu.
            </div>

        </div>


        <form
            method="GET"
            action="{{ route('trainings.index') }}"
            class="year-filter-form"
        >

            @if ($search !== '')
                <input type="hidden" name="search" value="{{ $search }}">
            @endif


            <div class="year-select-wrap">

                <select
                    name="year"
                    id="year"
                    class="year-select"
                    aria-label="Pilih tahun pelatihan"
                >

                    <option value="" @selected($year === '')>
                        Semua Tahun
                    </option>

                    @foreach ($years as $availableYear)

                        <option
                            value="{{ $availableYear }}"
                            @selected((string) $year === (string) $availableYear)
                        >
                            {{ $availableYear }}
                        </option>

                    @endforeach

                </select>

            </div>


            <button type="submit" class="action-button btn-primary">

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16l-6.5 8v6l-3 2v-8L4 4Z"/>
                </svg>

                Terapkan Filter

            </button>


            <a
                href="{{ route('trainings.index', ['search' => $search ?: null]) }}"
                class="action-button btn-light"
            >

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                    <path d="M3 3v5h5"/>
                </svg>

                Reset

            </a>

        </form>

    </section>


    {{-- ==================================================
         MAIN DATA PANEL
    ================================================== --}}

    <section class="panel">


        {{-- PANEL HEADER --}}

        <div class="panel-header">

            <div class="panel-heading">

                <div class="panel-icon">

                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                        <path d="M8 2v4m8-4v4M3 10h18"/>
                        <path d="M8 14h3m2 0h3M8 18h3"/>
                    </svg>

                </div>

                <div>

                    <h2>Daftar Pelatihan</h2>

                    <p>
                        Informasi lengkap program pelatihan terdaftar.
                    </p>

                </div>

            </div>


            <div class="panel-indicator">

                <span class="indicator-dot"></span>

                Data Pelatihan

            </div>

        </div>


        {{-- ACTIVE FILTER --}}

        <div class="filter-information">

            <span class="filter-label">
                Filter aktif:
            </span>

            @if ($year)

                <span class="filter-chip">

                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                        <path d="M8 2v4m8-4v4M3 10h18"/>
                    </svg>

                    Tahun {{ $year }}

                </span>

            @else

                <span class="filter-chip">

                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 5h16M4 12h16M4 19h16"/>
                    </svg>

                    Semua Tahun

                </span>

            @endif

        </div>


        {{-- ==================================================
             SEARCH SECTION
        ================================================== --}}

        <div class="search-section">

            <label for="training-search" class="search-title">
                Cari Data Pelatihan
            </label>


            <form
                method="GET"
                action="{{ route('trainings.index') }}"
                class="search-form"
            >

                <input type="hidden" name="year" value="{{ $year }}">


                <div class="search-input-wrap">

                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>

                    <input
                        type="search"
                        id="training-search"
                        name="search"
                        value="{{ $search }}"
                        class="search-input"
                        placeholder="Cari nama, kategori, kerja sama, angkatan, atau sumber dana..."
                        aria-label="Cari data pelatihan"
                    >

                </div>


                <div class="search-actions">

                    <button type="submit" class="action-button btn-primary">

                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>

                        Cari Data

                    </button>


                    <a
                        href="{{ route('trainings.index', ['year' => $year ?: null]) }}"
                        class="action-button btn-light"
                    >

                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>

                        Reset

                    </a>

                </div>

            </form>

        </div>


        {{-- ==================================================
             TABLE HEADING
        ================================================== --}}

        <div class="table-heading">

            <h3>Informasi Program Pelatihan</h3>

            <span>
                Geser tabel ke samping untuk melihat seluruh kolom.
            </span>

        </div>


        {{-- ==================================================
             TRAINING TABLE
        ================================================== --}}

        <div class="table-wrap">

            <table>

                <thead>

                    <tr>
                        <th>NO.</th>
                        <th>NAMA PELATIHAN</th>
                        <th>KATEGORI</th>
                        <th>KERJA SAMA</th>
                        <th>ANGKATAN</th>
                        <th>SUMBER DANA</th>
                        <th>JPL</th>
                        <th>TANGGAL MULAI</th>
                        <th>TANGGAL SELESAI</th>
                        <th>AKSI</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($trainings as $training)

                        <tr>

                            <td>
                                {{ $trainings->firstItem() + $loop->index }}
                            </td>


                            <td>
                                {{ $training->name ?: '-' }}
                            </td>


                            <td>
                                {{ $training->category?->name ?? '-' }}
                            </td>


                            <td>
                                {{ $training->organizer ?: '-' }}
                            </td>


                            <td>
                                {{ filled($training->batch_number)
                                    ? $training->batch_number
                                    : '-' }}
                            </td>


                            <td>
                                {{ $training->funding_source ?: '-' }}
                            </td>


                            <td>
                                {{ $training->jpl !== null
                                    ? $training->jpl
                                    : '-' }}
                            </td>


                            <td>
                                {{ $training->start_date
                                    ? $training->start_date->format('d-m-Y')
                                    : '-' }}
                            </td>


                            <td>
                                {{ $training->end_date
                                    ? $training->end_date->format('d-m-Y')
                                    : '-' }}
                            </td>


                            {{-- ACTIONS --}}

                            <td>

                                <div class="action-group">

                                    <a
                                        class="action-button btn-light"
                                        href="{{ route('trainings.edit', $training) }}"
                                        aria-label="Edit {{ $training->name }}"
                                    >

                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m16 4 4 4L9 19H5v-4L16 4Z"/>
                                            <path d="m14 6 4 4"/>
                                        </svg>

                                        Edit

                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('trainings.destroy', $training) }}"
                                        style="margin: 0;"
                                        onsubmit="return confirm('Yakin ingin menghapus pelatihan ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')


                                        <button
                                            class="action-button delete-button"
                                            type="submit"
                                            aria-label="Hapus {{ $training->name }}"
                                        >

                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/>
                                                <path d="M8 6V4h8v2"/>
                                                <path d="m19 6-1 14H6L5 6"/>
                                                <path d="M10 11v5m4-5v5"/>
                                            </svg>

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="10" class="empty-state">

                                <div class="empty-state-icon">

                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.6"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                                        <path d="M8 2v4m8-4v4M3 10h18"/>
                                        <path d="M12 14v4m-2-2h4"/>
                                    </svg>

                                </div>


                                @if ($search !== '' || $year !== '')

                                    <strong>Data Tidak Ditemukan</strong>

                                    Tidak ada data pelatihan yang sesuai dengan
                                    pencarian atau filter yang dipilih.

                                    <br>

                                    Coba ubah kata kunci pencarian atau pilih tahun lainnya.

                                @else

                                    <strong>Belum Ada Data Pelatihan</strong>

                                    Data pelatihan belum tersedia dalam sistem.

                                    <br>

                                    Silakan lakukan Impor Excel terlebih dahulu.

                                @endif

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ==================================================
             PAGINATION
        ================================================== --}}

        @if ($trainings->hasPages())

            <div class="pagination-wrap">

                <p>
                    Menampilkan navigasi data pelatihan
                </p>

                {{ $trainings->links() }}

            </div>

        @endif


    </section>

</div>

@endsection