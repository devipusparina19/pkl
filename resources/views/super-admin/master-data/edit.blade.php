@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">SUPER ADMIN / MASTER DATA</div>
        <h1>Edit {{ $config['title'] }}</h1>
        <p>Perbarui data referensi SIRAPIK dengan teliti.</p>
    </div>

    <a href="{{ route('super-admin.master-data.index', ['category' => $category]) }}"
       class="btn btn-light">
        Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong>Periksa kembali data yang dimasukkan.</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-card form-card">
    <form method="POST"
          action="{{ route('super-admin.master-data.update', [
              'category' => $category,
              'id' => $item->id,
          ]) }}">
        @csrf
        @method('PUT')

        @if ($category === 'districts')
            <div class="form-group">
                <label for="province_id">Provinsi <span>*</span></label>

                <select
                    name="province_id"
                    id="province_id"
                    class="form-control @error('province_id') input-error @enderror"
                    required>
                    <option value="">-- Pilih Provinsi --</option>

                    @foreach ($provinces as $province)
                        <option value="{{ $province->id }}"
                            @selected((string) old('province_id', $item->province_id) === (string) $province->id)>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>

                @error('province_id')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Nama Wilayah/Mitra/Mandiri <span>*</span></label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $item->name) }}"
                    maxlength="255"
                    class="form-control @error('name') input-error @enderror"
                    required>

                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Jenis Wilayah <span>*</span></label>

                <select
                    name="type"
                    id="type"
                    class="form-control @error('type') input-error @enderror"
                    required>
                    <option value="kabupaten"
                        @selected(old('type', $item->type) === 'kabupaten')>
                        Kabupaten
                    </option>
                    <option value="kota"
                        @selected(old('type', $item->type) === 'kota')>
                        Kota
                    </option>
                    <option value="mitra"
                        @selected(old('type', $item->type) === 'mitra')>
                        Mitra
                    </option>
                    <option value="mandiri"
                        @selected(old('type', $item->type) === 'mandiri')>
                        Mandiri
                    </option>
                </select>

                @error('type')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

        @elseif ($category === 'training-years')
            <div class="form-group">
                <label for="year">Tahun Pelatihan <span>*</span></label>
                <input
                    type="number"
                    name="year"
                    id="year"
                    value="{{ old('year', $item->year) }}"
                    min="1900"
                    max="2155"
                    class="form-control @error('year') input-error @enderror"
                    required>

                @error('year')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

        @else
            @php
                $fieldLabel = match ($category) {
                    'provinces' => 'Nama Provinsi',
                    'funding-sources' => 'Nama Sumber Dana',
                    'health-worker-types' => 'Nama Jenis Tenaga Kesehatan',
                    default => 'Nama',
                };
            @endphp

            <div class="form-group">
                <label for="name">{{ $fieldLabel }} <span>*</span></label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $item->name) }}"
                    maxlength="255"
                    class="form-control @error('name') input-error @enderror"
                    required>

                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
        @endif

        <div class="form-group">
            <label for="is_active">Status <span>*</span></label>

            <select
                name="is_active"
                id="is_active"
                class="form-control @error('is_active') input-error @enderror"
                required>
                <option value="1"
                    @selected((string) old('is_active', (int) $item->is_active) === '1')>
                    Aktif
                </option>
                <option value="0"
                    @selected((string) old('is_active', (int) $item->is_active) === '0')>
                    Nonaktif
                </option>
            </select>

            @error('is_active')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="{{ route('super-admin.master-data.index', ['category' => $category]) }}"
               class="btn btn-light">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection