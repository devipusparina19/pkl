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

        <a href="{{ route('super-admin.users.create') }}" class="btn btn-primary">
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
            <div class="stat-note">Pengguna untuk pemantauan.</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total Akun yang Dikelola</div>
            <div class="stat-number">
                {{ number_format($totalPengguna, 0, ',', '.') }}
            </div>
            <div class="stat-note">Akun Admin dan Pimpinan.</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="content-card">
            <h2>Manajemen Pengguna</h2>
            <p>
                Kelola akun Admin dan Pimpinan tanpa mengubah
                data peserta maupun data pelatihan.
            </p>

            <div class="inline-actions">
                <a href="{{ route('super-admin.users.index') }}"
                   class="btn btn-primary">
                    Kelola Akun
                </a>

                <a href="{{ route('super-admin.users.create') }}"
                   class="btn btn-light">
                    Tambah Akun
                </a>
            </div>
        </section>

        <section class="content-card">
            <h2>Pengaturan Sistem</h2>
            <p>
                Pengaturan tahun, provinsi, kabupaten/kota/mitra,
                sumber dana, dan jenis tenaga kesehatan akan
                dikelola melalui menu master data.
            </p>

            <span class="count-pill">Tahap berikutnya</span>
        </section>
    </div>
@endsection