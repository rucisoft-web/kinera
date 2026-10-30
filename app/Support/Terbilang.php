<?php

namespace App\Support;

use Carbon\CarbonInterface;

/** Angka & tanggal dengan huruf untuk naskah dinas (mis. "Pada hari ini, Kamis, tanggal tiga puluh ..."). */
class Terbilang
{
    protected const WORDS = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

    public static function number(int $n): string
    {
        return trim(match (true) {
            $n < 12 => self::WORDS[$n],
            $n < 20 => self::number($n - 10).' belas',
            $n < 100 => self::number(intdiv($n, 10)).' puluh '.self::number($n % 10),
            $n < 200 => 'seratus '.self::number($n - 100),
            $n < 1000 => self::number(intdiv($n, 100)).' ratus '.self::number($n % 100),
            $n < 2000 => 'seribu '.self::number($n - 1000),
            $n < 1000000 => self::number(intdiv($n, 1000)).' ribu '.self::number($n % 1000),
            default => (string) $n,
        });
    }

    public static function day(CarbonInterface $d): string
    {
        return ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$d->dayOfWeek];
    }

    /** @return array{hari:string,tanggal:string,bulan:string,tahun:string} */
    public static function date(CarbonInterface $d): array
    {
        return ['hari' => self::day($d), 'tanggal' => self::number($d->day), 'bulan' => Terms::months()[$d->month], 'tahun' => self::number($d->year)];
    }
}
