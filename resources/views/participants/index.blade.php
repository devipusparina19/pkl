@extends('layouts.app')

@section('title', 'Data Peserta')

@section('content')

<style>
    .participant-page {
        color: #172b4d;
        font-size: 14px;
    }

    .participant-page *,
    .participant-page *::before,
    .participant-page *::after {
        box-sizing: border-box;
    }

    /* JUDUL HALAMAN */
    .participant-page .page-heading {
        margin-bottom: 24px;
    }

    .participant-page .eyebrow {
        margin-bottom: 8px;
        color: #2457a7;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.4px;
    }

    .participant-page .page-heading h1 {
        margin: 0;
        color: #17365f;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.3;
    }

    .participant-page .page-heading p {
        margin: 9px 0 0;
        color: #718096;
        font-size: 14px;
        line-height: 1.7;
    }

    /* NOTIFIKASI */
    .participant-page .notice {
        margin-bottom: 18px;
        padding: 15px 18px;
        border-radius: 10px;
        font-size: 14px;
        line-height: 1.7;
    }

    .participant-page .notice-success {
        color: #166534;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }

    .participant-page .notice-error {
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
    }

    /* PANEL */
    .participant-page .panel {
        min-width: 0;
        padding: 21px;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 13px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, .035);
    }

    /* FILTER TAHUN */
    .participant-page .year-filter {
        margin-bottom: 18px;
        padding: 20px;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(23, 43, 77, .025);
    }

    .participant-page .filter-title {
        display: block;
        margin-bottom: 12px;
        color: #233d60;
        font-size: 15px;
        font-weight: 700;
    }

    .participant-page .year-filter-form {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 12px;
    }

    .participant-page .year-select-wrap {
        flex: 0 1 280px;
        min-width: 190px;
    }

    .participant-page .year-select {
        display: block;
        width: 100%;
        height: 46px;
        padding: 0 13px;
        color: #17365f;
        background: #fff;
        border: 1px solid #cbd8e8;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        outline: none;
    }

    .participant-page .year-select:focus,
    .participant-page .search-input:focus {
        border-color: #3978bd;
        box-shadow: 0 0 0 3px rgba(36, 87, 167, .1);
    }

    /* TOMBOL */
    .participant-page .action-button {
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

    .participant-page .btn-primary {
        color: #fff;
        background: #2865a8;
        border-color: #2865a8;
    }

    .participant-page .btn-primary:hover {
        color: #fff;
        background: #1d4f89;
        border-color: #1d4f89;
    }

    .participant-page .btn-light {
        color: #36516f;
        background: #edf2f8;
        border-color: #e1e8f1;
    }

    .participant-page .btn-light:hover {
        background: #e1eaf5;
    }

    /* PENCARIAN */
    .participant-page .search-panel {
        margin-bottom: 18px;
        padding: 0;
    }

    .participant-page .search-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 18px;
    }

    .participant-page .search-input {
        width: 100%;
        min-height: 46px;
        padding: 11px 14px;
        color: #17365f;
        background: #fff;
        border: 1px solid #cbd8e8;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        outline: none;
    }

    .participant-page .search-input::placeholder {
        color: #8494aa;
    }

    .participant-page .search-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* INFORMASI FILTER */
    .participant-page .filter-information {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-bottom: 16px;
    }

    .participant-page .filter-chip {
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
    .participant-page .table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e2eaf2;
        border-radius: 9px;
    }

    .participant-page table {
        width: 100%;
        min-width: 1350px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
    }

    .participant-page thead th {
        padding: 15px 13px;
        color: #425b79;
        background: #f3f7fc;
        border-bottom: 1px solid #dce5ef;
        font-size: 12px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
    }

    .participant-page tbody td {
        padding: 16px 13px;
        color: #334b68;
        border-bottom: 1px solid #e6edf5;
        font-size: 13px;
        line-height: 1.7;
        vertical-align: middle;
    }

    .participant-page tbody tr:last-child td {
        border-bottom: none;
    }

    .participant-page tbody tr:hover {
        background: #f8fbff;
    }

    .participant-page tbody td:first-child {
        color: #1c3659;
        font-weight: 600;
    }

    /* AKSI */
    .participant-page .action-group {
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
    }

    .participant-page .action-group .action-button {
        min-height: 35px;
        padding: 7px 12px;
        font-size: 12px;
    }

    .participant-page .delete-button {
        color: #dc2626;
        background: #fff1f2;
        border-color: #ffe0e4;
    }

    .participant-page .delete-button:hover {
        color: #b91c1c;
        background: #ffe4e6;
    }

    /* DATA KOSONG */
    .participant-page .empty-state {
        padding: 40px 18px;
        color: #8291a5;
        font-size: 14px;
        text-align: center;
    }

    /* PAGINATION */
    .participant-page .pagination-wrap {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        overflow-x: auto;
    }

    .participant-page .pagination-wrap nav {
        display: flex;
        justify-content: flex-end;
    }

    .participant-page .pagination-wrap svg {
        width: 18px;
        height: 18px;
    }

    .participant-page .pagination-wrap p {
        margin: 0;
        color: #718096;
        font-size: 13px;
    }

    .participant-page .pagination-wrap a,
    .participant-page .pagination-wrap span {
        font-size: 13px;
    }

    .participant-page :focus-visible {
        outline: 3px solid rgba(36, 87, 167, .3);
        outline-offset: 2px;
    }

    /* RESPONSIVE TABLET DAN HP */
    @media (max-width: 768px) {
        .participant-page .panel {
            padding: 15px;
        }

        .participant-page .page-heading h1 {
            font-size: 25px;
        }

        .participant-page .page-heading p {
            font-size: 13px;
        }

        .participant-page .year-filter {
            padding: 16px;
        }

        .participant-page .year-filter-form {
            align-items: stretch;
        }

        .participant-page .year-select-wrap {
            flex-basis: 100%;
        }

        .participant-page .year-filter-form .action-button {
            flex: 1;
        }

        .participant-page .search-panel {
            padding: 0;
        }

        .participant-page .search-actions .action-button {
            flex: 1;
        }

        .participant-page .pagination-wrap {
            justify-content: flex-start;
        }

        .participant-page table {
            min-width: 1350px;
        }
    }

    @media (max-width: 380px) {
        .participant-page .year-filter-form .action-button {
            padding: 9px 10px;
            font-size: 12px;
        }

        .participant-page .page-heading h1 {
            font-size: 23px;
        }
    }
</style>

@php
    $search = $search ?? '';
    $years = $years ?? collect();
    $selectedYear = $selectedYear ?? null;
    $yearFilter = $yearFilter ?? 'all';
@endphp

<div class="participant-page">

    {{-- JUDUL HALAMAN --}}
    <div class="page-heading">
        <div>
            <div class="eyebrow">DATA MASTER</div>
            <h1>Data Peserta</h1>
            <p>
                Data peserta yang berasal dari hasil Impor Excel.
            </p>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="notice notice-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="notice notice-error" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER TAHUN --}}
    <div class="year-filter">

        <label for="year" class="filter-title">
            Filter Tahun Pelatihan
        </label>

        <form
            method="GET"
            action="{{ route('participants.index') }}"
            class="year-filter-form"
        >

            @if ($search !== '')
                <input
                    type="hidden"
                    name="search"
                    value="{{ $search }}"
                >
            @endif

            <div class="year-select-wrap">
                <select
                    name="year"
                    id="year"
                    class="year-select"
                    aria-label="Pilih tahun pelatihan"
                >
                    <option value="all"
                        {{ $yearFilter === 'all' ? 'selected' : '' }}>
                        Semua Tahun
                    </option>

                    @foreach ($years as $availableYear)
                        <option
                            value="{{ $availableYear }}"
                            {{ (string) $yearFilter === (string) $availableYear ? 'selected' : '' }}
                        >
                            {{ $availableYear }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="action-button btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16l-6.5 8v6l-3 2v-8L4 4Z"/>
                </svg>
                Terapkan
            </button>

            <a
                href="{{ route('participants.index', ['year' => 'all', 'search' => $search ?: null]) }}"
                class="action-button btn-light"
            >
                <svg width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                    <path d="M3 3v5h5"/>
                </svg>
                Reset
            </a>

        </form>
    </div>

    {{-- PANEL DATA PESERTA --}}
    <div class="panel">

        {{-- INFORMASI FILTER AKTIF --}}
        <div class="filter-information">

            @if ($selectedYear)
                <span class="filter-chip">
                    Tahun Pelatihan: {{ $selectedYear }}
                </span>
            @else
                <span class="filter-chip">
                    Menampilkan Semua Tahun
                </span>
            @endif

        </div>

        {{-- PENCARIAN --}}
        <form
            method="GET"
            action="{{ route('participants.index') }}"
            class="search-form"
        >

            <input
                type="hidden"
                name="year"
                value="{{ $yearFilter }}"
            >

            <input
                type="search"
                name="search"
                value="{{ $search }}"
                class="search-input"
                placeholder="Cari nama, NIK, NIP, instansi, atau kabupaten/kota..."
                aria-label="Cari peserta"
            >

            <div class="search-actions">

                <button
                    class="action-button btn-primary"
                    type="submit"
                >
                    Cari
                </button>

                <a
                    class="action-button btn-light"
                    href="{{ route('participants.index', ['year' => $yearFilter]) }}"
                >
                    Reset
                </a>

            </div>
        </form>

        {{-- TABEL DATA PESERTA --}}
        <div class="table-wrap">

            <table>
                <thead>
                    <tr>
                        <th>NAMA LENGKAP</th>
                        <th>NIK</th>
                        <th>NIP</th>
                        <th>JENIS KELAMIN</th>
                        <th>PANGKAT/GOLONGAN</th>
                        <th>PROFESI NAKES</th>
                        <th>EMAIL</th>
                        <th>NOMOR TELEPON</th>
                        <th>INSTANSI/UNIT KERJA</th>
                        <th>KABUPATEN/KOTA/MITRA/MANDIRI</th>
                        <th>AKSI</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($participants as $participant)

                        <tr>
                            <td>
                                {{ $participant->name ?: '-' }}
                            </td>

                            <td>
                                {{ $participant->nik ?: '-' }}
                            </td>

                            <td>
                                {{ $participant->nip ?: '-' }}
                            </td>

                            <td>
                                @if ($participant->gender === 'L')
                                    Laki-laki
                                @elseif ($participant->gender === 'P')
                                    Perempuan
                                @else
                                    {{ $participant->gender ?: '-' }}
                                @endif
                            </td>

                            <td>
                                {{ $participant->rank ?: '-' }}
                            </td>

                            <td>
                                {{ $participant->profession ?: '-' }}
                            </td>

                            <td>
                                {{ $participant->email ?: '-' }}
                            </td>

                            <td>
                                {{ $participant->phone ?: '-' }}
                            </td>

                            <td>
                                {{ $participant->institution ?: '-' }}
                            </td>

                            <td>
                                {{ $participant->district ?: '-' }}
                            </td>

                            {{-- TOMBOL AKSI --}}
                            <td>
                                <div class="action-group">

                                    <a
                                        class="action-button btn-light"
                                        href="{{ route('participants.show', $participant) }}"
                                    >
                                        Lihat
                                    </a>

                                    <a
                                        class="action-button btn-light"
                                        href="{{ route('participants.edit', $participant) }}"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('participants.destroy', $participant) }}"
                                        method="POST"
                                        style="margin: 0;"
                                        onsubmit="return confirm('Yakin ingin menghapus peserta {{ addslashes($participant->name) }}? Data riwayat pelatihannya dan akun yang terhubung juga akan dihapus.');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-button delete-button"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="11" class="empty-state">

                                @if ($search !== '')
                                    Data peserta tidak ditemukan
                                    @if ($selectedYear)
                                        pada tahun {{ $selectedYear }}.
                                    @else
                                        untuk pencarian tersebut.
                                    @endif
                                @elseif ($selectedYear)
                                    Belum ada peserta yang mengikuti
                                    pelatihan pada tahun {{ $selectedYear }}.
                                @else
                                    Belum ada data peserta.
                                    Silakan lakukan Impor Excel terlebih dahulu.
                                @endif

                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        {{-- PAGINATION --}}
        @if ($participants->hasPages())
            <div class="pagination-wrap">
                {{ $participants->links() }}
            </div>
        @endif

    </div>

</div>

@endsection