@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')
<style>
    /* =========================
       HALAMAN KELOLA AKUN
    ========================= */
    .users-page {
        color: #183153;
        font-size: 15px;
        padding-top: 28px;
        padding-bottom: 36px;
    }

    .users-page .page-title {
        color: #16365f;
        font-size: 28px;
        font-weight: 750;
        line-height: 1.3;
        margin-bottom: 8px;
    }

    .users-page .page-description {
        color: #61738b;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .users-page .section-heading {
        color: #17365d;
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 0;
    }

    .users-page .section-description {
        color: #687b91;
        font-size: 14px;
        margin-top: 6px;
        margin-bottom: 0;
    }

    /* =========================
       TOMBOL
    ========================= */
    .users-page .btn {
        min-height: 42px;
        border-radius: 7px;
        padding: 9px 16px;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        transition: background-color .2s ease,
                    border-color .2s ease,
                    box-shadow .2s ease;
    }

    .users-page .btn i {
        font-size: 15px;
        vertical-align: -1px;
    }

    .users-page .btn-primary {
        background-color: #245fa8;
        border-color: #245fa8;
    }

    .users-page .btn-primary:hover {
        background-color: #194b89;
        border-color: #194b89;
    }

    .users-page .btn:focus-visible,
    .users-page .form-control:focus-visible,
    .users-page .form-select:focus-visible {
        outline: 3px solid rgba(49, 113, 190, .25);
        outline-offset: 2px;
        box-shadow: none;
    }

    /* =========================
       ALERT
    ========================= */
    .users-page .alert {
        border-radius: 9px;
        padding: 16px 18px;
        font-size: 14px;
        margin-bottom: 22px;
    }

    /* =========================
       KARTU
    ========================= */
    .users-page .users-card {
        background: #fff;
        border: 1px solid #dce5ef !important;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(20, 47, 79, .045) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .users-page .users-card .card-body {
        padding: 24px;
    }

    .users-page .users-card .card-header {
        background: #fff;
        border-bottom: 1px solid #e1e8f0;
        padding: 20px 24px;
    }

    .users-page .users-card .card-footer {
        background: #fff;
        border-top: 1px solid #e1e8f0;
        padding: 16px 24px;
    }

    /* =========================
       FORM DAN FILTER
    ========================= */
    .users-page .form-label {
        display: block;
        color: #263e5b;
        font-size: 14px;
        font-weight: 650;
        margin-bottom: 8px;
    }

    .users-page .form-control,
    .users-page .form-select {
        min-height: 44px;
        border: 1px solid #cbd7e5;
        border-radius: 7px;
        color: #263e5b;
        background-color: #fff;
        font-size: 14px;
        padding: 10px 13px;
    }

    .users-page .form-control::placeholder {
        color: #8a99ab;
        opacity: 1;
    }

    .users-page .form-control:focus,
    .users-page .form-select:focus {
        border-color: #4c83c1;
        box-shadow: 0 0 0 3px rgba(49, 113, 190, .12);
    }

    .users-page .filter-actions {
        display: flex;
        gap: 8px;
    }

    .users-page .filter-actions .btn {
        flex: 1;
    }

    .users-page .filter-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    /* =========================
       TABEL AKUN
    ========================= */
    .users-page .table-responsive {
        width: 100%;
    }

    .users-page .users-table {
        width: 100%;
        margin-bottom: 0;
        color: #293f59;
        font-size: 14px;
    }

    .users-page .users-table thead th {
        background: #f1f5fa;
        color: #29425f;
        font-size: 13px;
        font-weight: 750;
        letter-spacing: .15px;
        padding: 16px 14px;
        border-bottom: 1px solid #d9e3ee;
        white-space: nowrap;
        vertical-align: middle;
    }

    .users-page .users-table tbody td {
        padding: 17px 14px;
        border-bottom: 1px solid #e7edf4;
        vertical-align: middle;
        line-height: 1.5;
    }

    .users-page .users-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .users-page .users-table tbody tr:hover td {
        background-color: #f8fafd;
    }

    .users-page .user-name {
        color: #1c3655;
        font-size: 14px;
        font-weight: 700;
    }

    .users-page .user-username {
        color: #536a83;
        white-space: nowrap;
    }

    .users-page .user-email {
        color: #536a83;
        overflow-wrap: anywhere;
    }

    /* =========================
       BADGE ROLE DAN STATUS
    ========================= */
    .users-page .account-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 650;
        white-space: nowrap;
    }

    .users-page .role-admin {
        background: #e8f0fc;
        color: #21579a;
    }

    .users-page .role-pimpinan {
        background: #e5f5ed;
        color: #18734b;
    }

    .users-page .status-active {
        background: #e5f5ed;
        color: #18734b;
    }

    .users-page .status-inactive {
        background: #edf0f4;
        color: #536273;
    }

    .users-page .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        display: inline-block;
    }

    /* =========================
       TOMBOL AKSI
    ========================= */
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
        min-height: 36px !important;
        padding: 7px 10px !important;
        border-radius: 6px !important;
        font-size: 12px !important;
        font-weight: 650 !important;
        white-space: nowrap;
    }

    .users-page .action-edit {
        color: #245fa8;
        background: #fff;
        border: 1px solid #8bb2df;
    }

    .users-page .action-edit:hover {
        color: #fff;
        background: #245fa8;
        border-color: #245fa8;
    }

    .users-page .action-deactivate {
        color: #946200;
        background: #fff;
        border: 1px solid #e3c16e;
    }

    .users-page .action-deactivate:hover {
        color: #fff;
        background: #a97808;
        border-color: #a97808;
    }

    .users-page .action-activate {
        color: #18734b;
        background: #fff;
        border: 1px solid #83c7a5;
    }

    .users-page .action-activate:hover {
        color: #fff;
        background: #18734b;
        border-color: #18734b;
    }

    .users-page .action-delete {
        color: #b42332;
        background: #fff;
        border: 1px solid #e3a0a7;
    }

    .users-page .action-delete:hover {
        color: #fff;
        background: #b42332;
        border-color: #b42332;
    }

    /* =========================
       DATA KOSONG
    ========================= */
    .users-page .empty-state {
        padding: 54px 20px !important;
        text-align: center;
    }

    .users-page .empty-state-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #edf3fa;
        color: #52749b;
        font-size: 28px;
        margin-bottom: 15px;
    }

    .users-page .empty-state-title {
        color: #263e5b;
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .users-page .empty-state-description {
        color: #708197;
        font-size: 14px;
        margin-bottom: 0;
    }

    /* =========================
       PAGINATION
    ========================= */
    .users-page .pagination {
        margin-bottom: 0;
        gap: 4px;
        flex-wrap: wrap;
    }

    .users-page .pagination .page-link {
        border-radius: 6px;
        color: #245fa8;
        font-size: 14px;
        padding: 8px 12px;
    }

    .users-page .pagination .active .page-link {
        background-color: #245fa8;
        border-color: #245fa8;
        color: #fff;
    }

    /* =========================
       RESPONSIVE
    ========================= */
    @media (max-width: 991.98px) {
        .users-page .page-title {
            font-size: 25px;
        }

        .users-page .users-card .card-body {
            padding: 20px;
        }

        .users-page .users-table {
            min-width: 900px;
        }
    }

    @media (max-width: 575.98px) {
        .users-page {
            padding-top: 18px;
        }

        .users-page .page-title {
            font-size: 23px;
        }

        .users-page .page-description {
            font-size: 14px;
        }

        .users-page .users-card .card-body {
            padding: 16px;
        }

        .users-page .users-card .card-header,
        .users-page .users-card .card-footer {
            padding: 16px;
        }

        .users-page .filter-actions {
            width: 100%;
        }

        .users-page .action-buttons {
            justify-content: flex-start;
        }
    }
</style>

<div class="container-fluid users-page">

    {{-- HEADER HALAMAN --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="page-title">Kelola Akun Pengguna</h1>
            <p class="page-description">
                Kelola akun Admin dan Pimpinan sistem SIRAPIK.
            </p>
        </div>

        <a href="{{ route('super-admin.users.create') }}"
           class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>
            Tambah Akun
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

    {{-- FILTER DAN PENCARIAN --}}
    <div class="card users-card">
        <div class="card-body">

            <div class="mb-4">
                <h2 class="section-heading">Pencarian dan Filter</h2>
                <p class="section-description">
                    Cari akun berdasarkan identitas, role, atau status akun.
                </p>
            </div>

            <form action="{{ route('super-admin.users.index') }}" method="GET">
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-lg-5">
                        <label for="search" class="form-label">
                            Cari Pengguna
                        </label>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Nama, username, atau email..."
                        >
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="role" class="form-label">
                            Filter Role
                        </label>

                        <select name="role" id="role" class="form-select">
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

                        <select name="status" id="status" class="form-select">
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
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Cari
                            </button>

                            <a href="{{ route('super-admin.users.index') }}"
                               class="btn btn-outline-secondary filter-reset">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- DAFTAR AKUN --}}
    <div class="card users-card">

        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h2 class="section-heading">Daftar Akun</h2>
                    <p class="section-description">
                        Informasi akun pengguna yang terdaftar dalam sistem.
                    </p>
                </div>

                <span class="badge rounded-pill text-bg-light border px-3 py-2"
                      style="font-size: 14px; color: #38516d !important;">
                    <i class="bi bi-people me-1"></i>
                    Total: {{ $users->total() }} akun
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle users-table">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 65px;">No.</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="min-width: 300px;">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="ps-4">
                                    {{ $users->firstItem() + $loop->index }}
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
                                            <i class="bi bi-shield-lock"></i>
                                            Admin
                                        </span>
                                    @elseif ($user->role === 'pimpinan')
                                        <span class="account-badge role-pimpinan">
                                            <i class="bi bi-person-badge"></i>
                                            Pimpinan
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

                                {{-- AKSI AKUN --}}
                                <td>
                                    <div class="action-buttons">

                                        {{-- EDIT AKUN --}}
                                        <a
                                            href="{{ route('super-admin.users.edit', $user->id) }}"
                                            class="btn action-btn action-edit"
                                            title="Edit akun"
                                            aria-label="Edit akun"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                            Edit Akun
                                        </a>

                                        {{-- AKTIFKAN / NONAKTIFKAN AKUN --}}
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
                                                    aria-label="Nonaktifkan akun"
                                                >
                                                    <i class="bi bi-person-slash"></i>
                                                    Nonaktifkan
                                                </button>
                                            @else
                                                <button
                                                    type="submit"
                                                    class="btn action-btn action-activate"
                                                    title="Aktifkan akun"
                                                    aria-label="Aktifkan akun"
                                                >
                                                    <i class="bi bi-person-check"></i>
                                                    Aktifkan
                                                </button>
                                            @endif
                                        </form>

                                        {{-- HAPUS AKUN --}}
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
                                                aria-label="Hapus akun"
                                            >
                                                <i class="bi bi-trash"></i>
                                                Hapus Akun
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

                                    <p class="empty-state-title">
                                        Belum Ada Akun Ditemukan
                                    </p>

                                    <p class="empty-state-description">
                                        Coba ubah kata kunci atau filter pencarian.
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

    </div>

</div>
@endsection