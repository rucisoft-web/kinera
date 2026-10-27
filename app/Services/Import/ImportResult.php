<?php

namespace App\Services\Import;

use App\Models\DataImport;

/** Hasil pratinjau atau proses import: ringkasan per sheet dan daftar masalah per baris. */
class ImportResult
{
    /** @var array<string,array{dibuat:int,diubah:int,sama:int,gagal:int}> */
    public array $summary = [];

    /** @var array<int,array{sheet:string,baris:?int,level:string,pesan:string}> */
    public array $issues = [];

    public ?DataImport $import = null;

    public function __construct(public bool $dryRun) {}

    public function count(string $sheet, string $what): void
    {
        $this->summary[$sheet] ??= ['dibuat' => 0, 'diubah' => 0, 'sama' => 0, 'gagal' => 0];
        $this->summary[$sheet][$what]++;
    }

    public function error(string $sheet, ?int $row, string $message): void
    {
        $this->issues[] = ['sheet' => $sheet, 'baris' => $row, 'level' => 'error', 'pesan' => $message];
    }

    public function warning(string $sheet, ?int $row, string $message): void
    {
        $this->issues[] = ['sheet' => $sheet, 'baris' => $row, 'level' => 'peringatan', 'pesan' => $message];
    }

    public function rowsOk(): int
    {
        return array_sum(array_map(fn ($s) => $s['dibuat'] + $s['diubah'] + $s['sama'], $this->summary));
    }

    public function rowsFailed(): int
    {
        return array_sum(array_column($this->summary, 'gagal'));
    }

    public function errorCount(): int
    {
        return count(array_filter($this->issues, fn ($i) => $i['level'] === 'error'));
    }
}
