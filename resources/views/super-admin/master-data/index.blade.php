@extends('layouts.app')

@section('title', 'Master Data ' . $config['title'])

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

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
        <div>
            <nav class="md-breadcrumb" aria-label="Breadcrumb">
                <span>Super Admin</span>
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                <span aria-current="page">Master Data</span>
            </nav>

            <h1>Master data <span>{{ strtolower($config['title']) }}.</span></h1>

            <p class="md-description">
                Kelola {{ strtolower($config['title']) }}
                sebagai referensi utama sistem SIRAPIK.
            </p>
        </div>

        <div class="md-heading-side">
            <div class="md-total">
                <small>
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 3 8l9 5 9-5-9-5Z"/><path d="m3 12 9 5 9-5"/><path d="m3 16 9 5 9-5"/></svg>
                    Total data
                </small>
                <strong id="md-counter" data-target="{{ (int) $totalItems }}">{{ number_format($totalItems, 0, ',', '.') }}</strong>
                <span>Data terdaftar</span>
            </div>

            <a
                href="{{ route('super-admin.master-data.create', ['category' => $category]) }}"
                class="md-btn md-btn-primary md-add-button"
            >
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                Tambah Data
            </a>
        </div>
    </header>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="md-alert md-alert-success" role="status">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
            <div>
                <strong>Berhasil</strong>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="md-alert md-alert-error" role="alert">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
            <div>
                <strong>Data tidak dapat diproses</strong>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="md-alert md-alert-error" role="alert">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
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

    {{-- FILTER DAN PENCARIAN --}}
    <section class="md-filter-panel" aria-labelledby="md-filter-title">
        <div class="md-section-heading">
            <div>
                <h2 id="md-filter-title">Temukan data</h2>
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
            <div class="md-field">
                <label for="search">Pencarian Data</label>

                <div class="md-input-wrap">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Masukkan nama atau tahun"
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

                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>

            <div class="md-filter-actions">
                <button type="submit" class="md-btn md-btn-primary">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 3H2l8 9.46V19l4 2v-8.54z"/></svg>
                    Terapkan Filter
                </button>

                <a
                    href="{{ route('super-admin.master-data.index', ['category' => $category]) }}"
                    class="md-btn md-btn-ghost"
                >
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- TABEL DATA --}}
    <section class="md-table-panel" aria-labelledby="md-table-title">

        <div class="md-table-heading">
            <div>
                <h2 id="md-table-title">Data {{ $config['title'] }}</h2>
                <p>Berikut daftar data yang tersimpan pada sistem SIRAPIK.</p>
            </div>

            <div class="md-table-meta">
                <span class="md-live-dot"></span>
                Data sistem
            </div>
        </div>

        <div class="md-table-container">
            <table class="md-table">
                <caption class="md-sr">Daftar data {{ $config['title'] }}</caption>
                <thead>
                    <tr>
                        <th scope="col" class="md-number-column">No.</th>

                        @foreach ($columns as $label)
                            <th scope="col">{{ $label }}</th>
                        @endforeach

                        <th scope="col">Status</th>
                        <th scope="col" class="md-action-column">Aksi</th>
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
                                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.4 3.6a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
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
                                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
                                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                                    </div>

                                    <strong>Belum ada data</strong>

                                    <p>
                                        Tidak ada data yang sesuai dengan
                                        pencarian atau filter yang dipilih.
                                    </p>

                                    <a
                                        href="{{ route('super-admin.master-data.create', ['category' => $category]) }}"
                                        class="md-btn md-btn-secondary"
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
    /* =====================================================
       PREMIUM BLUE EDITORIAL — MASTER DATA
       Semua selector di-scope ke .md-page
       ===================================================== */
    .md-page {
        --navy: #081B33;
        --midnight: #0B2545;
        --royal: #2457D6;
        --electric: #3478F6;
        --soft: #EAF2FF;
        --ice: #F3F7FC;
        --slate: #64748B;
        --text: #14243A;
        --border: #E1E9F2;
        --cyan: #38C6E8;
        --ok: #0F6B4A;
        --ok-bg: #E8F6EF;
        --ok-line: #B7E2CC;
        --danger: #A3202F;
        --danger-bg: #FFF1F2;
        --danger-line: #F2BCC3;
        --font-display: "Sora", "Segoe UI", Arial, sans-serif;
        --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
        --ease: cubic-bezier(.2, .7, .2, 1);

        width: 100%; min-width: 0; color: var(--text);
        font-family: var(--font-body); font-size: 15px; line-height: 1.65;
    }
    .md-page *, .md-page *::before, .md-page *::after { box-sizing: border-box; }
    .md-page button, .md-page input, .md-page select { font: inherit; }
    .md-page a { text-decoration: none; }
    .md-page svg.i {
        width: 1em; height: 1em; flex: none;
        fill: none; stroke: currentColor;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
    .md-page :focus-visible { outline: 3px solid var(--cyan); outline-offset: 3px; border-radius: 6px; }
    .md-sr { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); }

    /* ---------- Hero ---------- */
    .md-heading {
        position: relative; isolation: isolate; overflow: hidden;
        display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, .7fr);
        align-items: end; gap: clamp(22px, 4vw, 56px);
        margin-bottom: 24px; padding: clamp(28px, 4.5vw, 54px); border-radius: 26px; color: #fff;
        background: linear-gradient(120deg, var(--navy) 0%, var(--midnight) 60%, #173d86 100%);
        animation: md-rise .8s var(--ease) both;
    }
    .md-heading::before {
        content: ""; position: absolute; z-index: -1; right: -90px; top: -110px;
        width: 360px; height: 360px; border-radius: 50%;
        background: var(--royal); opacity: .45; filter: blur(60px);
        animation: md-drift 24s ease-in-out infinite alternate;
    }
    .md-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 600; color: rgba(255,255,255,.7); }
    .md-breadcrumb .i { font-size: 14px; }
    .md-breadcrumb span:last-child { color: var(--cyan); }
    .md-heading h1 {
        margin: 0 0 14px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.1rem, 5vw, 3.8rem); line-height: 1.06; letter-spacing: -.035em;
    }
    .md-heading h1 span { display: block; color: var(--cyan); }
    .md-description { max-width: 540px; margin: 0; font-size: 15px; line-height: 1.8; color: rgba(255,255,255,.82); }
    .md-heading-side { display: flex; flex-direction: column; align-items: flex-start; gap: 18px; justify-self: end; }
    .md-total small { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: rgba(255,255,255,.78); }
    .md-total small .i { font-size: 17px; color: var(--cyan); }
    .md-total strong {
        display: block; margin: 2px 0; font-family: var(--font-display); font-weight: 800; line-height: 1;
        font-size: clamp(2.6rem, 5vw, 3.8rem); letter-spacing: -.04em; font-variant-numeric: tabular-nums;
    }
    .md-total span { font-size: 13px; color: rgba(255,255,255,.7); }

    /* ---------- Buttons ---------- */
    .md-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        min-height: 50px; padding: 10px 20px; border: 1.5px solid transparent; border-radius: 12px;
        font-family: var(--font-display); font-size: 14px; font-weight: 600; white-space: nowrap; cursor: pointer;
        transition: transform .2s var(--ease), box-shadow .2s, background .2s, color .2s, border-color .2s;
    }
    .md-btn .i { font-size: 17px; }
    .md-btn:active { transform: scale(.97); }
    .md-btn-primary { color: #fff; background: linear-gradient(110deg, var(--royal), var(--electric)); box-shadow: 0 12px 26px rgba(36,87,214,.35); }
    .md-btn-primary:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 16px 32px rgba(36,87,214,.45); }
    .md-btn-secondary { color: var(--royal); background: #fff; border-color: #C5D6F5; }
    .md-btn-secondary:hover { color: var(--royal); background: var(--soft); transform: translateY(-2px); }
    .md-btn-ghost { color: var(--slate); background: transparent; }
    .md-btn-ghost:hover { color: var(--navy); background: var(--ice); }
    .md-add-button { min-height: 54px; padding: 12px 24px; font-size: 15px; }

    /* ---------- Alerts ---------- */
    .md-alert {
        display: flex; align-items: flex-start; gap: 12px; margin-bottom: 18px;
        padding: 15px 18px; border: 1px solid transparent; border-radius: 16px; font-size: 14px; line-height: 1.7;
    }
    .md-alert > .i { margin-top: 3px; font-size: 20px; }
    .md-alert strong { display: block; font-family: var(--font-display); font-size: 15px; font-weight: 600; }
    .md-alert p { margin: 2px 0 0; }
    .md-alert ul { margin: 6px 0 0; padding-left: 20px; }
    .md-alert-success { color: var(--ok); background: var(--ok-bg); border-color: var(--ok-line); }
    .md-alert-error { color: var(--danger); background: var(--danger-bg); border-color: var(--danger-line); }

    /* ---------- Filter ---------- */
    .md-filter-panel {
        margin-bottom: 20px; padding: clamp(18px, 2.6vw, 26px);
        border: 1px solid var(--border); border-radius: 22px; background: #fff; box-shadow: 0 14px 36px rgba(8,27,51,.05);
    }
    .md-section-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
    .md-section-heading h2 { margin: 0 0 4px; font-family: var(--font-display); font-size: 18px; font-weight: 700; letter-spacing: -.02em; color: var(--navy); }
    .md-section-heading p { margin: 0; font-size: 13.5px; color: var(--slate); }
    .md-filter-count {
        padding: 6px 12px; border-radius: 999px; background: var(--soft); border: 1px solid #D3E2FB;
        color: var(--royal); font-size: 12.5px; font-weight: 700; white-space: nowrap;
    }
    .md-filter-form { display: grid; grid-template-columns: minmax(220px, 1.7fr) minmax(180px, .8fr) auto; align-items: end; gap: 14px; }
    .md-field { min-width: 0; }
    .md-field label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: var(--navy); }
    .md-input-wrap, .md-select-wrap {
        position: relative; display: flex; align-items: center; min-height: 50px;
        border: 1.5px solid var(--border); border-radius: 12px; background: var(--ice);
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .md-input-wrap:hover, .md-select-wrap:hover { border-color: #c5d3e4; }
    .md-input-wrap:focus-within, .md-select-wrap:focus-within { background: #fff; border-color: var(--electric); box-shadow: 0 0 0 4px rgba(52,120,246,.16); }
    .md-input-wrap { gap: 11px; padding: 0 14px; }
    .md-input-wrap .i, .md-select-wrap > .i { font-size: 18px; color: var(--slate); }
    .md-input-wrap input {
        width: 100%; min-width: 0; height: 47px; padding: 0; border: 0; outline: none; background: transparent;
        color: var(--text); font-size: 14px;
    }
    .md-input-wrap input::placeholder { color: #8796aa; }
    .md-select-wrap select {
        width: 100%; height: 47px; padding: 0 40px 0 14px; appearance: none; border: 0; outline: none;
        background: transparent; color: var(--text); font-size: 14px; cursor: pointer;
    }
    .md-select-wrap > .i { position: absolute; right: 13px; pointer-events: none; }
    .md-filter-actions { display: flex; align-items: center; gap: 8px; }

    /* ---------- Tabel ---------- */
    .md-table-panel {
        overflow: hidden; margin-bottom: 20px; border: 1px solid var(--border); border-radius: 22px;
        background: #fff; box-shadow: 0 18px 44px rgba(8,27,51,.07);
    }
    .md-table-heading {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        padding: 20px clamp(18px, 2.6vw, 26px); border-bottom: 1px solid var(--border);
    }
    .md-table-heading h2 { margin: 0 0 3px; font-family: var(--font-display); font-size: 18px; font-weight: 700; letter-spacing: -.02em; color: var(--navy); }
    .md-table-heading p { margin: 0; font-size: 13.5px; color: var(--slate); }
    .md-table-meta {
        display: inline-flex; align-items: center; gap: 9px; padding: 7px 14px; border-radius: 999px;
        background: var(--soft); border: 1px solid #D3E2FB; color: var(--royal); font-size: 12.5px; font-weight: 700; white-space: nowrap;
    }
    .md-live-dot { width: 8px; height: 8px; border-radius: 50%; background: #14A37A; box-shadow: 0 0 0 4px rgba(20,163,122,.16); }
    .md-table-container { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: thin; }
    .md-table { width: 100%; min-width: 760px; border-collapse: collapse; text-align: left; }
    .md-table thead th {
        padding: 15px 18px; background: var(--navy); color: #fff;
        font-size: 12px; font-weight: 700; letter-spacing: .06em; white-space: nowrap;
    }
    .md-table tbody td { padding: 16px 18px; border-bottom: 1px solid #EBF0F6; font-size: 14px; color: #33465C; vertical-align: middle; }
    .md-table tbody tr { transition: background .2s; }
    .md-table tbody tr:hover { background: #F5F9FF; }
    .md-table tbody tr:last-child td { border-bottom: 0; }
    .md-number-column { width: 70px; }
    .md-number-cell { font-weight: 700; color: #7C8DA3 !important; font-variant-numeric: tabular-nums; }
    .md-primary-text { font-weight: 700; color: var(--navy); overflow-wrap: anywhere; }
    .md-type-label { display: inline-flex; padding: 5px 12px; border-radius: 999px; background: var(--soft); color: var(--royal); font-size: 12.5px; font-weight: 700; }
    .md-year-label {
        display: inline-grid; place-items: center; min-width: 68px; height: 32px; padding: 0 10px; border-radius: 10px;
        background: var(--soft); color: var(--royal); font-family: var(--font-display); font-size: 14px; font-weight: 700; font-variant-numeric: tabular-nums;
    }
    .md-status { display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 999px; font-size: 12.5px; font-weight: 700; white-space: nowrap; }
    .md-status > span { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
    .md-status-active { color: var(--ok); background: var(--ok-bg); }
    .md-status-inactive { color: #5A6B7E; background: #ECF0F4; }

    .md-action-column { min-width: 250px; }
    .md-row-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; }
    .md-row-actions form { display: inline-flex; margin: 0; }
    .md-action {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        min-height: 38px; padding: 7px 13px; border: 1.5px solid transparent; border-radius: 10px; background: #fff;
        font-family: var(--font-body); font-size: 13px; font-weight: 700; line-height: 1.3; white-space: nowrap; cursor: pointer;
        transition: transform .2s var(--ease), background .2s, color .2s, border-color .2s;
    }
    .md-action:hover { transform: translateY(-2px); }
    .md-action .i { font-size: 15px; }
    .md-action-edit { color: var(--royal); border-color: #C5D6F5; }
    .md-action-edit:hover { color: #fff; background: var(--royal); border-color: var(--royal); }
    .md-action-status { color: #4B5E75; border-color: var(--border); }
    .md-action-status:hover { color: var(--navy); background: var(--ice); border-color: #c5d3e4; }
    .md-action-delete { color: var(--danger); border-color: var(--danger-line); }
    .md-action-delete:hover { color: #fff; background: var(--danger); border-color: var(--danger); }

    .md-empty-cell { padding: 0 !important; }
    .md-empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 280px; padding: 36px 20px; text-align: center; }
    .md-empty-icon { display: grid; place-items: center; width: 60px; height: 60px; margin-bottom: 14px; border-radius: 18px; background: var(--soft); color: var(--royal); font-size: 28px; }
    .md-empty-state strong { font-family: var(--font-display); font-size: 17px; font-weight: 600; color: var(--navy); }
    .md-empty-state p { max-width: 400px; margin: 6px 0 16px; font-size: 14px; color: var(--slate); }

    /* ---------- Pagination ---------- */
    .md-pagination {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
        padding: 16px clamp(18px, 2.6vw, 26px); border-top: 1px solid var(--border);
    }
    .md-pagination-info { font-size: 13px; color: var(--slate); }
    .md-pagination-info strong { color: var(--navy); }
    .md-pagination-links { min-width: 0; overflow-x: auto; }
    .md-pagination-links nav { display: flex; align-items: center; gap: 5px; }
    .md-pagination-links nav > div:first-child { display: none; }
    .md-pagination-links nav > div:last-child { display: flex; flex-wrap: wrap; align-items: center; gap: 4px; }
    .md-pagination-links a, .md-pagination-links span[aria-current="page"] span, .md-pagination-links span[aria-disabled="true"] span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 38px; min-height: 38px; padding: 6px 10px;
        color: var(--royal); background: var(--ice); border: 1px solid var(--border); border-radius: 10px;
        font-size: 13px; font-weight: 600; transition: .2s;
    }
    .md-pagination-links a:hover { color: #fff; background: var(--royal); border-color: var(--royal); }
    .md-pagination-links span[aria-current="page"] span { color: #fff; background: var(--royal); border-color: var(--royal); }
    .md-pagination-links svg { width: 17px; height: 17px; }

    .md-footer { display: flex; justify-content: space-between; gap: 15px; padding: 4px 2px 16px; font-size: 12.5px; color: var(--slate); }
    .md-footer span:first-child { font-family: var(--font-display); font-weight: 700; color: var(--navy); }

    @keyframes md-rise { from { opacity: 0; transform: translate3d(0, 18px, 0); } to { opacity: 1; transform: none; } }
    @keyframes md-drift { to { transform: translate3d(-40px, 40px, 0); } }

    /* ---------- Responsive ---------- */
    @media (max-width: 1050px) {
        .md-heading { grid-template-columns: 1fr; align-items: start; }
        .md-heading-side { justify-self: start; }
        .md-filter-form { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .md-filter-actions { grid-column: 1 / -1; }
    }
    @media (max-width: 700px) {
        .md-add-button, .md-filter-actions .md-btn { width: 100%; }
        .md-filter-form { grid-template-columns: 1fr; }
        .md-filter-actions { flex-direction: column; align-items: stretch; }
        .md-section-heading { flex-direction: column; }
        .md-pagination { flex-direction: column; align-items: flex-start; }

        /* tabel menjadi kartu per baris */
        .md-table-container { overflow: visible; }
        .md-table { display: block; min-width: 0; }
        .md-table thead { display: none; }
        .md-table tbody { display: grid; gap: 12px; padding: 12px; }
        .md-table tbody tr { display: block; overflow: hidden; border: 1px solid var(--border); border-radius: 14px; }
        .md-table tbody td {
            display: grid; grid-template-columns: minmax(100px, 36%) minmax(0, 1fr); gap: 10px; align-items: center;
            padding: 11px 14px; font-size: 14px; border-bottom: 1px solid #EDF1F5; overflow-wrap: anywhere;
        }
        .md-table tbody td[data-label]::before { content: attr(data-label); font-size: 13px; font-weight: 600; color: var(--slate); }
        .md-table tbody tr td:last-child { display: block; border-bottom: 0; }
        .md-table tbody td.md-empty-cell { display: block; border: 0; }
        .md-action-column { min-width: 0; }
        .md-row-actions .md-action { flex: 1 1 auto; }
        .md-footer { flex-direction: column; gap: 2px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .md-page *, .md-page *::before, .md-page *::after { animation: none !important; transition-duration: .01ms !important; scroll-behavior: auto !important; }
    }
</style>

<script>
    (function () {
        var el = document.getElementById('md-counter');
        if (!el) { return; }
        var target = parseInt(el.getAttribute('data-target'), 10) || 0;
        if (target < 1 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }

        var fmt = new Intl.NumberFormat('id-ID');
        var start = null, dur = 900;
        el.textContent = '0';

        requestAnimationFrame(function step(t) {
            if (start === null) { start = t; }
            var p = Math.min((t - start) / dur, 1);
            el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 3))));
            if (p < 1) { requestAnimationFrame(step); }
        });
    })();
</script>

@endsection