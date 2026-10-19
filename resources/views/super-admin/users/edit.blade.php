@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <a href="{{ route('super-admin.users.index') }}"
           class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Daftar Akun
        </a>

        <h1 class="h3 mt-3 mb-1">Edit Akun Pengguna</h1>
        <p class="text-muted mb-0">
            Perbarui informasi akun Admin atau Pimpinan.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data yang dimasukkan.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h2 class="h5 mb-0">Informasi Akun</h2>
        </div>

        <div class="card-body p-4">
            <form
                action="{{ route('super-admin.users.update', $user->id) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                            maxlength="255"
                            autocomplete="name"
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="username" class="form-label">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username) }}"
                            required
                            maxlength="255"
                            autocomplete="username"
                        >
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                            maxlength="255"
                            autocomplete="email"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">
                            Role Pengguna <span class="text-danger">*</span>
                        </label>
                        <select
                            name="role"
                            id="role"
                            class="form-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="admin"
                                @selected(old('role', $user->role) === 'admin')>
                                Admin
                            </option>
                            <option value="pimpinan"
                                @selected(old('role', $user->role) === 'pimpinan')>
                                Pimpinan
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            Role Super Admin tidak dapat ditetapkan melalui
                            formulir ini.
                        </div>
                    </div>

                </div>

                <hr class="my-4">

                <h3 class="h6 mb-3">Ganti Password</h3>
                <p class="text-muted small">
                    Kosongkan kedua kolom password jika tidak ingin mengubah
                    password akun ini.
                </p>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="password" class="form-label">
                            Password Baru
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            autocomplete="new-password"
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">
                            Konfirmasi Password Baru
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="form-control"
                            placeholder="Ulangi password baru"
                            minlength="8"
                            autocomplete="new-password"
                        >
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('super-admin.users.index') }}"
                       class="btn btn-outline-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection