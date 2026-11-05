@extends('layouts.app')

@section('title', 'Data Pelatihan')

@section('content')

<style>
    .training-page {
        color: #172b4d;
        font-size: 14px;
    }

    .training-page *,
    .training-page *::before,
    .training-page *::after {
        box-sizing: border-box;
    }

    /* JUDUL HALAMAN */
    .training-page .page-heading {
        margin-bottom: 24px;
    }

    .training-page .eyebrow {
        margin-bottom: 8px;
        color: #2457a7;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.4px;
    }

    .training-page .page-heading h1 {
        margin: 0;
        color: #17365f;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.3;
    }

    .training-page .page-heading p {
        margin: 9px 0 0;
        color: #718096;
        font-size: 14px;
        line-height: 1.7;
    }

    /* NOTIFIKASI */
    .training-page .notice {
        margin-bottom: 18px;
        padding: 15px 18px;
        border-radius: 10px;
        font-size: 14px;
        line-height: 1.7;
    }

    .training-page .notice-success {
        color: #166534;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }

    .training-page .notice-error {
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
    }

    .training-page .notice ul {
        margin: 8px 0 0;
        padding-left: 22px;
    }

    /* PANEL */
    .training-page .panel {
        min-width: 0;
        padding: 21px;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 13px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, .035);
    }

    /* FILTER */
    .training-page .filter-section {
        margin-bottom: 18px;
        padding: 20px;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(23, 43, 77, .025);
    }

    .training-page .filter-title {
        display: block;
        margin-bottom: 12px;
        color: #233d60;
        font-size: 15px;
        font-weight: 700;
    }

    .training-page .filter-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .training-page .filter-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .training-page .search-wrap {
        flex: 1 1 300px;
        min-width: 220px;
    }

    .training-page .year-select-wrap {
        flex: 0 1 230px;
        min-width: 180px;
    }

    .training-page .search-input,
    .training-page .year-select {
        display: block;
        width: 100%;
        height: 46px;
        padding: 0 14px;
        color: #17365f;
        background: #fff;
        border: 1px solid #cbd8e8;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        outline: none;
    }

    .training-page .search-input::placeholder {
        color: #8494aa;
    }

    .training-page .search-input:focus,
    .training-page .year-select:focus {
        border-color: #3978bd;
        box-shadow: 0 0 0 3px rgba(36, 87, 167, .1);
    }

    /* TOMBOL */
    .training-page .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 19px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
        white-space: nowrap;
    }

    .training-page .btn-primary {
        color: #fff;
        background: #2865a8;
        border-color: #2865a8;
    }

    .training-page .btn-primary:hover {
        color: #fff;
        background: #1d4f89;
        border-color: #1d4f89;
    }

    .training-page .btn-light {
        color: #36516f;
        background: #edf2f8;
        border-color: #e1e8f1;
    }

    .training-page .btn-light:hover {
        background: #e1eaf5;
    }

    /* INFORMASI FILTER */
    .training-page .filter-information {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-bottom: 17px;
    }

    .training-page .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 12px;
        color: #2457a7;
        background: #edf4fd;
        border: 1px solid #dce9fa;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
    }

    /* TABEL */
    .training-page .table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e2eaf2;
        border-radius: 9px;
    }

    .training-page table {
        width: 100%;
        min-width: 1250px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
    }

    .training-page thead th {
        padding: 15px 13px;
        color: #425b79;
        background: #f3f7fc;
        border-bottom: 1px solid #dce5ef;
        font-size: 12px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
    }

    .training-page tbody td {
        padding: 16px 13px;
        color: #334b68;
        border-bottom: 1px solid #e6edf5;
        font-size: 13px;
        line-height: 1.7;
        vertical-align: middle;
    }

    .training-page tbody tr:last-child td {
        border-bottom: none;
    }

    .training-page tbody tr:hover {
        background: #f8fbff;
    }

    .training-page tbody td:nth-child(2) {
        color: #1c3659;
        font-weight: 600;
        min-width: 210px;
    }

    /* AKSI */
    .training-page .action-group {
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
    }

    .training-page .action-group .action-button {
        min-height: 35px;
        padding: 7px 12px;
        font-size: 12px;
    }

    .training-page .delete-button {
        color: #dc2626;
        background: #fff1f2;
        border-color: #ffe0e4;
    }

    .training-page .delete-button:hover {
        color: #b91c1c;
        background: #ffe4e6;
    }

    /* DATA KOSONG */
    .training-page .empty-state {
        padding: 40px 18px;
        color: #8291a5;
        font-size: 14px;
        line-height: 1.8;
        text-align: center;
    }

    /* PAGINATION */
    .training-page .pagination-wrap {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        overflow-x: auto;
    }

    .training-page .pagination-wrap nav {
        display: flex;
        justify-content: flex-end;
    }

    .training-page .pagination-wrap svg {
        width: 18px;
        height: 18px;
    }

    .training-page .pagination-wrap p {
        margin: 0;
        color: #718096;
        font-size: 13px;
    }

    .training-page .pagination-wrap a,
    .training-page .pagination-wrap span {
        font-size: 13px;
    }

    .training-page :focus-visible {
        outline: 3px solid rgba(36, 87, 167, .3);
        outline-offset: 2px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .training-page .panel {
            padding: 15px;
        }

        .training-page .page-heading h1 {
            font-size: 25px;
        }

        .training-page .page-heading p {
            font-size: 13px;
        }

        .training-page .filter-section {
            padding: 16px;
        }

        .training-page .filter-row {
            align-items: stretch;
        }

        .training-page .search-wrap,
        .training-page .year-select-wrap {
            flex-basis: 100%;
        }

        .training-page .filter-row .action-button {
            flex: 1;
        }

        .training-page .pagination-wrap {
            justify-content: flex-start;
        }

        .training-page table {
            min-width: 1250px;
        }
    }

    @media (max-width: 380px) {
        .training-page .filter-row .action-button {
            padding: 9px 10px;
            font-size: 12px;
        }

        .training-page .page-heading h1 {
            font-size: 23px;
        }
    }
</style>

@php
    $search = $search ?? '';
    $years = $years ?? collect();
    $year = $year ?? '';
@endphp

<div class="training-page">

    {{-- JUDUL HALAMAN --}}
    <div class="page-heading">
        <div>
            <div class="eyebrow">DATA MASTER</div>
            <h1>Data Pelatihan</h1>
            <p>
                Data pelatihan yang berasal dari hasil Impor Excel.
            </p>
        </div>
    </div>

    {{-- NOTIFIKASI BERHASIL --}}
    @if (session('success'))
        <div class="notice notice-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    {{-- NOTIFIKASI GAGAL --}}
    @if (session('error'))
        <div class="notice notice-error" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- PESAN VALIDASI --}}
    @if ($errors->any())
        <div class="notice notice-error" role="alert">
            <strong>Terjadi kesalahan.</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- PANEL DATA PELATIHAN --}}
    <div class="panel">

        {{-- INFORMASI FILTER AKTIF --}}
        <div class="filter-information">

            @if ($year)
                <span class="filter-chip">
                    Tahun Pelatihan: {{ $year }}
                </span>
            @else
                <span class="filter-chip">
                    Menampilkan Semua Tahun
                </span>
            @endif

            @if ($search !== '')
                <span class="filter-chip">
                    Pencarian: {{ $search }}
                </span>
            @endif

        </div>

        {{-- PENCARIAN DAN FILTER TAHUN --}}
        <div class="filter-section">

            <label for="year" class="filter-title">
                Pencarian dan Filter Data Pelatihan
            </label>

            <form
                method="GET"
                action="{{ route('trainings.index') }}"
                class="filter-form"
            >

                <div class="filter-row">

                    {{-- PENCARIAN --}}
                    <div class="search-wrap">
                        <input
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            class="search-input"
                            placeholder="Cari nama, kategori, kerja sama, angkatan, atau sumber dana..."
                            aria-label="Cari data pelatihan"
                        >
                    </div>

                    {{-- FILTER TAHUN --}}
                    <div class="year-select-wrap">
                        <select
                            name="year"
                            id="year"
                            class="year-select"
                            aria-label="Filter tahun pelatihan"
                        >
                            <option value="">
                                Semua Tahun
                            </option>

                            @foreach ($years as $availableYear)
                                <option
                                    value="{{ $availableYear }}"
                                    @selected(
                                        (string) $year === (string) $availableYear
                                    )
                                >
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- TOMBOL FILTER --}}
                <div class="filter-row">

                    <button
                        type="submit"
                        class="action-button btn-primary"
                    >
                        <svg width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M4 4h16l-6.5 8v6l-3 2v-8L4 4Z"/>
                        </svg>
                        Terapkan
                    </button>

                    <a
                        href="{{ route('trainings.index') }}"
                        class="action-button btn-light"
                    >
                        <svg width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>
                        Reset
                    </a>

                </div>

            </form>

        </div>

        {{-- TABEL DATA PELATIHAN --}}
        <div class="table-wrap">

            <table>
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA PELATIHAN</th>
                        <th>KATEGORI</th>
                        <th>KERJA SAMA</th>
                        <th>ANGKATAN</th>
                        <th>SUMBER DANA</th>
                        <th>JPL</th>
                        <th>TANGGAL MULAI</th>
                        <th>TANGGAL SELESAI</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($trainings as $training)

                        <tr>

                            {{-- NOMOR URUT --}}
                            <td>
                                {{ $trainings->firstItem() + $loop->index }}
                            </td>

                            {{-- NAMA PELATIHAN --}}
                            <td>
                                {{ $training->name ?: '-' }}
                            </td>

                            {{-- KATEGORI --}}
                            <td>
                                {{ $training->category?->name ?? '-' }}
                            </td>

                            {{-- KERJA SAMA --}}
                            <td>
                                {{ $training->organizer ?: '-' }}
                            </td>

                            {{-- ANGKATAN --}}
                            <td>
                                {{ filled($training->batch_number)
                                    ? $training->batch_number
                                    : '-' }}
                            </td>

                            {{-- SUMBER DANA --}}
                            <td>
                                {{ $training->funding_source ?: '-' }}
                            </td>

                            {{-- JPL --}}
                            <td>
                                {{ $training->jpl !== null
                                    ? $training->jpl
                                    : '-' }}
                            </td>

                            {{-- TANGGAL MULAI --}}
                            <td>
                                {{ $training->start_date
                                    ? $training->start_date->format('d-m-Y')
                                    : '-' }}
                            </td>

                            {{-- TANGGAL SELESAI --}}
                            <td>
                                {{ $training->end_date
                                    ? $training->end_date->format('d-m-Y')
                                    : '-' }}
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div class="action-group">

                                    <a
                                        class="action-button btn-light"
                                        href="{{ route('trainings.edit', $training) }}"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('trainings.destroy', $training) }}"
                                        style="margin: 0;"
                                        onsubmit="return confirm('Yakin ingin menghapus pelatihan ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="action-button delete-button"
                                            type="submit"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10" class="empty-state">

                                @if ($search !== '' || $year !== '')
                                    Data pelatihan tidak ditemukan.
                                    <br>
                                    Coba ubah pencarian atau pilih tahun lainnya.
                                @else
                                    Belum ada data pelatihan.
                                    <br>
                                    Silakan lakukan Impor Excel terlebih dahulu.
                                @endif

                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        {{-- PAGINATION --}}
        @if ($trainings->hasPages())
            <div class="pagination-wrap">
                {{ $trainings->links() }}
            </div>
        @endif

    </div>

</div>

@endsection