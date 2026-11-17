<?php

namespace App\Support;

/**
 * Mencocokkan teks Kabupaten/Kota dari Excel dengan Master Data wilayah.
 *
 * Yang ditoleransi:
 *  - spasi berlebihan, spasi tak terlihat (non-breaking space, zero-width)
 *  - perbedaan huruf besar/kecil
 *  - singkatan "Kab." / "Kab" untuk "Kabupaten"
 *  - awalan "Kota"/"Kabupaten" yang ada di Excel tetapi tidak ada di Master Data
 *    (atau sebaliknya), selama hasilnya tidak ambigu
 *
 * Yang TIDAK ditoleransi (ditolak, bukan ditebak):
 *  - "Kota Banjar" tidak akan dicocokkan ke "Kabupaten Banjar"
 *  - nama yang cocok dengan lebih dari satu wilayah
 */
class DistrictResolver
{
    public const REASON_EMPTY     = 'empty';
    public const REASON_UNKNOWN   = 'unknown';
    public const REASON_AMBIGUOUS = 'ambiguous';

    /** @var array<string, object> kunci kanonik => model District */
    private array $exact = [];

    /** @var array<string, array<int, array{type: string, district: object}>> */
    private array $byCore = [];

    /**
     * @param iterable<object> $districts Model District aktif (punya properti name).
     */
    public function __construct(iterable $districts)
    {
        foreach ($districts as $district) {
            $key = self::canonical((string) $district->name);

            if ($key === '') {
                continue;
            }

            if (!isset($this->exact[$key])) {
                $this->exact[$key] = $district;
            }

            [$type, $core] = self::split($key);

            $this->byCore[$core][] = [
                'type'     => $type,
                'district' => $district,
            ];
        }
    }

    /**
     * Membersihkan teks tanpa mengubah maknanya.
     */
    public static function clean(mixed $value): string
    {
        $text = (string) $value;

        // non-breaking space -> spasi biasa
        $text = str_replace("\xC2\xA0", ' ', $text);

        // karakter zero-width / BOM
        $text = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text) ?? $text;

        // rapikan spasi
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    /**
     * Bentuk kanonik untuk pembandingan (huruf kecil, tanpa tanda baca,
     * awalan "kab"/"kabupaten" diseragamkan).
     */
    public static function canonical(string $value): string
    {
        $text = mb_strtolower(self::clean($value));

        $text = str_replace(['.', ',', '/', '_', '-'], ' ', $text);
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? $text);

        $text = preg_replace('/^(kabupaten|kab)(\s+|$)/u', 'kabupaten ', $text) ?? $text;

        return trim($text);
    }

    /**
     * Memisahkan awalan jenis wilayah dari nama intinya.
     *
     * @return array{0: string, 1: string} [jenis ('kabupaten'|'kota'|''), nama inti]
     */
    public static function split(string $canonical): array
    {
        if (str_starts_with($canonical, 'kabupaten ')) {
            return ['kabupaten', trim(substr($canonical, 10))];
        }

        if (str_starts_with($canonical, 'kota ')) {
            return ['kota', trim(substr($canonical, 5))];
        }

        return ['', $canonical];
    }

    /**
     * @return array{district: ?object, reason: ?string}
     */
    public function resolve(mixed $raw): array
    {
        $clean = self::clean($raw);

        if ($clean === '') {
            return ['district' => null, 'reason' => self::REASON_EMPTY];
        }

        $key = self::canonical($clean);

        // 1. Cocok persis (setelah normalisasi)
        if (isset($this->exact[$key])) {
            return ['district' => $this->exact[$key], 'reason' => null];
        }

        // 2. Cocok berdasarkan nama inti
        [$type, $core] = self::split($key);

        $candidates = $this->byCore[$core] ?? [];

        if ($type !== '') {
            // Awalan di Excel harus sama dengan awalan di Master Data,
            // atau Master Data memang tidak memakai awalan.
            $candidates = array_values(array_filter(
                $candidates,
                fn (array $c): bool => $c['type'] === $type || $c['type'] === ''
            ));
        }

        if (count($candidates) === 1) {
            return ['district' => $candidates[0]['district'], 'reason' => null];
        }

        if (count($candidates) > 1) {
            return ['district' => null, 'reason' => self::REASON_AMBIGUOUS];
        }

        return ['district' => null, 'reason' => self::REASON_UNKNOWN];
    }
}