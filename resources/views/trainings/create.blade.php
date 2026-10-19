
@extends('layouts.app')

@section('title', 'Tambah Pelatihan')

@section('content')
<div class="page-header">
    <h1>Tambah Pelatihan</h1>
    <p>Masukkan informasi pelatihan yang akan dicatat dalam sistem.</p>
</div>

<div class="content-card">
    <form action="{{ route('trainings.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nama Pelatihan *</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                maxlength="255"
                required
                placeholder="Contoh: Pelatihan Manajemen Puskesmas"
            >
            @error('name')
                <p style="color:#b91c1c">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="field">Bidang Pelatihan</label>
            <input
                id="field"
                type="text"
                name="field"
                value="{{ old('field') }}"
                maxlength="150"
                placeholder="Contoh: Manajemen Kesehatan"
            >
            @error('field')
                <p style="color:#b91c1c">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="organizer">Penyelenggara</label>
            <input
                id="organizer"
                type="text"
                name="organizer"
                value="{{ old('organizer') }}"
                maxlength="255"
                placeholder="Nama instansi penyelenggara"
            >
            @error('organizer')
                <p style="color:#b91c1c">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="location">Lokasi Pelatihan</label>
            <input
                id="location"
                type="text"
                name="location"
                value="{{ old('location') }}"
                maxlength="255"
                placeholder="Contoh: Banjarmasin"
            >
            @error('location')
                <p style="color:#b91c1c">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="start_date">Tanggal Mulai *</label>
            <input
                id="start_date"
                type="date"
                name="start_date"
                value="{{ old('start_date') }}"
                required
            >
            @error('start_date')
                <p style="color:#b91c1c">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="end_date">Tanggal Selesai</label>
            <input
                id="end_date"
                type="date"
                name="end_date"
                value="{{ old('end_date') }}"
            >
            @error('end_date')
                <p style="color:#b91c1c">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Keterangan</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Keterangan tambahan jika diperlukan"
            >{{ old('description') }}</textarea>
            @error('description')
                <p style="color:#b91c1c">{{ $message }}</p>
            @enderror
        </div>

        <p style="font-size:12px;color:#64748b">
            * Wajib diisi.
        </p>

        <button type="submit" class="btn">Simpan Pelatihan</button>
        <a href="{{ route('trainings.index') }}"
           class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection