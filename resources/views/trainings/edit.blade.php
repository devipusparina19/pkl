@extends('layouts.app')

@section('title', 'Edit Pelatihan')

@section('content')
<div class="training-editor">

    {{-- PAGE HEADING --}}
    <header class="training-heading">
        <div class="heading-main">
            <div class="heading-eyebrow">
                <span class="eyebrow-line"></span>
                DATA MASTER
                <span class="eyebrow-divider">/</span>
                PELATIHAN
            </div>

            <h1>Edit <span>Pelatihan</span></h1>

            <p>
                Perbarui informasi pelatihan untuk memastikan data tetap
                akurat, lengkap, dan tersimpan dengan baik.
            </p>
        </div>

        <div class="heading-aside">
            <div class="aside-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 19.5V5.8C4 4.8 4.8 4 5.8 4H18a2 2 0 0 1 2 2v13.5H4Z"
                        stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                    <path d="M8 8h8M8 12h8M8 16h4"
                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    <path d="m15.5 17.5 4-4 1.5 1.5-4 4-2 .5.5-2Z"
                        fill="currentColor"/>
                </svg>
            </div>
            <div>
                <span class="aside-label">MODE HALAMAN</span>
                <strong>Perubahan Data</strong>
            </div>
        </div>
    </header>

    {{-- FORM ERROR --}}
    @if ($errors->any())
        <div class="training-alert" role="alert">
            <div class="alert-icon">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"
                        stroke="currentColor" stroke-width="1.8"/>
                    <path d="M12 7v6m0 4h.01"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round"/>
                </svg>
            </div>

            <div>
                <strong>Periksa kembali data yang dimasukkan.</strong>
                <p>Ada beberapa bagian yang perlu diperbaiki:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORM CONTAINER --}}
    <div class="training-form-shell">

        <div class="form-topbar">
            <div class="topbar-left">
                <span class="topbar-indicator"></span>
                FORMULIR PELATIHAN
            </div>
            <span class="topbar-status">EDIT DATA</span>
        </div>

        <form action="{{ route('trainings.update', $training) }}"
              method="POST"
              class="training-form">

            @csrf
            @method('PUT')

            {{-- SECTION 01 --}}
            <section class="form-section">
                <div class="section-heading">
                    <div class="section-number">01</div>
                    <div>
                        <h2>Informasi Pelatihan</h2>
                        <p>Identitas dan klasifikasi program pelatihan.</p>
                    </div>
                </div>

                <div class="form-grid">
                    {{-- Nama Pelatihan --}}
                    <div class="form-group field-wide">
                        <label for="name">
                            Nama Pelatihan <span class="required">*</span>
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $training->name) }}"
                            maxlength="255"
                            class="form-control @error('name') input-error @enderror"
                            placeholder="Contoh: Pelatihan Kepemimpinan Administrator"
                            autocomplete="off"
                            required
                        >

                        @error('name')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="form-group">
                        <label for="training_category_id">
                            Kategori Pelatihan <span class="required">*</span>
                        </label>

                        <select
                            id="training_category_id"
                            name="training_category_id"
                            class="form-control @error('training_category_id') input-error @enderror"
                            required
                        >
                            <option value="">Pilih kategori pelatihan</option>

                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        (string) old(
                                            'training_category_id',
                                            $training->training_category_id
                                        ) === (string) $category->id
                                    )
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('training_category_id')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Angkatan --}}
                    <div class="form-group">
                        <label for="batch_number">Angkatan</label>

                        <input
                            id="batch_number"
                            type="text"
                            name="batch_number"
                            value="{{ old('batch_number', $training->batch_number) }}"
                            maxlength="100"
                            class="form-control @error('batch_number') input-error @enderror"
                            placeholder="Masukkan nomor angkatan"
                        >

                        @error('batch_number')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="section-divider">
                <span></span>
            </div>

            {{-- SECTION 02 --}}
            <section class="form-section">
                <div class="section-heading">
                    <div class="section-number">02</div>
                    <div>
                        <h2>Penyelenggaraan</h2>
                        <p>Informasi pendanaan, penyelenggara, dan lokasi.</p>
                    </div>
                </div>

                <div class="form-grid">
                    {{-- Sumber Dana --}}
                    <div class="form-group">
                        <label for="funding_source">Sumber Dana</label>

                        <select
                            id="funding_source"
                            name="funding_source"
                            class="form-control @error('funding_source') input-error @enderror"
                        >
                            <option value="">Pilih sumber dana</option>

                            @foreach ($fundingSources as $source)
                                <option
                                    value="{{ $source->name }}"
                                    @selected(
                                        old('funding_source', $training->funding_source) === $source->name
                                    )
                                >
                                    {{ $source->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('funding_source')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Penyelenggara --}}
                    <div class="form-group">
                        <label for="organizer">Penyelenggara</label>

                        <input
                            id="organizer"
                            type="text"
                            name="organizer"
                            value="{{ old('organizer', $training->organizer) }}"
                            maxlength="255"
                            class="form-control @error('organizer') input-error @enderror"
                            placeholder="Nama instansi penyelenggara"
                        >

                        @error('organizer')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kabupaten/Kota --}}
                    <div class="form-group field-wide">
                        <label for="district">Kabupaten/Kota</label>

                        <select
                            id="district"
                            name="district"
                            class="form-control @error('district') input-error @enderror"
                        >
                            <option value="">Pilih kabupaten/kota</option>

                            @foreach ($districts as $district)
                                <option
                                    value="{{ $district->name }}"
                                    @selected(
                                        old('district', $training->district) === $district->name
                                    )
                                >
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('district')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="section-divider">
                <span></span>
            </div>

            {{-- SECTION 03 --}}
            <section class="form-section">
                <div class="section-heading">
                    <div class="section-number">03</div>
                    <div>
                        <h2>Waktu & Durasi</h2>
                        <p>Atur jadwal pelaksanaan dan jumlah jam pelajaran.</p>
                    </div>
                </div>

                <div class="form-grid">
                    {{-- Tanggal Mulai --}}
                    <div class="form-group">
                        <label for="start_date">
                            Tanggal Mulai <span class="required">*</span>
                        </label>

                        <input
                            id="start_date"
                            type="date"
                            name="start_date"
                            value="{{ old('start_date', $training->start_date?->format('Y-m-d')) }}"
                            class="form-control @error('start_date') input-error @enderror"
                            required
                        >

                        @error('start_date')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div class="form-group">
                        <label for="end_date">Tanggal Selesai</label>

                        <input
                            id="end_date"
                            type="date"
                            name="end_date"
                            value="{{ old('end_date', $training->end_date?->format('Y-m-d')) }}"
                            class="form-control @error('end_date') input-error @enderror"
                        >

                        @error('end_date')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- JPL --}}
                    <div class="form-group field-half">
                        <label for="jpl">Jumlah Jam Pelajaran (JPL)</label>

                        <div class="input-with-suffix">
                            <input
                                id="jpl"
                                type="number"
                                name="jpl"
                                value="{{ old('jpl', $training->jpl) }}"
                                min="0"
                                step="1"
                                class="form-control @error('jpl') input-error @enderror"
                                placeholder="Contoh: 40"
                            >
                            <span class="input-suffix">JPL</span>
                        </div>

                        @error('jpl')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- FORM FOOTER --}}
            <div class="form-footer">
                <div class="footer-note">
                    <span class="required">*</span>
                    <span>Wajib diisi</span>
                </div>

                <div class="form-actions">
                    <a href="{{ route('trainings.index') }}"
                       class="btn-cancel">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m15 18-6-6 6-6"
                                stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Batal
                    </a>

                    <button type="submit" class="btn-submit">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12.5 9.5 17 19 7"
                                stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </form>
    </div>

    <div class="training-bottom-note">
        <span class="bottom-note-mark"></span>
        Pastikan informasi pelatihan sudah benar sebelum menyimpan perubahan.
    </div>

</div>

<style>
/* =========================================================
   TRAINING EDITOR — MODERN BLUE EDITORIAL
   Scoped styles: hanya memengaruhi halaman ini.
========================================================= */

.training-editor {
    --tr-blue: #1756a9;
    --tr-blue-dark: #123d73;
    --tr-blue-deep: #102e52;
    --tr-blue-soft: #edf4fc;
    --tr-blue-pale: #f5f8fd;
    --tr-ink: #182b43;
    --tr-muted: #718096;
    --tr-border: #e2e9f2;
    --tr-danger: #d74747;

    width: 100%;
    max-width: none;
    margin: 0;
    padding: 30px clamp(18px, 2.5vw, 42px) 45px;
    color: var(--tr-ink);
    font-family: Inter, "Segoe UI", Arial, sans-serif;
    box-sizing: border-box;
}

.training-editor *,
.training-editor *::before,
.training-editor *::after {
    box-sizing: border-box;
}

/* HEADING */

.training-heading {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 28px;
    margin-bottom: 32px;
}

.heading-eyebrow {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    color: var(--tr-blue);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 2px;
}

.eyebrow-line {
    width: 30px;
    height: 3px;
    border-radius: 5px;
    background: var(--tr-blue);
}

.eyebrow-divider {
    color: #b2bdcc;
}

.training-heading h1 {
    margin: 0;
    color: var(--tr-blue-deep);
    font-size: clamp(34px, 3.5vw, 46px);
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -1.8px;
}

.training-heading h1 span {
    color: var(--tr-blue);
}

.training-heading p {
    max-width: 680px;
    margin: 12px 0 0;
    color: var(--tr-muted);
    font-size: 16px;
    line-height: 1.7;
}

.heading-aside {
    display: flex;
    align-items: center;
    gap: 13px;
    flex-shrink: 0;
    padding: 15px 19px;
    background: #f0f5fc;
    border: 1px solid #e0eaf7;
    border-radius: 14px;
}

.aside-icon {
    display: grid;
    place-items: center;
    width: 43px;
    height: 43px;
    color: white;
    background: var(--tr-blue);
    border-radius: 12px;
}

.aside-icon svg {
    width: 23px;
    height: 23px;
}

.aside-label {
    display: block;
    margin-bottom: 4px;
    color: #8292a7;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.2px;
}

.heading-aside strong {
    display: block;
    color: var(--tr-blue-deep);
    font-size: 14px;
    font-weight: 750;
}

/* ERROR ALERT */

.training-alert {
    display: flex;
    gap: 15px;
    padding: 19px 22px;
    margin-bottom: 24px;
    color: #923737;
    background: #fff5f4;
    border: 1px solid #f4d7d4;
    border-left: 4px solid #d74747;
    border-radius: 12px;
    animation: trFadeUp .35s ease both;
}

.alert-icon {
    flex-shrink: 0;
}

.alert-icon svg {
    width: 25px;
    height: 25px;
}

.training-alert strong {
    font-size: 15px;
}

.training-alert p {
    margin: 5px 0;
    font-size: 14px;
}

.training-alert ul {
    margin: 7px 0 0;
    padding-left: 19px;
    font-size: 14px;
    line-height: 1.8;
}

/* FORM SHELL */

.training-form-shell {
    width: 100%;
    overflow: hidden;
    background: #fff;
    border: 1px solid var(--tr-border);
    border-radius: 18px;
    box-shadow: 0 8px 35px rgba(24, 52, 88, .055);
    animation: trFadeUp .45s ease both;
}

.form-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 57px;
    padding: 0 30px;
    background: #f8fafd;
    border-bottom: 1px solid var(--tr-border);
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #50647e;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.topbar-indicator {
    width: 8px;
    height: 8px;
    background: #2e78c9;
    border-radius: 50%;
    box-shadow: 0 0 0 4px #e3effc;
}

.topbar-status {
    padding: 7px 12px;
    color: var(--tr-blue);
    background: #eaf2fc;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
}

.training-form {
    padding: 30px clamp(20px, 3vw, 42px) 0;
}

/* SECTION */

.form-section {
    padding: 8px 0 34px;
}

.section-heading {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 26px;
}

.section-number {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 47px;
    height: 47px;
    color: var(--tr-blue);
    background: var(--tr-blue-soft);
    border: 1px solid #dce9f8;
    border-radius: 13px;
    font-size: 15px;
    font-weight: 850;
    letter-spacing: .5px;
}

.section-heading h2 {
    margin: 0;
    color: var(--tr-blue-deep);
    font-size: 20px;
    font-weight: 800;
    letter-spacing: -.4px;
}

.section-heading p {
    margin: 5px 0 0;
    color: var(--tr-muted);
    font-size: 14px;
    line-height: 1.5;
}

.section-divider {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 0 0 28px;
}

.section-divider span {
    width: 100%;
    height: 1px;
    background: var(--tr-border);
}

/* FORM GRID */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    column-gap: 30px;
    row-gap: 23px;
}

.form-group {
    min-width: 0;
}

.field-wide {
    grid-column: 1 / -1;
}

.field-half {
    grid-column: span 1;
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    color: #344860;
    font-size: 15px;
    font-weight: 750;
}

.required {
    color: #e05252;
    font-weight: 800;
}

.form-control {
    display: block;
    width: 100%;
    min-height: 53px;
    padding: 12px 16px;
    color: #243b56;
    background-color: #fbfcfe;
    border: 1px solid #dce4ee;
    border-radius: 10px;
    outline: none;
    font-family: inherit;
    font-size: 15px;
    font-weight: 500;
    transition:
        border-color .2s ease,
        background .2s ease,
        box-shadow .2s ease,
        transform .2s ease;
}

.form-control::placeholder {
    color: #a2afbf;
    font-weight: 400;
}

.form-control:hover {
    border-color: #b4c8e0;
    background-color: #fff;
}

.form-control:focus {
    background-color: #fff;
    border-color: var(--tr-blue);
    box-shadow: 0 0 0 4px rgba(23, 86, 169, .10);
}

.form-control option {
    color: #243b56;
}

.form-control.input-error {
    border-color: #dc6464;
    background: #fffafa;
}

.form-control.input-error:focus {
    box-shadow: 0 0 0 4px rgba(220, 100, 100, .10);
}

.field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin: 8px 0 0;
    color: var(--tr-danger);
    font-size: 13px;
    font-weight: 600;
    line-height: 1.5;
}

.field-error::before {
    content: "!";
    display: inline-grid;
    place-items: center;
    flex-shrink: 0;
    width: 16px;
    height: 16px;
    margin-top: 1px;
    color: white;
    background: var(--tr-danger);
    border-radius: 50%;
    font-size: 10px;
    font-weight: 800;
}

/* JPL INPUT */

.input-with-suffix {
    position: relative;
}

.input-with-suffix .form-control {
    padding-right: 65px;
}

.input-suffix {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    color: #8494a9;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .5px;
    pointer-events: none;
}

/* FORM FOOTER */

.form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin: 0 calc(clamp(20px, 3vw, 42px) * -1);
    padding: 22px clamp(20px, 3vw, 42px);
    background: #f8fafd;
    border-top: 1px solid var(--tr-border);
}

.footer-note {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #7a899d;
    font-size: 13px;
    font-weight: 600;
}

.form-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-cancel,
.btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 49px;
    padding: 0 21px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 750;
    text-decoration: none;
    cursor: pointer;
    transition:
        background .2s ease,
        color .2s ease,
        border-color .2s ease,
        box-shadow .2s ease,
        transform .2s ease;
}

.btn-cancel svg,
.btn-submit svg {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.btn-cancel {
    color: #53667e;
    background: #fff;
    border: 1px solid #dce4ee;
}

.btn-cancel:hover {
    color: var(--tr-blue);
    background: #f0f5fc;
    border-color: #b8cce4;
    transform: translateY(-2px);
}

.btn-submit {
    color: white;
    background: var(--tr-blue);
    border: 1px solid var(--tr-blue);
    box-shadow: 0 5px 13px rgba(23, 86, 169, .16);
}

.btn-submit:hover {
    background: var(--tr-blue-dark);
    border-color: var(--tr-blue-dark);
    box-shadow: 0 8px 18px rgba(23, 86, 169, .23);
    transform: translateY(-2px);
}

.btn-submit:active,
.btn-cancel:active {
    transform: translateY(0);
}

.btn-submit:focus-visible,
.btn-cancel:focus-visible {
    outline: 3px solid rgba(23, 86, 169, .25);
    outline-offset: 3px;
}

/* BOTTOM NOTE */

.training-bottom-note {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 19px;
    color: #8492a5;
    font-size: 13px;
    line-height: 1.6;
}

.bottom-note-mark {
    width: 7px;
    height: 7px;
    flex-shrink: 0;
    background: #6b9bd1;
    border-radius: 50%;
}

/* ANIMATION */

@keyframes trFadeUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* TABLET */

@media (max-width: 900px) {
    .training-editor {
        padding: 25px 22px 38px;
    }

    .training-heading {
        align-items: flex-start;
    }

    .heading-aside {
        padding: 12px;
    }

    .form-grid {
        column-gap: 20px;
    }
}

/* MOBILE */

@media (max-width: 640px) {
    .training-editor {
        padding: 20px 14px 30px;
    }

    .training-heading {
        display: block;
        margin-bottom: 23px;
    }

    .training-heading h1 {
        font-size: 34px;
        letter-spacing: -1.2px;
    }

    .training-heading p {
        font-size: 14px;
    }

    .heading-aside {
        display: inline-flex;
        margin-top: 19px;
    }

    .form-topbar {
        min-height: 51px;
        padding: 0 17px;
    }

    .topbar-left {
        font-size: 10px;
        letter-spacing: 1px;
    }

    .training-form {
        padding: 24px 17px 0;
    }

    .form-grid {
        grid-template-columns: minmax(0, 1fr);
        row-gap: 20px;
    }

    .field-wide,
    .field-half {
        grid-column: auto;
    }

    .section-heading {
        gap: 12px;
        margin-bottom: 21px;
    }

    .section-number {
        width: 41px;
        height: 41px;
        font-size: 13px;
    }

    .section-heading h2 {
        font-size: 18px;
    }

    .section-heading p {
        font-size: 13px;
    }

    .form-section {
        padding-bottom: 27px;
    }

    .form-divider {
        margin-bottom: 23px;
    }

    .form-footer {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 17px;
        margin-right: -17px;
        margin-left: -17px;
        padding: 19px 17px;
    }

    .form-actions {
        display: grid;
        grid-template-columns: .8fr 1.4fr;
        width: 100%;
    }

    .btn-cancel,
    .btn-submit {
        min-height: 48px;
        padding: 0 10px;
        font-size: 13px;
    }

    .footer-note {
        font-size: 12px;
    }

    .training-bottom-note {
        align-items: flex-start;
        font-size: 12px;
    }
}

/* SMALL MOBILE */

@media (max-width: 360px) {
    .training-editor {
        padding-right: 11px;
        padding-left: 11px;
    }

    .training-heading h1 {
        font-size: 30px;
    }

    .form-actions {
        grid-template-columns: 1fr;
    }

    .btn-cancel,
    .btn-submit {
        width: 100%;
    }
}

/* ACCESSIBILITY */

@media (prefers-reduced-motion: reduce) {
    .training-editor *,
    .training-editor *::before,
    .training-editor *::after {
        scroll-behavior: auto !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>
@endsection