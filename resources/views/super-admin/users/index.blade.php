
@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')
<style>
    /* =====================================================
       SIRAPIK — USER MANAGEMENT
       Scoped styles: tidak mengubah halaman lainnya.
    ===================================================== */
    .users-page {
        --navy: #102d50;
        --blue: #2164ad;
        --blue-dark: #174d89;
        --blue-soft: #eaf2fc;
        --ink: #203a56;
        --muted: #71839a;
        --line: #e3eaf2;
        --surface: #ffffff;
        --background: #f4f7fb;
        --green: #16764b;
        --red: #bd3544;
        --amber: #986400;

        color: var(--ink);
        font-family: inherit;
        font-size: 16px;
        padding: 28px 0 48px;
    }

    .users-page *,
    .users-page *::before,
    .users-page *::after {
        box-sizing: border-box;
    }

    .users-page .page-shell {
        max-width: 1600px;
        margin: 0 auto;
    }

    /* HEADER */
    .users-page .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 22px;
        margin-bottom: 30px;
    }

    .users-page .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        color: var(--blue);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 1.7px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .users-page .eyebrow::before {
        content: "";
        width: 28px;
        height: 3px;
        border-radius: 5px;
        background: var(--blue);
    }

    .users-page .page-title {
        color: var(--navy);
        font-size: clamp(30px, 3vw, 42px);
        font-weight: 800;
        letter-spacing: -1.2px;
        line-height: 1.15;
        margin: 0 0 10px;
    }

    .users-page .page-description {
        color: var(--muted);
        font-size: 16px;
        line-height: 1.7;
        margin: 0;
        max-width: 650px;
    }

    /* BUTTONS */
    .users-page .btn {
        min-height: 44px;
        border-radius: 9px;
        padding: 10px 17px;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: transform .2s ease,
                    background-color .2s ease,
                    border-color .2s ease,
                    box-shadow .2s ease;
    }

    .users-page .btn:hover {
        transform: translateY(-2px);
    }

    .users-page .btn:focus-visible,
    .users-page input:focus-visible,
    .users-page select:focus-visible {
        outline: 3px solid rgba(33, 100, 173, .25);
        outline-offset: 3px;
        box-shadow: none;
    }

    .users-page .btn-primary {
        color: #fff;
        background: var(--blue);
        border-color: var(--blue);
        box-shadow: 0 5px 14px rgba(33, 100, 173, .16);
    }

    .users-page .btn-primary:hover {
        color: #fff;
        background: var(--blue-dark);
        border-color: var(--blue-dark);
    }

    .users-page .add-user-btn {
        min-height: 50px;
        padding: 12px 21px;
        border-radius: 11px;
        font-size: 15px;
    }

    /* ALERTS */
    .users-page .alert {
        border: 0;
        border-radius: 12px;
        padding: 17px 20px;
        margin-bottom: 20px;
        font-size: 15px;
        box-shadow: 0 3px 12px rgba(20, 47, 79, .035);
    }

    /* CARDS */
    .users-page .users-card {
        background: var(--surface);
        border: 1px solid var(--line) !important;
        border-radius: 16px;
        box-shadow: 0 7px 25px rgba(22, 49, 79, .045) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .users-page .card-body {
        padding: 25px;
    }

    .users-page .card-header {
        padding: 22px 25px;
        background: #fff;
        border-bottom: 1px solid var(--line);
    }

    .users-page .card-footer {
        padding: 19px 25px;
        background: #fff;
        border-top: 1px solid var(--line);
    }

    .users-page .section-heading {
        color: var(--navy);
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -.35px;
        margin: 0;
    }

    .users-page .section-description {
        color: var(--muted);
        font-size: 14px;
        line-height: 1.6;
        margin: 7px 0 0;
    }

    /* SEARCH AND FILTER */
    .users-page .form-label {
        display: block;
        color: var(--ink);
        font-size: 14px;
        font-weight: 750;
        margin-bottom: 9px;
    }

    .users-page .form-control,
    .users-page .form-select {
        width: 100%;
        min-height: 48px;
        padding: 11px 14px;
        color: var(--ink);
        background-color: #fff;
        border: 1px solid #d6e0eb;
        border-radius: 9px;
        font-size: 15px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .users-page .form-control::placeholder {
        color: #98a7b8;
    }

    .users-page .form-control:focus,
    .users-page .form-select:focus {
        border-color: #6c9ed4;
        box-shadow: 0 0 0 4px rgba(33, 100, 173, .09);
    }

    .users-page .filter-actions {
        display: flex;
        gap: 9px;
    }

    .users-page .filter-actions .btn {
        flex: 1;
        min-width: 0;
    }

    .users-page .filter-reset {
        color: #52677f;
        background: #f5f7fa;
        border: 1px solid #dce4ed;
        text-decoration: none;
    }

    .users-page .filter-reset:hover {
        color: var(--navy);
        background: #eaf0f7;
    }

    /* TABLE */
    .users-page .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .users-page .users-table {
        width: 100%;
        min-width: 950px;
        margin: 0;
        color: var(--ink);
        font-size: 14px;
    }

    .users-page .users-table thead th {
        padding: 17px 15px;
        color: #526982;
        background: #f3f7fc;
        border-bottom: 1px solid #e0e8f1;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .7px;
        text-transform: uppercase;
        white-space: nowrap;
        vertical-align: middle;
    }

    .users-page .users-table tbody td {
        padding: 18px 15px;
        border-bottom: 1px solid #edf1f6;
        vertical-align: middle;
        line-height: 1.55;
    }

    .users-page .users-table tbody tr {
        transition: background-color .18s ease;
    }

    .users-page .users-table tbody tr:hover {
        background: #f7faff;
    }

    .users-page .users-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .users-page .user-number {
        color: #8999ab;
        font-size: 13px;
        font-weight: 700;
    }

    .users-page .user-name {
        color: var(--navy);
        font-size: 15px;
        font-weight: 800;
    }

    .users-page .user-username {
        color: #526b86;
        font-weight: 650;
        white-space: nowrap;
    }

    .users-page .user-email {
        color: #60748a;
        overflow-wrap: anywhere;
    }

    /* ROLE AND STATUS */
    .users-page .account-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 750;
        white-space: nowrap;
    }

    .users-page .role-admin {
        color: #205a9b;
        background: #eaf2fc;
    }

    .users-page .role-pimpinan {
        color: #176b4b;
        background: #e8f6ef;
    }

    .users-page .status-active {
        color: #16764b;
        background: #e8f6ef;
    }

    .users-page .status-inactive {
        color: #687789;
        background: #edf1f5;
    }

    .users-page .status-dot {
        width: 8px;
        height: 8px;
        display: inline-block;
        border-radius: 50%;
        background: currentColor;
    }

    /* ACTIONS */
    .users-page .action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 7px;
    }

    .users-page .action-buttons form {
        display: inline-flex;
        margin: 0;
    }

    .users-page .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 37px;
        padding: 8px 11px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .users-page .action-edit {
        color: #245fa8;
        background: #fff;
        border-color: #b5cce5;
    }

    .users-page .action-edit:hover {
        color: #fff;
        background: var(--blue);
        border-color: var(--blue);
    }

    .users-page .action-deactivate {
        color: var(--amber);
        background: #fff;
        border-color: #e7d19b;
    }

    .users-page .action-deactivate:hover {
        color: #fff;
        background: #a97808;
        border-color: #a97808;
    }

    .users-page .action-activate {
        color: var(--green);
        background: #fff;
        border-color: #a8d6bd;
    }

    .users-page .action-activate:hover {
        color: #fff;
        background: var(--green);
        border-color: var(--green);
    }

    .users-page .action-delete {
        color: var(--red);
        background: #fff;
        border-color: #e6b6bc;
    }

    .users-page .action-delete:hover {
        color: #fff;
        background: var(--red);
        border-color: var(--red);
    }

    /* EMPTY STATE */
    .users-page .empty-state {
        padding: 65px 20px !important;
        text-align: center;
    }

    .users-page .empty-state-icon {
        display: inline-flex;
        width: 72px;
        height: 72px;
        align-items: center;
        justify-content: center;
        color: var(--blue);
        background: var(--blue-soft);
        border-radius: 22px;
        font-size: 30px;
        margin-bottom: 18px;
    }

    .users-page .empty-state-title {
        color: var(--navy);
        font-size: 19px;
        font-weight: 800;
        margin: 0 0 8px;
    }

    .users-page .empty-state-description {
        color: var(--muted);
        font-size: 15px;
        margin: 0;
    }

    /* TOTAL */
    .users-page .total-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        color: #24588f;
        background: #edf4fc;
        border: 1px solid #dce9f8;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 750;
        white-space: nowrap;
    }

    /* PAGINATION */
    .users-page .pagination {
        gap: 5px;
        flex-wrap: wrap;
        margin: 0;
    }

    .users-page .pagination .page-link {
        min-width: 38px;
        border-radius: 8px;
        color: var(--blue);
        border-color: #e0e8f1;
        font-size: 14px;
        text-align: center;
    }

    .users-page .pagination .active .page-link {
        color: #fff;
        background: var(--blue);
        border-color: var(--blue);
    }

    /* RESPONSIVE */
    @media (max-width: 991.98px) {
        .users-page {
            padding-top: 20px;
        }

        .users-page .card-body {
            padding: 21px;
        }

        .users-page .page-header {
            align-items: flex-start;
        }
    }

    @media (max-width: 575.98px) {
        .users-page {
            padding-top: 12px;
            padding-bottom: 28px;
        }

        .users-page .page-header {
            gap: 17px;
            margin-bottom: 22px;
        }

        .users-page .page-title {
            font-size: 29px;
        }

        .users-page .page-description {
            font-size: 14px;
        }

        .users-page .add-user-btn {
            width: 100%;
        }

        .users-page .card-body {
            padding: 17px;
        }

        .users-page .card-header,
        .users-page .card-footer {
            padding: 17px;
        }

        .users-page .section-heading {
            font-size: 19px;
        }

        .users-page .filter-actions {
            width: 100%;
        }

        .users-page .filter-actions .btn {
            padding-left: 10px;
            padding-right: 10px;
        }

        .users-page .total-badge {
            width: 100%;
            justify-content: center;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .users-page *,
        .users-page *::before,
        .users-page *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
        }
    }
</style>

<div class="container-fluid users-page">
    <div class="page-shell">

        {{-- HEADER --}}
        <header class="page-header">
            <div>
                <div class="eyebrow">SIRAPIK / ADMINISTRASI</div>

                <h1 class="page-title">Kelola Akun Pengguna</h1>

                <p class="page-description">
                    Pusat pengelolaan akun Admin dan Pimpinan.
                    Atur informasi, hak akses, serta status akun pengguna
                    dalam satu tempat.
                </p>
            </div>

            <a href="{{ route('super-admin.users.create') }}"
               class="btn btn-primary add-user-btn">
                <i class="bi bi-person-plus"></i>
                Tambah Akun
            </a>
        </header>

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show"
                 role="status">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    Terjadi kesalahan:
                </strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- PENCARIAN DAN FILTER --}}
        <section class="card users-card">
            <div class="card-body">
                <div class="mb-4">
                    <h2 class="section-heading">
                        Pencarian dan Filter
                    </h2>

                    <p class="section-description">
                        Temukan akun berdasarkan nama, username, email,
                        role, atau status keaktifan.
                    </p>
                </div>

                <form action="{{ route('super-admin.users.index') }}"
                      method="GET">

                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-5">
                            <label for="search" class="form-label">
                                Cari Pengguna
                            </label>

                            <input
                                type="search"
                                name="search"
                                id="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Masukkan nama, username, atau email..."
                            >
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="role" class="form-label">
                                Role Pengguna
                            </label>

                            <select name="role"
                                    id="role"
                                    class="form-select">
                                <option value="">Semua Role</option>

                                <option value="admin"
                                    @selected(request('role') === 'admin')>
                                    Admin
                                </option>

                                <option value="pimpinan"
                                    @selected(request('role') === 'pimpinan')>
                                    Pimpinan
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-2">
                            <label for="status" class="form-label">
                                Status Akun
                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-select">
                                <option value="">Semua Status</option>

                                <option value="active"
                                    @selected(request('status') === 'active')>
                                    Aktif
                                </option>

                                <option value="inactive"
                                    @selected(request('status') === 'inactive')>
                                    Nonaktif
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-lg-2">
                            <div class="filter-actions">
                                <button type="submit"
                                        class="btn btn-primary">
                                    <i class="bi bi-search"></i>
                                    Cari
                                </button>

                                <a href="{{ route('super-admin.users.index') }}"
                                   class="btn filter-reset">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- DAFTAR AKUN --}}
        <section class="card users-card">

            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between
                            align-items-center gap-3">

                    <div>
                        <h2 class="section-heading">
                            Daftar Akun Pengguna
                        </h2>

                        <p class="section-description">
                            Informasi seluruh akun yang terdaftar
                            dalam sistem SIRAPIK.
                        </p>
                    </div>

                    <div class="total-badge">
                        <i class="bi bi-people-fill"></i>
                        Total {{ $users->total() }} akun
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle users-table">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 65px;">
                                    No.
                                </th>
                                <th>Nama Pengguna</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center">Status</th>
                                <th class="text-center"
                                    style="min-width: 300px;">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <span class="user-number">
                                            {{ $users->firstItem() + $loop->index }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="user-name">
                                            {{ $user->name }}
                                        </div>
                                    </td>

                                    <td>
                                        <span class="user-username">
                                            {{ $user->username }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="user-email">
                                            {{ $user->email }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($user->role === 'admin')
                                            <span class="account-badge role-admin">
                                                <i class="bi bi-shield-lock-fill"></i>
                                                Admin
                                            </span>
                                        @elseif ($user->role === 'pimpinan')
                                            <span class="account-badge role-pimpinan">
                                                <i class="bi bi-person-badge-fill"></i>
                                                Pimpinan
                                            </span>
                                        @else
                                            <span class="account-badge">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
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
                                            >
                                                <i class="bi bi-pencil-square"></i>
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
                                                    >
                                                        <i class="bi bi-person-slash"></i>
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button
                                                        type="submit"
                                                        class="btn action-btn action-activate"
                                                        title="Aktifkan akun"
                                                    >
                                                        <i class="bi bi-person-check"></i>
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
                                                >
                                                    <i class="bi bi-trash3"></i>
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
                                            <i class="bi bi-people"></i>
                                        </div>

                                        <h3 class="empty-state-title">
                                            Belum Ada Akun Ditemukan
                                        </h3>

                                        <p class="empty-state-description">
                                            Tidak ada akun yang sesuai dengan
                                            pencarian atau filter yang dipilih.
                                            Coba ubah kata kunci pencarian.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
@endsection
