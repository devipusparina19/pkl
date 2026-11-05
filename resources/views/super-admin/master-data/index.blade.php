@extends('layouts.app')

@section('title', 'Master Data ' . $config['title'])

@section('content')

@php
    $columns = match ($category) {
        'provinces' => [
            'name' => 'Nama Provinsi',
        ],
        'districts' => [
            'name' => 'Nama Wilayah',
            'type' => 'Jenis Wilayah',
            'province' => 'Provinsi',
        ],
        'funding-sources' => [
            'name' => 'Nama Sumber Dana',
        ],
        'training-years' => [
            'year' => 'Tahun',
        ],
        'health-worker-types' => [
            'name' => 'Nama Jenis Tenaga Kesehatan',
        ],
        'training-categories' => [
            'name' => 'Nama Kategori Pelatihan',
        ],
        default => [],
    };

    $totalItems = $items->total();
    $activeFilters = collect([
        $filters['search'] ?? null,
        $filters['status'] ?? null,
    ])->filter()->count();
@endphp

<div class="md-page">

    {{-- HEADER HALAMAN --}}
    <header class="md-heading">
        <div class="md-heading-main">
            <div class="md-breadcrumb">
                <span>SUPER ADMIN</span>
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
                <span>MASTER DATA</span>
            </div>

            <div class="md-title-row">
                <div>
                    <span class="md-kicker">PENGELOLAAN REFERENSI SISTEM</span>
                    <h1>Master Data</h1>
                    <p class="md-description">
                        Kelola {{ strtolower($config['title']) }}
                        sebagai referensi utama sistem SIRAPIK.
                    </p>
                </div>

                <div class="md-heading-decoration" aria-hidden="true">
                    <span class="md-decoration-line"></span>
                    <span class="md-decoration-circle"></span>
                    <span class="md-decoration-square"></span>
                </div>
            </div>
        </div>

        <a
            href="{{ route('super-admin.master-data.create', ['category' => $category]) }}"
            class="md-add-button"
        >
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 5v14M5 12h14"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"/>
            </svg>
            <span>Tambah Data</span>
        </a>
    </header>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="md-alert md-alert-success" role="status">
            <span class="md-alert-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m5 12 4 4L19 6"
                        stroke="currentColor"
                        stroke-width="2.2"
                        stroke-linecap="round"
                        stroke-linejoin="round"/>
                </svg>
            </span>
            <div>
                <strong>Berhasil</strong>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="md-alert md-alert-error" role="alert">
            <span class="md-alert-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"
                        stroke="currentColor" stroke-width="2"/>
                    <path d="M12 8v5m0 3h.01"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"/>
                </svg>
            </span>
            <div>
                <strong>Data tidak dapat diproses</strong>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="md-alert md-alert-error" role="alert">
            <span class="md-alert-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"
                        stroke="currentColor" stroke-width="2"/>
                    <path d="M12 8v5m0 3h.01"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"/>
                </svg>
            </span>
            <div>
                <strong>Periksa kembali data berikut</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- RINGKASAN --}}
    <section class="md-overview">
        <div class="md-overview-copy">
            <span class="md-overview-label">REFERENSI AKTIF</span>
            <h2>{{ $config['title'] }}</h2>
            <p>
                Data master yang digunakan untuk mendukung
                pengelolaan informasi dan operasional SIRAPIK.
            </p>
        </div>

        <div class="md-overview-count">
            <span class="md-count-label">TOTAL DATA</span>
            <strong>{{ number_format($totalItems, 0, ',', '.') }}</strong>
            <span class="md-count-caption">Data terdaftar</span>
        </div>

        <div class="md-overview-mark" aria-hidden="true">
            <svg viewBox="0 0 120 120" fill="none">
                <rect x="15" y="15" width="90" height="90" rx="22"
                    stroke="currentColor" stroke-width="1.5"/>
                <path d="M37 43h46M37 60h46M37 77h28"
                    stroke="currentColor"
                    stroke-width="4"
                    stroke-linecap="round"/>
                <circle cx="87" cy="78" r="12"
                    fill="currentColor" opacity=".15"/>
                <path d="m81 78 4 4 8-9"
                    stroke="currentColor"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"/>
            </svg>
        </div>
    </section>

    {{-- FILTER DAN PENCARIAN --}}
    <section class="md-filter-panel">
        <div class="md-section-heading">
            <div class="md-section-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"
                        stroke="currentColor" stroke-width="1.8"/>
                    <path d="m16 16 4 4"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"/>
                </svg>
            </div>

            <div>
                <h2>Temukan Data</h2>
                <p>Gunakan pencarian dan filter untuk menemukan data.</p>
            </div>

            @if ($activeFilters > 0)
                <span class="md-filter-count">
                    {{ $activeFilters }} filter aktif
                </span>
            @endif
        </div>

        <form
            method="GET"
            action="{{ route('super-admin.master-data.index', ['category' => $category]) }}"
            class="md-filter-form"
        >
            <div class="md-field md-search-field">
                <label for="search">Pencarian Data</label>

                <div class="md-input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"
                            stroke="currentColor" stroke-width="1.8"/>
                        <path d="m16 16 4 4"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>
                    </svg>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Masukkan nama atau tahun..."
                    >
                </div>
            </div>

            <div class="md-field">
                <label for="status">Status Data</label>

                <div class="md-select-wrap">
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option
                            value="active"
                            @selected(($filters['status'] ?? '') === 'active')
                        >
                            Aktif
                        </option>
                        <option
                            value="inactive"
                            @selected(($filters['status'] ?? '') === 'inactive')
                        >
                            Nonaktif
                        </option>
                    </select>

                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m7 10 5 5 5-5"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="md-filter-actions">
                <button type="submit" class="md-filter-button">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m4 5 6.5 7.5V19l3 1v-7.5L20 5H4Z"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linejoin="round"/>
                    </svg>
                    Terapkan Filter
                </button>

                <a
                    href="{{ route('super-admin.master-data.index', ['category' => $category]) }}"
                    class="md-reset-button"
                >
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- TABEL DATA --}}
    <section class="md-table-panel">

        <div class="md-table-heading">
            <div>
                <span class="md-table-kicker">DAFTAR REFERENSI</span>
                <h2>Data {{ $config['title'] }}</h2>
                <p>
                    Berikut daftar data yang tersimpan
                    pada sistem SIRAPIK.
                </p>
            </div>

            <div class="md-table-meta">
                <span class="md-live-dot"></span>
                Data sistem
            </div>
        </div>

        <div class="md-table-container">
            <table class="md-table">
                <thead>
                    <tr>
                        <th class="md-number-column">No.</th>

                        @foreach ($columns as $label)
                            <th>{{ $label }}</th>
                        @endforeach

                        <th>Status</th>
                        <th class="md-action-column">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td data-label="No." class="md-number-cell">
                                {{ $items->firstItem() + $loop->index }}
                            </td>

                            @foreach ($columns as $field => $label)
                                <td data-label="{{ $label }}">
                                    @if ($field === 'province')
                                        <span class="md-primary-text">
                                            {{ $item->province?->name ?? '-' }}
                                        </span>

                                    @elseif ($field === 'type')
                                        @php
                                            $typeLabels = [
                                                'kabupaten' => 'Kabupaten',
                                                'kota' => 'Kota',
                                                'mitra' => 'Mitra',
                                                'mandiri' => 'Mandiri',
                                            ];

                                            $typeLabel = $typeLabels[$item->type ?? '']
                                                ?? ucfirst($item->type ?? '-');
                                        @endphp

                                        <span class="md-type-label">
                                            {{ $typeLabel }}
                                        </span>

                                    @elseif ($field === 'year')
                                        <span class="md-year-label">
                                            {{ $item->year }}
                                        </span>

                                    @else
                                        <span class="md-primary-text">
                                            {{ $item->{$field} ?? '-' }}
                                        </span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- STATUS --}}
                            <td data-label="Status">
                                @if ($item->is_active)
                                    <span class="md-status md-status-active">
                                        <span></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="md-status md-status-inactive">
                                        <span></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td data-label="Aksi">
                                <div class="md-row-actions">

                                    <a
                                        href="{{ route('super-admin.master-data.edit', [
                                            'category' => $category,
                                            'id' => $item->id,
                                        ]) }}"
                                        class="md-action md-action-edit"
                                        aria-label="Edit data"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none"
                                            aria-hidden="true">
                                            <path d="m14 6 4 4M4 20l4.5-1 10.8-10.8a2.12 2.12 0 0 0-3-3L5.5 16 4 20Z"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('super-admin.master-data.status', [
                                            'category' => $category,
                                            'id' => $item->id,
                                        ]) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="md-action md-action-status"
                                        >
                                            @if ($item->is_active)
                                                Nonaktifkan
                                            @else
                                                Aktifkan
                                            @endif
                                        </button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('super-admin.master-data.destroy', [
                                            'category' => $category,
                                            'id' => $item->id,
                                        ]) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini? Data yang sudah dihapus tidak dapat dikembalikan.');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="md-action md-action-delete"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none"
                                                aria-hidden="true">
                                                <path d="M4 7h16M10 11v6m4-6v6M6 7l1 13h10l1-13M9 7V4h6v3"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="{{ count($columns) + 3 }}"
                                class="md-empty-cell"
                            >
                                <div class="md-empty-state">
                                    <div class="md-empty-icon">
                                        <svg viewBox="0 0 24 24" fill="none"
                                            aria-hidden="true">
                                            <path d="M4 5h16v14H4z"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linejoin="round"/>
                                            <path d="M8 10h8M8 14h5"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"/>
                                        </svg>
                                    </div>

                                    <strong>Belum Ada Data</strong>

                                    <p>
                                        Tidak ada data yang sesuai dengan
                                        pencarian atau filter yang dipilih.
                                    </p>

                                    <a
                                        href="{{ route('super-admin.master-data.create', ['category' => $category]) }}"
                                        class="md-empty-link"
                                    >
                                        Tambahkan data pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($items->hasPages() || $items->total() > 0)
            <div class="md-pagination">
                <div class="md-pagination-info">
                    Menampilkan
                    <strong>{{ $items->firstItem() ?? 0 }}</strong>
                    sampai
                    <strong>{{ $items->lastItem() ?? 0 }}</strong>
                    dari
                    <strong>{{ $items->total() }}</strong>
                    data
                </div>

                <div class="md-pagination-links">
                    {{ $items->links() }}
                </div>
            </div>
        @endif

    </section>

    {{-- FOOTER HALAMAN --}}
    <footer class="md-footer">
        <span>SIRAPIK</span>
        <span>Pengelolaan Master Data</span>
    </footer>

</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .md-page {
        --md-navy: #102d50;
        --md-blue: #1d5488;
        --md-blue-bright: #2876b9;
        --md-blue-soft: #eaf3fb;
        --md-ink: #1d3047;
        --md-muted: #718198;
        --md-border: #e1e8f0;
        --md-surface: #ffffff;
        --md-background: #f4f7fb;
        --md-teal: #168b83;
        --md-green: #16805c;
        --md-red: #c34b55;

        width: 100%;
        max-width: none;
        min-width: 0;
        color: var(--md-ink);
        font-family: 'Plus Jakarta Sans', 'DM Sans', sans-serif;
        font-size: 15px;
        line-height: 1.65;
        animation: md-enter .5s ease both;
    }

    .md-page *,
    .md-page *::before,
    .md-page *::after {
        box-sizing: border-box;
    }

    .md-page button,
    .md-page input,
    .md-page select {
        font: inherit;
    }

    .md-page a {
        text-decoration: none;
    }

    .md-page svg {
        display: block;
        width: 20px;
        height: 20px;
        flex: 0 0 auto;
    }

    /* HEADER */

    .md-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 28px;
        width: 100%;
        padding: 10px 0 30px;
    }

    .md-heading-main {
        min-width: 0;
        flex: 1;
    }

    .md-breadcrumb {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 23px;
        color: #8292a7;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.3px;
    }

    .md-breadcrumb svg {
        width: 14px;
        height: 14px;
        color: #a4b2c3;
    }

    .md-breadcrumb span:last-child {
        color: var(--md-blue);
    }

    .md-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .md-kicker,
    .md-table-kicker {
        display: block;
        margin-bottom: 8px;
        color: var(--md-blue-bright);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.8px;
    }

    .md-heading h1 {
        margin: 0;
        color: var(--md-navy);
        font-size: clamp(34px, 4vw, 48px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -1.8px;
    }

    .md-description {
        margin: 11px 0 0;
        color: var(--md-muted);
        font-size: 16px;
        line-height: 1.7;
    }

    .md-heading-decoration {
        position: relative;
        width: 150px;
        height: 75px;
        flex: 0 0 150px;
        overflow: hidden;
        opacity: .8;
    }

    .md-decoration-line {
        position: absolute;
        top: 36px;
        left: 0;
        width: 135px;
        height: 1px;
        background: #b7ccdf;
        transform: rotate(-19deg);
    }

    .md-decoration-circle {
        position: absolute;
        top: 3px;
        right: 28px;
        width: 46px;
        height: 46px;
        border: 8px solid #d9e8f5;
        border-radius: 50%;
    }

    .md-decoration-square {
        position: absolute;
        right: 5px;
        bottom: 4px;
        width: 17px;
        height: 17px;
        border-radius: 4px;
        background: #2b77ad;
        transform: rotate(25deg);
    }

    .md-add-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 52px;
        padding: 0 23px;
        border: 1px solid var(--md-navy);
        border-radius: 9px;
        background: var(--md-navy);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 7px 16px rgba(16, 45, 80, .14);
        transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
    }

    .md-add-button:hover {
        transform: translateY(-3px);
        background: #1b4d7c;
        box-shadow: 0 11px 22px rgba(16, 45, 80, .2);
        color: #fff;
    }

    .md-add-button svg {
        width: 19px;
        height: 19px;
    }

    /* ALERT */

    .md-alert {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 20px;
        padding: 17px 20px;
        border: 1px solid;
        border-radius: 10px;
        animation: md-enter .3s ease both;
    }

    .md-alert-icon {
        display: grid;
        width: 35px;
        height: 35px;
        flex: 0 0 35px;
        place-items: center;
        border-radius: 9px;
    }

    .md-alert-icon svg {
        width: 20px;
        height: 20px;
    }

    .md-alert strong {
        display: block;
        margin-bottom: 2px;
        font-size: 15px;
        font-weight: 800;
    }

    .md-alert p {
        margin: 0;
        font-size: 14px;
    }

    .md-alert ul {
        margin: 5px 0 0;
        padding-left: 20px;
        font-size: 14px;
    }

    .md-alert-success {
        border-color: #b9e5d2;
        background: #effaf5;
        color: #176c50;
    }

    .md-alert-success .md-alert-icon {
        background: #d9f3e6;
    }

    .md-alert-error {
        border-color: #f0c8cb;
        background: #fff5f5;
        color: #a83943;
    }

    .md-alert-error .md-alert-icon {
        background: #ffe5e6;
    }

    /* OVERVIEW */

    .md-overview {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
        min-height: 185px;
        margin-bottom: 22px;
        padding: 30px 36px;
        overflow: hidden;
        border: 1px solid #d8e5f1;
        border-radius: 13px;
        background:
            linear-gradient(105deg, #edf5fc 0%, #f7faff 62%, #e8f2f9 100%);
    }

    .md-overview::before {
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: var(--md-blue);
        content: "";
    }

    .md-overview-copy {
        position: relative;
        z-index: 1;
        max-width: 650px;
    }

    .md-overview-label {
        color: #3879aa;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.6px;
    }

    .md-overview h2 {
        margin: 8px 0 6px;
        color: var(--md-navy);
        font-size: clamp(23px, 2.5vw, 30px);
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: -.7px;
    }

    .md-overview-copy p {
        max-width: 540px;
        margin: 0;
        color: #657b94;
        font-size: 15px;
    }

    .md-overview-count {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        min-width: 145px;
        padding-left: 28px;
        border-left: 1px solid #cbdce9;
    }

    .md-count-label {
        color: #6d839a;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.4px;
    }

    .md-overview-count strong {
        margin: 1px 0;
        color: var(--md-navy);
        font-size: 42px;
        font-weight: 800;
        line-height: 1.35;
        letter-spacing: -1px;
    }

    .md-count-caption {
        color: #71869c;
        font-size: 13px;
    }

    .md-overview-mark {
        position: absolute;
        right: 165px;
        bottom: -42px;
        width: 160px;
        height: 160px;
        color: #7da6c9;
        opacity: .13;
        transform: rotate(-12deg);
    }

    .md-overview-mark svg {
        width: 100%;
        height: 100%;
    }

    /* FILTER */

    .md-filter-panel,
    .md-table-panel {
        width: 100%;
        margin-bottom: 22px;
        border: 1px solid var(--md-border);
        border-radius: 12px;
        background: var(--md-surface);
        box-shadow: 0 5px 20px rgba(22, 49, 78, .035);
    }

    .md-filter-panel {
        padding: 25px 28px 28px;
    }

    .md-section-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 22px;
    }

    .md-section-icon {
        display: grid;
        width: 43px;
        height: 43px;
        flex: 0 0 43px;
        place-items: center;
        border-radius: 10px;
        background: var(--md-blue-soft);
        color: var(--md-blue);
    }

    .md-section-icon svg {
        width: 22px;
        height: 22px;
    }

    .md-section-heading h2 {
        margin: 0;
        color: var(--md-navy);
        font-size: 19px;
        font-weight: 800;
    }

    .md-section-heading p {
        margin: 2px 0 0;
        color: var(--md-muted);
        font-size: 13px;
    }

    .md-filter-count {
        margin-left: auto;
        padding: 6px 11px;
        border-radius: 20px;
        background: #eaf4fb;
        color: #28638e;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .md-filter-form {
        display: grid;
        grid-template-columns: minmax(220px, 1.7fr) minmax(180px, .8fr) auto;
        align-items: end;
        gap: 18px;
    }

    .md-field {
        min-width: 0;
    }

    .md-field label {
        display: block;
        margin-bottom: 8px;
        color: #344960;
        font-size: 14px;
        font-weight: 700;
    }

    .md-input-wrap,
    .md-select-wrap {
        position: relative;
        display: flex;
        align-items: center;
        min-height: 49px;
        border: 1px solid #dce5ee;
        border-radius: 8px;
        background: #fbfcfe;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .md-input-wrap:focus-within,
    .md-select-wrap:focus-within {
        border-color: #4b8fc4;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(40, 118, 185, .11);
    }

    .md-input-wrap {
        gap: 11px;
        padding: 0 14px;
    }

    .md-input-wrap svg {
        color: #8294a8;
    }

    .md-input-wrap input {
        width: 100%;
        min-width: 0;
        height: 47px;
        padding: 0;
        border: 0;
        outline: none;
        background: transparent;
        color: var(--md-ink);
        font-size: 14px;
    }

    .md-input-wrap input::placeholder {
        color: #9aa8b8;
    }

    .md-select-wrap select {
        width: 100%;
        height: 47px;
        padding: 0 40px 0 14px;
        appearance: none;
        border: 0;
        outline: none;
        background: transparent;
        color: var(--md-ink);
        font-size: 14px;
        cursor: pointer;
    }

    .md-select-wrap > svg {
        position: absolute;
        right: 13px;
        width: 17px;
        height: 17px;
        color: #8294a8;
        pointer-events: none;
    }

    .md-filter-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .md-filter-button,
    .md-reset-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 49px;
        padding: 0 17px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 750;
        white-space: nowrap;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease, border-color .2s ease;
    }

    .md-filter-button {
        border: 1px solid var(--md-blue);
        background: var(--md-blue);
        color: #fff;
    }

    .md-filter-button:hover {
        transform: translateY(-2px);
        background: var(--md-navy);
        color: #fff;
    }

    .md-filter-button svg {
        width: 17px;
        height: 17px;
    }

    .md-reset-button {
        border: 1px solid #dce5ee;
        background: #fff;
        color: #5c6f84;
    }

    .md-reset-button:hover {
        border-color: #b7cde0;
        background: #f0f6fb;
        color: var(--md-blue);
    }

    /* TABLE */

    .md-table-panel {
        overflow: hidden;
    }

    .md-table-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 26px 28px 22px;
        border-bottom: 1px solid var(--md-border);
    }

    .md-table-heading h2 {
        margin: 0;
        color: var(--md-navy);
        font-size: 23px;
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .md-table-heading p {
        margin: 5px 0 0;
        color: var(--md-muted);
        font-size: 14px;
    }

    .md-table-meta {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 8px 13px;
        border: 1px solid #dce9f2;
        border-radius: 30px;
        background: #f7fbff;
        color: #5b728a;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .md-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #24a17b;
        box-shadow: 0 0 0 4px #dff4eb;
    }

    .md-table-container {
        width: 100%;
        overflow-x: auto;
        scrollbar-color: #c5d5e4 #f3f6fa;
        scrollbar-width: thin;
    }

    .md-table {
        width: 100%;
        min-width: 760px;
        border-collapse: separate;
        border-spacing: 0;
        text-align: left;
    }

    .md-table thead th {
        padding: 15px 17px;
        border-bottom: 1px solid #dfe7ef;
        background: #f4f7fb;
        color: #5b6f85;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .35px;
        white-space: nowrap;
    }

    .md-table thead th:first-child {
        padding-left: 25px;
    }

    .md-table tbody td {
        padding: 17px;
        border-bottom: 1px solid #edf1f5;
        color: #53677d;
        font-size: 14px;
        vertical-align: middle;
    }

    .md-table tbody tr {
        transition: background .18s ease;
    }

    .md-table tbody tr:hover {
        background: #f7fbff;
    }

    .md-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .md-number-column {
        width: 70px;
    }

    .md-number-cell {
        padding-left: 25px !important;
        color: #8b9bad !important;
        font-weight: 700;
    }

    .md-primary-text {
        color: #263d57;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .md-type-label {
        display: inline-flex;
        padding: 5px 10px;
        border: 1px solid #dbe8f3;
        border-radius: 6px;
        background: #f0f6fb;
        color: #37668e;
        font-size: 12px;
        font-weight: 750;
    }

    .md-year-label {
        display: inline-flex;
        min-width: 68px;
        justify-content: center;
        padding: 5px 10px;
        border-radius: 6px;
        background: #edf4fc;
        color: #225985;
        font-size: 14px;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    .md-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 11px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .md-status > span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .md-status-active {
        background: #eaf7f0;
        color: #18734f;
    }

    .md-status-active > span {
        background: #1c9a67;
    }

    .md-status-inactive {
        background: #f0f2f5;
        color: #68778a;
    }

    .md-status-inactive > span {
        background: #8995a3;
    }

    .md-action-column {
        min-width: 245px;
    }

    .md-row-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 7px;
    }

    .md-row-actions form {
        display: inline-flex;
        margin: 0;
    }

    .md-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-height: 34px;
        padding: 6px 10px;
        border: 1px solid transparent;
        border-radius: 6px;
        background: #fff;
        font-size: 12px;
        font-weight: 750;
        line-height: 1.3;
        white-space: nowrap;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease, transform .18s ease;
    }

    .md-action:hover {
        transform: translateY(-1px);
    }

    .md-action svg {
        width: 15px;
        height: 15px;
    }

    .md-action-edit {
        border-color: #cfe1f2;
        background: #f0f7fd;
        color: #245f91;
    }

    .md-action-edit:hover {
        border-color: #9dc1df;
        background: #e2f0fc;
        color: #174b78;
    }

    .md-action-status {
        border-color: #e2e8ee;
        background: #f8fafc;
        color: #617387;
    }

    .md-action-status:hover {
        border-color: #c4d2df;
        background: #edf3f8;
    }

    .md-action-delete {
        border-color: #f2d6d8;
        background: #fff7f7;
        color: #b34b53;
    }

    .md-action-delete:hover {
        border-color: #eab8bc;
        background: #ffeded;
        color: #a52e39;
    }

    /* EMPTY STATE */

    .md-empty-cell {
        padding: 0 !important;
    }

    .md-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 280px;
        padding: 35px 20px;
        text-align: center;
    }

    .md-empty-icon {
        display: grid;
        width: 62px;
        height: 62px;
        margin-bottom: 14px;
        place-items: center;
        border-radius: 17px;
        background: #edf4fb;
        color: #3975a6;
    }

    .md-empty-icon svg {
        width: 29px;
        height: 29px;
    }

    .md-empty-state strong {
        color: var(--md-navy);
        font-size: 19px;
        font-weight: 800;
    }

    .md-empty-state p {
        max-width: 400px;
        margin: 7px 0 14px;
        color: var(--md-muted);
        font-size: 14px;
    }

    .md-empty-link {
        color: var(--md-blue);
        font-size: 14px;
        font-weight: 800;
        text-decoration: underline !important;
        text-underline-offset: 4px;
    }

    /* PAGINATION */

    .md-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 18px 25px;
        border-top: 1px solid var(--md-border);
        background: #fbfcfe;
    }

    .md-pagination-info {
        color: #7a899a;
        font-size: 13px;
    }

    .md-pagination-info strong {
        color: #344c65;
        font-weight: 800;
    }

    .md-pagination-links {
        min-width: 0;
    }

    .md-pagination-links nav {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .md-pagination-links nav > div:first-child {
        display: none;
    }

    .md-pagination-links nav > div:last-child > span,
    .md-pagination-links nav > div:last-child > a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        min-height: 36px;
        margin: 0 2px;
        padding: 5px 10px;
        border: 1px solid #e0e8f0;
        border-radius: 7px;
        background: #fff;
        color: #526a83;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .md-pagination-links nav > div:last-child span[aria-current="page"] span {
        border-color: var(--md-blue);
        background: var(--md-blue);
        color: #fff;
    }

    .md-pagination-links nav > div:last-child a:hover {
        border-color: #a9c7df;
        background: #edf5fc;
        color: var(--md-blue);
    }

    /* FOOTER */

    .md-footer {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 7px 2px 18px;
        color: #8998a9;
        font-size: 12px;
    }

    .md-footer span:first-child {
        color: #496b8c;
        font-weight: 800;
        letter-spacing: 1px;
    }

    /* ANIMASI */

    @keyframes md-enter {
        from {
            opacity: 0;
            transform: translateY(9px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* TABLET */

    @media (max-width: 1050px) {
        .md-heading-decoration {
            display: none;
        }

        .md-filter-form {
            grid-template-columns: minmax(200px, 1fr) minmax(160px, .7fr);
        }

        .md-filter-actions {
            grid-column: 1 / -1;
        }

        .md-overview-mark {
            right: 130px;
        }
    }

    @media (max-width: 760px) {
        .md-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 20px;
        }

        .md-add-button {
            width: 100%;
        }

        .md-overview {
            align-items: flex-start;
            flex-direction: column;
            gap: 22px;
            padding: 25px 25px 25px 29px;
        }

        .md-overview-count {
            width: 100%;
            min-width: 0;
            padding: 15px 0 0;
            border-top: 1px solid #cbdce9;
            border-left: 0;
        }

        .md-overview-count strong {
            font-size: 36px;
        }

        .md-overview-mark {
            right: 15px;
            bottom: 25px;
            width: 110px;
            height: 110px;
        }

        .md-filter-panel {
            padding: 22px 20px;
        }

        .md-filter-form {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .md-filter-actions {
            grid-column: auto;
        }

        .md-filter-button,
        .md-reset-button {
            flex: 1;
        }

        .md-table-heading {
            align-items: flex-start;
            flex-direction: column;
            padding: 23px 20px;
        }

        .md-pagination {
            align-items: flex-start;
            flex-direction: column;
        }

        .md-pagination-links {
            width: 100%;
            overflow-x: auto;
        }
    }

    /* MOBILE */

    @media (max-width: 600px) {
        .md-page {
            font-size: 14px;
        }

        .md-breadcrumb {
            margin-bottom: 16px;
            font-size: 10px;
        }

        .md-heading h1 {
            font-size: 34px;
        }

        .md-description {
            font-size: 14px;
        }

        .md-heading {
            padding-bottom: 22px;
        }

        .md-overview {
            min-height: auto;
            padding: 22px 20px 22px 25px;
        }

        .md-overview h2 {
            font-size: 23px;
        }

        .md-overview-copy p {
            font-size: 13px;
        }

        .md-overview-mark {
            right: -5px;
            bottom: 30px;
            width: 85px;
            height: 85px;
        }

        .md-section-heading {
            flex-wrap: wrap;
        }

        .md-filter-count {
            margin-left: 0;
        }

        .md-filter-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .md-filter-button,
        .md-reset-button {
            width: 100%;
        }

        .md-table-heading h2 {
            font-size: 21px;
        }

        .md-table-heading p {
            font-size: 13px;
        }

        .md-table-container {
            overflow: visible;
            padding: 12px;
            background: #f7f9fc;
        }

        .md-table {
            display: block;
            width: 100%;
            min-width: 0;
        }

        .md-table thead {
            display: none;
        }

        .md-table tbody {
            display: flex;
            flex-direction: column;
            gap: 13px;
        }

        .md-table tbody tr {
            display: block;
            overflow: hidden;
            border: 1px solid #e0e8f0;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 3px 10px rgba(20, 47, 76, .035);
        }

        .md-table tbody tr:hover {
            background: #fff;
        }

        .md-table tbody td {
            display: grid;
            grid-template-columns: minmax(105px, 36%) minmax(0, 1fr);
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-bottom: 1px solid #edf1f5;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .md-table tbody td::before {
            content: attr(data-label);
            color: #78899b;
            font-size: 12px;
            font-weight: 750;
        }

        .md-table tbody td:last-child {
            border-bottom: 0;
        }

        .md-table tbody td.md-empty-cell {
            display: block;
            padding: 0 !important;
            border: 0;
        }

        .md-table tbody td.md-empty-cell::before {
            display: none;
        }

        .md-row-actions {
            justify-content: flex-start;
            gap: 6px;
        }

        .md-action {
            min-height: 32px;
            padding: 6px 8px;
            font-size: 11px;
        }

        .md-pagination {
            padding: 17px;
        }

        .md-footer {
            flex-direction: column;
            gap: 3px;
        }
    }

    @media (max-width: 380px) {
        .md-table tbody td {
            grid-template-columns: minmax(85px, 34%) minmax(0, 1fr);
            gap: 8px;
            padding: 10px;
        }

        .md-action {
            padding: 5px 7px;
        }

        .md-overview-mark {
            opacity: .07;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .md-page *,
        .md-page *::before,
        .md-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

@endsection