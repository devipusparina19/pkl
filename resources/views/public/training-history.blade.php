<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#12345a">
    <meta name="description" content="Informasi riwayat pelatihan peserta SIRAPIK.">

    <title>Hasil Riwayat Pelatihan — SIRAPIK</title>

    <link rel="stylesheet" href="{{ asset('css/sirapik.css') }}">

    <style>
        :root {
            --navy-dark: #0b2340;
            --navy: #12345a;
            --blue: #1d4c7c;
            --blue-accent: #2463a6;
            --blue-soft: #edf4fc;
            --ink: #26364a;
            --muted: #64748b;
            --border: #dce4ee;
            --green: #207653;
            --green-soft: #e9f6ef;
            --white: #fff;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            position: relative;
            isolation: isolate;
            min-height: 100vh;
            margin: 0;
            padding: 35px 20px;
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.7;
            color: var(--ink);
            background: #f3f6fa;
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                linear-gradient(
                    125deg,
                    rgba(11, 35, 64, .84),
                    rgba(18, 52, 90, .76),
                    rgba(29, 76, 124, .79)
                ),
                url("{{ asset('images/login-bapelkes.jpg') }}")
                center / cover no-repeat;
        }

        .page-container {
            width: min(1050px, 100%);
            margin: 0 auto;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .75);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 24px 65px rgba(5, 22, 44, .28);
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 23px 30px;
            color: #fff;
            background: linear-gradient(
                110deg,
                #0b2340,
                #12345a 60%,
                #1d4c7c
            );
        }

        .brand-logo {
            display: flex;
            flex: 0 0 54px;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .4);
            border-radius: 11px;
            background: #fff;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            padding: 4px;
            object-fit: contain;
        }

        .brand-heading h1 {
            margin: 0 0 2px;
            color: #fff;
            font-size: 23px;
            font-weight: 800;
            line-height: 1.4;
        }

        .brand-heading p {
            margin: 0;
            color: rgba(255, 255, 255, .88);
            font-size: 12px;
        }

        .result-section {
            padding: 30px;
            background: #fff;
        }

        .eyebrow {
            display: block;
            margin-bottom: 7px;
            color: var(--blue-accent);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .result-heading {
            margin-bottom: 22px;
        }

        .result-heading h2 {
            margin: 0 0 5px;
            color: var(--navy-dark);
            font-size: 27px;
            font-weight: 800;
            line-height: 1.4;
        }

        .result-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
        }

        .alert-success {
            margin-bottom: 24px;
            padding: 13px 15px;
            border: 1px solid #b9e3cd;
            border-radius: 9px;
            background: var(--green-soft);
            color: var(--green);
            line-height: 1.8;
        }

        .alert-success strong {
            display: block;
            margin-bottom: 2px;
        }

        .section-heading {
            margin: 23px 0 12px;
            color: var(--navy);
            font-size: 19px;
            font-weight: 800;
        }

        .participant-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0 22px;
            margin-bottom: 25px;
        }

        .info-item {
            min-width: 0;
            padding: 12px 0;
            border-bottom: 1px solid #e8edf4;
        }

        .info-item span {
            display: block;
            margin-bottom: 3px;
            color: var(--muted);
            font-size: 12px;
        }

        .info-item strong {
            display: block;
            color: #334155;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .history-summary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 17px;
        }

        .summary-box {
            min-width: 0;
            padding: 15px 17px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #f8fbff;
        }

        .summary-box span {
            display: block;
            color: var(--muted);
            font-size: 12px;
        }

        .summary-box strong {
            display: block;
            margin-top: 3px;
            color: var(--navy);
            font-size: 21px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: 9px;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 650px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px 14px;
            border-bottom: 1px solid #e8edf4;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f1f5fa;
            color: #52647a;
            font-size: 12px;
            font-weight: 800;
        }

        td {
            color: #36475c;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover td {
            background: #f8fbff;
        }

        .training-name {
            color: var(--navy);
            font-weight: 750;
        }

        .status {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 6px;
            background: var(--blue-soft);
            color: var(--navy);
            font-size: 11px;
            font-weight: 750;
        }

        .empty-state {
            padding: 22px 17px;
            border: 1px dashed var(--border);
            border-radius: 9px;
            background: #f8fbff;
            color: var(--muted);
            text-align: center;
        }

        .result-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 11px;
            margin-top: 23px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .secondary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 43px;
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--navy);
            font-family: inherit;
            font-size: 13px;
            font-weight: 750;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, border-color .2s;
        }

        .secondary-button:hover {
            border-color: #b8cbe1;
            background: var(--blue-soft);
        }

        .primary-button {
            border-color: var(--navy);
            background: var(--navy);
            color: #fff;
        }

        .primary-button:hover {
            border-color: var(--blue);
            background: var(--blue);
        }

        .page-footer {
            padding: 17px 25px;
            border-top: 1px solid var(--border);
            background: #f8fafc;
            color: var(--muted);
            font-size: 11px;
            text-align: center;
        }

        .page-footer p {
            margin: 0;
        }

        @media (max-width: 760px) {
            body {
                padding: 18px 11px;
            }

            .page-header {
                padding: 20px;
            }

            .result-section {
                padding: 23px 20px;
            }

            .participant-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0 17px;
            }

            .result-heading h2 {
                font-size: 24px;
            }
        }

        @media (max-width: 520px) {
            .page-header {
                gap: 11px;
                padding: 17px;
            }

            .brand-logo {
                flex-basis: 45px;
                width: 45px;
                height: 45px;
            }

            .brand-heading h1 {
                font-size: 20px;
            }

            .brand-heading p {
                font-size: 11px;
            }

            .result-section {
                padding: 21px 16px;
            }

            .result-heading h2 {
                font-size: 22px;
            }

            .participant-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .history-summary {
                grid-template-columns: minmax(0, 1fr);
            }

            .result-actions {
                flex-direction: column;
            }

            .secondary-button {
                width: 100%;
                min-height: 45px;
            }

            .table-wrapper {
                overflow: visible;
                border: none;
            }

            table {
                display: block;
                min-width: 0;
                width: 100%;
            }

            thead {
                display: none;
            }

            tbody {
                display: grid;
                gap: 12px;
            }

            tbody tr {
                display: block;
                overflow: hidden;
                border: 1px solid var(--border);
                border-radius: 9px;
                background: #fff;
            }

            tbody td {
                display: grid;
                grid-template-columns: minmax(105px, 38%) minmax(0, 1fr);
                gap: 9px;
                padding: 11px;
                border-bottom: 1px solid #edf1f5;
                font-size: 13px;
            }

            tbody td::before {
                content: attr(data-label);
                color: var(--muted);
                font-size: 12px;
                font-weight: 700;
            }

            tbody td:last-child {
                border-bottom: none;
            }
        }

        @media (max-width: 350px) {
            body {
                padding: 9px;
            }

            .result-section {
                padding: 18px 12px;
            }

            tbody td {
                grid-template-columns: minmax(85px, 36%) minmax(0, 1fr);
                gap: 6px;
                padding: 9px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
<main class="page-container">

    <!-- IDENTITAS SIRAPIK -->
    <header class="page-header">
        <div class="brand-logo">
            <img
                src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                alt="Logo Provinsi Kalimantan Selatan"
            >
        </div>

        <div class="brand-heading">
            <h1>SIRAPIK</h1>
            <p>Bapelkes Provinsi Kalimantan Selatan</p>
        </div>
    </header>

    <!-- HALAMAN HASIL PENCARIAN -->
    <section class="result-section" id="hasil-pencarian">

        <header class="result-heading">
            <span class="eyebrow">Hasil Pencarian</span>

            <h2>Riwayat Pelatihan Anda</h2>

            <p>
                Berikut informasi peserta dan riwayat pelatihan
                yang tercatat di SIRAPIK.
            </p>
        </header>

        <div class="alert-success" role="status">
            <strong>Data peserta berhasil ditemukan.</strong>
            NIK dan nama lengkap sesuai dengan data peserta yang terdaftar.
        </div>

        <!-- INFORMASI PESERTA -->
        <h3 class="section-heading">Informasi Peserta</h3>

        <div class="participant-grid">
            <div class="info-item">
                <span>Nama Lengkap</span>
                <strong>{{ $participant->name }}</strong>
            </div>

            <div class="info-item">
                <span>NIK</span>
                <strong>{{ $participant->nik ?: '-' }}</strong>
            </div>

            <div class="info-item">
                <span>NIP</span>
                <strong>{{ $participant->nip ?: '-' }}</strong>
            </div>

            <div class="info-item">
                <span>Instansi</span>
                <strong>{{ $participant->institution ?: '-' }}</strong>
            </div>

            <div class="info-item">
                <span>Kabupaten/Kota/Mitra</span>
                <strong>{{ $participant->district ?: '-' }}</strong>
            </div>

            <div class="info-item">
                <span>Profesi</span>
                <strong>{{ $participant->profession ?: '-' }}</strong>
            </div>
        </div>

        <!-- DAFTAR RIWAYAT PELATIHAN -->
        <h3 class="section-heading">Daftar Riwayat Pelatihan</h3>

        <div class="history-summary">
            <div class="summary-box">
                <span>Total Riwayat Pelatihan</span>
                <strong>{{ $participant->histories->count() }}</strong>
            </div>

            <div class="summary-box">
                <span>Status Data Peserta</span>
                <strong>Terdaftar</strong>
            </div>
        </div>

        @if ($participant->histories->isEmpty())

            <div class="empty-state">
                Belum ada riwayat pelatihan yang tercatat untuk peserta ini.
            </div>

        @else

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pelatihan</th>
                            <th>Angkatan</th>
                            <th>Sumber Dana</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($participant->histories as $history)
                            <tr>
                                <td data-label="No.">
                                    {{ $loop->iteration }}
                                </td>

                                <td data-label="Nama Pelatihan">
                                    <span class="training-name">
                                        {{ $history->training?->name ?? 'Nama pelatihan tidak tersedia' }}
                                    </span>
                                </td>

                                <td data-label="Angkatan">
                                    {{ $history->training?->batch_number ?: '-' }}
                                </td>

                                <td data-label="Sumber Dana">
                                    {{ $history->training?->funding_source ?: '-' }}
                                </td>

                                <td data-label="Status">
                                    @if ($history->status)
                                        <span class="status">
                                            {{ $history->status }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif

        <!-- TOMBOL NAVIGASI -->
        <div class="result-actions">
            <a
                href="{{ route('public.dashboard') }}"
                class="secondary-button"
            >
                ← Kembali ke Dashboard Publik
            </a>
        </div>

    </section>

    <!-- FOOTER -->
    <footer class="page-footer">
        <p>
            &copy; {{ date('Y') }} SIRAPIK —
            Bapelkes Provinsi Kalimantan Selatan
        </p>
    </footer>

</main>
</body>
</html>