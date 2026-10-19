
@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Kelola Akun Pengguna</h1>
            <p class="text-muted mb-0">
                Kelola akun Admin dan Pimpinan sistem SIRAPIK.
            </p>
        </div>

        <a href="{{ route('super-admin.users.create') }}"
           class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>
            Tambah Akun
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('super-admin.users.index') }}" method="GET">
                <div class="row g-3 align-items-end">

                    <div class="col-md-5">
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

                    <div class="col-md-3">
                        <label for="role" class="form-label">Filter Role</label>
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

                    <div class="col-md-2">
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

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a href="{{ route('super-admin.users.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Daftar Akun</h2>
                <span class="text-muted small">
                    Total: {{ $users->total() }} akun
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3" style="width: 60px;">No.</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 210px;">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-3">
                                    {{ $users->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $user->name }}
                                    </div>
                                </td>

                                <td>{{ $user->username }}</td>

                                <td>{{ $user->email }}</td>

                                <td>
                                    @if ($user->role === 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @elseif ($user->role === 'pimpinan')
                                        <span class="badge bg-success">Pimpinan</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($user->is_active)
                                        <span class="badge bg-success">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-inline-flex flex-wrap justify-content-center gap-1">

                                        <a
                                            href="{{ route('super-admin.users.edit', $user->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Edit akun"
                                            aria-label="Edit akun"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

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
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Nonaktifkan akun"
                                                    aria-label="Nonaktifkan akun"
                                                >
                                                    <i class="bi bi-person-slash"></i>
                                                </button>
                                            @else
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-success"
                                                    title="Aktifkan akun"
                                                    aria-label="Aktifkan akun"
                                                >
                                                    <i class="bi bi-person-check"></i>
                                                </button>
                                            @endif
                                        </form>

                                        <form
                                            action="{{ route('super-admin.users.destroy', $user->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Hapus akun"
                                                aria-label="Hapus akun"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-people fs-2 text-muted"></i>
                                    <p class="fw-semibold mt-2 mb-1">
                                        Belum ada akun ditemukan
                                    </p>
                                    <p class="text-muted small mb-0">
                                        Coba ubah kata kunci atau filter pencarian.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
            <div class="card-footer bg-white">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection