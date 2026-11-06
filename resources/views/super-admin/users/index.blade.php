@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* =====================================================
       PREMIUM BLUE EDITORIAL — KELOLA AKUN
       Semua selector di-scope ke .users-page
       ===================================================== */
    .users-page {
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
        --warn: #8A5A12;
        --warn-bg: #FFF4E3;
        --warn-line: #EBCF9B;
        --danger: #A3202F;
        --danger-bg: #FFF1F2;
        --danger-line: #F2BCC3;
        --font-display: "Sora", "Segoe UI", Arial, sans-serif;
        --font-body: "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
        --ease: cubic-bezier(.2, .7, .2, 1);

        width: 100%; padding: 0 0 40px; color: var(--text);
        font-family: var(--font-body); font-size: 15px; line-height: 1.65;
    }
    .users-page *, .users-page *::before, .users-page *::after { box-sizing: border-box; }
    .users-page button, .users-page input, .users-page select { font: inherit; }
    .users-page svg.i {
        width: 1em; height: 1em; flex: none;
        fill: none; stroke: currentColor;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }
    .users-page :focus-visible { outline: 3px solid var(--cyan); outline-offset: 3px; border-radius: 6px; }
    .users-page .page-shell { max-width: 1600px; margin: 0 auto; }

    /* ---------- Hero ---------- */
    .users-page .page-header {
        position: relative; isolation: isolate; overflow: hidden;
        display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, .7fr);
        align-items: end; gap: clamp(22px, 4vw, 56px);
        margin-bottom: 26px; padding: clamp(28px, 4.5vw, 54px); border-radius: 26px; color: #fff;
        background: linear-gradient(120deg, var(--navy) 0%, var(--midnight) 60%, #173d86 100%);
        animation: up-rise .8s var(--ease) both;
    }
    .users-page .page-header::before {
        content: ""; position: absolute; z-index: -1; right: -90px; top: -110px;
        width: 360px; height: 360px; border-radius: 50%;
        background: var(--royal); opacity: .45; filter: blur(60px);
        animation: up-drift 24s ease-in-out infinite alternate;
    }
    .users-page .page-title {
        margin: 0 0 14px; font-family: var(--font-display); font-weight: 800;
        font-size: clamp(2.2rem, 5.2vw, 4rem); line-height: 1.06; letter-spacing: -.035em;
    }
    .users-page .page-title span { display: block; color: var(--cyan); }
    .users-page .page-description { max-width: 560px; margin: 0; font-size: 15px; line-height: 1.8; color: rgba(255,255,255,.82); }
    .users-page .hero-side { display: flex; flex-direction: column; align-items: flex-start; gap: 18px; justify-self: end; }
    .users-page .hero-total small { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: rgba(255,255,255,.78); }
    .users-page .hero-total small .i { font-size: 17px; color: var(--cyan); }
    .users-page .hero-total strong {
        display: block; font-family: var(--font-display); font-weight: 800; line-height: 1;
        font-size: clamp(2.6rem, 5vw, 3.8rem); letter-spacing: -.04em; font-variant-numeric: tabular-nums;
    }

    /* ---------- Buttons ---------- */
    .users-page .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        min-height: 50px; padding: 10px 20px; border: 1.5px solid transparent; border-radius: 12px;
        font-family: var(--font-display); font-size: 14px; font-weight: 600; text-decoration: none; white-space: nowrap; cursor: pointer;
        transition: transform .2s var(--ease), box-shadow .2s, background .2s, color .2s, border-color .2s;
    }
    .users-page .btn .i { font-size: 17px; }
    .users-page .btn:active { transform: scale(.97); }
    .users-page .btn-primary { color: #fff; background: linear-gradient(110deg, var(--royal), var(--electric)); border-color: transparent; box-shadow: 0 12px 26px rgba(36,87,214,.35); }
    .users-page .btn-primary:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 16px 32px rgba(36,87,214,.45); }
    .users-page .btn-ghost { color: var(--slate); background: transparent; }
    .users-page .btn-ghost:hover { color: var(--navy); background: var(--ice); }
    .users-page .add-user-btn { min-height: 54px; padding: 12px 24px; font-size: 15px; }

    /* ---------- Alerts (tetap kompatibel dengan Bootstrap dismiss) ---------- */
    .users-page .alert {
        position: relative; display: flex; align-items: flex-start; gap: 12px;
        margin-bottom: 18px; padding: 15px 48px 15px 18px; border: 1px solid transparent; border-radius: 16px;
        font-size: 14px; line-height: 1.7;
    }
    .users-page .alert > .i { margin-top: 3px; font-size: 19px; }
    .users-page .alert ul { margin: 6px 0 0; padding-left: 20px; }
    .users-page .alert-success { color: var(--ok); background: var(--ok-bg); border-color: var(--ok-line); }
    .users-page .alert-danger { color: var(--danger); background: var(--danger-bg); border-color: var(--danger-line); }
    .users-page .alert .btn-close {
        position: absolute; top: 12px; right: 12px; width: 28px; height: 28px; padding: 0;
        border: 0; border-radius: 8px; background: transparent; cursor: pointer; opacity: .7;
        font-size: 0; line-height: 1;
    }
    .users-page .alert .btn-close::before { content: "×"; font-size: 22px; color: currentColor; }
    .users-page .alert .btn-close:hover { opacity: 1; background: rgba(0,0,0,.06); }

    /* ---------- Filter ---------- */
    .users-page .filter-panel {
        margin-bottom: 20px; padding: clamp(18px, 2.6vw, 26px);
        border: 1px solid var(--border); border-radius: 22px; background: #fff;
        box-shadow: 0 14px 36px rgba(8,27,51,.05);
    }
    .users-page .section-heading { margin: 0 0 4px; font-family: var(--font-display); font-size: 18px; font-weight: 700; letter-spacing: -.02em; color: var(--navy); }
    .users-page .section-description { margin: 0 0 18px; font-size: 13.5px; color: var(--slate); }
    .users-page .filter-grid { display: grid; grid-template-columns: 1.7fr .9fr .9fr auto; gap: 14px; align-items: end; }
    .users-page .form-label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: var(--navy); }
    .users-page .form-control, .users-page .form-select {
        display: block; width: 100%; min-width: 0; height: 50px; padding: 0 14px;
        color: var(--text); background-color: var(--ice);
        border: 1.5px solid var(--border); border-radius: 12px; font-size: 14px;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .users-page .form-control::placeholder { color: #8796aa; }
    .users-page .form-control:hover, .users-page .form-select:hover { border-color: #c5d3e4; }
    .users-page .form-control:focus, .users-page .form-select:focus {
        outline: none; background-color: #fff; border-color: var(--electric); box-shadow: 0 0 0 4px rgba(52,120,246,.16);
    }
    .users-page .filter-actions { display: flex; gap: 8px; }

    /* ---------- Tabel ---------- */
    .users-page .users-card {
        overflow: hidden; border: 1px solid var(--border); border-radius: 22px;
        background: #fff; box-shadow: 0 18px 44px rgba(8,27,51,.07);
    }
    .users-page .card-top {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        padding: 20px clamp(18px, 2.6vw, 26px); border-bottom: 1px solid var(--border);
    }
    .users-page .card-top .section-description { margin: 0; }
    .users-page .total-badge {
        display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px;
        border-radius: 999px; background: var(--soft); border: 1px solid #D3E2FB;
        color: var(--royal); font-size: 13px; font-weight: 700; white-space: nowrap;
    }
    .users-page .total-badge .i { font-size: 16px; }
    .users-page .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: thin; }
    .users-page .users-table { width: 100%; min-width: 960px; margin: 0; border-collapse: collapse; text-align: left; }
    .users-page .users-table thead th {
        padding: 15px 16px; background: var(--navy); color: #fff;
        font-size: 12px; font-weight: 700; letter-spacing: .06em; white-space: nowrap; text-align: left;
    }
    .users-page .users-table thead th.center { text-align: center; }
    .users-page .users-table tbody td { padding: 16px; border-bottom: 1px solid #EBF0F6; font-size: 14px; color: #33465C; vertical-align: middle; }
    .users-page .users-table tbody tr { transition: background .2s; }
    .users-page .users-table tbody tr:hover { background: #F5F9FF; }
    .users-page .users-table tbody tr:last-child td { border-bottom: 0; }
    .users-page .user-number { font-weight: 700; color: #7C8DA3; font-variant-numeric: tabular-nums; }
    .users-page .user-name { font-weight: 700; color: var(--navy); }
    .users-page .user-username { color: #4B5E75; font-weight: 600; white-space: nowrap; }
    .users-page .user-email { color: var(--slate); overflow-wrap: anywhere; }
    .users-page td.center { text-align: center; }

    .users-page .account-badge {
        display: inline-flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 999px;
        background: var(--ice); color: var(--slate); font-size: 12.5px; font-weight: 700; white-space: nowrap;
    }
    .users-page .account-badge .i { font-size: 14px; }
    .users-page .role-admin { color: var(--royal); background: var(--soft); }
    .users-page .role-pimpinan { color: #6A4FB3; background: #F1ECFB; }
    .users-page .status-active { color: var(--ok); background: var(--ok-bg); }
    .users-page .status-inactive { color: #5A6B7E; background: #ECF0F4; }
    .users-page .status-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }

    /* Aksi */
    .users-page .action-buttons { display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 7px; }
    .users-page .action-buttons form { display: inline-flex; margin: 0; }
    .users-page .action-btn {
        min-height: 38px; padding: 7px 13px; border-radius: 10px; font-family: var(--font-body);
        font-size: 13px; font-weight: 700; background: #fff;
    }
    .users-page .action-edit { color: var(--royal); border-color: #C5D6F5; }
    .users-page .action-edit:hover { color: #fff; background: var(--royal); border-color: var(--royal); transform: translateY(-2px); }
    .users-page .action-deactivate { color: var(--warn); border-color: var(--warn-line); }
    .users-page .action-deactivate:hover { color: #fff; background: #A97808; border-color: #A97808; transform: translateY(-2px); }
    .users-page .action-activate { color: var(--ok); border-color: var(--ok-line); }
    .users-page .action-activate:hover { color: #fff; background: var(--ok); border-color: var(--ok); transform: translateY(-2px); }
    .users-page .action-delete { color: var(--danger); border-color: var(--danger-line); }
    .users-page .action-delete:hover { color: #fff; background: var(--danger); border-color: var(--danger); transform: translateY(-2px); }

    /* Kosong */
    .users-page .empty-state { padding: 56px 20px !important; text-align: center; }
    .users-page .empty-state-icon {
        display: grid; place-items: center; width: 60px; height: 60px; margin: 0 auto 14px;
        border-radius: 18px; background: var(--soft); color: var(--royal); font-size: 28px;
    }
    .users-page .empty-state-title { margin: 0 0 4px; font-family: var(--font-display); font-size: 17px; font-weight: 600; color: var(--navy); }
    .users-page .empty-state-description { margin: 0; font-size: 14px; color: var(--slate); }

    /* Pagination (Bootstrap maupun Tailwind) */
    .users-page .card-footer { padding: 16px clamp(18px, 2.6vw, 26px); border-top: 1px solid var(--border); overflow-x: auto; }
    .users-page .pagination { display: flex; flex-wrap: wrap; gap: 5px; margin: 0; padding: 0; list-style: none; }
    .users-page .pagination .page-link, .users-page .card-footer nav a, .users-page .card-footer nav span > span {
        display: inline-flex; align-items: center; justify-content: center; min-width: 38px; min-height: 38px; padding: 6px 10px;
        color: var(--royal); background: var(--ice); border: 1px solid var(--border); border-radius: 10px;
        font-size: 13px; text-decoration: none; transition: .2s;
    }
    .users-page .pagination .page-link:hover, .users-page .card-footer nav a:hover { color: #fff; background: var(--royal); border-color: var(--royal); }
    .users-page .pagination .active .page-link { color: #fff; background: var(--royal); border-color: var(--royal); }
    .users-page .card-footer svg { width: 17px; height: 17px; }

    @keyframes up-rise { from { opacity: 0; transform: translate3d(0, 18px, 0); } to { opacity: 1; transform: none; } }
    @keyframes up-drift { to { transform: translate3d(-40px, 40px, 0); } }

    /* ---------- Responsive ---------- */
    @media (max-width: 1100px) {
        .users-page .page-header { grid-template-columns: 1fr; align-items: start; }
        .users-page .hero-side { justify-self: start; }
        .users-page .filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .users-page .filter-search, .users-page .filter-actions { grid-column: 1 / -1; }
    }
    @media (max-width: 760px) {
        .users-page .add-user-btn, .users-page .filter-actions .btn { width: 100%; }
        .users-page .filter-grid { grid-template-columns: 1fr; }
        .users-page .filter-actions { flex-direction: column; }
        .users-page .total-badge { width: 100%; justify-content: center; }

        /* tabel menjadi kartu per baris */
        .users-page .table-responsive { overflow: visible; }
        .users-page .users-table { display: block; min-width: 0; }
        .users-page .users-table thead { display: none; }
        .users-page .users-table tbody { display: grid; gap: 12px; padding: 12px; }
        .users-page .users-table tbody tr { display: block; overflow: hidden; border: 1px solid var(--border); border-radius: 14px; }
        .users-page .users-table tbody td {
            display: grid; grid-template-columns: 100px minmax(0, 1fr); gap: 10px; align-items: center;
            padding: 11px 14px; font-size: 14px; border-bottom: 1px solid #EDF1F5; text-align: left;
        }
        .users-page .users-table tbody td[data-label]::before { content: attr(data-label); font-size: 13px; font-weight: 600; color: var(--slate); }
        .users-page .users-table tbody tr td:last-child { display: block; border-bottom: 0; }
        .users-page .users-table td.center { text-align: left; }
        .users-page .users-table td.empty-state { display: block; }
        .users-page .action-buttons { justify-content: flex-start; }
        .users-page .action-buttons .action-btn { flex: 1 1 auto; }
    }
    @media (prefers-reduced-motion: reduce) {
        .users-page *, .users-page *::before, .users-page *::after { animation: none !important; transition-duration: .01ms !important; scroll-behavior: auto !important; }
    }
</style>

<div class="container-fluid users-page">
    <div class="page-shell">

        {{-- HEADER --}}
        <header class="page-header">
            <div>
                <h1 class="page-title">Kelola akun <span>pengguna.</span></h1>

                <p class="page-description">
                    Pusat pengelolaan akun Admin dan Pimpinan. Atur informasi,
                    hak akses, serta status akun pengguna dalam satu tempat.
                </p>
            </div>

            <div class="hero-side">
                <div class="hero-total">
                    <small>
                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Total akun
                    </small>
                    <strong id="up-counter" data-target="{{ (int) $users->total() }}">{{ $users->total() }}</strong>
                </div>

                <a href="{{ route('super-admin.users.create') }}" class="btn btn-primary add-user-btn">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M19 8v6"/><path d="M22 11h-6"/></svg>
                    Tambah Akun
                </a>
            </div>
        </header>

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="status">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                <div>{{ session('success') }}</div>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                <div>{{ session('error') }}</div>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- PENCARIAN DAN FILTER --}}
        <section class="filter-panel" aria-labelledby="filter-title">
            <h2 class="section-heading" id="filter-title">Pencarian dan filter</h2>

            <p class="section-description">
                Temukan akun berdasarkan nama, username, email, role, atau status keaktifan.
            </p>

            <form action="{{ route('super-admin.users.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="filter-search">
                        <label for="search" class="form-label">Cari Pengguna</label>

                        <input
                            type="search"
                            name="search"
                            id="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Masukkan nama, username, atau email"
                        >
                    </div>

                    <div>
                        <label for="role" class="form-label">Role Pengguna</label>

                        <select name="role" id="role" class="form-select">
                            <option value="">Semua Role</option>

                            <option value="admin" @selected(request('role') === 'admin')>
                                Admin
                            </option>

                            <option value="pimpinan" @selected(request('role') === 'pimpinan')>
                                Pimpinan
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="form-label">Status Akun</label>

                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>

                            <option value="active" @selected(request('status') === 'active')>
                                Aktif
                            </option>

                            <option value="inactive" @selected(request('status') === 'inactive')>
                                Nonaktif
                            </option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            Cari
                        </button>

                        <a href="{{ route('super-admin.users.index') }}" class="btn btn-ghost">
                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </section>

        {{-- DAFTAR AKUN --}}
        <section class="users-card" aria-labelledby="list-title">

            <div class="card-top">
                <div>
                    <h2 class="section-heading" id="list-title">Daftar akun pengguna</h2>

                    <p class="section-description">
                        Informasi seluruh akun yang terdaftar dalam sistem SIRAPIK.
                    </p>
                </div>

                <div class="total-badge">
                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Total {{ $users->total() }} akun
                </div>
            </div>

            <div class="table-responsive">
                <table class="users-table">
                    <caption style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">
                        Daftar akun pengguna SIRAPIK
                    </caption>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 70px;">No.</th>
                            <th scope="col">Nama Pengguna</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col" class="center">Status</th>
                            <th scope="col" class="center" style="min-width: 300px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td data-label="No.">
                                    <span class="user-number">
                                        {{ $users->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td data-label="Nama">
                                    <div class="user-name">{{ $user->name }}</div>
                                </td>

                                <td data-label="Username">
                                    <span class="user-username">{{ $user->username }}</span>
                                </td>

                                <td data-label="Email">
                                    <span class="user-email">{{ $user->email }}</span>
                                </td>

                                <td data-label="Role">
                                    @if ($user->role === 'admin')
                                        <span class="account-badge role-admin">
                                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 4 7v5c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-4z"/></svg>
                                            Admin
                                        </span>
                                    @elseif ($user->role === 'pimpinan')
                                        <span class="account-badge role-pimpinan">
                                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg>
                                            Pimpinan
                                        </span>
                                    @else
                                        <span class="account-badge">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="center" data-label="Status">
                                    @if ($user->is_active)
                                        <span class="account-badge status-active">
                                            <span class="status-dot"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="account-badge status-inactive">
                                            <span class="status-dot"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('super-admin.users.edit', $user->id) }}"
                                            class="btn action-btn action-edit"
                                            title="Edit akun {{ $user->name }}"
                                            aria-label="Edit akun {{ $user->name }}"
                                        >
                                            <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.4 3.6a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                            Edit
                                        </a>

                                        {{-- STATUS --}}
                                        <form
                                            action="{{ route('super-admin.users.status', $user->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ addslashes($user->name) }}?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            @if ($user->is_active)
                                                <button
                                                    type="submit"
                                                    class="btn action-btn action-deactivate"
                                                    title="Nonaktifkan akun"
                                                    aria-label="Nonaktifkan akun {{ $user->name }}"
                                                >
                                                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                                                    Nonaktifkan
                                                </button>
                                            @else
                                                <button
                                                    type="submit"
                                                    class="btn action-btn action-activate"
                                                    title="Aktifkan akun"
                                                    aria-label="Aktifkan akun {{ $user->name }}"
                                                >
                                                    <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                                    Aktifkan
                                                </button>
                                            @endif
                                        </form>

                                        {{-- HAPUS --}}
                                        <form
                                            action="{{ route('super-admin.users.destroy', $user->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn action-btn action-delete"
                                                title="Hapus akun"
                                                aria-label="Hapus akun {{ $user->name }}"
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
                                <td colspan="7" class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    </div>

                                    <h3 class="empty-state-title">Belum ada akun ditemukan</h3>

                                    <p class="empty-state-description">
                                        Tidak ada akun yang sesuai dengan pencarian atau filter
                                        yang dipilih. Coba ubah kata kunci pencarian.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if ($users->hasPages())
                <div class="card-footer">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif

        </section>
    </div>
</div>

<script>
    (function () {
        var el = document.getElementById('up-counter');
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