@extends('layouts.app')

@section('title', 'Master Data ' . $config['title'] . ' · ARTA')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

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

    $pageActive = $items->getCollection()->filter(fn ($row) => (bool) $row->is_active)->count();
    $pageInactive = $items->getCollection()->count() - $pageActive;
@endphp

{{-- Icon sprite (gaya Lucide, inline, tanpa dependency) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <symbol id="i-chevron" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></symbol>
    <symbol id="i-layers" viewBox="0 0 24 24"><path d="M12 3 3 8l9 5 9-5-9-5Z"/><path d="m3 12 9 5 9-5"/><path d="m3 16 9 5 9-5"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24"><path d="M12 5v14"/><path d="M5 12h14"/></symbol>
    <symbol id="i-ok" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="i-alert" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></symbol>
    <symbol id="i-filter" viewBox="0 0 24 24"><path d="M22 3H2l8 9.46V19l4 2v-8.54z"/></symbol>
    <symbol id="i-reset" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></symbol>
    <symbol id="i-down" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="i-edit" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.4 3.6a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
    <symbol id="i-power" viewBox="0 0 24 24"><path d="M12 2v10"/><path d="M18.4 6.6a9 9 0 1 1-12.8 0"/></symbol>
    <symbol id="i-trash" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></symbol>
    <symbol id="i-inbox" viewBox="0 0 24 24"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></symbol>
    <symbol id="i-pulse" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/></symbol>
</svg>

<div class="md-progress" id="mdProgress" aria-hidden="true"></div>

<div class="md-page" id="mdPage">

    {{-- ================= HERO ================= --}}
    <header class="md-heading">
        <span class="md-grid-bg" aria-hidden="true"></span>
        <span class="md-orb md-orb-1" aria-hidden="true"></span>
        <span class="md-orb md-orb-2" aria-hidden="true"></span>

        <div class="md-heading-main">
            <nav class="md-breadcrumb" aria-label="Breadcrumb" data-reveal>
                <span>Super Admin</span>
                <svg class="i" aria-hidden="true"><use href="#i-chevron"/></svg>
                <span aria-current="page">Master Data</span>
            </nav>

            <div class="md-brand" data-reveal style="--d:.05s">
                <span class="md-brand-mark" aria-hidden="true"><svg class="i"><use href="#i-pulse"/></svg></span>
                ARTA &middot; Referensi Sistem
            </div>

            <h1 data-reveal style="--d:.1s">
                Master data
                <span>{{ strtolower($config['title']) }}.</span>
            </h1>

            <p class="md-description" data-reveal style="--d:.18s">
                Kelola {{ strtolower($config['title']) }} sebagai referensi utama
                <strong>ARTA</strong> &mdash; Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan.
            </p>

            <div class="md-hero-cta" data-reveal style="--d:.24s">
                <a href="{{ route('super-admin.master-data.create', ['category' => $category]) }}"
                   class="md-btn md-btn-primary md-add-button">
                    <svg class="i" aria-hidden="true"><use href="#i-plus"/></svg>
                    Tambah Data
                </a>
            </div>
        </div>

        <div class="md-bento" data-reveal style="--d:.3s">
            <div class="md-tile md-tile-main">
                <small>
                    <svg class="i" aria-hidden="true"><use href="#i-layers"/></svg>
                    Total data
                </small>
                <strong id="md-counter" data-count="{{ (int) $totalItems }}">{{ number_format($totalItems, 0, ',', '.') }}</strong>
                <span>Data terdaftar</span>
            </div>

            <div class="md-tile">
                <b data-count="{{ $pageActive }}">{{ $pageActive }}</b>
                <span>Aktif di halaman ini</span>
            </div>

            <div class="md-tile">
                <b data-count="{{ $pageInactive }}">{{ $pageInactive }}</b>
                <span>Nonaktif di halaman ini</span>
            </div>
        </div>
    </header>

    {{-- ================= NOTIFIKASI ================= --}}
    @if (session('success'))
        <div class="md-alert md-alert-success" role="status" data-reveal>
            <svg class="i" aria-hidden="true"><use href="#i-ok"/></svg>
            <div>
                <strong>Berhasil</strong>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="md-alert md-alert-error" role="alert" data-reveal>
            <svg class="i" aria-hidden="true"><use href="#i-alert"/></svg>
            <div>
                <strong>Data tidak dapat diproses</strong>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="md-alert md-alert-error" role="alert" data-reveal>
            <svg class="i" aria-hidden="true"><use href="#i-alert"/></svg>
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

    {{-- ================= FILTER ================= --}}
    <section class="md-filter-panel" aria-labelledby="md-filter-title" data-reveal>
        <div class="md-section-heading">
            <div>
                <h2 id="md-filter-title">Temukan data</h2>
                <p>Gunakan pencarian dan filter untuk menemukan data.</p>
            </div>

            @if ($activeFilters > 0)
                <span class="md-filter-count">{{ $activeFilters }} filter aktif</span>
            @endif
        </div>

        <form method="GET"
              action="{{ route('super-admin.master-data.index', ['category' => $category]) }}"
              class="md-filter-form">

            <div class="md-field">
                <label for="search">Pencarian Data</label>
                <div class="md-input-wrap">
                    <svg class="i" aria-hidden="true"><use href="#i-search"/></svg>
                    <input type="text" id="search" name="search"
                           value="{{ $filters['search'] ?? '' }}"
                           placeholder="Masukkan nama atau tahun"
                           autocomplete="off">
                </div>
            </div>

            <div class="md-field">
                <label for="status">Status Data</label>
                <div class="md-select-wrap">
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option>
                        <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Nonaktif</option>
                    </select>
                    <svg class="i" aria-hidden="true"><use href="#i-down"/></svg>
                </div>
            </div>

            <div class="md-filter-actions">
                <button type="submit" class="md-btn md-btn-primary">
                    <svg class="i" aria-hidden="true"><use href="#i-filter"/></svg>
                    Terapkan Filter
                </button>

                <a href="{{ route('super-admin.master-data.index', ['category' => $category]) }}"
                   class="md-btn md-btn-ghost" data-keep-scroll>
                    <svg class="i" aria-hidden="true"><use href="#i-reset"/></svg>
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- ================= TABEL ================= --}}
    <section class="md-table-panel" aria-labelledby="md-table-title" data-reveal>

        <div class="md-table-heading">
            <div>
                <h2 id="md-table-title">Data {{ $config['title'] }}</h2>
                <p>Berikut daftar data yang tersimpan pada sistem ARTA.</p>
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
                        <tr style="--i: {{ $loop->index }}">
                            <td data-label="No." class="md-number-cell">
                                {{ $items->firstItem() + $loop->index }}
                            </td>

                            @foreach ($columns as $field => $label)
                                <td data-label="{{ $label }}">
                                    @if ($field === 'province')
                                        <span class="md-primary-text">{{ $item->province?->name ?? '-' }}</span>

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

                                        <span class="md-type-label">{{ $typeLabel }}</span>

                                    @elseif ($field === 'year')
                                        <span class="md-year-label">{{ $item->year }}</span>

                                    @else
                                        <span class="md-primary-text">{{ $item->{$field} ?? '-' }}</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- STATUS --}}
                            <td data-label="Status">
                                @if ($item->is_active)
                                    <span class="md-status md-status-active"><span></span>Aktif</span>
                                @else
                                    <span class="md-status md-status-inactive"><span></span>Nonaktif</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td data-label="Aksi">
                                <div class="md-row-actions">

                                    <a href="{{ route('super-admin.master-data.edit', [
                                            'category' => $category,
                                            'id' => $item->id,
                                        ]) }}"
                                       class="md-action md-action-edit"
                                       aria-label="Edit data">
                                        <svg class="i" aria-hidden="true"><use href="#i-edit"/></svg>
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('super-admin.master-data.status', [
                                              'category' => $category,
                                              'id' => $item->id,
                                          ]) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="md-action md-action-status">
                                            <svg class="i" aria-hidden="true"><use href="#i-power"/></svg>
                                            @if ($item->is_active)
                                                Nonaktifkan
                                            @else
                                                Aktifkan
                                            @endif
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('super-admin.master-data.destroy', [
                                              'category' => $category,
                                              'id' => $item->id,
                                          ]) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini? Data yang sudah dihapus tidak dapat dikembalikan.');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="md-action md-action-delete">
                                            <svg class="i" aria-hidden="true"><use href="#i-trash"/></svg>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 3 }}" class="md-empty-cell">
                                <div class="md-empty-state">
                                    <div class="md-empty-icon">
                                        <svg class="i" aria-hidden="true"><use href="#i-inbox"/></svg>
                                    </div>

                                    <strong>Belum ada data</strong>

                                    <p>
                                        Tidak ada data yang sesuai dengan
                                        pencarian atau filter yang dipilih.
                                    </p>

                                    <a href="{{ route('super-admin.master-data.create', ['category' => $category]) }}"
                                       class="md-btn md-btn-secondary">
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
        <span>ARTA</span>
        <span>Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan &middot; Pengelolaan Master Data</span>
    </footer>

</div>

<style>
    /* =====================================================
       ARTA — PREMIUM BLUE EDITORIAL · MASTER DATA
       Semua selector di-scope ke .md-page / .md-progress
       ===================================================== */

    .md-progress {
        position: fixed; top: 0; left: 0; right: 0; z-index: 2000;
        height: 3px; pointer-events: none;
        background: linear-gradient(90deg, #38C6E8, #3478F6, #2457D6);
        transform: scaleX(0); transform-origin: 0 50%;
    }

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

        width: 100%; max-width: none; min-width: 0;
        color: var(--text);
        font-family: var(--font-body); font-size: 15px; line-height: 1.65;
        -webkit-font-smoothing: antialiased;
    }
    .md-page *, .md-page *::before, .md-page *::after { box-sizing: border-box; }
    .md-page button, .md-page input, .md-page select { font: inherit; }
    .md-page a { text-decoration: none; }
    .md-page a, .md-page button { -webkit-tap-highlight-color: transparent; }
    .md-page svg.i {
        width: 1em; height: 1em; flex: none;
        fill: none; stroke: currentColor;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
    .md-page :focus-visible { outline: 3px solid var(--cyan); outline-offset: 3px; border-radius: 8px; }
    .md-sr { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }

    /* ---------- Scroll reveal (aktif hanya bila JS jalan) ---------- */
    .md-page.js [data-reveal] {
        opacity: 0; transform: translate3d(0, 22px, 0);
        transition: opacity .7s var(--ease), transform .7s var(--ease);
        transition-delay: var(--d, 0s);
        will-change: opacity, transform;
    }
    .md-page.js [data-reveal].is-in { opacity: 1; transform: none; }

    /* ---------- Hero ---------- */
    .md-heading {
        position: relative; isolation: isolate; overflow: hidden;
        display: grid; grid-template-columns: minmax(0, 1.45fr) minmax(0, .8fr);
        align-items: end; gap: clamp(24px, 4vw, 64px);
        margin-bottom: 24px;
        padding: clamp(26px, 4.5vw, 60px);
        border-radius: 28px; color: #fff;
        background:
            radial-gradient(800px 380px at 90% -10%, rgba(52,120,246,.5), transparent 60%),
            radial-gradient(600px 320px at 0% 110%, rgba(56,198,232,.2), transparent 60%),
            linear-gradient(125deg, var(--navy) 0%, var(--midnight) 60%, #123A74 100%);
    }
    .md-grid-bg {
        position: absolute; inset: 0; z-index: -1;
        background-image:
            linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px);
        background-size: 52px 52px;
        -webkit-mask-image: radial-gradient(ellipse at 75% 25%, #000 10%, transparent 70%);
        mask-image: radial-gradient(ellipse at 75% 25%, #000 10%, transparent 70%);
    }
    .md-orb {
        position: absolute; z-index: -1; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.14);
        animation: md-float 16s ease-in-out infinite;
    }
    .md-orb-1 { width: 380px; height: 380px; top: -160px; right: -80px; }
    .md-orb-2 { width: 200px; height: 200px; bottom: -90px; right: 30%; background: rgba(56,198,232,.07); animation-delay: -6s; }

    .md-breadcrumb {
        display: flex; align-items: center; gap: 8px; margin-bottom: 26px;
        font-size: 13px; font-weight: 600; color: rgba(255,255,255,.7);
    }
    .md-breadcrumb .i { font-size: 14px; }
    .md-breadcrumb span:last-child { color: var(--cyan); }

    .md-brand {
        display: inline-flex; align-items: center; gap: 12px; margin-bottom: 18px;
        font-size: 12px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: var(--cyan);
    }
    .md-brand-mark {
        display: grid; place-items: center; width: 40px; height: 40px; border-radius: 12px; font-size: 19px; color: #fff;
        background: linear-gradient(135deg, var(--electric), var(--cyan));
        box-shadow: 0 10px 28px rgba(52,120,246,.45);
    }

    .md-heading h1 {
        margin: 0 0 18px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.3rem, 6.4vw, 4.6rem); line-height: 1.02; letter-spacing: -.045em;
        overflow-wrap: anywhere;
    }
    .md-heading h1 span {
        display: block;
        background: linear-gradient(95deg, #8FB8FF 0%, var(--cyan) 60%, #B6F0FF 100%);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .md-description { max-width: 560px; margin: 0; font-size: clamp(15px, 1.4vw, 17px); line-height: 1.8; color: rgba(255,255,255,.82); }
    .md-description strong { color: #fff; }
    .md-hero-cta { margin-top: 28px; }

    /* Bento stat */
    .md-bento { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .md-tile {
        padding: 20px 18px; border: 1px solid rgba(255,255,255,.15); border-radius: 20px;
        background: rgba(255,255,255,.07);
        -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
    }
    .md-tile b {
        display: block; font-family: var(--font-display); font-size: clamp(1.8rem, 3vw, 2.4rem);
        font-weight: 800; line-height: 1; letter-spacing: -.04em; font-variant-numeric: tabular-nums;
    }
    .md-tile span { display: block; margin-top: 8px; font-size: 12px; font-weight: 600; line-height: 1.4; color: #A9C3E6; }
    .md-tile-main { grid-column: 1 / -1; padding: 24px 22px; background: rgba(255,255,255,.1); }
    .md-tile-main small { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: rgba(255,255,255,.8); }
    .md-tile-main small .i { font-size: 17px; color: var(--cyan); }
    .md-tile-main strong {
        display: block; margin: 6px 0 2px; font-family: var(--font-display); font-weight: 800; line-height: 1;
        font-size: clamp(2.8rem, 5.5vw, 4.4rem); letter-spacing: -.05em; font-variant-numeric: tabular-nums;
    }
    .md-tile-main span { margin: 0; font-size: 13px; color: rgba(255,255,255,.7); }

    /* ---------- Buttons ---------- */
    .md-btn {
        position: relative;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        min-height: 50px; padding: 10px 20px; border: 1.5px solid transparent; border-radius: 13px;
        font-family: var(--font-display); font-size: 14px; font-weight: 600; white-space: nowrap; cursor: pointer;
        transition: transform .2s var(--ease), box-shadow .2s, background-color .2s, color .2s, border-color .2s;
    }
    .md-btn .i { font-size: 17px; }
    .md-btn:active { transform: scale(.97); }
    .md-btn-primary { color: #fff; background: linear-gradient(110deg, var(--royal), var(--electric)); box-shadow: 0 12px 26px rgba(36,87,214,.4); }
    .md-btn-primary:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 18px 34px rgba(36,87,214,.5); }
    .md-btn-secondary { color: var(--royal); background: #fff; border-color: #C5D6F5; }
    .md-btn-secondary:hover { color: var(--royal); background: var(--soft); transform: translateY(-2px); }
    .md-btn-ghost { color: var(--slate); background: transparent; }
    .md-btn-ghost:hover { color: var(--navy); background: var(--ice); }
    .md-add-button { min-height: 56px; padding: 12px 28px; font-size: 15px; }

    /* Loading state (hanya transform/opacity pada animasi) */
    .md-page .is-loading { pointer-events: none; opacity: .8; cursor: progress; }
    .md-page .is-loading > .i { display: none; }
    .md-page .is-loading::before {
        content: ""; width: 14px; height: 14px; flex: none;
        border: 2px solid currentColor; border-right-color: transparent; border-radius: 50%;
        animation: md-spin .75s linear infinite;
    }

    /* ---------- Alerts ---------- */
    .md-alert {
        display: flex; align-items: flex-start; gap: 12px; margin-bottom: 18px;
        padding: 16px 18px; border: 1px solid transparent; border-radius: 16px; font-size: 14px; line-height: 1.7;
    }
    .md-alert > .i { margin-top: 3px; font-size: 20px; }
    .md-alert strong { display: block; font-family: var(--font-display); font-size: 15px; font-weight: 600; }
    .md-alert p { margin: 2px 0 0; }
    .md-alert ul { margin: 6px 0 0; padding-left: 20px; }
    .md-alert-success { color: var(--ok); background: var(--ok-bg); border-color: var(--ok-line); }
    .md-alert-error { color: var(--danger); background: var(--danger-bg); border-color: var(--danger-line); }

    /* ---------- Filter ---------- */
    .md-filter-panel {
        margin-bottom: 20px; padding: clamp(18px, 2.6vw, 28px);
        border: 1px solid var(--border); border-radius: 24px; background: #fff;
        box-shadow: 0 14px 36px rgba(8,27,51,.05);
    }
    .md-section-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
    .md-section-heading h2 { margin: 0 0 4px; font-family: var(--font-display); font-size: 19px; font-weight: 700; letter-spacing: -.02em; color: var(--navy); }
    .md-section-heading p { margin: 0; font-size: 13.5px; color: var(--slate); }
    .md-filter-count {
        padding: 6px 12px; border-radius: 999px; background: var(--soft); border: 1px solid #D3E2FB;
        color: var(--royal); font-size: 12.5px; font-weight: 700; white-space: nowrap;
    }
    .md-filter-form { display: grid; grid-template-columns: minmax(220px, 1.7fr) minmax(180px, .8fr) auto; align-items: end; gap: 14px; }
    .md-field { min-width: 0; }
    .md-field label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: var(--navy); }
    .md-input-wrap, .md-select-wrap {
        position: relative; display: flex; align-items: center; min-height: 52px;
        border: 1.5px solid var(--border); border-radius: 13px; background: var(--ice);
        transition: border-color .2s, box-shadow .2s, background-color .2s;
    }
    .md-input-wrap:hover, .md-select-wrap:hover { border-color: #c5d3e4; }
    .md-input-wrap:focus-within, .md-select-wrap:focus-within { background: #fff; border-color: var(--electric); box-shadow: 0 0 0 4px rgba(52,120,246,.16); }
    .md-input-wrap { gap: 11px; padding: 0 14px; }
    .md-input-wrap .i, .md-select-wrap > .i { font-size: 18px; color: var(--slate); }
    .md-input-wrap input {
        width: 100%; min-width: 0; height: 49px; padding: 0; border: 0; outline: none; background: transparent;
        color: var(--text); font-size: 14.5px;
    }
    .md-input-wrap input::placeholder { color: #8796aa; }
    .md-select-wrap select {
        width: 100%; height: 49px; padding: 0 40px 0 14px; appearance: none; -webkit-appearance: none;
        border: 0; outline: none; background: transparent; color: var(--text); font-size: 14.5px; cursor: pointer;
    }
    .md-select-wrap > .i { position: absolute; right: 13px; pointer-events: none; }
    .md-filter-actions { display: flex; align-items: center; gap: 8px; }

    /* ---------- Tabel ---------- */
    .md-table-panel {
        overflow: hidden; margin-bottom: 20px; border: 1px solid var(--border); border-radius: 24px;
        background: #fff; box-shadow: 0 22px 50px -20px rgba(8,27,51,.18);
    }
    .md-table-heading {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        padding: 22px clamp(18px, 2.6vw, 28px); border-bottom: 1px solid var(--border);
        background: linear-gradient(180deg, var(--ice), #fff);
    }
    .md-table-heading h2 { margin: 0 0 3px; font-family: var(--font-display); font-size: 19px; font-weight: 700; letter-spacing: -.02em; color: var(--navy); }
    .md-table-heading p { margin: 0; font-size: 13.5px; color: var(--slate); }
    .md-table-meta {
        display: inline-flex; align-items: center; gap: 9px; padding: 7px 14px; border-radius: 999px;
        background: var(--soft); border: 1px solid #D3E2FB; color: var(--royal); font-size: 12.5px; font-weight: 700; white-space: nowrap;
    }
    .md-live-dot { position: relative; width: 8px; height: 8px; border-radius: 50%; background: #14A37A; }
    .md-live-dot::after {
        content: ""; position: absolute; inset: 0; border-radius: 50%; background: #14A37A;
        animation: md-ping 2s ease-out infinite;
    }
    .md-table-container { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: thin; }
    .md-table { width: 100%; min-width: 780px; border-collapse: collapse; text-align: left; }
    .md-table thead th {
        padding: 15px 20px; background: var(--navy); color: #fff;
        font-size: 12px; font-weight: 700; letter-spacing: .06em; white-space: nowrap;
    }
    .md-table tbody td { padding: 17px 20px; border-bottom: 1px solid #EBF0F6; font-size: 14px; color: #33465C; vertical-align: middle; }
    .md-table tbody tr { transition: background-color .2s; }
    .md-page.js .md-table tbody tr {
        animation: md-row .55s var(--ease) both;
        animation-delay: calc(var(--i, 0) * 45ms + .2s);
    }
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

    .md-action-column { min-width: 290px; }
    .md-row-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; }
    .md-row-actions form { display: inline-flex; margin: 0; }
    .md-action {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        min-height: 40px; padding: 7px 13px; border: 1.5px solid transparent; border-radius: 11px; background: #fff;
        font-family: var(--font-body); font-size: 13px; font-weight: 700; line-height: 1.3; white-space: nowrap; cursor: pointer;
        transition: transform .2s var(--ease), background-color .2s, color .2s, border-color .2s;
    }
    .md-action:hover { transform: translateY(-2px); }
    .md-action:active { transform: scale(.97); }
    .md-action .i { font-size: 15px; }
    .md-action-edit { color: var(--royal); border-color: #C5D6F5; }
    .md-action-edit:hover { color: #fff; background: var(--royal); border-color: var(--royal); }
    .md-action-status { color: #4B5E75; border-color: var(--border); }
    .md-action-status:hover { color: var(--navy); background: var(--ice); border-color: #c5d3e4; }
    .md-action-delete { color: var(--danger); border-color: var(--danger-line); }
    .md-action-delete:hover { color: #fff; background: var(--danger); border-color: var(--danger); }

    .md-empty-cell { padding: 0 !important; }
    .md-empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 300px; padding: 36px 20px; text-align: center; }
    .md-empty-icon {
        display: grid; place-items: center; width: 64px; height: 64px; margin-bottom: 14px; border-radius: 20px;
        background: var(--soft); color: var(--royal); font-size: 28px;
        animation: md-float 6s ease-in-out infinite;
    }
    .md-empty-state strong { font-family: var(--font-display); font-size: 18px; font-weight: 600; color: var(--navy); }
    .md-empty-state p { max-width: 400px; margin: 6px 0 18px; font-size: 14px; color: var(--slate); }

    /* ---------- Pagination (Tailwind & Bootstrap) ---------- */
    .md-pagination {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
        padding: 16px clamp(18px, 2.6vw, 28px); border-top: 1px solid var(--border);
    }
    .md-pagination-info { font-size: 13px; color: var(--slate); }
    .md-pagination-info strong { color: var(--navy); }
    .md-pagination-links { min-width: 0; max-width: 100%; overflow-x: auto; }
    .md-pagination-links nav { display: flex; align-items: center; gap: 5px; }
    .md-pagination-links nav > div:first-child { display: none; }
    .md-pagination-links nav > div:last-child { display: flex; flex-wrap: wrap; align-items: center; gap: 4px; }
    .md-pagination-links .pagination { display: flex; flex-wrap: wrap; gap: 4px; margin: 0; padding: 0; list-style: none; }
    .md-pagination-links a,
    .md-pagination-links span[aria-current="page"] span,
    .md-pagination-links span[aria-disabled="true"] span,
    .md-pagination-links .page-link {
        display: inline-flex; align-items: center; justify-content: center; min-width: 40px; min-height: 40px; padding: 6px 10px;
        color: var(--royal); background: var(--ice); border: 1px solid var(--border); border-radius: 11px;
        font-size: 13px; font-weight: 600; transition: background-color .2s, color .2s, border-color .2s;
    }
    .md-pagination-links a:hover, .md-pagination-links .page-link:hover { color: #fff; background: var(--royal); border-color: var(--royal); }
    .md-pagination-links span[aria-current="page"] span,
    .md-pagination-links .page-item.active .page-link { color: #fff; background: var(--royal); border-color: var(--royal); }
    .md-pagination-links .page-item.disabled .page-link { color: #9AA9BB; background: var(--ice); }
    .md-pagination-links svg { width: 17px; height: 17px; }

    .md-footer { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 6px 15px; padding: 4px 2px 16px; font-size: 12.5px; color: var(--slate); }
    .md-footer span:first-child { font-family: var(--font-display); font-weight: 800; letter-spacing: .08em; color: var(--navy); }

    /* ---------- Keyframes ---------- */
    @keyframes md-float { 0%, 100% { transform: translate3d(0,0,0); } 50% { transform: translate3d(-16px, 20px, 0); } }
    @keyframes md-spin { to { transform: rotate(360deg); } }
    @keyframes md-ping { 0% { transform: scale(1); opacity: .6; } 80%, 100% { transform: scale(3); opacity: 0; } }
    @keyframes md-row { from { opacity: 0; transform: translate3d(0, 12px, 0); } to { opacity: 1; transform: none; } }

    /* ---------- Responsive ---------- */
    @media (max-width: 1050px) {
        .md-heading { grid-template-columns: 1fr; align-items: start; }
        .md-bento { max-width: 560px; }
        .md-filter-form { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .md-filter-actions { grid-column: 1 / -1; }
    }
    @media (max-width: 700px) {
        .md-heading { border-radius: 22px; }
        .md-add-button, .md-filter-actions .md-btn { width: 100%; }
        .md-filter-form { grid-template-columns: 1fr; }
        .md-filter-actions { flex-direction: column; align-items: stretch; }
        .md-section-heading { flex-direction: column; }
        .md-pagination { flex-direction: column; align-items: flex-start; }
        .md-filter-panel, .md-table-panel { border-radius: 20px; }

        /* tabel menjadi kartu per baris */
        .md-table-container { overflow: visible; }
        .md-table { display: block; min-width: 0; }
        .md-table thead { display: none; }
        .md-table tbody { display: grid; gap: 12px; padding: 12px; }
        .md-table tbody tr { display: block; overflow: hidden; border: 1px solid var(--border); border-radius: 16px; background: #fff; }
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
        .md-page *, .md-page *::before, .md-page *::after {
            animation: none !important;
            transition-duration: .01ms !important;
            scroll-behavior: auto !important;
        }
        .md-page.js [data-reveal] { opacity: 1; transform: none; }
    }
</style>

<script>
(function () {
    'use strict';

    var root = document.getElementById('mdPage');
    if (!root) { return; }
    root.classList.add('js');

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- 1. Jaga posisi scroll (tidak lompat ke atas) ---------- */
    var KEY = 'arta:md-scroll:' + location.pathname;

    function saveScroll() {
        try {
            sessionStorage.setItem(KEY, JSON.stringify({
                y: window.pageYOffset || document.documentElement.scrollTop || 0,
                t: Date.now()
            }));
        } catch (e) {}
    }

    function readScroll() {
        try {
            var raw = sessionStorage.getItem(KEY);
            if (!raw) { return null; }
            var data = JSON.parse(raw);
            /* kedaluwarsa 20 detik agar tidak "nyangkut" di kunjungan lain */
            if (!data || (Date.now() - data.t) > 20000) {
                sessionStorage.removeItem(KEY);
                return null;
            }
            return data.y || 0;
        } catch (e) { return null; }
    }

    var savedY = readScroll();
    if (savedY !== null) {
        window.scrollTo(0, savedY);
        /* ulangi setelah font/gambar selesai agar tetap pas */
        window.addEventListener('load', function () {
            window.scrollTo(0, savedY);
            try { sessionStorage.removeItem(KEY); } catch (e) {}
        });
    }

    /* ---------- 2. Scroll progress bar ---------- */
    var bar = document.getElementById('mdProgress');
    var ticking = false;
    function updateBar() {
        var h = document.documentElement;
        var max = (h.scrollHeight - h.clientHeight) || 1;
        var p = Math.min(1, Math.max(0, (window.pageYOffset || h.scrollTop) / max));
        bar.style.transform = 'scaleX(' + p + ')';
        ticking = false;
    }
    window.addEventListener('scroll', function () {
        if (!ticking) { ticking = true; requestAnimationFrame(updateBar); }
    }, { passive: true });
    window.addEventListener('resize', updateBar);
    updateBar();

    /* ---------- 3. Scroll reveal ---------- */
    var reveals = root.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window && !reduce) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
            });
        }, { threshold: .06, rootMargin: '0px 0px -30px 0px' });
        reveals.forEach(function (el) { io.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('is-in'); });
    }

    /* ---------- 4. Counter angka ---------- */
    var fmt = new Intl.NumberFormat('id-ID');
    root.querySelectorAll('[data-count]').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
        if (reduce || target < 1) { return; }
        var start = null, dur = 1000;
        el.textContent = '0';
        setTimeout(function () {
            requestAnimationFrame(function step(ts) {
                if (start === null) { start = ts; }
                var p = Math.min((ts - start) / dur, 1);
                el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 3))));
                if (p < 1) { requestAnimationFrame(step); }
            });
        }, 300);
    });

    /* ---------- 5. Form: loading + simpan posisi ---------- */
    root.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            /* confirm() Hapus yang dibatalkan sudah memanggil preventDefault */
            if (e.defaultPrevented) { return; }
            if (form.dataset.sending === '1') { e.preventDefault(); return; }

            form.dataset.sending = '1';
            saveScroll();

            var btn = e.submitter || form.querySelector('button[type="submit"]');
            if (btn) {
                btn.classList.add('is-loading');
                btn.setAttribute('aria-busy', 'true');
                /* disable setelah event selesai agar data form tetap terkirim */
                setTimeout(function () { btn.disabled = true; }, 0);
            }
        });
    });

    /* ---------- 6. Link yang harus tetap di posisi (pagination, reset) ---------- */
    root.querySelectorAll('.md-pagination-links a, a[data-keep-scroll]').forEach(function (a) {
        a.addEventListener('click', saveScroll);
    });

    /* ---------- 7. Kembali via tombol Back (bfcache): reset status loading ---------- */
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) { return; }
        root.querySelectorAll('form').forEach(function (f) { delete f.dataset.sending; });
        root.querySelectorAll('.is-loading').forEach(function (b) {
            b.classList.remove('is-loading');
            b.removeAttribute('aria-busy');
            b.disabled = false;
        });
    });
})();
</script>

@endsection