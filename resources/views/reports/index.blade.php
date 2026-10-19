
@extends('layouts.app')

@section('title', 'Laporan Pelatihan')

@section('content')
<div class="page-header">
    <h1>Laporan Riwayat Pelatihan</h1>
    <p>Pantau frekuensi keikutsertaan peserta berdasarkan tahun.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Keikutsertaan {{ $year }}</div>
        <div class="stat-value">{{ $totalParticipations }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Peserta Perlu Ditinjau</div>
        <div class="stat-value">{{ $participantsToReview }}</div>
        <div class="stat-label">Ambang: {{ $minimum }} pelatihan per tahun</div>
    </div>
</div>

<div class="content-card">
    <h2>Filter Laporan</h2>

    <form method="GET" action="{{ route('reports.index') }}"
          style="display:flex;gap:12px;flex-wrap:wrap;align-items:end">
        <div class="form-group" style="flex:1;min-width:120px">
            <label for="year">Tahun</label>
            <input id="year" type="number" name="year"
                   min="2000" max="2100" value="{{ $year }}">
        </div>

        <div class="form-group" style="flex:1;min-width:120px">
            <label for="minimum">Ambang peninjauan</label>
            <input id="minimum" type="number" name="minimum"
                   min="1" max="100" value="{{ $minimum }}">
        </div>

        <div class="form-group" style="flex:2;min-width:200px">
            <label for="search">Nama atau NIP</label>
            <input id="search" type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari peserta...">
        </div>

        <button class="btn" type="submit">Terapkan Filter</button>
    </form>

    <form method="GET" action="{{ route('reports.export') }}"
          style="margin-top:10px">
        <input type="hidden" name="year" value="{{ $year }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <button type="submit" class="btn">Ekspor Excel</button>
    </form>
</div>

<div class="content-card">
    <h2>Frekuensi Pelatihan per Peserta</h2>
    <p>
        Status peninjauan merupakan indikator awal untuk membantu staf
        mengevaluasi pemerataan kesempatan pelatihan. Angka ini bukan
        bukti bahwa peserta memiliki beban kerja berlebih.
    </p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Peserta</th>
                    <th>NIP</th>
                    <th>Jumlah Keikutsertaan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($participants as $participant)
                    <tr>
                        <td>{{ $participants->firstItem() + $loop->index }}</td>
                        <td>{{ $participant->name }}</td>
                        <td>{{ $participant->nip }}</td>
                        <td>{{ $participant->participations_count }}</td>
                        <td>
                            @if ($participant->participations_count >= $minimum)
                                <span style="color:#92400e;font-weight:600">
                                    Perlu ditinjau
                                </span>
                            @else
                                <span style="color:#166534">
                                    Dalam ambang
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:25px">
                            Tidak ada data peserta untuk filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px">
        {{ $participants->links() }}
    </div>
</div>
@endsection