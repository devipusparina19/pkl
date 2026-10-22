@extends('layouts.app')

@section('title', 'Data Pelatihan')

@section('content')
<div class="page-header">
    <h1>Data Pelatihan</h1>
    <p>
        Kelola daftar pelatihan dari input manual maupun hasil import Excel.
    </p>
</div>

<div class="content-card">

    @if (session('success'))
        <div class="alert alert-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Pencarian dan tombol aksi --}}
    <form method="GET"
          action="{{ route('trainings.index') }}"
          style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px">

        <input
            type="text"
            name="search"
            value="{{ $search ?? request('search') }}"
            placeholder="Cari nama atau penyelenggara..."
            aria-label="Cari data pelatihan"
            style="flex:1;min-width:220px"
        >

        <button class="btn" type="submit">
            Cari
        </button>

        <a href="{{ route('trainings.index') }}"
           class="btn btn-secondary">
            Reset
        </a>

        @if (\Illuminate\Support\Facades\Route::has('trainings.create'))
            <a href="{{ route('trainings.create') }}" class="btn">
                + Tambah Pelatihan
            </a>
        @endif
    </form>

    {{-- Informasi hasil pencarian --}}
    @if (request()->filled('search'))
        <p style="margin-bottom:16px;color:#64748b;font-size:13px">
            Hasil pencarian untuk:
            <strong>{{ request('search') }}</strong>
        </p>
    @endif

    {{-- Tabel data pelatihan --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelatihan</th>
                    <th>Penyelenggara</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($trainings as $training)
                    <tr>
                        <td>
                            {{ $trainings->firstItem() + $loop->index }}
                        </td>

                        <td>
                            {{ $training->name ?? '-' }}
                        </td>

                        <td>
                            {{ $training->organizer ?? '-' }}
                        </td>

                        <td>
                            {{ $training->start_date
                                ? \Illuminate\Support\Carbon::parse($training->start_date)->format('d-m-Y')
                                : '-' }}
                        </td>

                        <td>
                            {{ $training->end_date
                                ? \Illuminate\Support\Carbon::parse($training->end_date)->format('d-m-Y')
                                : '-' }}
                        </td>

                        <td style="white-space:nowrap">
                            @if (\Illuminate\Support\Facades\Route::has('trainings.edit'))
                                <a
                                    class="btn btn-secondary"
                                    href="{{ route('trainings.edit', $training) }}"
                                >
                                    Edit
                                </a>
                            @endif

                            @if (\Illuminate\Support\Facades\Route::has('trainings.destroy'))
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
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            style="text-align:center;padding:28px">

                            @if (request()->filled('search'))
                                Data pelatihan tidak ditemukan.
                                Coba gunakan kata kunci lain.
                            @else
                                Belum ada data pelatihan.
                                Silakan tambah data manual atau lakukan
                                import Excel terlebih dahulu.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination tetap membawa kata kunci pencarian --}}
    <div style="margin-top:20px">
        {{ $trainings->appends(request()->query())->links() }}
    </div>

</div>
@endsection