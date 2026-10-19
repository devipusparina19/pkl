
@extends('layouts.app')

@section('title', 'Edit Pelatihan')

@section('content')
<div class="page-header">
    <h1>Edit Pelatihan</h1>
    <p>Perbarui informasi pelatihan yang tersimpan.</p>
</div>

<div class="content-card">
    <form action="{{ route('trainings.update', $training) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Pelatihan *</label>
            <input id="name" type="text" name="name"
                   value="{{ old('name', $training->name) }}"
                   maxlength="255" required>
            @error('name') <p style="color:#b91c1c">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="field">Bidang Pelatihan</label>
            <input id="field" type="text" name="field"
                   value="{{ old('field', $training->field) }}"
                   maxlength="150">
            @error('field') <p style="color:#b91c1c">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="organizer">Penyelenggara</label>
            <input id="organizer" type="text" name="organizer"
                   value="{{ old('organizer', $training->organizer) }}"
                   maxlength="255">
            @error('organizer') <p style="color:#b91c1c">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="location">Lokasi</label>
            <input id="location" type="text" name="location"
                   value="{{ old('location', $training->location) }}"
                   maxlength="255">
            @error('location') <p style="color:#b91c1c">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="start_date">Tanggal Mulai *</label>
            <input id="start_date" type="date" name="start_date"
                   value="{{ old('start_date', $training->start_date?->format('Y-m-d')) }}"
                   required>
            @error('start_date') <p style="color:#b91c1c">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="end_date">Tanggal Selesai</label>
            <input id="end_date" type="date" name="end_date"
                   value="{{ old('end_date', $training->end_date?->format('Y-m-d')) }}">
            @error('end_date') <p style="color:#b91c1c">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="description">Keterangan</label>
            <textarea id="description" name="description" rows="4">{{ old('description', $training->description) }}</textarea>
            @error('description') <p style="color:#b91c1c">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn">Simpan Perubahan</button>
        <a href="{{ route('trainings.index') }}"
           class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection