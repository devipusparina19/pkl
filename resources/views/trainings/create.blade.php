@extends('layouts.app')

@section('title', 'Tambah Pelatihan')

@section('content')
<div class="page-header">
    <h1>Tambah Pelatihan</h1>
    <p>Masukkan informasi pelatihan yang akan dicatat dalam sistem.</p>
</div>

<div class="content-card">

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data yang dimasukkan.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('trainings.store') }}" method="POST">
        @csrf

        {{-- 1. Nama Pelatihan --}}
        <div class="form-group">
            <label for="name">Nama Pelatihan <span>*</span></label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                maxlength="255"
                class="form-control @error('name') input-error @enderror"
                required
                placeholder="Contoh: Pelatihan Manajemen Puskesmas"
            >
            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 2. Kategori --}}
        <div class="form-group">
            <label for="training_category_id">Kategori Pelatihan <span>*</span></label>
            <select
                id="training_category_id"
                name="training_category_id"
                class="form-control @error('training_category_id') input-error @enderror"
                required
            >
                <option value="">-- Pilih Kategori Pelatihan --</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected((string) old('training_category_id') === (string) $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('training_category_id')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 3. Nomor Angkatan --}}
        <div class="form-group">
            <label for="batch_number">Nomor Angkatan</label>
            <input
                id="batch_number"
                type="text"
                name="batch_number"
                value="{{ old('batch_number') }}"
                maxlength="100"
                class="form-control @error('batch_number') input-error @enderror"
                placeholder="Contoh: Angkatan 1"
            >
            @error('batch_number')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 4. Sumber Dana --}}
        <div class="form-group">
            <label for="funding_source">Sumber Dana</label>
            <select
                id="funding_source"
                name="funding_source"
                class="form-control @error('funding_source') input-error @enderror"
            >
                <option value="">-- Pilih Sumber Dana --</option>

                @foreach ($fundingSources as $source)
                    <option
                        value="{{ $source->name }}"
                        @selected(old('funding_source') === $source->name)
                    >
                        {{ $source->name }}
                    </option>
                @endforeach
            </select>
            @error('funding_source')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 5. Penyelenggara --}}
        <div class="form-group">
            <label for="organizer">Penyelenggara</label>
            <input
                id="organizer"
                type="text"
                name="organizer"
                value="{{ old('organizer') }}"
                maxlength="255"
                class="form-control @error('organizer') input-error @enderror"
                placeholder="Nama instansi penyelenggara"
            >
            @error('organizer')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 6. Tanggal Mulai --}}
        <div class="form-group">
            <label for="start_date">Tanggal Mulai <span>*</span></label>
            <input
                id="start_date"
                type="date"
                name="start_date"
                value="{{ old('start_date') }}"
                class="form-control @error('start_date') input-error @enderror"
                required
            >
            @error('start_date')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 7. Tanggal Selesai --}}
        <div class="form-group">
            <label for="end_date">Tanggal Selesai</label>
            <input
                id="end_date"
                type="date"
                name="end_date"
                value="{{ old('end_date') }}"
                class="form-control @error('end_date') input-error @enderror"
            >
            @error('end_date')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 8. JPL --}}
        <div class="form-group">
            <label for="jpl">JPL</label>
            <input
                id="jpl"
                type="number"
                name="jpl"
                value="{{ old('jpl') }}"
                min="0"
                step="1"
                class="form-control @error('jpl') input-error @enderror"
                placeholder="Masukkan jumlah JPL"
            >
            @error('jpl')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 9. Kabupaten/Kota --}}
        <div class="form-group">
            <label for="district">Kabupaten/Kota</label>
            <select
                id="district"
                name="district"
                class="form-control @error('district') input-error @enderror"
            >
                <option value="">-- Pilih Kabupaten/Kota --</option>

                @foreach ($districts as $district)
                    <option
                        value="{{ $district->name }}"
                        @selected(old('district') === $district->name)
                    >
                        {{ $district->name }}
                    </option>
                @endforeach
            </select>
            @error('district')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 10. Keterangan --}}
        <div class="form-group">
            <label for="description">Keterangan</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                class="form-control @error('description') input-error @enderror"
                placeholder="Masukkan keterangan tambahan pelatihan"
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <p style="font-size:12px;color:#64748b">
            <span>*</span> Wajib diisi.
        </p>

        <div class="form-actions">
            <button type="submit" class="btn">
                Simpan Pelatihan
            </button>

            <a href="{{ route('trainings.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection