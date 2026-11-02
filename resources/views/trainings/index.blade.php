@extends('layouts.app')

@section('title', 'Data Pelatihan')

@section('content')
<div class="page-header">
    <h1>Data Pelatihan</h1>
    <p>Data pelatihan yang berasal dari hasil Impor Excel.</p>
</div>

<div class="content-card">

    {{-- Notifikasi berhasil --}}
    @if (session('success'))
        <div class="alert alert-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi gagal --}}
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Pesan validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Terjadi kesalahan.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Pencarian dan filter tahun --}}
    <form
        method="GET"
        action="{{ route('trainings.index') }}"
        style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px"
    >
        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Cari nama, kategori, kerja sama, angkatan, atau sumber dana..."
            aria-label="Cari data pelatihan"
            style="flex:1;min-width:220px"
        >

        <select
            name="year"
            aria-label="Filter tahun pelatihan"
            style="min-width:150px"
        >
            <option value="">Semua Tahun</option>

            @foreach ($years as $availableYear)
                <option
                    value="{{ $availableYear }}"
                    @selected(
                        (string) ($year ?? '') === (string) $availableYear
                    )
                >
                    {{ $availableYear }}
                </option>
            @endforeach
        </select>

        <button class="btn" type="submit">
            Terapkan
        </button>

        <a
            href="{{ route('trainings.index') }}"
            class="btn btn-secondary"
        >
            Reset
        </a>
    </form>

    {{-- Informasi filter aktif --}}
    @if (request()->filled('search') || request()->filled('year'))
        <p style="margin-bottom:16px;color:#64748b;font-size:13px">
            Menampilkan data

            @if (request()->filled('search'))
                dengan pencarian:
                <strong>{{ request('search') }}</strong>
            @endif

            @if (request()->filled('year'))
                {{ request()->filled('search') ? 'dan' : 'untuk' }}
                tahun:
                <strong>{{ request('year') }}</strong>
            @endif
        </p>
    @endif

    {{-- Tabel Data Pelatihan --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelatihan</th>
                    <th>Kategori</th>
                    <th>Kerja Sama</th>
                    <th>Angkatan</th>
                    <th>Sumber Dana</th>
                    <th>JPL</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($trainings as $training)
                    <tr>
                        {{-- Nomor urut --}}
                        <td>
                            {{ $trainings->firstItem() + $loop->index }}
                        </td>

                        {{-- Nama pelatihan --}}
                        <td>
                            {{ $training->name ?: '-' }}
                        </td>

                        {{-- Kategori dari Master Data --}}
                        <td>
                            {{ $training->category?->name ?? '-' }}
                        </td>

                        {{-- Kerja sama / penyelenggara --}}
                        <td>
                            {{ $training->organizer ?: '-' }}
                        </td>

                        {{-- Angkatan --}}
                        <td>
                            {{ filled($training->batch_number)
                                ? $training->batch_number
                                : '-' }}
                        </td>

                        {{-- Sumber dana --}}
                        <td>
                            {{ $training->funding_source ?: '-' }}
                        </td>

                        {{-- Jumlah JPL --}}
                        <td>
                            {{ $training->jpl !== null
                                ? $training->jpl
                                : '-' }}
                        </td>

                        {{-- Tanggal mulai --}}
                        <td>
                            {{ $training->start_date
                                ? $training->start_date->format('d-m-Y')
                                : '-' }}
                        </td>

                        {{-- Tanggal selesai --}}
                        <td>
                            {{ $training->end_date
                                ? $training->end_date->format('d-m-Y')
                                : '-' }}
                        </td>

                        {{-- Aksi --}}
                        <td style="white-space:nowrap">
                            <a
                                class="btn btn-secondary"
                                href="{{ route('trainings.edit', $training) }}"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('trainings.destroy', $training) }}"
                                style="display:inline"
                                onsubmit="return confirm('Yakin ingin menghapus pelatihan ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn"
                                    type="submit"
                                    style="background:#b91c1c"
                                >
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center;padding:28px">
                            @if (request()->filled('search') || request()->filled('year'))
                                Data pelatihan tidak ditemukan.
                                Coba ubah pencarian atau pilih tahun lainnya.
                            @else
                                Belum ada data pelatihan.
                                Silakan lakukan Impor Excel terlebih dahulu.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top:20px">
        {{ $trainings->links() }}
    </div>

</div>
@endsection