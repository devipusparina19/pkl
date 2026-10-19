@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Impor Data Peserta Pelatihan</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('imports.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="mb-3">
            <label for="angkatan" class="form-label">Angkatan</label>
            <input
                type="text"
                name="angkatan"
                id="angkatan"
                class="form-control"
                value="{{ old('angkatan') }}"
                placeholder="Contoh: Angkatan 1"
                maxlength="50"
                required
            >
        </div>

        <div class="mb-3">
            <label for="sumber_dana" class="form-label">Sumber Dana</label>
            <input
                type="text"
                name="sumber_dana"
                id="sumber_dana"
                class="form-control"
                value="{{ old('sumber_dana') }}"
                placeholder="Masukkan sumber dana"
                maxlength="255"
                required
            >
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">File Excel Peserta</label>
            <input
                type="file"
                name="file"
                id="file"
                class="form-control"
                accept=".xlsx,.xls,.csv"
                required
            >
            <small class="text-muted">
                Pilih file Excel data peserta pelatihan.
            </small>
        </div>

        <button type="submit" class="btn btn-primary">
            Impor Data
        </button>
    </form>

    @if (session('import_summary'))
        @php($summary = session('import_summary'))

        <hr>
        <h5>Hasil Impor</h5>

        <ul>
            <li>Baris diproses: {{ $summary['processed'] ?? 0 }}</li>
            <li>Peserta baru: {{ $summary['participants'] ?? 0 }}</li>
            <li>Pelatihan baru: {{ $summary['trainings'] ?? 0 }}</li>
            <li>Riwayat baru: {{ $summary['histories'] ?? 0 }}</li>
            <li>Dilewati: {{ $summary['skipped'] ?? 0 }}</li>
        </ul>

        @if (!empty($summary['errors']))
            <h6>Catatan impor</h6>
            <ul>
                @foreach ($summary['errors'] as $error)
                    <li>
                        {{ is_array($error) ? json_encode($error) : $error }}
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
</div>
@endsection