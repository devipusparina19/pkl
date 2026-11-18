<?php

namespace App\Exports;

/**
 * Pendukung bersama untuk ekspor Excel dan PDF laporan riwayat pelatihan.
 *  - Palet warna pie satu sumber (Excel dan PDF memakai konstanta yang sama)
 *  - Penyesuaian payload: hapus kolom "Keikutsertaan" pada tabel
 *    "Rincian Sumber Dana" dan ubah judul pie.
 * Data, perhitungan, dan fungsi lain tidak diubah.
 */
class ReportExportSupport
{
    /**
     * Warna irisan pie (urutan = urutan baris tabel), kontras tinggi:
     * biru, merah, hijau, oranye, ungu, cyan, abu gelap, pink.
     */
    public const PALETTE = [
        '1F4FD8', // biru
        'E02424', // merah
        '16A34A', // hijau
        'F59E0B', // oranye
        '7C3AED', // ungu
        '06B6D4', // cyan
        '374151', // abu gelap
        'EC4899', // pink
    ];

    public const PIE_TITLE_OLD = 'Keikutsertaan Berdasarkan Sumber Dana';
    public const PIE_TITLE_NEW = 'Pelatihan Berdasarkan Sumber Dana';

    public const TABLE_KEY = 'Rincian Sumber Dana';
    public const COLUMN_REMOVED = 'Keikutsertaan';

    public static function normalize(array $report): array
    {
        foreach (['tables', 'details'] as $key) {
            foreach (($report[$key] ?? []) as $i => $table) {
                if (is_array($table)) {
                    $report[$key][$i] = self::normalizeTable($table);
                }
            }
        }

        return $report;
    }

    private static function normalizeTable(array $t): array
    {
        // Judul pie
        if (!empty($t['pie']['title'])) {
            $t['pie']['title'] = str_ireplace(self::PIE_TITLE_OLD, self::PIE_TITLE_NEW, (string) $t['pie']['title']);
        }

        // Hapus kolom "Keikutsertaan" hanya pada tabel "Rincian Sumber Dana"
        if (
            empty($t['title'])
            || stripos((string) $t['title'], self::TABLE_KEY) === false
            || empty($t['columns'])
        ) {
            return $t;
        }

        $remove = [];
        foreach ($t['columns'] as $i => $col) {
            if (strcasecmp(trim((string) ($col['label'] ?? '')), self::COLUMN_REMOVED) === 0) {
                $remove[] = $i;
            }
        }

        if (!$remove) {
            return $t;
        }

        // Peta indeks lama -> indeks baru
        $map = [];
        $new = 0;
        foreach (array_keys($t['columns']) as $i) {
            if (!in_array($i, $remove, true)) {
                $map[$i] = $new++;
            }
        }

        $drop = array_flip($remove);

        $t['columns'] = array_values(array_diff_key($t['columns'], $drop));

        foreach (($t['rows'] ?? []) as $r => $row) {
            if (isset($row['cells']) && is_array($row['cells'])) {
                $t['rows'][$r]['cells'] = array_values(array_diff_key($row['cells'], $drop));
            }
        }

        if (!empty($t['total']['cells']) && is_array($t['total']['cells'])) {
            $t['total']['cells'] = array_values(array_diff_key($t['total']['cells'], $drop));
        }

        // Sesuaikan indeks kolom yang dipakai diagram pie
        if (!empty($t['pie'])) {
            $label = $t['pie']['label'] ?? null;
            $value = $t['pie']['value'] ?? null;

            $t['pie']['label'] = $map[$label] ?? 0;

            if (isset($map[$value])) {
                $t['pie']['value'] = $map[$value];
            } else {
                // Cadangan: kolom nilai pie ikut terhapus -> pakai kolom angka pertama yang tersisa
                $fallback = null;
                foreach ($t['columns'] as $i => $col) {
                    if ($i !== $t['pie']['label'] && str_contains((string) ($col['format'] ?? ''), '#,##0')) {
                        $fallback = $i;
                        break;
                    }
                }
                $t['pie']['value'] = $fallback ?? 1;
            }
        }

        return $t;
    }
}