<?php

namespace App\Support;

/** Modul Kontrak Kinerja BLU (PER-6/PB/2025) hanya tampil untuk instansi berpola BLU. */
class BluModule
{
    public static function enabled(): bool
    {
        $company = app(BscContext::class)->company();

        return $company === null || $company->isBlu();
    }
}
