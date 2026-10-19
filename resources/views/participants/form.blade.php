
@extends('layouts.app')

@section('title', $participant->exists ? 'Edit Peserta' : 'Tambah Peserta')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">DATA MASTER</div>
        <h1>{{ $participant->exists ? 'Edit Peserta' : 'Tambah Peserta' }}</h1>
        <p>Lengkapi informasi peserta sesuai kolom data Excel.</p>
    </div>
</div>

@if (!$participant->exists)
    <div class="alert alert-success" role="status">
        <strong>Informasi akun peserta</strong><br>
        Nama lengkap dan NIP digunakan sebagai identitas peserta.
        Pastikan data ditulis dengan benar.
    </div>
@endif

<div class="panel form-panel">
    <form
        method="POST"
        action="{{ $participant->exists
            ? route('participants.update', $participant)
            : route('participants.store') }}"
    >
        @csrf

        @if ($participant->exists)
            @method('PUT')
        @endif

        <div class="form-grid">

            {{-- Nama Lengkap --}}
            <div class="form-field">
                <label for="name">Nama Lengkap *</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $participant->name) }}"
                    maxlength="255"
                    required
                >
                @error('name')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- NIK --}}
            <div class="form-field">
                <label for="nik">NIK</label>
                <input
                    id="nik"
                    name="nik"
                    type="text"
                    value="{{ old('nik', $participant->nik) }}"
                    maxlength="30"
                    inputmode="numeric"
                >
                @error('nik')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- NIP --}}
            <div class="form-field">
                <label for="nip">NIP *</label>
                <input
                    id="nip"
                    name="nip"
                    type="text"
                    value="{{ old('nip', $participant->nip) }}"
                    maxlength="30"
                    required
                >
                @error('nip')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div class="form-field">
                <label for="gender">Jenis Kelamin</label>
                <select id="gender" name="gender">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L"
                        {{ old('gender', $participant->gender) === 'L' ? 'selected' : '' }}>
                        Laki-laki
                    </option>
                    <option value="P"
                        {{ old('gender', $participant->gender) === 'P' ? 'selected' : '' }}>
                        Perempuan
                    </option>
                </select>
                @error('gender')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Pangkat/Golongan --}}
            <div class="form-field">
                <label for="rank">Pangkat/Golongan</label>
                <input
                    id="rank"
                    name="rank"
                    type="text"
                    value="{{ old('rank', $participant->rank) }}"
                    maxlength="100"
                >
                @error('rank')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Profesi Nakes --}}
            <div class="form-field">
                <label for="profession">Profesi Nakes</label>
                <input
                    id="profession"
                    name="profession"
                    type="text"
                    value="{{ old('profession', $participant->profession) }}"
                    maxlength="255"
                >
                @error('profession')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-field">
                <label for="email">Email *</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $participant->email) }}"
                    maxlength="255"
                    autocomplete="email"
                    required
                >
                @error('email')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Nomor Telepon --}}
            <div class="form-field">
                <label for="phone">Nomor Telepon</label>
                <input
                    id="phone"
                    type="text"
                    name="phone"
                    value="{{ old('phone', $participant->phone) }}"
                    maxlength="30"
                >
                @error('phone')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Instansi/Unit Kerja --}}
            <div class="form-field">
                <label for="institution">Instansi/Unit Kerja</label>
                <input
                    id="institution"
                    name="institution"
                    type="text"
                    value="{{ old('institution', $participant->institution) }}"
                    maxlength="255"
                >
                @error('institution')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Unit Kerja tambahan jika diperlukan --}}
            <div class="form-field">
                <label for="unit">Unit Kerja / Puskesmas (Opsional)</label>
                <input
                    id="unit"
                    name="unit"
                    type="text"
                    value="{{ old('unit', $participant->unit) }}"
                    maxlength="255"
                >
                @error('unit')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Angkatan --}}
            <div class="form-field">
                <label for="angkatan">Angkatan</label>
                <input
                    id="angkatan"
                    name="angkatan"
                    type="text"
                    value="{{ old('angkatan') }}"
                    maxlength="50"
                    placeholder="Contoh: 1"
                >
                @error('angkatan')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Sumber Dana --}}
            <div class="form-field">
                <label for="sumber_dana">Sumber Dana</label>
                <input
                    id="sumber_dana"
                    name="sumber_dana"
                    type="text"
                    value="{{ old('sumber_dana') }}"
                    maxlength="255"
                    placeholder="Contoh: Dinas Kesehatan Kabupaten Banjar"
                >
                @error('sumber_dana')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Kabupaten/Kota --}}
            <div class="form-field">
                <label for="district">Kabupaten/Kota</label>
                <input
                    id="district"
                    name="district"
                    type="text"
                    value="{{ old('district', $participant->district) }}"
                    maxlength="255"
                >
                @error('district')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Provinsi --}}
            <div class="form-field">
                <label for="province">Provinsi</label>
                <input
                    id="province"
                    name="province"
                    type="text"
                    value="{{ old('province', $participant->province ?? 'Kalimantan Selatan') }}"
                    maxlength="255"
                >
                @error('province')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Jabatan --}}
            <div class="form-field">
                <label for="position">Jabatan</label>
                <input
                    id="position"
                    name="position"
                    type="text"
                    value="{{ old('position', $participant->position) }}"
                    maxlength="255"
                >
                @error('position')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            {{-- Catatan --}}
            <div class="form-field full-width">
                <label for="notes">Catatan</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="3"
                >{{ old('notes', $participant->notes) }}</textarea>
                @error('notes')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

        </div>

        <div class="form-actions">
            <a class="btn btn-light" href="{{ route('participants.index') }}">
                Batal
            </a>

            <button class="btn btn-primary" type="submit">
                {{ $participant->exists ? 'Simpan Perubahan' : 'Simpan Peserta' }}
            </button>
        </div>
    </form>
</div>
@endsection