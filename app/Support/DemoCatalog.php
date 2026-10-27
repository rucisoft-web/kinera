<?php

namespace App\Support;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

/** Daftar perusahaan contoh (studi kasus) yang dipakai di halaman depan, login, dan Kenali Ruci BSC. */
class DemoCatalog
{
    /** [kode, nama, sektor, pelajaran utama] */
    public const CASES = [
        ['DFI', 'Diamond Food Indonesia', 'Makanan & minuman', 'Pertumbuhan merek, distribusi, dan efisiensi rantai dingin.'],
        ['HMN', 'Medikaloka Hermina', 'Jaringan rumah sakit', 'Ekspansi kapasitas vs. utilisasi, mutu klinis, dan kepatuhan KRIS.'],
        ['MEDC', 'Medco Energi Internasional', 'Energi & sumber daya alam', 'Nilai, fokus, pertumbuhan: biaya rendah, deleveraging, akuisisi, dan transisi energi.'],
        ['PWON', 'Pakuwon Jati', 'Properti & superblok', 'Pendapatan berulang ±79% vs. siklus residensial dan perkantoran yang melemah.'],
        ['KAEF', 'Kimia Farma', 'Farmasi terintegrasi (BUMN)', 'Strategi pemulihan: rugi menyempit, restrukturisasi utang, likuiditas dan utilisasi pabrik.'],
        ['SMGR', 'Semen Indonesia', 'Semen & bahan bangunan (BUMN)', 'Kelebihan pasokan: margin di bawah tekanan, efisiensi energi, dekarbonisasi, dan ekspor.'],
        ['MAPI', 'Mitra Adiperkasa', 'Ritel gaya hidup', 'Ekspansi gerai vs. produktivitas, persediaan, omnichannel, dan unit F&B yang merugi.'],
        ['GOTO', 'GoTo Gojek Tokopedia', 'Ekosistem digital', 'Menuju laba berkelanjutan: EBITDA disesuaikan, fintech, keandalan layanan.'],
        ['ASII', 'Astra International', 'Konglomerasi multisektor', 'Portofolio tujuh segmen: otomotif, jasa keuangan, alat berat, agribisnis, dan alokasi modal.'],
        ['BBCA', 'Bank Central Asia', 'Perbankan', 'Franchise dana murah (CASA), kualitas kredit, digital banking, dan risiko siber.'],
        ['TLKM', 'Telkom Indonesia', 'Telekomunikasi (BUMN)', 'Transformasi digital: seluler, IndiHome/FMC, data center, dan efisiensi biaya.'],
        ['BIRD', 'Blue Bird', 'Transportasi darat', 'Pemulihan armada, persaingan aplikasi, elektrifikasi armada, dan keselamatan.'],
        ['BTPN', 'SMBC Indonesia', 'Perbankan & konglomerasi keuangan', 'Pendapatan naik, laba anjlok karena biaya kredit anak usaha: strategi pemulihan kualitas aset.'],
        ['BMTP', 'Bank Mandiri Taspen', 'Perbankan pensiunan & UMKM', 'Bank sangat sehat yang bergantung pada satu segmen: diversifikasi yang hati-hati dan dana murah.'],
    ];

    /** Perioda pembuka sebuah perusahaan: tahun berjalan (aktif) lebih dulu, lalu tahun terbaru. */
    public static function startPeriodId(int $companyId): ?int
    {
        return AnalysisPeriod::where('company_id', $companyId)
            ->orderByDesc('is_active')->orderByDesc('year')->value('id');
    }

    /**
     * Perusahaan contoh yang terpasang, dengan perioda tahun berjalan dan jumlah sasaran/KPI/inisiatifnya.
     *
     * @return list<array{code:string,name:string,sector:string,lesson:string,year:?int,objectives:int,kpis:int,initiatives:int}>
     */
    public static function installed(): array
    {
        return app(DhastramContext::class)->withoutScope(function () {
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
