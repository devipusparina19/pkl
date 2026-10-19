@extends('layouts.app')

@section('title', $training->exists ? 'Edit Pelatihan' : 'Tambah Pelatihan')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">DATA MASTER</div>
        <h1>{{ $training->exists ? 'Edit Pelatihan' : 'Tambah Pelatihan' }}</h1>
        <p>Masukkan informasi kegiatan secara konsisten agar laporan akurat.</p>
    </div>
</div>

<div class="panel form-panel">
    <form method="POST"
          action="{{ $training->exists ? route('trainings.update', $training) : route('trainings.store') }}">
        @csrf
        @if ($training->exists)
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-field">
                <label for="code">Kode / Angkatan</label>
                <input id="code" name="code" value="{{ old('code', $training->code) }}">
                @error('code') <small class="field-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-field">
                <label for="name">Nama Pelatihan *</label>
                <input id="name" name="name" value="{{ old('name', $training->name) }}" required>
                @error('name') <small class="field-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-field">
                <label for="field">Bidang Kesehatan</label>
                <input id="field" name="field" value="{{ old('field', $training->field) }}">
            </div>

            <div class="form-field">
                <label for="organizer">Penyelenggara</label>
                <input id="organizer" name="organizer" value="{{ old('organizer', $training->organizer) }}">
            </div>

            <div class="form-field">
                <label for="start_date">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date"
                       value="{{ old('start_date', $training->start_date?->format('Y-m-d')) }}">
            </div>

            <div class="form-field">
                <label for="end_date">Tanggal Selesai</label>
                <input type="date" id="end_date" name="end_date"
                       value="{{ old('end_date', $training->end_date?->format('Y-m-d')) }}">
                @error('end_date') <small class="field-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-field">
                <label for="duration_days">Durasi (hari)</label>
                <input type="number" min="0" max="3650" id="duration_days"
                       name="duration_days" value="{{ old('duration_days', $training->duration_days ?? 0) }}">
            </div>

            <div class="form-field">
                <label for="location">Lokasi</label>
                <input id="location" name="location" value="{{ old('location', $training->location) }}">
            </div>

            <div class="form-field full-width">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4">{{ old('description', $training->description) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <a class="btn btn-light" href="{{ route('trainings.index') }}">Batal</a>
            <button class="btn btn-primary" type="submit">Simpan Pelatihan</button>
        </div>
    </form>
</div>
@endsection