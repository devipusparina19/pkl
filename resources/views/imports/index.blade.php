@extends('layouts.app')

@section('content')
<div class="import-page">
    <div class="page-heading">
        <div>
            <h1>Import Data Peserta Pelatihan</h1>
            <p>Unggah Excel dan tentukan angkatan, wilayah/mitra, serta sumber dana pelatihan.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Import belum dapat diproses.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('import_result'))
        @php($result = session('import_result'))

        <div class="alert alert-success">
            <strong>Import selesai diproses.</strong>
            <ul>
                <li>Peserta baru: {{ $result['created_participants'] ?? 0 }}</li>
                <li>Peserta diperbarui: {{ $result['updated_participants'] ?? 0 }}</li>
                <li>Pelatihan baru: {{ $result['created_trainings'] ?? 0 }}</li>
                <li>Riwayat pelatihan baru: {{ $result['created_histories'] ?? 0 }}</li>
            </ul>

            @if (!empty($result['errors']))
                <div class="import-notes">
                    <strong>Catatan baris yang perlu diperiksa:</strong>
                    <ul>
                        @foreach ($result['errors'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="import-card">
        <div class="card-heading">
            <div class="card-icon" aria-hidden="true">⇧</div>
            <div>
                <h2>Unggah file Excel</h2>
                <p>Periksa pilihan data sebelum memulai import.</p>
            </div>
        </div>

        <form
            id="importForm"
            action="{{ route('imports.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="file">File Excel <span class="required">*</span></label>
                    <input
                        type="file"
                        name="file"
                        id="file"
                        class="form-control"
                        accept=".xlsx,.xls,.csv"
                        required
                    >
                    <small class="field-help">
                        Format yang didukung: XLSX, XLS, atau CSV. Maksimal 10 MB.
                    </small>
                    <div id="selectedFile" class="selected-file" hidden></div>
                </div>

                <div class="form-group">
                    <label for="angkatan">Angkatan <span class="required">*</span></label>
                    <input
                        type="text"
                        name="angkatan"
                        id="angkatan"
                        class="form-control"
                        value="{{ old('angkatan', '1') }}"
                        placeholder="Contoh: 1"
                        maxlength="100"
                        required
                    >
                    <small class="field-help">
                        Isi nomor atau nama angkatan pelatihan.
                    </small>
                </div>

                <div class="form-group">
                    <label for="kabupaten_kota_mitra">Kabupaten/Kota/Mitra <span class="required">*</span></label>
                    <select
                        name="kabupaten_kota_mitra"
                        id="kabupaten_kota_mitra"
                        class="form-control form-select"
                        required
                    >
                        <option value="">-- Pilih Kabupaten/Kota/Mitra --</option>

                        @foreach ($districts ?? collect() as $district)
                            <option
                                value="{{ $district->name }}"
                                @selected(old('kabupaten_kota_mitra') === $district->name)
                            >
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="field-help">
                        Pilihan mengikuti master data SIRAPIK.
                    </small>
                </div>

                <div class="form-group full-width">
                    <label for="sumber_dana">Sumber Dana <span class="required">*</span></label>
                    <select
                        name="sumber_dana"
                        id="sumber_dana"
                        class="form-control form-select"
                        required
                    >
                        <option value="">-- Pilih Sumber Dana --</option>

                        @foreach ($fundingSources ?? collect() as $source)
                            <option
                                value="{{ $source->name }}"
                                @selected(old('sumber_dana') === $source->name)
                            >
                                {{ $source->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="field-help">
                        Pilihan mengikuti master sumber dana SIRAPIK.
                    </small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="submitButton">
                    <span aria-hidden="true">⇧</span>
                    <span>Import Excel</span>
                </button>

                <button type="reset" class="btn btn-secondary" id="resetButton">
                    Reset Form
                </button>
            </div>
        </form>
    </div>

    <div class="info-card">
        <div class="info-icon" aria-hidden="true">i</div>
        <div>
            <strong>Perhatikan sebelum import</strong>
            <p>
                Pastikan baris judul dan kolom peserta pada Excel sesuai format
                yang digunakan SIRAPIK. Peserta yang sudah terdaftar dapat
                diperbarui berdasarkan NIP atau NIK, dan riwayat pelatihan
                yang sama tidak dibuat berulang.
            </p>
        </div>
    </div>
</div>

<style>
    .import-page {
        width: 100%;
        max-width: 1120px;
        margin: 0 auto;
        padding: 30px 28px 42px;
        color: #243247;
    }

    .page-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .page-heading h1 {
        margin: 0 0 7px;
        color: #17365d;
        font-size: clamp(24px, 3vw, 30px);
        font-weight: 750;
        line-height: 1.3;
    }

    .page-heading p,
    .card-heading p {
        margin: 0;
        color: #637187;
        font-size: 14px;
        line-height: 1.7;
    }

    .import-card {
        padding: 26px;
        background: #fff;
        border: 1px solid #dce4ed;
        border-radius: 12px;
        box-shadow: 0 3px 14px rgba(24, 45, 75, .05);
    }

    .card-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        padding-bottom: 22px;
        margin-bottom: 23px;
        border-bottom: 1px solid #e7edf4;
    }

    .card-icon {
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        flex: 0 0 44px;
        border-radius: 10px;
        background: #eaf1fb;
        color: #245ba7;
        font-size: 25px;
        font-weight: 800;
    }

    .card-heading h2 {
        margin: 0 0 4px;
        color: #17365d;
        font-size: 19px;
        font-weight: 750;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 20px;
    }

    .form-group {
        min-width: 0;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #243247;
        font-size: 14px;
        font-weight: 700;
    }

    .required {
        color: #c03939;
    }

    .import-page .form-control {
        display: block;
        width: 100%;
        min-width: 0;
        min-height: 46px;
        padding: 11px 13px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background-color: #fff;
        color: #243247;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.5;
        box-shadow: none;
    }

    .import-page input[type="file"].form-control {
        padding: 8px 10px;
    }

    .import-page .form-control:focus {
        border-color: #245ba7;
        outline: 3px solid rgba(36, 91, 167, .12);
        outline-offset: 0;
    }

    .import-page select.form-select {
        cursor: pointer;
        padding-right: 38px;
    }

    .field-help {
        display: block;
        margin-top: 7px;
        color: #718096;
        font-size: 12px;
        line-height: 1.6;
    }

    .selected-file {
        margin-top: 9px;
        padding: 9px 12px;
        border-radius: 6px;
        background: #f1f6fc;
        color: #245ba7;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .form-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 28px;
        padding-top: 21px;
        border-top: 1px solid #e7edf4;
    }

    .import-page .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 43px;
        padding: 10px 18px;
        border: 1px solid transparent;
        border-radius: 7px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color .15s ease;
    }

    .import-page .btn-primary {
        background: #245ba7;
        border-color: #245ba7;
        color: #fff;
    }

    .import-page .btn-primary:hover {
        background: #1b477f;
    }

    .import-page .btn-primary:disabled {
        opacity: .7;
        cursor: wait;
    }

    .import-page .btn-secondary {
        background: #fff;
        border-color: #cbd5e1;
        color: #334155;
    }

    .import-page .btn-secondary:hover {
        background: #f3f6fa;
    }

    .import-page .alert {
        padding: 16px 19px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-size: 14px;
    }

    .import-page .alert ul {
        margin: 9px 0 0;
        padding-left: 22px;
    }

    .import-page .alert li {
        margin: 3px 0;
        overflow-wrap: anywhere;
    }

    .import-page .alert-danger {
        background: #fff1f1;
        border-color: #f0caca;
        color: #8f2929;
    }

    .import-page .alert-success {
        background: #edf8f0;
        border-color: #c9e8d0;
        color: #23623a;
    }

    .import-notes {
        margin-top: 15px;
        padding-top: 13px;
        border-top: 1px solid #c9e8d0;
    }

    .info-card {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-top: 19px;
        padding: 18px 20px;
        border: 1px solid #d7e5f7;
        border-radius: 9px;
        background: #f2f7fd;
    }

    .info-icon {
        display: grid;
        place-items: center;
        width: 27px;
        height: 27px;
        flex: 0 0 27px;
        border-radius: 50%;
        background: #dceafb;
        color: #245ba7;
        font-weight: 800;
    }

    .info-card strong {
        display: block;
        margin-bottom: 4px;
        color: #17365d;
        font-size: 14px;
    }

    .info-card p {
        margin: 0;
        color: #53657d;
        font-size: 13px;
        line-height: 1.8;
    }

    @media (max-width: 700px) {
        .import-page {
            padding: 23px 15px 32px;
        }

        .import-card {
            padding: 19px 16px;
        }

        .form-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 18px;
        }

        .full-width {
            grid-column: auto;
        }

        .page-heading h1 {
            font-size: 24px;
        }

        .card-heading h2 {
            font-size: 17px;
        }

        .form-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('file');
    const selectedFile = document.getElementById('selectedFile');
    const importForm = document.getElementById('importForm');
    const submitButton = document.getElementById('submitButton');
    const resetButton = document.getElementById('resetButton');

    if (fileInput && selectedFile) {
        fileInput.addEventListener('change', function () {
            const file = this.files && this.files.length
                ? this.files[0]
                : null;

            if (!file) {
                selectedFile.textContent = '';
                selectedFile.hidden = true;
                return;
            }

            selectedFile.textContent =
                'File dipilih: ' + file.name +
                ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';

            selectedFile.hidden = false;
        });
    }

    if (importForm && submitButton) {
        importForm.addEventListener('submit', function () {
            if (!importForm.checkValidity()) {
                return;
            }

            submitButton.disabled = true;

            const buttonText =
                submitButton.querySelector('span:last-child');

            if (buttonText) {
                buttonText.textContent = 'Memproses import...';
            }
        });
    }

    if (resetButton && fileInput && selectedFile) {
        resetButton.addEventListener('click', function () {
            window.setTimeout(function () {
                selectedFile.textContent = '';
                selectedFile.hidden = true;

                if (submitButton) {
                    submitButton.disabled = false;

                    const buttonText =
                        submitButton.querySelector('span:last-child');

                    if (buttonText) {
                        buttonText.textContent = 'Import Excel';
                    }
                }
            }, 0);
        });
    }
});
</script>
@endsection