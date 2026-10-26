@extends('layouts.app')

@section('title', 'Data Peserta')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">DATA MASTER</div>
        <h1>Data Peserta</h1>
        <p>Data peserta yang berasal dari hasil Impor Excel.</p>
    </div>
</div>

@if (session('success'))
    <div class="panel" role="status">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="panel" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="panel">
    <form method="GET"
          action="{{ route('participants.index') }}"
          class="search-form">

        <input
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Cari nama, NIK, NIP, instansi, atau kabupaten/kota..."
        >

        <button class="btn btn-primary" type="submit">Cari</button>

        <a class="btn btn-light" href="{{ route('participants.index') }}">
            Reset
        </a>
    </form>

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
                    <th>KABUPATEN/KOTA/MITRA</th>
                    <th>ANGKATAN</th>
                    <th>SUMBER DANA</th>
                    <th>AKSI</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($participants as $participant)
                    <tr>
                        <td>{{ $participant->name ?: '-' }}</td>
                        <td>{{ $participant->nik ?: '-' }}</td>
                        <td>{{ $participant->nip ?: '-' }}</td>

                        <td>
                            @if ($participant->gender === 'L')
                                Laki-laki
                            @elseif ($participant->gender === 'P')
                                Perempuan
                            @else
                                {{ $participant->gender ?: '-' }}
                            @endif
                        </td>

                        <td>{{ $participant->rank ?: '-' }}</td>
                        <td>{{ $participant->profession ?: '-' }}</td>
                        <td>{{ $participant->email ?: '-' }}</td>
                        <td>{{ $participant->phone ?: '-' }}</td>
                        <td>{{ $participant->institution ?: '-' }}</td>
                        <td>{{ $participant->district ?: '-' }}</td>

                        <td>
                            @forelse ($participant->histories as $history)
                                @if ($history->training)
                                    <div>
                                        {{ $history->training->batch_number ?? '-' }}
                                    </div>
                                @endif
                            @empty
                                -
                            @endforelse
                        </td>

                        <td>
                            @forelse ($participant->histories as $history)
                                @if ($history->training)
                                    <div>
                                        {{ $history->training->funding_source ?? '-' }}
                                    </div>
                                @endif
                            @empty
                                -
                            @endforelse
                        </td>

                        <td>
                            <div style="display:flex; gap:8px; align-items:center; white-space:nowrap;">
                                <a
                                    class="btn btn-light"
                                    href="{{ route('participants.show', $participant) }}"
                                    style="text-decoration:none;"
                                >
                                    Lihat
                                </a>

                                <a
                                    class="btn btn-light"
                                    href="{{ route('participants.edit', $participant) }}"
                                    style="text-decoration:none;"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('participants.destroy', $participant) }}"
                                    method="POST"
                                    style="margin:0;"
                                    onsubmit="return confirm('Yakin ingin menghapus peserta {{ addslashes($participant->name) }}? Data riwayat pelatihannya dan akun yang terhubung juga akan dihapus.');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-light"
                                        style="color:#dc2626;"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="empty-state">
                            @if ($search !== '')
                                Data peserta tidak ditemukan.
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

    <div class="pagination-wrap">
        {{ $participants->links() }}
    </div>
</div>
@endsection