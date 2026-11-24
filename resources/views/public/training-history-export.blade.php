<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Cegah browser menyimpan salinan lama halaman ini --}}
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Ekspor Riwayat Pelatihan - {{ $participant->name }} | ARTA</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #E5E7EB;
            color: #000;
            font-family: Arial, "Liberation Sans", Helvetica, sans-serif;
            font-size: 12pt;
            line-height: 1.3;
        }

        /* ===== Toolbar (hanya layar) ===== */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            max-width: 210mm;
            margin: 0 auto 18px;
            font-size: 13px;
        }

        .toolbar-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 9px 16px;
            border: 1px solid #1F2937;
            border-radius: 4px;
            background: #fff;
            color: #111;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-print {
            background: #1F2937;
            color: #fff;
        }

        /* ===== Kertas ===== */
        .paper {
            display: flex;
            flex-direction: column;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm 25mm 12mm;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .18);
        }

        /* ===== Kop surat ===== */
        .letterhead {
            position: relative;
            min-height: 30mm;
            padding-bottom: 5px;
            border-bottom: 1.5px solid #000;
            text-align: center;
        }

        .letterhead-logo {
            position: absolute;
            top: 0;
            left: -4mm;
            width: 24mm;
            height: 28mm;
        }

        .letterhead-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .letterhead-text {
            padding-left: 22mm;
        }

        .letterhead-text p {
            margin: 0;
        }

        .lh-gov,
        .lh-dept {
            font-size: 13pt;
            font-weight: 700;
            line-height: 1.2;
        }

        .lh-office {
            margin-top: 1px !important;
            font-size: 16pt;
            font-weight: 700;
            line-height: 1.2;
        }

        .lh-office .uptd {
            font-size: 14pt;
        }

        .lh-address {
            margin-top: 3px !important;
            font-size: 8.5pt;
            line-height: 1.3;
        }

        /* ===== Tanggal ===== */
        .letter-date {
            margin: 4px 0 14px;
            text-align: right;
            font-size: 12pt;
        }

        /* ===== Blok Sifat & Hal ===== */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .meta-table td {
            padding: 1px 0;
            border: 0;
            font-size: 12pt;
            vertical-align: top;
        }

        .meta-table .lbl {
            width: 24mm;
            white-space: nowrap;
        }

        .meta-table .sep {
            width: 6mm;
        }

        /* ===== Judul ===== */
        .doc-title {
            margin: 4px 0 14px;
            text-align: center;
            font-size: 12pt;
            font-weight: 700;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .section-title {
            margin: 14px 0 6px;
            font-size: 12pt;
            font-weight: 700;
        }

        /* ===== Identitas ===== */
        .identity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .identity-table td {
            padding: 2px 0;
            border: 0;
            font-size: 12pt;
            vertical-align: top;
        }

        .identity-table .lbl {
            width: 40mm;
            white-space: nowrap;
        }

        .identity-table .sep {
            width: 6mm;
        }

        .identity-table .val {
            overflow-wrap: anywhere;
        }

        /* ===== Tabel riwayat ===== */
        .table-wrap {
            width: 100%;
        }

        table.history {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.history thead {
            display: table-header-group;
        }

        table.history th,
        table.history td {
            padding: 5px 3px;
            border: 1px solid #000;
            font-size: 9pt;
            line-height: 1.3;
            vertical-align: middle;
            text-align: left;
            overflow-wrap: anywhere;
        }

        table.history th {
            font-weight: 700;
            text-align: center;
            /* Header tidak boleh terpotong di tengah kata */
            overflow-wrap: normal;
            word-break: keep-all;
            white-space: nowrap;
        }

        /* Header yang terdiri dari dua kata boleh turun baris antar kata */
        table.history th.th-wrap {
            white-space: normal;
        }

        table.history td.center {
            text-align: center;
        }

        .col-no       { width: 9mm; }
        .col-angkatan { width: 19mm; }
        .col-tanggal  { width: 23mm; }
        .col-durasi   { width: 15mm; }
        .col-jpl      { width: 11mm; }
        .col-dana     { width: 21mm; }
        .col-status   { width: 17mm; }

        .empty-row {
            padding: 14px !important;
            text-align: center !important;
        }

        /* ===== Paragraf penutup ===== */
        .letter-body {
            margin: 16px 0 0;
            text-align: justify;
            text-indent: 12mm;
            font-size: 12pt;
            line-height: 1.3;
        }

        /* ===== Footer ===== */
        .footer-document {
            margin-top: auto;
            padding-top: 30px;
            text-align: center;
            font-size: 8pt;
            line-height: 1.4;
        }

        /* ===== Responsif layar ===== */
        @media screen and (max-width: 850px) {
            body {
                padding: 12px;
            }

            .paper {
                width: 100%;
                min-height: auto;
                padding: 20px;
                overflow-x: auto;
            }

            .letterhead-logo {
                left: 0;
                width: 16mm;
                height: 20mm;
            }

            .letterhead-text {
                padding-left: 18mm;
            }

            .lh-gov,
            .lh-dept {
                font-size: 10pt;
            }

            .lh-office {
                font-size: 12pt;
            }

            .lh-office .uptd {
                font-size: 10pt;
            }

            .lh-address {
                font-size: 7pt;
            }

            table.history {
                min-width: 560px;
            }

            .toolbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* ===== Cetak / PDF ===== */
        @page {
            size: A4 portrait;
            margin: 15mm 25mm 12mm;
        }

        @media print {
            html,
            body {
                width: 100%;
                margin: 0;
                padding: 0;
                background: #fff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .toolbar {
                display: none !important;
            }

            .paper {
                width: 100%;
                min-height: 0;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            .letterhead-logo {
                left: -3mm;
            }

            tr {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            a {
                color: inherit;
                text-decoration: none;
            }
        }
    </style>
</head>

<body>

    {{-- Toolbar hanya tampil di layar, tidak ikut PDF --}}
    <div class="toolbar">
        <div>Pratinjau dokumen riwayat pelatihan peserta.</div>

        <div class="toolbar-actions">
            <a href="{{ route('public.training-history') }}" class="btn">Kembali</a>

            <button type="button" class="btn btn-print" onclick="window.print()">
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <main class="paper">

        {{-- KOP SURAT --}}
        <header class="letterhead">
            <div class="letterhead-logo">
                <img
                    src="{{ asset('images/LOGO PROVINSI KALIMANTAN SELATAN.png') }}"
                    alt="Logo Provinsi Kalimantan Selatan"
                >
            </div>

            <div class="letterhead-text">
                <p class="lh-gov">PEMERINTAH PROVINSI KALIMANTAN SELATAN</p>
                <p class="lh-dept">DINAS KESEHATAN</p>
                <p class="lh-office">
                    <span class="uptd">UPTD</span> BALAI PELATIHAN KESEHATAN
                </p>
                <p class="lh-address">
                    Jalan H. Mistar Tjokrokusumo No. 5A Banjarbaru Kode Pos 70714
                    <br>
                    Telepon : (0511) 477201
                    <br>
                    Email : info@bapelkes.kalselprov.go.id
                    &nbsp; Website : www.bapelkes.kalselprov.go.id
                </p>
            </div>
        </header>

        {{-- TANGGAL --}}
        <div class="letter-date">
            Banjarbaru, {{ $printedAt->copy()->locale('id')->translatedFormat('d F Y') }}
        </div>

        {{-- SIFAT & HAL --}}
        <table class="meta-table">
            <tr>
                <td class="lbl">Sifat</td>
                <td class="sep">:</td>
                <td>Biasa</td>
            </tr>
            <tr>
                <td class="lbl">Hal</td>
                <td class="sep">:</td>
                <td>Laporan Riwayat Pelatihan Peserta</td>
            </tr>
        </table>

        {{-- JUDUL --}}
        <div class="doc-title">Laporan Riwayat Pelatihan</div>

        {{-- IDENTITAS PESERTA --}}
        <div class="section-title">A. Identitas Peserta</div>

        <table class="identity-table">
            <tr>
                <td class="lbl">Nama lengkap</td>
                <td class="sep">:</td>
                <td class="val">{{ $participant->name ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">NIK</td>
                <td class="sep">:</td>
                <td class="val">{{ $participant->nik ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">NIP</td>
                <td class="sep">:</td>
                <td class="val">{{ $participant->nip ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Jenis kelamin</td>
                <td class="sep">:</td>
                <td class="val">{{ $participant->gender ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Instansi</td>
                <td class="sep">:</td>
                <td class="val">{{ $participant->institution ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Kabupaten/Kota</td>
                <td class="sep">:</td>
                <td class="val">{{ $participant->district ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Provinsi</td>
                <td class="sep">:</td>
                <td class="val">{{ $participant->province ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Profesi</td>
                <td class="sep">:</td>
                <td class="val">{{ $participant->profession ?: '-' }}</td>
            </tr>
        </table>

        {{-- TABEL RIWAYAT --}}
        <div class="section-title">B. Daftar Riwayat Pelatihan</div>

        <div class="table-wrap">
            <table class="history">
                <colgroup>
                    <col class="col-no">
                    <col>
                    <col class="col-angkatan">
                    <col class="col-tanggal">
                    <col class="col-durasi">
                    <col class="col-jpl">
                    <col class="col-dana">
                    <col class="col-status">
                </colgroup>

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Pelatihan</th>
                        <th>Angkatan</th>
                        <th>Tanggal</th>
                        <th>Durasi</th>
                        <th>JPL</th>
                        <th class="th-wrap">Sumber Dana</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($histories as $history)
                        @php
                            $training = $history->training;

                            // Durasi memakai nilai efektif: hasil edit manual jika ada,
                            // jika tidak ada maka nilai dari Excel. Sama persis dengan halaman utama.
                            $duration = $training?->effective_duration;
                        @endphp

                        <tr>
                            <td class="center">{{ $loop->iteration }}</td>

                            <td>
                                {{ $training?->name ?? 'Nama pelatihan tidak tersedia' }}
                            </td>

                            <td class="center">{{ $training?->batch_number ?: '-' }}</td>

                            <td class="center">
                                @if ($training?->start_date)
                                    {{ \Carbon\Carbon::parse($training->start_date)->format('d-m-Y') }}
                                @else
                                    -
                                @endif

                                @if ($training?->end_date)
                                    <br>s.d.<br>
                                    {{ \Carbon\Carbon::parse($training->end_date)->format('d-m-Y') }}
                                @endif
                            </td>

                            <td class="center">
                                {{ ($duration !== null && $duration !== '') ? $duration . ' hari' : '-' }}
                            </td>

                            <td class="center">{{ $training?->jpl ?: '-' }}</td>

                            <td class="center">{{ $training?->funding_source ?: '-' }}</td>

                            <td class="center">{{ $history->status ? ucfirst($history->status) : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-row">
                                Belum terdapat riwayat pelatihan yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PARAGRAF PENUTUP --}}
        <p class="letter-body">
            Dokumen ini memuat informasi identitas peserta dan riwayat
            keikutsertaan pelatihan berdasarkan data yang tersimpan dalam
            ARTA : Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan.
            Informasi pada dokumen mengikuti data yang tersedia pada saat
            dokumen dicetak.
        </p>

        {{-- FOOTER --}}
        <footer class="footer-document">
            Dokumen ini dicetak dari ARTA : Aplikasi Rekam Riwayat Pelatihan Tenaga Kesehatan
            <br>
            UPTD Balai Pelatihan Kesehatan Provinsi Kalimantan Selatan
            &nbsp;|&nbsp; Dicetak pada {{ $printedAt->format('d-m-Y H:i') }}
        </footer>

    </main>

</body>
</html>