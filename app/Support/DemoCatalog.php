<?php

namespace App\Support;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

/** Daftar satker BLU contoh (studi kasus) yang dipakai di halaman depan, login, dan Kenali Kinera. */
class DemoCatalog
{
    /** [kode, nama, sektor, pelajaran utama] */
    public const CASES = [
        ['UINSUKA', 'UIN Sunan Kalijaga Yogyakarta', 'BLU pendidikan tinggi (Kementerian Agama)', 'Kontrak Kinerja Pemimpin BLU: PNBP 99,48% & pendapatan kerja sama di bawah 65% menggugurkan syarat insentif; adendum 2025 dan usulan perubahan 2026.'],
        ['UINRF', 'UIN Raden Fatah Palembang', 'BLU pendidikan tinggi (Kementerian Agama)', 'Target Perjanjian Kinerja terlalu rendah (capaian sampai 553%, dibatasi capaian maksimum), PNBP bergantung UKT, dan belanja modal 72,55%; usulan perubahan target akreditasi 2026.'],
    ];

    /** Perioda pembuka sebuah satker BLU: tahun berjalan (aktif) lebih dulu, lalu tahun terbaru. */
    public static function startPeriodId(int $companyId): ?int
    {
        return AnalysisPeriod::where('company_id', $companyId)
            ->orderByDesc('is_active')->orderByDesc('year')->value('id');
    }

    /**
     * Satker BLU contoh yang terpasang, dengan perioda tahun berjalan dan jumlah sasaran/IK/inisiatifnya.
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
