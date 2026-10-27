<?php

namespace App\Support;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

/** Membuat kode dokumen per instansi/perioda, mis. OBJ/UINSUKA/2026/001 */
class BscSequence
{
    public static function next(string $prefix, ?int $companyId = null, ?int $periodId = null, ?int $year = null): string
    {
        $ctx = app(BscContext::class);
        $companyId ??= $ctx->companyId();
        $periodId ??= $ctx->periodId();
        $year ??= $periodId ? (int) DB::table('analysis_periods')->where('id', $periodId)->value('year') : (int) now()->year;
        $companyCode = Company::whereKey($companyId)->value('code') ?? 'X';

        $number = DB::transaction(function () use ($prefix, $companyId, $periodId, $year) {
            $row = DB::table('bsc_sequences')->where([
                'company_id' => $companyId, 'analysis_period_id' => $periodId, 'prefix' => $prefix, 'year' => $year,
            ])->lockForUpdate()->first();
            if (! $row) {
                DB::table('bsc_sequences')->insert([
                    'company_id' => $companyId, 'analysis_period_id' => $periodId, 'prefix' => $prefix, 'year' => $year,
                    'last_number' => 1, 'created_at' => now(), 'updated_at' => now(),
                ]);

                return 1;
            }
            DB::table('bsc_sequences')->where('id', $row->id)->update(['last_number' => $row->last_number + 1, 'updated_at' => now()]);

            return $row->last_number + 1;
        });

        return sprintf('%s/%s/%d/%03d', $prefix, $companyCode, $year, $number);
    }
}
