<?php

namespace App\Support;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

/** Daftar instansi contoh (studi kasus) yang dipakai di halaman depan, login, dan Kenali Kinera. */
class DemoCatalog
{
    /** [kode, nama, sektor, pelajaran utama] */
    public const CASES = [
        ['UINSUKA', 'UIN Sunan Kalijaga Yogyakarta', 'PTKIN berpola BLU (Kementerian Agama)', 'Renstra 2025–2029 diturunkan ke peta strategi: 8 Sasaran Program, IKSP/IKSK, 7 strategi transformasi menuju PTNBH & Kampus 2; Kontrak Kinerja BLU dengan PNBP 99,48% dan pendapatan kerja sama di bawah 65%, serta usulan perubahan target 2026.'],
    ];

    /** Perioda pembuka sebuah instansi: tahun berjalan (aktif) lebih dulu, lalu tahun terbaru. */
    public static function startPeriodId(int $companyId): ?int
    {
        return AnalysisPeriod::where('company_id', $companyId)
            ->orderByDesc('is_active')->orderByDesc('year')->value('id');
    }

    /**
     * Instansi contoh yang terpasang, dengan perioda tahun berjalan dan jumlah sasaran/IK/inisiatifnya.
     *
     * @return list<array{code:string,name:string,sector:string,lesson:string,year:?int,objectives:int,kpis:int,initiatives:int}>
     */
    public static function installed(): array
    {
        return app(BscContext::class)->withoutScope(function () {
            $companies = Company::whereIn('code', array_column(self::CASES, 0))->get()->keyBy('code');
            $out = [];
            foreach (self::CASES as [$code, $name, $sector, $lesson]) {
                $company = $companies->get($code);
                if (! $company) {
                    continue;
                }
                $periodId = self::startPeriodId($company->id);
                $count = fn (string $table) => $periodId ? DB::table($table)->where('analysis_period_id', $periodId)->count() : 0;
                $out[] = [
                    'code' => $code, 'name' => $name, 'sector' => $sector, 'lesson' => $lesson,
                    'year' => $periodId ? AnalysisPeriod::whereKey($periodId)->value('year') : null,
                    'objectives' => $count('strategic_objectives'),
                    'kpis' => $count('kpis'),
                    'initiatives' => $count('strategic_initiatives'),
                ];
            }

            return $out;
        });
    }
}
