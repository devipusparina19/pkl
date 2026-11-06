@extends('layouts.app')

@section('title', 'Data Pelatihan')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       PREMIUM BLUE EDITORIAL — DATA PELATIHAN
       Semua selector di-scope ke .tp agar tidak bentrok
       dengan layouts.app.
       ===================================================== */
    .tp {
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
        --danger: #B42335;
        --danger-bg: #FFF1F2;
        --danger-line: #F7D1D5;
        --ok: #0F6B4A;
        --ok-bg: #E8F6EF;
        --ok-line: #B7E2CC;
        --font-display: "Sora", "Segoe UI", Arial, sans-serif;
        --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
        --ease: cubic-bezier(.2, .7, .2, 1);

        width: 100%; color: var(--text);
        font-family: var(--font-body); font-size: 15px; line-height: 1.65;
    }
    .tp *, .tp *::before, .tp *::after { box-sizing: border-box; }
    .tp button, .tp input, .tp select { font: inherit; }
    .tp svg.i {
        width: 1em; height: 1em; flex: none;
        fill: none; stroke: currentColor;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
    .tp :focus-visible { outline: 3px solid var(--cyan); outline-offset: 3px; border-radius: 6px; }

    /* ---------- Header editorial ---------- */
    .tp .head {
        position: relative; isolation: isolate; overflow: hidden;
        display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, .7fr);
        align-items: end; gap: clamp(20px, 4vw, 56px);
        margin-bottom: 28px; padding: clamp(28px, 4vw, 48px);
        border-radius: 24px; color: #fff;
        background: linear-gradient(120deg, var(--navy) 0%, var(--midnight) 60%, #173d86 100%);
        animation: tpRise .8s var(--ease) both;
    }
    .tp .head::before {
        content: ""; position: absolute; z-index: -1; right: -90px; top: -110px;
        width: 340px; height: 340px; border-radius: 50%;
        background: var(--royal); opacity: .45; filter: blur(60px);
        animation: tpDrift 24s ease-in-out infinite alternate;
    }
    .tp .head h1 {
        margin: 0 0 12px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2rem, 4.6vw, 3.4rem); line-height: 1.08; letter-spacing: -.035em;
    }
    .tp .head h1 span { display: block; color: var(--cyan); }
    .tp .head p { max-width: 520px; margin: 0; font-size: 15px; line-height: 1.8; color: rgba(255,255,255,.82); }
    .tp .head-stat {
        padding: 20px 24px; border-radius: 18px;
        border: 1px solid rgba(255,255,255,.2); background: rgba(255,255,255,.08);
    }
    .tp .head-stat small { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: rgba(255,255,255,.78); }
    .tp .head-stat small .i { font-size: 18px; color: var(--cyan); }
    .tp .head-stat strong {
        display: block; margin-top: 6px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.6rem, 5vw, 3.8rem); line-height: 1; letter-spacing: -.04em;
        font-variant-numeric: tabular-nums;
    }
    .tp .head-stat span { font-size: 13px; color: rgba(255,255,255,.7); }

    /* ---------- Notice ---------- */
    .tp .notice {
        display: flex; align-items: flex-start; gap: 12px; margin-bottom: 18px;
        padding: 15px 18px; border-radius: 14px; font-size: 14px; line-height: 1.7;
    }
    .tp .notice > .i { margin-top: 3px; font-size: 19px; }
    .tp .notice ul { margin: 6px 0 0; padding-left: 20px; }
    .tp .notice-success { color: var(--ok); background: var(--ok-bg); border: 1px solid var(--ok-line); }
    .tp .notice-error { color: var(--danger); background: var(--danger-bg); border: 1px solid var(--danger-line); }

    /* ---------- Toolbar (cari + filter tahun) ---------- */
    .tp .toolbar {
        display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
        gap: 14px; margin-bottom: 18px;
    }
    .tp .tool {
        padding: 18px 20px; border: 1px solid var(--border); border-radius: 18px; background: #fff;
    }
    .tp .tool label.t {
        display: block; margin-bottom: 10px;
        font-family: var(--font-display); font-size: 14px; font-weight: 600; color: var(--navy);
    }
    .tp .tool form { display: flex; align-items: center; gap: 10px; }
    .tp .field { position: relative; flex: 1; min-width: 0; }
    .tp .field .i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        font-size: 18px; color: var(--slate); pointer-events: none;
    }
    .tp .input, .tp .select {
        display: block; width: 100%; height: 50px; padding: 0 14px;
        color: var(--text); background: var(--ice);
        border: 1.5px solid var(--border); border-radius: 12px; font-size: 14px;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .tp .input { padding-left: 42px; }
    .tp .select { cursor: pointer; }
    .tp .input::placeholder { color: #8796aa; }
    .tp .input:hover, .tp .select:hover { border-color: #c5d3e4; }
    .tp .input:focus, .tp .select:focus {
        outline: none; background: #fff; border-color: var(--electric);
        box-shadow: 0 0 0 4px rgba(52,120,246,.16);
    }

    /* ---------- Buttons ---------- */
    .tp .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        min-height: 50px; padding: 10px 18px; border: 1.5px solid transparent; border-radius: 12px;
        font-family: var(--font-display); font-size: 14px; font-weight: 600;
        text-decoration: none; white-space: nowrap; cursor: pointer;
        transition: transform .2s var(--ease), box-shadow .2s, background .2s, color .2s, border-color .2s;
    }
    .tp .btn .i { font-size: 17px; }
    .tp .btn:active { transform: scale(.97); }
    .tp .btn-primary { color: #fff; background: linear-gradient(110deg, var(--royal), var(--electric)); box-shadow: 0 10px 22px rgba(36,87,214,.28); }
    .tp .btn-primary:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 14px 28px rgba(36,87,214,.38); }
    .tp .btn-secondary { color: var(--royal); background: #fff; border-color: #C5D6F5; }
    .tp .btn-secondary:hover { color: var(--royal); background: var(--soft); transform: translateY(-2px); }
    .tp .btn-ghost { color: var(--slate); background: transparent; }
    .tp .btn-ghost:hover { color: var(--navy); background: var(--ice); }
    .tp .btn-danger { color: var(--danger); background: var(--danger-bg); border-color: var(--danger-line); }
    .tp .btn-danger:hover { color: #fff; background: var(--danger); border-color: var(--danger); transform: translateY(-2px); }
    .tp .btn-sm { min-height: 38px; padding: 7px 13px; font-size: 13px; border-radius: 10px; }

    /* ---------- Active filter ---------- */
    .tp .active-filter { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; }
    .tp .active-filter > span { font-size: 13px; font-weight: 600; color: var(--slate); }
    .tp .chip {
        display: inline-flex; align-items: center; gap: 7px; padding: 6px 12px;
        border-radius: 999px; background: var(--soft); border: 1px solid #D3E2FB;
        color: var(--royal); font-size: 13px; font-weight: 700;
    }
    .tp .chip .i { font-size: 14px; }

    /* ---------- Table ---------- */
    .tp .panel {
        overflow: hidden; border: 1px solid var(--border); border-radius: 20px;
        background: #fff; box-shadow: 0 18px 44px rgba(8,27,51,.07);
    }
    .tp .panel-top {
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        padding: 18px 24px; border-bottom: 1px solid var(--border);
    }
    .tp .panel-top strong { font-family: var(--font-display); font-size: 16px; font-weight: 600; color: var(--navy); }
    .tp .panel-top span { font-size: 12px; color: var(--slate); }
    .tp .table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: thin; }
    .tp table { width: 100%; min-width: 1120px; margin: 0; border-collapse: collapse; }
    .tp thead th {
        padding: 15px 16px; background: var(--navy); color: #fff;
        font-size: 12px; font-weight: 700; letter-spacing: .06em; text-align: left; white-space: nowrap;
    }
    .tp tbody td {
        padding: 16px; border-bottom: 1px solid #EBF0F6;
        font-size: 14px; color: #33465C; vertical-align: middle;
    }
    .tp tbody tr { transition: background .2s; }
    .tp tbody tr:hover { background: #F5F9FF; }
    .tp tbody tr:last-child td { border-bottom: 0; }
    .tp td.no { width: 60px; font-weight: 700; color: #7C8DA3; font-variant-numeric: tabular-nums; }
    .tp td.nm { min-width: 240px; font-weight: 700; color: var(--navy); }
    .tp td.dt { white-space: nowrap; font-variant-numeric: tabular-nums; }
    .tp .actions { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
    .tp .actions form { margin: 0; }

    .tp .empty { padding: 56px 20px; text-align: center; color: var(--slate); line-height: 1.8; }
    .tp .empty .ic {
        display: grid; place-items: center; width: 60px; height: 60px; margin: 0 auto 14px;
        border-radius: 18px; background: var(--soft); color: var(--royal); font-size: 28px;
    }
    .tp .empty strong { display: block; margin-bottom: 4px; font-family: var(--font-display); font-size: 17px; color: var(--navy); }

    /* ---------- Pagination (Laravel default view) ---------- */
    .tp .pager {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
        padding: 16px 24px; border-top: 1px solid var(--border); overflow-x: auto;
    }
    .tp .pager p { margin: 0; font-size: 13px; color: var(--slate); }
    .tp .pager nav, .tp .pager nav > div:last-child { display: flex; align-items: center; flex-wrap: wrap; gap: 4px; }
    .tp .pager nav > div:first-child { display: none; }
    .tp .pager a, .tp .pager span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 38px; min-height: 38px; padding: 6px 10px;
        color: var(--royal); background: var(--ice); border: 1px solid var(--border);
        border-radius: 10px; font-size: 13px; text-decoration: none; transition: .2s;
    }
    .tp .pager a:hover { color: #fff; background: var(--royal); border-color: var(--royal); }
    .tp .pager span[aria-current="page"] span { color: #fff; background: var(--royal); border-color: var(--royal); }
    .tp .pager svg { width: 17px; height: 17px; }

    @keyframes tpRise { from { opacity: 0; transform: translate3d(0, 18px, 0); } to { opacity: 1; transform: none; } }
    @keyframes tpDrift { to { transform: translate3d(-40px, 40px, 0); } }

    /* ---------- Responsive ---------- */
    @media (max-width: 992px) {
        .tp .head { grid-template-columns: 1fr; }
        .tp .toolbar { grid-template-columns: 1fr; }
    }
    @media (max-width: 760px) {
        .tp .tool form { flex-direction: column; align-items: stretch; }
        .tp .tool form .btn { width: 100%; }
        .tp .panel-top { align-items: flex-start; flex-direction: column; gap: 2px; padding: 16px; }
        .tp .panel-top span { display: none; }

        /* tabel menjadi kartu per baris, tanpa scroll horizontal */
        .tp .table-wrap { overflow: visible; }
        .tp table { display: block; min-width: 0; }
        .tp thead { display: none; }
        .tp tbody { display: grid; gap: 12px; padding: 12px; }
        .tp tbody tr { display: block; overflow: hidden; border: 1px solid var(--border); border-radius: 14px; }
        .tp tbody td {
            display: grid; grid-template-columns: 112px minmax(0, 1fr); gap: 10px;
            padding: 11px 14px; font-size: 14px; border-bottom: 1px solid #EDF1F5;
        }
        .tp tbody td[data-label]::before { content: attr(data-label); font-size: 13px; font-weight: 600; color: var(--slate); }
        .tp tbody tr td:last-child { border-bottom: 0; }
        .tp td.no, .tp td.nm { width: auto; min-width: 0; }
        .tp td.empty { display: block; }
        .tp .actions .btn { flex: 1; }
        .tp .pager { padding: 14px 16px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .tp *, .tp *::before, .tp *::after { animation: none !important; transition-duration: .01ms !important; }
    }
</style>

@php
    $search = $search ?? '';
    $years = $years ?? collect();
    $year = $year ?? '';
@endphp

<div class="tp">

    {{-- HEADER --}}
    <header class="head">
        <div>
            <h1>Data pelatihan <span>terkelola rapi.</span></h1>
            <p>Kelola dan pantau seluruh program pelatihan yang telah terdaftar dalam sistem.</p>
        </div>

        <div class="head-stat">
            <small>
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4m8-4v4M3 10h18"/></svg>
                Total pelatihan
            </small>
            <strong id="tp-counter" data-target="{{ $trainings->total() }}">{{ $trainings->total() }}</strong>
            <span>{{ ($search !== '' || $year !== '') ? 'Sesuai pencarian atau filter' : 'Seluruh data terdaftar' }}</span>
        </div>
    </header>

    {{-- NOTIFICATIONS --}}
    @if (session('success'))
        <div class="notice notice-success" role="status">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="notice notice-error" role="alert">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="notice notice-error" role="alert">
            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
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

    {{-- TOOLBAR: PENCARIAN + FILTER TAHUN --}}
    <section class="toolbar" aria-label="Pencarian dan filter">

        <div class="tool">
            <label for="training-search" class="t">Cari data pelatihan</label>

            <form method="GET" action="{{ route('trainings.index') }}">
                <input type="hidden" name="year" value="{{ $year }}">

                <div class="field">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="search"
                        id="training-search"
                        name="search"
                        value="{{ $search }}"
                        class="input"
                        placeholder="Nama, kategori, kerja sama, angkatan, sumber dana"
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    Cari data
                </button>

                <a class="btn btn-ghost" href="{{ route('trainings.index', ['year' => $year ?: null]) }}">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset
                </a>
            </form>
        </div>

        <div class="tool">
            <label for="year" class="t">Filter tahun pelatihan</label>

            <form method="GET" action="{{ route('trainings.index') }}">
                @if ($search !== '')
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif

                <div class="field">
                    <select name="year" id="year" class="select">
                        <option value="" @selected($year === '')>Semua Tahun</option>
                        @foreach ($years as $availableYear)
                            <option value="{{ $availableYear }}" @selected((string) $year === (string) $availableYear)>
                                {{ $availableYear }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 3H2l8 9.46V19l4 2v-8.54z"/></svg>
                    Terapkan
                </button>

                <a class="btn btn-ghost" href="{{ route('trainings.index', ['search' => $search ?: null]) }}">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset
                </a>
            </form>
        </div>

    </section>

    {{-- FILTER AKTIF --}}
    <div class="active-filter">
        <span>Filter aktif:</span>
        @if ($year)
            <span class="chip">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4m8-4v4M3 10h18"/></svg>
                Tahun {{ $year }}
            </span>
        @else
            <span class="chip">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16M4 12h16M4 19h16"/></svg>
                Semua Tahun
            </span>
        @endif
    </div>

    {{-- TABEL --}}
    <section class="panel" aria-labelledby="tp-table-title">
        <div class="panel-top">
            <strong id="tp-table-title">Informasi program pelatihan</strong>
            <span>Geser tabel ke samping untuk melihat seluruh kolom.</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama Pelatihan</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Kerja Sama</th>
                        <th scope="col">Angkatan</th>
                        <th scope="col">Sumber Dana</th>
                        <th scope="col">JPL</th>
                        <th scope="col">Tanggal Mulai</th>
                        <th scope="col">Tanggal Selesai</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($trainings as $training)
                        <tr>
                            <td class="no" data-label="No.">{{ $trainings->firstItem() + $loop->index }}</td>

                            <td class="nm" data-label="Nama Pelatihan">{{ $training->name ?: '-' }}</td>

                            <td data-label="Kategori">{{ $training->category?->name ?? '-' }}</td>

                            <td data-label="Kerja Sama">{{ $training->organizer ?: '-' }}</td>

                            <td data-label="Angkatan">
                                {{ filled($training->batch_number) ? $training->batch_number : '-' }}
                            </td>

                            <td data-label="Sumber Dana">{{ $training->funding_source ?: '-' }}</td>

                            <td data-label="JPL">
                                {{ $training->jpl !== null ? $training->jpl : '-' }}
                            </td>

                            <td class="dt" data-label="Tanggal Mulai">
                                {{ $training->start_date ? $training->start_date->format('d-m-Y') : '-' }}
                            </td>

                            <td class="dt" data-label="Tanggal Selesai">
                                {{ $training->end_date ? $training->end_date->format('d-m-Y') : '-' }}
                            </td>

                            <td data-label="Aksi">
                                <div class="actions">
                                    <a
                                        class="btn btn-secondary btn-sm"
                                        href="{{ route('trainings.edit', $training) }}"
                                        aria-label="Edit {{ $training->name }}"
                                    >
                                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.4 3.6a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('trainings.destroy', $training) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus pelatihan ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-danger btn-sm"
                                            type="submit"
                                            aria-label="Hapus {{ $training->name }}"
                                        >
                                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="empty">
                                <div class="ic">
                                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4m8-4v4M3 10h18"/><path d="M12 14v4m-2-2h4"/></svg>
                                </div>

                                @if ($search !== '' || $year !== '')
                                    <strong>Data tidak ditemukan</strong>
                                    Tidak ada data pelatihan yang sesuai dengan pencarian atau filter yang dipilih.<br>
                                    Coba ubah kata kunci pencarian atau pilih tahun lainnya.
                                @else
                                    <strong>Belum ada data pelatihan</strong>
                                    Data pelatihan belum tersedia dalam sistem.<br>
                                    Silakan lakukan Impor Excel terlebih dahulu.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($trainings->hasPages())
            <div class="pager">
                <p>Menampilkan navigasi data pelatihan</p>
                {{ $trainings->links() }}
            </div>
        @endif
    </section>

</div>

<script>
    (function () {
        var el = document.getElementById('tp-counter');
        if (!el) { return; }
        var target = parseInt(el.getAttribute('data-target'), 10) || 0;
        if (target < 1 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }
        var start = null, dur = 900;
        el.textContent = '0';
        requestAnimationFrame(function step(t) {
            if (start === null) { start = t; }
            var p = Math.min((t - start) / dur, 1);
            el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
            if (p < 1) { requestAnimationFrame(step); }
        });
    })();
</script>

@endsection