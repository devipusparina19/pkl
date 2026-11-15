@php
    $b   = 'border:1px solid #000000;';
    $th  = $b . 'font-weight:bold;text-align:center;vertical-align:middle;color:#000000;background-color:#D9E2F3;';
    $td  = $b . 'vertical-align:middle;color:#000000;';
    $tc  = $td . 'text-align:center;';
    $sec = 'font-weight:bold;font-size:12px;border-bottom:2px solid #000000;color:#000000;';
    $txt = 'color:#000000;';
    $labelSpan = $cols - 3;
    $hasGender = $genderTotal > 0;
    $pct = fn ($v, $t) => number_format($t > 0 ? $v / $t * 100 : 0, 1, ',', '.') . '%';
    $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];
    $n = 0;
    $letter = 'A';
@endphp

<table>
    {{-- Baris 1-3: judul --}}
    <tr><td colspan="{{ $cols }}" style="font-weight:bold;font-size:14px;text-align:center;text-decoration:underline;color:#000000;">{{ mb_strtoupper($meta['title'] ?? 'LAPORAN RIWAYAT PELATIHAN') }}</td></tr>
    <tr><td colspan="{{ $cols }}" style="font-weight:bold;font-size:11px;text-align:center;color:#000000;">TAHUN ANGGARAN {{ $meta['year'] ?? '-' }}</td></tr>
    <tr><td colspan="{{ $cols }}"></td></tr>

    {{-- Baris 4-10: informasi & filter aktif (7 baris, jangan diubah jumlahnya) --}}
    @foreach ($info as $line)
        <tr>
            <td colspan="2" style="{{ $txt }}">{{ $line[0] }}</td>
            <td colspan="{{ $cols - 2 }}" style="{{ $txt }}">: {{ $line[1] }}</td>
        </tr>
    @endforeach
    <tr><td colspan="{{ $cols }}"></td></tr>

    {{-- Baris 12-14: ringkasan (3 angka hero) --}}
    <tr><td colspan="{{ $cols }}" style="{{ $sec }}">{{ $romans[$n++] }}. RINGKASAN</td></tr>
    <tr>
        @foreach ($boxes as $bx)
            <td colspan="{{ $bx['span'] }}" style="{{ $th }}">{{ $bx['label'] }}</td>
        @endforeach
    </tr>
    <tr>
        @foreach ($boxes as $bx)
            <td colspan="{{ $bx['span'] }}" style="{{ $th }}font-size:18px;height:32px;">{{ $bx['value'] }}</td>
        @endforeach
    </tr>
    <tr><td colspan="{{ $cols }}"></td></tr>

    {{-- Baris 16: judul diagram, baris 17-32: ruang diagram pie --}}
    @if ($hasGender)
        <tr><td colspan="{{ $cols }}" style="{{ $sec }}">{{ $romans[$n++] }}. DIAGRAM JENIS KELAMIN PESERTA</td></tr>
        @for ($k = 0; $k < 16; $k++)
            <tr><td colspan="{{ $cols }}"></td></tr>
        @endfor
    @endif

    {{-- Baris 33: rekapitulasi --}}
    <tr><td colspan="{{ $cols }}" style="{{ $sec }}">{{ $romans[$n++] }}. REKAPITULASI</td></tr>

    {{-- Tabel jenis kelamin: judul baris 34, header baris 35, data mulai baris 36 --}}
    @if ($hasGender)
        <tr><td colspan="{{ $cols }}" style="font-weight:bold;color:#000000;">{{ $letter }}. JENIS KELAMIN PESERTA</td></tr>
        <tr>
            <td style="{{ $th }}">No</td>
            <td colspan="{{ $labelSpan }}" style="{{ $th }}">Jenis Kelamin</td>
            <td style="{{ $th }}">Peserta</td>
            <td style="{{ $th }}">Persentase</td>
        </tr>
        @foreach ($genderRecap as $i => $g)
            <tr>
                <td style="{{ $tc }}">{{ $i + 1 }}</td>
                <td colspan="{{ $labelSpan }}" style="{{ $td }}">{{ $g['label'] }}</td>
                <td style="{{ $tc }}">{{ $g['value'] }}</td>
                <td style="{{ $tc }}">{{ $pct($g['value'], $genderTotal) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="{{ $labelSpan + 1 }}" style="{{ $th }}">TOTAL</td>
            <td style="{{ $th }}">{{ $genderTotal }}</td>
            <td style="{{ $th }}">100,0%</td>
        </tr>
        <tr><td colspan="{{ $cols }}"></td></tr>
        @php $letter = 'B'; @endphp
    @endif

    {{-- Profesi, sumber dana, per pelatihan, kategori, wilayah x dana, daftar peserta --}}
    @foreach ($tables as $t)
        <tr><td colspan="{{ $cols }}" style="font-weight:bold;color:#000000;">{{ $letter }}. {{ $t['title'] }}</td></tr>
        <tr>
            @foreach ($t['columns'] as $c)
                <td colspan="{{ $c['span'] }}" style="{{ $th }}">{{ $c['label'] }}</td>
            @endforeach
        </tr>

        @forelse ($t['rows'] as $r)
            <tr>
                @foreach ($t['columns'] as $ci => $c)
                    @php
                        $v = $r['cells'][$ci] ?? '';
                        $v = ($v === null || $v === '') ? '-' : $v;
                        $style = (($c['align'] ?? 'c') === 'l' ? $td : $tc)
                            . (!empty($r['bold']) ? 'font-weight:bold;background-color:#EAF2FF;' : '');
                    @endphp
                    <td colspan="{{ $c['span'] }}" style="{{ $style }}">{{ $v }}</td>
                @endforeach
            </tr>
        @empty
            <tr><td colspan="{{ $cols }}" style="{{ $td }}">{{ $t['empty'] ?? 'Tidak ada data.' }}</td></tr>
        @endforelse

        <tr><td colspan="{{ $cols }}"></td></tr>
        @php $letter = chr(ord($letter) + 1); @endphp
    @endforeach

    {{-- Data lengkap riwayat: satu baris per peserta per kegiatan --}}
    <tr><td colspan="{{ $cols }}" style="{{ $sec }}">{{ $romans[$n++] }}. DATA LENGKAP (PESERTA PER KEGIATAN)</td></tr>
    <tr>
        <td style="{{ $th }}">No</td>
        @foreach ($headings as $h)
            <td style="{{ $th }}">{{ $h }}</td>
        @endforeach
    </tr>
    @forelse ($rows as $i => $row)
        <tr>
            <td style="{{ $tc }}">{{ $i + 1 }}</td>
            @foreach ($headings as $c => $h)
                @php $v = $row[$c] ?? ''; $v = ($v === null || $v === '') ? '-' : $v; @endphp
                <td style="{{ preg_match('/\b(nik|nip|mulai)\b/i', (string) $h) ? $tc : $td }}">{{ $v }}</td>
            @endforeach
        </tr>
    @empty
        <tr><td colspan="{{ count($headings) + 1 }}" style="{{ $th }}">Tidak terdapat data yang sesuai dengan filter yang dipilih.</td></tr>
    @endforelse

    <tr><td colspan="{{ $cols }}"></td></tr>
    <tr><td colspan="{{ $cols }}" style="font-style:italic;font-size:9px;color:#000000;">Dokumen ini dihasilkan oleh {{ $sistem }}, UPTD Balai Pelatihan Kesehatan Provinsi Kalimantan Selatan, berdasarkan data yang tersimpan pada saat dokumen diekspor.</td></tr>
</table>