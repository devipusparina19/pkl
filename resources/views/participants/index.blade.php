
@extends('layouts.app')

@section('title', 'Data Peserta')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">DATA MASTER</div>
        <h1>Data Peserta</h1>
        <p>Kelola data peserta pelatihan.</p>
    </div>

    <a class="btn btn-primary" href="{{ route('participants.create') }}">
        + Tambah Peserta
    </a>
</div>

<div class="panel">
    <form method="GET"
          action="{{ route('participants.index') }}"
          class="search-form">

        <input
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Cari nama, NIK, NIP, atau instansi..."
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

                        <td>
                            {{ $participant->institution ?: '-' }}
                            @if ($participant->unit)
                                <div class="muted">
                                    {{ $participant->unit }}
                                </div>
                            @endif
                        </td>

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
                            <a
                                class="table-link"
                                href="{{ route('participants.show', $participant) }}"
                            >
                                Lihat →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="empty-state">
                            Belum ada data peserta.
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