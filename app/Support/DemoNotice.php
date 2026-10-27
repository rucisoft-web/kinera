<?php

namespace App\Support;

use App\Models\Company;

/** Peringatan "data demo" untuk perusahaan contoh (is_demo), dipakai di banner panel, laporan cetak, dan file export. */
class DemoNotice
{
    public static function for(?Company $company): ?string
    {
        if (! $company?->is_demo) {
            return null;
        }

        return $company->demo_source
            ? 'DATA DEMO untuk pembelajaran. Angka 2025 secara garis besar mengacu pada '.$company->demo_source.'; target, rincian bulanan, data 2026, dan sebagian indikator adalah simulasi. Bukan data resmi perusahaan dan tidak untuk keputusan investasi.'
            : 'DATA DEMO untuk pembelajaran. Perusahaan dan seluruh angkanya fiktif (rekaan).';
    }

    public static function current(): ?string
    {
        return self::for(app(DhastramContext::class)->company());
    }
}
