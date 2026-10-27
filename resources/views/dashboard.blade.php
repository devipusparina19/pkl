@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    .dashboard-admin {
        --ink: #172b4d;
        --muted: #6c7b91;
        --line: #e3eaf1;
        --blue: #2457a7;
        --teal: #087f8c;
        color: var(--ink);
        padding-bottom: 28px;
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
        padding: 30px 32px;
        margin-bottom: 25px;
        color: #fff;
        background:
            radial-gradient(circle at 92% 8%, rgba(86,211,205,.22), transparent 27%),
            linear-gradient(118deg, #172b4d 0%, #204a7e 68%, #176b79 100%);
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(23,43,77,.12);
    }

    .dashboard-admin .heading-copy {
        max-width: 650px;
    }

    .dashboard-admin .heading-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 12px;
        color: #bce9e8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
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
        font-size: clamp(25px, 3vw, 35px);
        font-weight: 800;
    }

    .dashboard-admin .dashboard-heading p {
        margin: 12px 0 0;
        color: rgba(255,255,255,.8);
        font-size: 14px;
        line-height: 1.8;
    }

    .dashboard-admin .heading-mark {
        display: flex;
        flex: 0 0 76px;
        align-items: center;
        justify-content: center;
        width: 76px;
        height: 76px;
        color: #d4fbf5;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.19);
        border-radius: 22px;
    }

    .dashboard-admin svg {
        width: 20px;
        height: 20px;
    }

    .dashboard-admin .heading-mark svg {
        width: 38px;
        height: 38px;
    }

    .dashboard-admin .section-intro {
        margin-bottom: 15px;
    }

    .dashboard-admin .section-kicker {
        display: block;
        margin-bottom: 5px;
        color: var(--teal);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.8px;
        text-transform: uppercase;
    }

    .dashboard-admin .section-intro h2 {
        margin: 0;
        font-size: 19px;
        font-weight: 800;
    }

    .dashboard-admin .section-intro p,
    .dashboard-admin .panel-heading p {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.8;
    }

    .dashboard-admin .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 30px;
    }

    .dashboard-admin .stat-card {
        min-width: 0;
        padding: 22px 21px 20px;
        background: #fff;
        border: 1px solid var(--line);
        border-top: 3px solid var(--stat-accent, var(--blue));
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(23,43,77,.035);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .dashboard-admin .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(23,43,77,.08);
    }

    .dashboard-admin .stat-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .dashboard-admin .stat-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        color: var(--stat-accent, var(--blue));
        background: var(--stat-soft, #eaf1fb);
        border-radius: 13px;
    }

    .dashboard-admin .stat-index {
        color: #a4b0c0;
        font-size: 11px;
        font-weight: 800;
    }

    .dashboard-admin .stat-label {
        min-height: 38px;
        color: var(--muted);
        font-size: 12px;
        font-weight: 700;
        line-height: 1.6;
    }

    .dashboard-admin .stat-value {
        margin-top: 5px;
        font-size: clamp(27px, 2.5vw, 35px);
        font-weight: 850;
        line-height: 1.25;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
    }

    .dashboard-admin .stat-caption {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        margin-top: 13px;
        padding-top: 12px;
        color: #8492a6;
        font-size: 11px;
        line-height: 1.6;
        border-top: 1px solid #edf1f6;
    }

    .dashboard-admin .content-card {
        min-width: 0;
        margin-bottom: 22px;
        padding: 25px;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(23,43,77,.035);
    }

    .dashboard-admin .panel-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 19px;
    }

    .dashboard-admin .panel-heading h2 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.5;
    }

    .dashboard-admin .panel-number {
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
        letter-spacing: .5px;
    }

    .dashboard-admin .table-responsive {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e7edf4;
        border-radius: 11px;
    }

    .dashboard-admin table {
        width: 100%;
        min-width: 440px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        text-align: left;
    }

    .dashboard-admin thead th {
        padding: 14px 17px;
        color: #64758c;
        background: #f5f8fc;
        border-bottom: 1px solid #e2eaf2;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .7px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dashboard-admin tbody td {
        padding: 14px 17px;
        color: #43536b;
        border-bottom: 1px solid #edf1f6;
        font-size: 13px;
        line-height: 1.7;
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
        font-weight: 700;
    }

    .dashboard-admin .number-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 37px;
        min-height: 29px;
        padding: 5px 10px;
        color: var(--blue);
        background: #eaf1fc;
        border: 1px solid #dce8fa;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
    }

    .dashboard-admin .filter-form {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 19px;
        padding: 15px;
        background: #f7f9fc;
        border: 1px solid #e7edf4;
        border-radius: 11px;
    }

    .dashboard-admin .filter-field {
        display: flex;
        flex: 1 1 180px;
        flex-direction: column;
        gap: 6px;
        min-width: 150px;
    }

    .dashboard-admin .filter-field label {
        color: #526984;
        font-size: 11px;
        font-weight: 800;
    }

    .dashboard-admin .filter-field select {
        width: 100%;
        min-height: 42px;
        padding: 9px 12px;
        color: var(--ink);
        background: #fff;
        border: 1px solid #d9e2ed;
        border-radius: 8px;
        font: inherit;
        font-size: 12px;
    }

    .dashboard-admin .filter-button,
    .dashboard-admin .reset-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease;
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

    .dashboard-admin .info-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 16px;
        padding: 13px 15px;
        color: #526984;
        background: #f1f7fc;
        border: 1px solid #e0ebf5;
        border-left: 3px solid #4b8cc8;
        border-radius: 9px;
        font-size: 12px;
        line-height: 1.8;
    }

    .dashboard-admin .info-note strong {
        color: #294967;
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
        border-radius: 10px;
    }

    .dashboard-admin .empty-state {
        padding: 25px 17px;
        color: #8391a5;
        text-align: center;
        font-size: 12px;
    }

    .dashboard-admin .profession-summary {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
    }

    .dashboard-admin .profession-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 11px;
        color: #087f8c;
        background: #e6f6f5;
        border: 1px solid #ccecea;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
    }

    .dashboard-admin :focus-visible {
        outline: 3px solid rgba(8,127,140,.55);
        outline-offset: 3px;
    }

    @media (max-width: 1100px) {
        .dashboard-admin .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .dashboard-admin .dashboard-heading {
            align-items: flex-start;
            gap: 14px;
            padding: 22px 18px;
        }

        .dashboard-admin .dashboard-heading h1 {
            font-size: 25px;
        }

        .dashboard-admin .dashboard-heading p {
            font-size: 12px;
        }

        .dashboard-admin .heading-mark {
            flex-basis: 45px;
            width: 45px;
            height: 45px;
            border-radius: 12px;
        }

        .dashboard-admin .heading-mark svg {
            width: 25px;
            height: 25px;
        }

        .dashboard-admin .stats-grid {
            gap: 10px;
        }

        .dashboard-admin .stat-card {
            padding: 15px 13px;
            border-radius: 12px;
        }

        .dashboard-admin .stat-value {
            font-size: 26px;
        }

        .dashboard-admin .stat-label {
            font-size: 11px;
        }

        .dashboard-admin .content-card {
            padding: 16px 13px;
            margin-bottom: 16px;
        }

        .dashboard-admin .panel-heading h2 {
            font-size: 15px;
        }

        .dashboard-admin .panel-heading p {
            font-size: 11px;
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
    $totalParticipants = $totalParticipants ?? 0;
    $totalTrainings = $totalTrainings ?? 0;
    $totalParticipations = $totalParticipations ?? 0;
    $year = $year ?? now()->year;
    $participationsThisYear = $participationsThisYear ?? 0;
    $totalTrainingsSelectedYear = $totalTrainingsSelectedYear ?? 0;

    $years = $years ?? collect([$year]);
    $sort = $sort ?? 'desc';
    $selectedProfession = $selectedProfession ?? '';

    $frequentParticipants = $frequentParticipants ?? collect();
    $fieldStats = $fieldStats ?? collect();
    $professionOptions = $professionOptions ?? collect();
    $trainedParticipants = $trainedParticipants ?? collect();
@endphp

<div class="dashboard-admin">

    {{-- HEADER --}}
    <header class="dashboard-heading">
        <div class="heading-copy">
            <div class="heading-eyebrow">Sistem Informasi Pelatihan</div>
            <h1>Dashboard Admin SIRAPIK</h1>
            <p>
                Pusat informasi pengelolaan peserta, pelatihan, dan
                keikutsertaan. Gunakan filter untuk melihat data berdasarkan
                tahun dan profesi.
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
            <p>Ringkasan data peserta dan pelatihan yang tercatat di sistem.</p>
        </div>

        <div class="stats-grid">

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
                    <span class="stat-index">01 / DATA</span>
                </div>
                <div class="stat-label">Total Peserta Terdaftar</div>
                <div class="stat-value">
                    {{ number_format((int) $totalParticipants, 0, ',', '.') }}
                </div>
                <div class="stat-caption">Jumlah seluruh peserta dalam sistem.</div>
            </div>

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
                <div class="stat-label">Total Pelatihan</div>
                <div class="stat-value">
                    {{ number_format((int) $totalTrainings, 0, ',', '.') }}
                </div>
                <div class="stat-caption">Jumlah seluruh program pelatihan.</div>
            </div>

            <div class="stat-card"
                 style="--stat-accent:#8b65bd;--stat-soft:#f1eafb;">
                <div class="stat-topline">
                    <span class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round">
                            <path d="M8 2h8l4 4v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"/>
                            <path d="M16 2v5h5M8 12h8M8 16h8"/>
                        </svg>
                    </span>
                    <span class="stat-index">03 / RIWAYAT</span>
                </div>
                <div class="stat-label">Total Keikutsertaan</div>
                <div class="stat-value">
                    {{ number_format((int) $totalParticipations, 0, ',', '.') }}
                </div>
                <div class="stat-caption">Akumulasi keikutsertaan seluruh tahun.</div>
            </div>

            <div class="stat-card"
                 style="--stat-accent:#bd8530;--stat-soft:#fbf2e2;">
                <div class="stat-topline">
                    <span class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round">
                            <rect x="3" y="5" width="18" height="16" rx="2"/>
                            <path d="M16 3v4M8 3v4M3 10h18M9 15l2 2 4-4"/>
                        </svg>
                    </span>
                    <span class="stat-index">04 / TAHUN</span>
                </div>
                <div class="stat-label">Keikutsertaan Tahun {{ $year }}</div>
                <div class="stat-value">
                    {{ number_format((int) $participationsThisYear, 0, ',', '.') }}
                </div>
                <div class="stat-caption">
                    Jumlah keikutsertaan pada tahun yang dipilih melalui filter.
                </div>
            </div>

        </div>
    </section>

    {{-- JUMLAH PELATIHAN PER PESERTA --}}
    <section class="content-card" aria-labelledby="frequent-title">
        <div class="panel-heading">
            <div>
                <span class="section-kicker">Pemantauan peserta</span>
                <h2 id="frequent-title">Jumlah Keikutsertaan Peserta dalam Pelatihan</h2>
                <p>
                    Pilih tahun dan urutan untuk melihat peserta dengan jumlah
                    pelatihan paling sedikit atau paling banyak.
                </p>
            </div>
            <div class="panel-number">Filter peserta</div>
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
                <label for="participant-sort">Urutkan Jumlah Pelatihan</label>
                <select id="participant-sort" name="sort">
                    <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>
                        Jumlah Pelatihan Tertinggi
                    </option>
                    <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>
                        Jumlah Pelatihan Terendah
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

            <a href="{{ url()->current() }}" class="reset-button">
                Reset
            </a>
        </form>

        <div class="table-responsive">
            <table>
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
                                Belum ada data peserta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="info-note">
            <span aria-hidden="true">ⓘ</span>
            <div>
                <strong>Catatan:</strong>
                Jumlah pelatihan dihitung berdasarkan riwayat keikutsertaan
                peserta pada tahun yang dipilih. Urutan dapat diubah melalui
                filter di atas.
            </div>
        </div>
    </section>

    {{-- PESERTA TERLATIH BERDASARKAN PROFESI --}}
    <section class="content-card" aria-labelledby="profession-title">
        <div class="panel-heading">
            <div>
                <span class="section-kicker">Data peserta terlatih</span>
                <h2 id="profession-title">Peserta Terlatih Berdasarkan Jenis Profesi</h2>
                <p>
                    Pilih tahun dan profesi untuk menampilkan nama peserta yang
                    mengikuti pelatihan pada tahun tersebut.
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

            {{-- Pertahankan filter urutan yang dipilih pada bagian sebelumnya. --}}
            <input type="hidden" name="sort" value="{{ $sort }}">

            <button type="submit" class="filter-button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round">
                    <path d="M3 4h18l-7 8v6l-4 2v-8L3 4Z"/>
                </svg>
                Tampilkan Peserta
            </button>

            <a href="{{ url()->current() }}" class="reset-button">
                Reset
            </a>
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
                <table>
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
                                    "{{ $selectedProfession }}" yang mengikuti
                                    pelatihan pada tahun {{ $year }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                Pilih tahun dan jenis profesi, lalu klik
                <strong>Tampilkan Peserta</strong> untuk melihat nama-nama
                peserta terlatih.
            </div>
        @endif
    </section>

</div>
@endsection