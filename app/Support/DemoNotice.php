<?php

namespace App\Support;

use App\Models\Company;

/** Peringatan "data demo" untuk satker BLU contoh (is_demo), dipakai di banner panel, laporan cetak, dan file export. */
class DemoNotice
{
    public static function for(?Company $company): ?string
    {
        if (! $company?->is_demo) {
            return null;
        }

        return $company->demo_source
            ? 'DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Angka 2025 sebagian mengacu pada '.$company->demo_source.'; rincian triwulanan, Kontrak Kinerja, sebagian realisasi indikator layanan, dan seluruh data 2026 (proyeksi) adalah simulasi. Bukan data resmi satker BLU tersebut, Pembina Teknis, maupun Kementerian Keuangan.'
            : 'DATA DEMO, hanya untuk keperluan memaksimalkan penyajian fitur-fitur perangkat lunak. Satker BLU dan seluruh angkanya fiktif (rekaan).';
    }

    public static function current(): ?string
    {
        return self::for(app(DhastramContext::class)->company());
    }
}
