
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi SIRAPIK</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 25px 30px 35px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #243247;
            line-height: 1.45;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header h1 {
            font-size: 15px;
            margin: 0 0 4px;
            color: #17365d;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 11px;
            margin: 0 0 4px;
            font-weight: normal;
        }

        .header p {
            margin: 0;
            font-size: 8px;
        }

        .report-title {
            text-align: center;
            margin: 16px 0;
        }

        .report-title h3 {
            font-size: 13px;
            margin: 0 0 4px;
            text-transform: uppercase;
        }

        .muted {
            color: #64748b;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #17365d;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin: 18px 0 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #d7dee8;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #eaf0f7;
            color: #17365d;
            font-weight: bold;
            text-align: left;
        }

        .summary td {
            width: 25%;
            text-align: center;
            padding: 10px 5px;
            background: #f5f8fc;
        }

        .summary strong {
            display: block;
            font-size: 15px;
            color: #17365d;
        }

        .summary span {
            font-size: 8px;
            color: #64748b;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-border td {
            border: none;
            padding: 2px 0;
        }

        .footer {
            margin-top: 18px;
            font-size: 8px;
            color: #64748b;
            text-align: right;
        }

        .signature {
            width: 45%;
            margin-left: auto;
            margin-top: 25px;
            text-align: center;
        }

        .signature .space {
            height: 48px;
        }

        .page-break {
            page-break-before: always;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Bapelkes Kalimantan Selatan</h1>
        <h2>Sistem Informasi Riwayat Pelatihan (SIRAPIK)</h2>
        <p>Laporan Rekapitulasi Data Peserta dan Riwayat Pelatihan</p>
    </div>

    <div class="report-title">
        <h3>Laporan Rekapitulasi Peserta</h3>
        <div class="muted">
            Tahun Laporan: {{ $year ?? 'Semua Tahun' }}
        </div>
        @if(!empty($search))
            <div class="muted">Kata kunci pencarian: {{ $search }}</div>
        @endif
    </div>

    <div class="section-title">Ringkasan Data</div>

    <table class="summary">
        <tr>
            <td>
                <strong>{{ number_format($totalParticipants ?? 0, 0, ',', '.') }}</strong>
                <span>Total Peserta</span>
            </td>
            <td>
                <strong>{{ number_format($totalTrainings ?? 0, 0, ',', '.') }}</strong>
                <span>Total Pelatihan</span>
            </td>
            <td>
                <strong>{{ number_format($totalParticipations ?? 0, 0, ',', '.') }}</strong>
                <span>Total Keikutsertaan</span>
            </td>
            <td>
                <strong>{{ number_format(($maleCount ?? 0) + ($femaleCount ?? 0), 0, ',', '.') }}</strong>
                <span>Total Berdasarkan Jenis Kelamin</span>
            </td>
        </tr>
    </table>

    <div class="section-title">Rekapitulasi Jenis Kelamin</div>

    <table>
        <thead>
            <tr>
                <th style="width: 60%">Jenis Kelamin</th>
                <th class="text-center">Jumlah Peserta</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Laki-laki</td>
                <td class="text-center">
                    {{ number_format($maleCount ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Perempuan</td>
                <td class="text-center">
                    {{ number_format($femaleCount ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">
        Rekap Peserta Berdasarkan Kabupaten/Kota/Mitra/Mandiri
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%" class="text-center">No.</th>
                <th>Wilayah Asal/Wilayah Kerja Peserta</th>
                <th style="width: 25%" class="text-center">Jumlah Peserta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($districtRecap ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ data_get($item, 'district', data_get($item, 'name', '-')) }}
                    </td>
                    <td class="text-center">
                        {{ number_format(data_get($item, 'total', data_get($item, 'total_participants', 0)), 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center muted">
                        Belum ada data rekapitulasi wilayah peserta.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Rekapitulasi Berdasarkan Profesi</div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%" class="text-center">No.</th>
                <th>Profesi</th>
                <th style="width: 25%" class="text-center">Jumlah Peserta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($professionRecap ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ data_get($item, 'profession', data_get($item, 'name', '-')) }}
                    </td>
                    <td class="text-center">
                        {{ number_format(data_get($item, 'total', data_get($item, 'total_participants', 0)), 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center muted">
                        Belum ada data rekapitulasi profesi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Daftar Peserta dan Riwayat Keikutsertaan</div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">No.</th>
                <th>Nama Peserta</th>
                <th style="width: 15%">NIP</th>
                <th style="width: 15%">NIK</th>
                <th>Profesi</th>
                <th>Wilayah Peserta</th>
                <th style="width: 10%" class="text-center">Keikutsertaan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants ?? [] as $index => $participant)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $participant->name ?? '-' }}</td>
                    <td>{{ $participant->nip ?: '-' }}</td>
                    <td>{{ $participant->nik ?: '-' }}</td>
                    <td>{{ $participant->profession ?: '-' }}</td>
                    <td>{{ $participant->district ?: '-' }}</td>
                    <td class="text-center">
                        {{ number_format($participant->participations_count ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center muted">
                        Tidak ada data peserta untuk ditampilkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada:
        {{ isset($printedAt) ? $printedAt->format('d-m-Y H:i:s') : now()->format('d-m-Y H:i:s') }}
    </div>

    <div class="signature">
        <div>Banjarmasin, {{ isset($printedAt) ? $printedAt->format('d F Y') : now()->format('d F Y') }}</div>
        <div>Mengetahui,</div>
        <div class="space"></div>
        <strong>____________________________</strong>
        <div>Pejabat yang berwenang</div>
    </div>

</body>
</html>
