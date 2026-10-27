<?php

namespace App\Support;

class Terms
{
    public const PERSPECTIVES = ['keuangan', 'pelanggan', 'proses', 'sdm'];

    public const PHASES = ['brahman', 'atman', 'karma_phala', 'punarbhawa', 'moksa'];

    public static function get(string $key): string
    {
        $dict = config('bsc.dictionaries.'.config('bsc.terms', 'instansi'), []);

        return $dict[$key] ?? $key;
    }

    /** @return array<string,string> */
    public static function perspectives(): array
    {
        return collect(self::PERSPECTIVES)->mapWithKeys(fn ($p) => [$p => self::get($p)])->all();
    }

    /** @return array<string,string> */
    public static function phases(): array
    {
        return collect(self::PHASES)->mapWithKeys(fn ($p) => [$p => self::get($p)])->all();
    }

    public static function perspective(?string $key): string
    {
        return $key ? self::get($key) : '-';
    }

    public static function perspectiveColor(?string $key): string
    {
        return match ($key) {
            'keuangan' => '#1F4E8C',
            'pelanggan' => '#2A8C9E',
            'proses' => '#5C5AA8',
            'sdm' => '#C88A12',
            default => '#5B6B82',
        };
    }

    public static function months(): array
    {
        return [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    }

    public static function monthShort(int $m): string
    {
        return ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'][$m] ?? (string) $m;
    }
}
