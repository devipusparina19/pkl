@extends('layouts.app')

@section('title', 'Data Peserta')

@section('content')

<style>
    /* =====================================================
       EXECUTIVE BLUE — PARTICIPANT WORKSPACE
    ===================================================== */

    .participant-page,
    .participant-page * {
        box-sizing: border-box;
    }

    .participant-page {
        --blue-950: #122c4b;
        --blue-900: #17365f;
        --blue-800: #204b78;
        --blue-700: #2865a8;
        --blue-600: #3479bb;
        --blue-100: #e8f1fb;
        --blue-50: #f3f7fc;
        --ink: #253b55;
        --muted: #718198;
        --line: #e0e8f1;
        --surface: #ffffff;

        width: 100%;
        color: var(--ink);
        font-family: inherit;
        font-size: 14px;
    }

    .participant-page button,
    .participant-page input,
    .participant-page select {
        font-family: inherit;
    }

    /* =====================================================
       PAGE HEADER
    ===================================================== */

    .participant-page .page-heading {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 27px;
        padding: 7px 0 8px 22px;
        border-left: 4px solid var(--blue-700);
    }

    .participant-page .heading-content {
        min-width: 0;
    }

    .participant-page .eyebrow {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 8px;
        color: var(--blue-700);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .participant-page .eyebrow::before {
        width: 21px;
        height: 2px;
        background: #8db5df;
        content: "";
    }

    .participant-page .page-heading h1 {
        margin: 0;
        color: var(--blue-950);
        font-size: clamp(27px, 3vw, 34px);
        font-weight: 800;
        letter-spacing: -.8px;
        line-height: 1.2;
    }

    .participant-page .page-heading p {
        margin: 10px 0 0;
        color: var(--muted);
        font-size: 15px;
        line-height: 1.65;
    }

    .participant-page .heading-mark {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 58px;
        height: 58px;
        color: var(--blue-700);
        background: var(--blue-50);
        border: 1px solid #dce9f7;
        border-radius: 16px;
    }

    .participant-page .heading-mark svg {
        width: 29px;
        height: 29px;
    }

    /* =====================================================
       NOTIFICATION
    ===================================================== */

    .participant-page .notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 19px;
        padding: 15px 18px;
        border-radius: 9px;
        font-size: 14px;
        line-height: 1.6;
        animation: participantFade .3s ease both;
    }

    .participant-page .notice svg {
        flex-shrink: 0;
        width: 19px;
        height: 19px;
        margin-top: 1px;
    }

    .participant-page .notice-success {
        color: #166534;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }

    .participant-page .notice-error {
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
    }

    /* =====================================================
       YEAR FILTER SECTION
    ===================================================== */

    .participant-page .year-filter {
        position: relative;
        display: grid;
        grid-template-columns: minmax(190px, .7fr) minmax(0, 1.7fr);
        align-items: center;
        gap: 25px;
        margin-bottom: 20px;
        padding: 22px 24px;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(23, 54, 95, .035);
    }

    .participant-page .year-filter::before {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 4px;
        background: var(--blue-700);
        content: "";
    }

    .participant-page .year-filter-heading {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .participant-page .year-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 43px;
        height: 43px;
        color: var(--blue-700);
        background: var(--blue-50);
        border-radius: 10px;
    }

    .participant-page .year-icon svg {
        width: 21px;
        height: 21px;
    }

    .participant-page .filter-title {
        display: block;
        margin: 1px 0 5px;
        color: var(--blue-950);
        font-size: 15px;
        font-weight: 800;
    }

    .participant-page .filter-description {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.5;
    }

    .participant-page .year-filter-form {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .participant-page .year-select-wrap {
        flex: 1 1 200px;
        min-width: 160px;
    }

    .participant-page .year-select {
        display: block;
        width: 100%;
        height: 44px;
        padding: 0 14px;
        color: var(--blue-900);
        background: #fff;
        border: 1px solid #cbd8e8;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    /* =====================================================
       BUTTON SYSTEM
    ===================================================== */

    .participant-page .action-button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px;
        min-height: 40px;
        padding: 9px 15px;
        border: 1px solid transparent !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        font-weight: 750 !important;
        line-height: 1.3;
        text-decoration: none !important;
        white-space: nowrap;
        cursor: pointer;
        transition:
            background .2s ease,
            border-color .2s ease,
            color .2s ease,
            box-shadow .2s ease,
            transform .2s ease;
    }

    .participant-page .action-button svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .participant-page .action-button:active {
        transform: translateY(1px);
    }

    .participant-page .btn-primary {
        color: #fff !important;
        background: var(--blue-700) !important;
        border-color: var(--blue-700) !important;
    }

    .participant-page .btn-primary:hover {
        color: #fff !important;
        background: var(--blue-800) !important;
        border-color: var(--blue-800) !important;
        box-shadow: 0 4px 10px rgba(40, 101, 168, .17);
    }

    .participant-page .btn-light {
        color: var(--blue-700) !important;
        background: var(--blue-50) !important;
        border-color: #dce8f5 !important;
    }

    .participant-page .btn-light:hover {
        color: var(--blue-900) !important;
        background: #e4eef9 !important;
        border-color: #c8dbee !important;
    }

    .participant-page .delete-button {
        color: #fff !important;
        background: #2865a8 !important;
        border-color: #2865a8 !important;
    }

    .participant-page .delete-button:hover {
        color: #fff !important;
        background: #1d4f89 !important;
        border-color: #1d4f89 !important;
    }

    /* =====================================================
       MAIN DATA PANEL
    ===================================================== */

    .participant-page .panel {
        width: 100%;
        min-width: 0;
        padding: 23px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 12px;
        box-shadow: 0 5px 18px rgba(23, 54, 95, .04);
    }

    .participant-page .panel-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 18px;
        border-bottom: 1px solid #e8edf4;
    }

    .participant-page .panel-heading h2 {
        margin: 0;
        color: var(--blue-950);
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .participant-page .panel-heading p {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.5;
    }

    .participant-page .panel-heading-mark {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        color: var(--blue-700);
        background: var(--blue-50);
        border-radius: 10px;
    }

    .participant-page .panel-heading-mark svg {
        width: 21px;
        height: 21px;
    }

    /* =====================================================
       ACTIVE FILTER
    ===================================================== */

    .participant-page .filter-information {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-bottom: 17px;
    }

    .participant-page .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 34px;
        padding: 7px 12px;
        color: var(--blue-800);
        background: var(--blue-50);
        border: 1px solid #dce9f7;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 750;
    }

    .participant-page .filter-chip svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
    }

    /* =====================================================
       SEARCH
    ===================================================== */

    .participant-page .search-form {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .participant-page .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .participant-page .search-icon {
        position: absolute;
        top: 50%;
        left: 14px;
        width: 19px;
        height: 19px;
        color: #8394aa;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .participant-page .search-input {
        display: block;
        width: 100%;
        min-height: 45px;
        padding: 10px 14px 10px 43px;
        color: var(--blue-900);
        background: #fff;
        border: 1px solid #cbd8e8;
        border-radius: 8px;
        font-size: 14px !important;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .participant-page .search-input::placeholder {
        color: #8998ab;
    }

    .participant-page .search-actions {
        display: flex;
        align-items: center;
        flex-shrink: 0;
        gap: 8px;
    }

    .participant-page .year-select:focus,
    .participant-page .search-input:focus {
        border-color: var(--blue-600);
        box-shadow: 0 0 0 3px rgba(52, 121, 187, .12);
    }

    /* =====================================================
       TABLE
    ===================================================== */

    .participant-page .table-wrap {
        display: block;
        width: 100%;
        overflow-x: auto !important;
        border: 1px solid var(--line);
        border-radius: 9px;
        -webkit-overflow-scrolling: touch;
    }

    .participant-page .table-wrap table {
        width: 100% !important;
        min-width: 1500px !important;
        margin: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        background: #fff;
        font-family: inherit !important;
    }

    .participant-page .table-wrap thead th {
        padding: 17px 15px !important;
        color: #fff !important;
        background: var(--blue-900) !important;
        border: none !important;
        border-bottom: 2px solid var(--blue-700) !important;
        font-family: inherit !important;
        font-size: 12px !important;
        font-weight: 800 !important;
        line-height: 1.5 !important;
        letter-spacing: .45px;
        text-align: left !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }

    .participant-page .table-wrap tbody td {
        padding: 17px 15px !important;
        color: #344b67 !important;
        border: none !important;
        border-bottom: 1px solid #e8edf3 !important;
        font-family: inherit !important;
        font-size: 14px !important;
        font-weight: 400 !important;
        line-height: 1.65 !important;
        text-align: left;
        vertical-align: middle !important;
    }

    .participant-page .table-wrap tbody tr:nth-child(odd) {
        background: #f7f9fc !important;
    }

    .participant-page .table-wrap tbody tr:nth-child(even) {
        background: #fff !important;
    }

    .participant-page .table-wrap tbody tr {
        transition: background .18s ease;
    }

    .participant-page .table-wrap tbody tr:hover {
        background: #edf4fc !important;
    }

    .participant-page .table-wrap tbody tr:last-child td {
        border-bottom: none !important;
    }

    .participant-page .table-wrap tbody td:first-child {
        color: var(--blue-950) !important;
        font-weight: 750 !important;
    }

    /* =====================================================
       TABLE ACTIONS
    ===================================================== */

    .participant-page .action-group {
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
    }

    .participant-page .action-group .action-button {
        min-height: 35px;
        padding: 7px 12px;
        font-size: 12px !important;
    }

    .participant-page .action-group form {
        display: inline-block;
        margin: 0;
    }

    /* =====================================================
       EMPTY STATE
    ===================================================== */

    .participant-page .empty-state {
        padding: 48px 20px !important;
        color: #718198 !important;
        font-size: 14px !important;
        line-height: 1.8 !important;
        text-align: center !important;
    }

    /* =====================================================
       PAGINATION
    ===================================================== */

    .participant-page .pagination-wrap {
        display: flex;
        justify-content: flex-end;
        margin-top: 22px;
        overflow-x: auto;
    }

    .participant-page .pagination-wrap nav {
        display: flex;
        justify-content: flex-end;
    }

    .participant-page .pagination-wrap svg {
        width: 18px;
        height: 18px;
    }

    .participant-page .pagination-wrap p {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
    }

    .participant-page .pagination-wrap a,
    .participant-page .pagination-wrap span {
        font-size: 13px;
    }

    /* =====================================================
       ACCESSIBILITY & ANIMATION
    ===================================================== */

    .participant-page :focus-visible {
        outline: 3px solid rgba(40, 101, 168, .3);
        outline-offset: 3px;
    }

    @keyframes participantFade {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .participant-page *,
        .participant-page *::before,
        .participant-page *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
        }
    }

    /* =====================================================
       TABLET
    ===================================================== */

    @media (max-width: 992px) {
        .participant-page .year-filter {
            grid-template-columns: 1fr;
            gap: 17px;
        }

        .participant-page .panel {
            padding: 19px;
        }

        .participant-page .table-wrap table {
            min-width: 1500px !important;
        }
    }

    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 768px) {
        .participant-page .page-heading {
            margin-bottom: 21px;
            padding-left: 16px;
        }

        .participant-page .page-heading h1 {
            font-size: 27px;
        }

        .participant-page .page-heading p {
            font-size: 14px;
        }

        .participant-page .heading-mark {
            width: 47px;
            height: 47px;
            border-radius: 12px;
        }

        .participant-page .heading-mark svg {
            width: 24px;
            height: 24px;
        }

        .participant-page .year-filter {
            padding: 18px 16px;
        }

        .participant-page .year-filter-form {
            align-items: stretch;
        }

        .participant-page .year-select-wrap {
            flex-basis: 100%;
        }

        .participant-page .year-filter-form .action-button {
            flex: 1;
        }

        .participant-page .panel {
            padding: 15px 12px;
        }

        .participant-page .panel-heading {
            margin-bottom: 16px;
        }

        .participant-page .panel-heading h2 {
            font-size: 18px;
        }

        .participant-page .panel-heading-mark {
            width: 38px;
            height: 38px;
        }

        .participant-page .search-form {
            align-items: stretch;
            flex-direction: column;
        }

        .participant-page .search-input {
            min-height: 46px;
        }

        .participant-page .search-actions {
            width: 100%;
        }

        .participant-page .search-actions .action-button {
            flex: 1;
        }

        .participant-page .table-wrap thead th {
            padding: 14px 12px !important;
            font-size: 11px !important;
        }

        .participant-page .table-wrap tbody td {
            padding: 14px 12px !important;
            font-size: 13px !important;
        }

        .participant-page .pagination-wrap {
            justify-content: flex-start;
        }
    }

    @media (max-width: 420px) {
        .participant-page .page-heading h1 {
            font-size: 25px;
        }

        .participant-page .heading-mark {
            width: 42px;
            height: 42px;
        }

        .participant-page .filter-chip {
            font-size: 12px;
        }

        .participant-page .action-button {
            padding: 9px 11px;
        }
    }
</style>

@php
    $search = $search ?? '';
    $years = $years ?? collect();
    $selectedYear = $selectedYear ?? null;
    $yearFilter = $yearFilter ?? 'all';
@endphp

<div class="participant-page">

    {{-- =================================================
         HEADER HALAMAN
    ================================================== --}}

    <div class="page-heading">

        <div class="heading-content">
            <div class="eyebrow">DATA MASTER</div>

            <h1>Data Peserta</h1>

            <p>
                Kelola dan telusuri informasi peserta yang berasal
                dari hasil Impor Excel.
            </p>
        </div>

        <div class="heading-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="10" cy="7" r="4"/>
                <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>

    </div>

    {{-- =================================================
         NOTIFIKASI
    ================================================== --}}

    @if (session('success'))
        <div class="notice notice-success" role="status">
            <svg viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M20 6 9 17l-5-5"/>
            </svg>

            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="notice notice-error" role="alert">
            <svg viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4"/>
                <path d="M12 16h.01"/>
            </svg>

            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- =================================================
         FILTER TAHUN
    ================================================== --}}

    <section class="year-filter">

        <div class="year-filter-heading">

            <div class="year-icon">
                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                    <path d="M8 14h2M14 14h2M8 17h2"/>
                </svg>
            </div>

            <div>
                <label for="year" class="filter-title">
                    Filter Tahun Pelatihan
                </label>

                <p class="filter-description">
                    Pilih tahun untuk menyaring data peserta.
                </p>
            </div>

        </div>

        <form
            method="GET"
            action="{{ route('participants.index') }}"
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
                    <option value="all"
                        {{ $yearFilter === 'all' ? 'selected' : '' }}>
                        Semua Tahun
                    </option>

                    @foreach ($years as $availableYear)
                        <option
                            value="{{ $availableYear }}"
                            {{ (string) $yearFilter === (string) $availableYear ? 'selected' : '' }}
                        >
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="action-button btn-primary">
                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M4 4h16l-6.5 8v6l-3 2v-8L4 4Z"/>
                </svg>

                Terapkan
            </button>

            <a
                href="{{ route('participants.index', [
                    'year' => 'all',
                    'search' => $search ?: null
                ]) }}"
                class="action-button btn-light"
            >
                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                    <path d="M3 3v5h5"/>
                </svg>

                Reset
            </a>

        </form>

    </section>

    {{-- =================================================
         PANEL DATA PESERTA
    ================================================== --}}

    <section class="panel">

        {{-- PANEL HEADER --}}

        <div class="panel-heading">

            <div>
                <h2>Daftar Peserta</h2>

                <p>
                    Informasi identitas dan data peserta pelatihan.
                </p>
            </div>

            <div class="panel-heading-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M8 6h13M8 12h13M8 18h13"/>
                    <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                </svg>
            </div>

        </div>

        {{-- FILTER AKTIF --}}

        <div class="filter-information">

            @if ($selectedYear)
                <span class="filter-chip">
                    <svg viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                        <path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>

                    Tahun Pelatihan: {{ $selectedYear }}
                </span>
            @else
                <span class="filter-chip">
                    <svg viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>

                    Menampilkan Semua Tahun
                </span>
            @endif

        </div>

        {{-- PENCARIAN --}}

        <form
            method="GET"
            action="{{ route('participants.index') }}"
            class="search-form"
        >

            <input type="hidden" name="year" value="{{ $yearFilter }}">

            <div class="search-input-wrap">

                <svg class="search-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-4-4"/>
                </svg>

                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    class="search-input"
                    placeholder="Cari nama, NIK, NIP, instansi, atau kabupaten/kota..."
                    aria-label="Cari peserta"
                >

            </div>

            <div class="search-actions">

                <button class="action-button btn-primary" type="submit">
                    <svg viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>

                    Cari
                </button>

                <a
                    class="action-button btn-light"
                    href="{{ route('participants.index', [
                        'year' => $yearFilter
                    ]) }}"
                >
                    Reset
                </a>

            </div>

        </form>

        {{-- =================================================
             TABEL DATA PESERTA
        ================================================== --}}

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>NAMA LENGKAP</th>
                        <th>NIK</th>
                        <th>NIP</th>
                        <th>JENIS KELAMIN</th>
                        <th>PANGKAT/GOLONGAN</th>
                        <th>PROFESI NAKES</th>
                        <th>EMAIL</th>
                        <th>NOMOR TELEPON</th>
                        <th>INSTANSI/UNIT KERJA</th>
                        <th>KABUPATEN/KOTA/MITRA/MANDIRI</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($participants as $participant)

                        <tr>

                            <td>{{ $participant->name ?: '-' }}</td>

                            <td>{{ $participant->nik ?: '-' }}</td>

                            <td>{{ $participant->nip ?: '-' }}</td>

                            <td>
                                @if ($participant->gender === 'L')
                                    Laki-laki
                                @elseif ($participant->gender === 'P')
                                    Perempuan
                                @else
                                    {{ $participant->gender ?: '-' }}
                                @endif
                            </td>

                            <td>{{ $participant->rank ?: '-' }}</td>

                            <td>{{ $participant->profession ?: '-' }}</td>

                            <td>{{ $participant->email ?: '-' }}</td>

                            <td>{{ $participant->phone ?: '-' }}</td>

                            <td>{{ $participant->institution ?: '-' }}</td>

                            <td>{{ $participant->district ?: '-' }}</td>

                            <td>

                                <div class="action-group">

                                    <a
                                        class="action-button btn-primary"
                                        href="{{ route('participants.show', $participant) }}"
                                    >
                                        Lihat
                                    </a>

                                    <a
                                        class="action-button btn-light"
                                        href="{{ route('participants.edit', $participant) }}"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('participants.destroy', $participant) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus peserta {{ addslashes($participant->name) }}? Data riwayat pelatihannya dan akun yang terhubung juga akan dihapus.');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-button delete-button"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="11" class="empty-state">

                                @if ($search !== '')

                                    Data peserta tidak ditemukan

                                    @if ($selectedYear)
                                        pada tahun {{ $selectedYear }}.
                                    @else
                                        untuk pencarian tersebut.
                                    @endif

                                @elseif ($selectedYear)

                                    Belum ada peserta yang mengikuti pelatihan
                                    pada tahun {{ $selectedYear }}.

                                @else

                                    Belum ada data peserta.
                                    Silakan lakukan Impor Excel terlebih dahulu.

                                @endif

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- =================================================
             PAGINATION
        ================================================== --}}

        @if ($participants->hasPages())

            <div class="pagination-wrap">
                {{ $participants->links() }}
            </div>

        @endif

    </section>

</div>

@endsection