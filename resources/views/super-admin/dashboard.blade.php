@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">PANEL PENGELOLAAN SISTEM</span>
            <h1>Dashboard Super Admin</h1>
            <p>
                Pantau akun pengguna dan konfigurasi sistem SIRAPIK.
            </p>
        </div>

        <a href="{{ route('super-admin.users.create') }}"
           class="btn btn-primary">
            + Tambah Akun
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Akun Admin</div>
            <div class="stat-number">
                {{ number_format($totalAdmin, 0, ',', '.') }}
            </div>
            <div class="stat-note">Pengelola data operasional.</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total Akun Pimpinan</div>
            <div class="stat-number">
                {{ number_format($totalPimpinan, 0, ',', '.') }}
            </div>
            <div class="stat-note">Akun untuk pemantauan.</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Akun Aktif</div>
            <div class="stat-number">
                {{ number_format($totalAktif, 0, ',', '.') }}
            </div>
            <div class="stat-note">Akun Admin dan Pimpinan yang aktif.</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Akun Nonaktif</div>
            <div class="stat-number">
                {{ number_format($totalNonaktif, 0, ',', '.') }}
            </div>
            <div class="stat-note">Akun Admin dan Pimpinan yang nonaktif.</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="content-card">
            <h2>Manajemen Pengguna</h2>
            <p>
                Kelola akun Admin dan Pimpinan. Super Admin tidak
                mengelola data peserta maupun data pelatihan.
            </p>
            <p>
                Gunakan menu Kelola Akun di sidebar untuk melihat
                dan mengelola akun yang terdaftar.
            </p>
        </section>

        <section class="content-card">
            <h2>Data Master</h2>
            <p>
                Kelola provinsi, kabupaten/kota/mitra, sumber dana,
                tahun pelatihan, dan jenis tenaga kesehatan.
            </p>

            <div class="inline-actions">
                <a href="{{ route('super-admin.master-data.index', [
                    'category' => 'provinces'
                ]) }}" class="btn btn-light">
                    Buka Data Master
                </a>
            </div>
        </section>
    </div>
@endsection