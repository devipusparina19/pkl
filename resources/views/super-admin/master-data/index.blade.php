@extends('layouts.app')

@section('content')
@php
    $columns = match ($category) {
        'provinces' => ['name' => 'Nama Provinsi'],
        'districts' => [
            'name' => 'Nama Wilayah',
            'type' => 'Jenis',
            'province' => 'Provinsi',
        ],
        'funding-sources' => ['name' => 'Nama Sumber Dana'],
        'training-years' => ['year' => 'Tahun'],
        'health-worker-types' => ['name' => 'Nama Jenis Tenaga Kesehatan'],
        default => [],
    };
@endphp

<div class="page-heading">
    <div>
        <div class="eyebrow">SUPER ADMIN</div>
        <h1>Master Data {{ $config['title'] }}</h1>
        <p>Kelola referensi sistem SIRAPIK.</p>
    </div>

    <a href="{{ route('super-admin.master-data.create', ['category' => $category]) }}"
       class="btn btn-primary">
        + Tambah Data
    </a>
</div>

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

<div class="content-card">
    <form method="GET"
          action="{{ route('super-admin.master-data.index', ['category' => $category]) }}"
          class="filter-grid">

        <div>
            <label for="search">Pencarian</label>
            <input
                type="text"
                id="search"
                name="search"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="Cari nama atau tahun..."
                class="form-control">
        </div>

        <div>
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="active"
                    @selected(($filters['status'] ?? '') === 'active')>
                    Aktif
                </option>
                <option value="inactive"
                    @selected(($filters['status'] ?? '') === 'inactive')>
                    Nonaktif
                </option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">Terapkan</button>
            <a href="{{ route('super-admin.master-data.index', ['category' => $category]) }}"
               class="btn btn-light">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 70px;">No.</th>

                    @foreach ($columns as $label)
                        <th>{{ $label }}</th>
                    @endforeach

                    <th>Status</th>
                    <th style="min-width: 190px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            {{ $items->firstItem() + $loop->index }}
                        </td>

                        @foreach ($columns as $field => $label)
                            <td>
                                @if ($field === 'province')
                                    {{ $item->province?->name ?? '-' }}

                                @elseif ($field === 'type')
                                    @switch($item->type)
                                        @case('kabupaten')
                                            Kabupaten
                                            @break
                                        @case('kota')
                                            Kota
                                            @break
                                        @case('mitra')
                                            Mitra
                                        @case('mandiri')
                                            Mandiri
                                            @break
                                        @default
                                            {{ ucfirst($item->type ?? '-') }}
                                    @endswitch

                                @elseif ($field === 'year')
                                    {{ $item->year }}

                                @else
                                    {{ $item->{$field} ?? '-' }}
                                @endif
                            </td>
                        @endforeach

                        <td>
                            @if ($item->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>

                        <td>
                            <div class="table-actions">
                                <a
                                    href="{{ route('super-admin.master-data.edit', [
                                        'category' => $category,
                                        'id' => $item->id,
                                    ]) }}"
                                    class="btn btn-outline btn-sm">
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('super-admin.master-data.status', [
                                        'category' => $category,
                                        'id' => $item->id,
                                    ]) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="btn btn-light btn-sm">
                                        {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form
                                    method="POST"
                                    action="{{ route('super-admin.master-data.destroy', [
                                        'category' => $category,
                                        'id' => $item->id,
                                    ]) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 3 }}"
                            style="text-align: center; padding: 28px;">
                            Belum ada data yang sesuai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $items->links() }}
    </div>
</div>
@endsection