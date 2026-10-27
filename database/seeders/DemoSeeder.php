<?php

namespace Database\Seeders;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\UserContext;
use App\Services\Import\BscImporter;
use App\Services\Import\BscTemplate;
use App\Support\DhastramContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/**
 * Perusahaan contoh untuk demo & pelatihan (is_demo = true, hapus dengan `php artisan rucibsc:hapus-contoh`).
 *
 * - DFI: PT Diamond Food Indonesia Tbk — demo lengkap 2025 + 2026, lihat DiamondFoodSeeder.
 * - HMN: PT Medikaloka Hermina Tbk — demo lengkap jaringan rumah sakit 2025 + 2026, lihat HerminaSeeder.
 * - MEDC, PWON, KAEF: Medco Energi, Pakuwon Jati, Kimia Farma — demo lengkap 2025 + 2026 (energi, properti, farmasi).
 * - SMGR, MAPI, GOTO, ASII, BBCA, TLKM, BIRD: Semen Indonesia, MAP, GoTo, Astra, BCA, Telkom, Blue Bird — demo lengkap 2025 + 2026.
 * - BTPN: PT Bank SMBC Indonesia Tbk — demo lengkap 2025 + 2026 (bank universal & induk konglomerasi).
 * - BMTP: PT Bank Mandiri Taspen — demo lengkap 2025 + 2026 (bank pensiunan; target dari RBB).
 * - Semua demo bertanda is_demo dan menampilkan peringatan "data demo" (App\Support\DemoNotice).
 * - NLP & KSB: perusahaan fiktif dengan data bulanan Jan–Sep 2026, dimasukkan lewat BscImporter
 *   (jalur yang sama dengan upload Excel), sehingga seeder ini sekaligus menguji format workbook.
 */
class DemoSeeder extends Seeder
{
    public const PASSWORD = 'Contoh-2026!';

    public const ADMIN_EMAIL = 'admin@rucibsc.local';

    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => self::ADMIN_EMAIL], [
            'name' => 'Super Admin Rucisoft', 'job_title' => 'Konsultan Rucisoft', 'department' => 'Rucisoft', 'is_active' => true,
            'password' => Hash::make(config('dhastram.demo_admin_password')), 'must_change_password' => false, 'email_verified_at' => now(),
        ]);
        $this->assign($admin, 'super-admin', null);

        (new DiamondFoodSeeder(self::PASSWORD))->run();
        (new HerminaSeeder(self::PASSWORD))->run();
        (new MedcoSeeder(self::PASSWORD))->run();
        (new PakuwonSeeder(self::PASSWORD))->run();
        (new KimiaFarmaSeeder(self::PASSWORD))->run();
        (new SemenIndonesiaSeeder(self::PASSWORD))->run();
        (new MitraAdiperkasaSeeder(self::PASSWORD))->run();
        (new GotoSeeder(self::PASSWORD))->run();
        (new AstraSeeder(self::PASSWORD))->run();
        (new BcaSeeder(self::PASSWORD))->run();
        (new TelkomSeeder(self::PASSWORD))->run();
        (new BlueBirdSeeder(self::PASSWORD))->run();
        (new SmbcIndonesiaSeeder(self::PASSWORD))->run();
        (new MandiriTaspenSeeder(self::PASSWORD))->run();

        foreach ([$this->logistik(), $this->klinik()] as $def) {
            $company = Company::updateOrCreate(['code' => $def['company']['code']], $def['company'] + ['is_active' => true, 'is_demo' => true]);
            foreach ($def['periods'] as $p) {
                AnalysisPeriod::updateOrCreate(['company_id' => $company->id, 'code' => (string) $p['year']], $p + [
                    'name' => 'Tahun '.$p['year'], 'start_date' => $p['year'].'-01-01', 'end_date' => $p['year'].'-12-31',
                ]);
            }
            $period = AnalysisPeriod::where('company_id', $company->id)->where('is_active', true)->firstOrFail();
            foreach ($def['users'] as [$name, $email, $title, $dept, $roles]) {
                $user = User::updateOrCreate(['email' => $email], [
                    'name' => $name, 'job_title' => $title, 'department' => $dept, 'is_active' => true,
                    'password' => Hash::make(self::PASSWORD), 'must_change_password' => false, 'email_verified_at' => now(),
                ]);
                $user->companies()->syncWithoutDetaching([$company->id]);
                foreach ($roles as $slug) {
                    $this->assign($user, $slug, $company->id);
                }
                UserContext::updateOrCreate(['user_id' => $user->id], ['company_id' => $company->id, 'analysis_period_id' => $period->id]);
            }
            foreach ($def['imports'] as $i => [$fileName, $sheets, $approve]) {
                $this->import($company, $period, $admin, $fileName, $sheets, $approve);
            }
        }
        UserContext::updateOrCreate(['user_id' => $admin->id], [
            'company_id' => Company::where('code', 'DFI')->value('id'),
            'analysis_period_id' => AnalysisPeriod::whereHas('company', fn ($q) => $q->where('code', 'DFI'))->where('is_active', true)->value('id'),
        ]);
        app(DhastramContext::class)->clear();
    }

    protected function assign(User $user, string $slug, ?int $companyId): void
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        if (! $user->roles()->where('roles.id', $role->id)->wherePivot('company_id', $companyId)->exists()) {
            $user->roles()->attach($role->id, ['company_id' => $companyId]);
        }
    }

    protected function import(Company $company, AnalysisPeriod $period, User $admin, string $fileName, array $sheets, bool $approve): void
    {
        $file = storage_path('app/private/exports/seed-'.$company->code.'-'.uniqid().'.xlsx');
        app(BscTemplate::class)->withRows($file, $sheets);
        try {
            $result = app(BscImporter::class)->run($file, $period, $admin, ['approve_actuals' => $approve, 'file_name' => $fileName]);
        } finally {
            @unlink($file);
        }
        if ($result->errorCount() > 0) {
            throw new RuntimeException('Data contoh '.$company->code.' gagal diimport: '.json_encode($result->issues, JSON_UNESCAPED_UNICODE));
        }
    }

    // ------------------------------------------------------------------ perusahaan fiktif

    /** Deret bulanan deterministik: nilai dasar × (1 + tren × bulan) ± variasi. */
    protected function series(float $base, float $trend, float $noise, int $months, int $decimals, int $seed): array
    {
        mt_srand($seed);
        $out = [];
        for ($m = 1; $m <= $months; $m++) {
            $out[] = round($base * (1 + $trend * $m) * (1 + (mt_rand(-1000, 1000) / 1000) * $noise), $decimals);
        }

        return $out;
    }

    /** @param array<int,array{0:string,1:string,2:string,3:string,4:string,5:string,6:int,7:float,8:float,9:float,10:float,11:string}> $kpis */
    protected function fictive(array $kpis, string $prefixEmail): array
    {
        $kpiRows = $targets = $draft = $approved = [];
        foreach ($kpis as $n => [$code, $obj, $name, $unit, $dir, $agg, $dec, $target, $actual, $trend, $noise, $owner]) {
            $kpiRows[] = [$code, $obj, $name, $unit, $dir, 'bulanan', $agg, 1, null, null, $dec, $obj[0] === 'K' ? 'ya' : 'tidak', 'tidak', null, $prefixEmail.$owner.'@contoh.local', null];
            $targets[] = array_merge([$code], $this->series($target, $trend, 0, 12, $dec, 1), [null]);
            $values = $this->series($actual, $trend, $noise, 9, $dec, 100 + $n);
            $approved[] = array_merge([$code], array_slice($values, 0, 8), array_fill(0, 4, null), [null, 'Sistem operasional (data fiktif)']);
            $draft[] = array_merge([$code], array_fill(0, 8, null), [$values[8]], array_fill(0, 3, null), ['Menunggu verifikasi', 'Sistem operasional (data fiktif)']);
        }

        return [$kpiRows, $targets, $approved, $draft];
    }

    protected function logistik(): array
    {
        // kode, sasaran, nama, satuan, polaritas, agregasi, desimal, target dasar, realisasi dasar, tren/bulan, variasi, pemilik
        [$kpis, $targets, $approved, $draft] = $this->fictive([
            ['K1.1', 'K1', 'Pendapatan jasa logistik', 'Rp juta', 'naik', 'jumlah', 0, 4000, 3900, 0.01, 0.06, 'keuangan'],
            ['K2.1', 'K2', 'Rasio biaya operasional terhadap pendapatan', '%', 'turun', 'rata-rata', 1, 82, 84, 0, 0.03, 'keuangan'],
            ['C1.1', 'C1', 'Pengiriman tepat waktu (OTD)', '%', 'naik', 'rata-rata', 1, 95, 93, 0.001, 0.02, 'operasi'],
            ['C1.2', 'C1', 'Keluhan pelanggan', 'kasus', 'turun', 'jumlah', 0, 20, 23, -0.01, 0.2, 'operasi'],
            ['P1.1', 'P1', 'Utilisasi armada', '%', 'naik', 'rata-rata', 1, 85, 81, 0.002, 0.04, 'operasi'],
            ['P1.2', 'P1', 'Barang rusak dalam pengiriman', '%', 'turun', 'rata-rata', 2, 0.5, 0.55, 0, 0.2, 'operasi'],
            ['L1.1', 'L1', 'Jam pelatihan per karyawan', 'jam', 'naik', 'jumlah', 1, 2, 1.8, 0, 0.25, 'sdm'],
            ['L1.2', 'L1', 'Turnover karyawan', '%', 'turun', 'rata-rata', 2, 1.5, 1.6, 0, 0.2, 'sdm'],
        ], 'nlp.');

        return [
            'company' => ['code' => 'NLP', 'name' => 'PT Nusantara Logistik Prima (fiktif)', 'industry' => 'Logistik & ekspedisi', 'city' => 'Surabaya',
                'description' => 'Perusahaan fiktif untuk demo multi-perusahaan. Semua angka rekaan.'],
            'periods' => [
                ['code' => '2026', 'year' => 2026, 'status' => 'berjalan', 'is_active' => true, 'notes' => 'Realisasi Jan–Agu disetujui, Sep masih draf (data fiktif).'],
                ['code' => '2027', 'year' => 2027, 'status' => 'perencanaan', 'is_active' => false, 'notes' => 'Disiapkan untuk perencanaan.'],
            ],
            'users' => [
                ['Admin NLP (fiktif)', 'nlp.admin@contoh.local', 'Admin Sistem', 'IT', ['company-admin']],
                ['Direktur NLP (fiktif)', 'nlp.direktur@contoh.local', 'Direktur', 'Direksi', ['pimpinan']],
                ['Manajer Keuangan NLP (fiktif)', 'nlp.keuangan@contoh.local', 'Manajer Keuangan', 'Keuangan', ['keuangan']],
                ['Manajer Operasi NLP (fiktif)', 'nlp.operasi@contoh.local', 'Manajer Operasi', 'Operasi', ['kepala-unit']],
                ['Manajer SDM NLP (fiktif)', 'nlp.sdm@contoh.local', 'Manajer SDM', 'SDM', ['kepala-unit']],
            ],
            'imports' => [
                ['nlp-struktur-dan-realisasi-jan-agu-2026.xlsx', [
                    'Jati Diri' => [['visi', 'Menjadi mitra logistik paling andal di Indonesia Timur.'], ['misi', 'Mengirim tepat waktu, aman, dan efisien bagi setiap pelanggan.']],
                    'Nilai Inti' => [['Andal', null, 'Menepati janji pengiriman.', null], ['Aman', null, 'Keselamatan orang dan barang nomor satu.', null]],
                    'Sasaran' => [
                        ['K1', 'Keuangan', 'Menumbuhkan pendapatan', null, null, 2, 'nlp.keuangan@contoh.local'],
                        ['K2', 'Keuangan', 'Mengendalikan biaya operasional', null, null, 1, 'nlp.keuangan@contoh.local'],
                        ['C1', 'Kepuasan Pelanggan', 'Layanan pengiriman yang andal', null, null, 1, 'nlp.operasi@contoh.local'],
                        ['P1', 'Proses Internal', 'Operasi armada yang efisien & aman', null, null, 1, 'nlp.operasi@contoh.local'],
                        ['L1', 'SDM & Pembelajaran', 'SDM yang kompeten dan bertahan', null, null, 1, 'nlp.sdm@contoh.local'],
                    ],
                    'Relasi Sasaran' => [['L1', 'P1', 2, null], ['P1', 'C1', 3, null], ['C1', 'K1', 3, null], ['P1', 'K2', 2, null]],
                    'KPI' => $kpis, 'Target' => $targets, 'Realisasi' => $approved,
                    'Inisiatif' => [
                        ['NLP-I1', 'Sistem pelacakan armada real-time', 'C1, P1', null, 'nlp.operasi@contoh.local', '2026-02-01', '2026-11-30', 'berjalan', 5, 4, 3, 4, 850000000, null, 'OTD ≥ 95%'],
                        ['NLP-I2', 'Akademi pengemudi', 'L1', null, 'nlp.sdm@contoh.local', '2026-03-01', '2026-12-31', 'berjalan', 3, 3, 2, 2, 150000000, null, 'Turnover < 1,5%'],
                    ],
                ], true],
                ['nlp-realisasi-sep-2026.xlsx', ['Realisasi' => $draft], false],
            ],
        ];
    }

    protected function klinik(): array
    {
        [$kpis, $targets, $approved, $draft] = $this->fictive([
            ['K1.1', 'K1', 'Pendapatan layanan', 'Rp juta', 'naik', 'jumlah', 0, 1500, 1520, 0.008, 0.05, 'keuangan'],
            ['K1.2', 'K1', 'Margin operasi', '%', 'naik', 'rata-rata', 1, 18, 16.5, 0, 0.08, 'keuangan'],
            ['C1.1', 'C1', 'Indeks kepuasan pasien', 'indeks', 'naik', 'rata-rata', 2, 4.5, 4.4, 0, 0.02, 'layanan'],
            ['C1.2', 'C1', 'Pasien dilayani < 30 menit', '%', 'naik', 'rata-rata', 1, 90, 86, 0.003, 0.03, 'layanan'],
            ['P1.1', 'P1', 'Kepatuhan SOP klinis', '%', 'naik', 'rata-rata', 1, 98, 97, 0, 0.01, 'layanan'],
            ['P1.2', 'P1', 'Insiden keselamatan pasien', 'kasus', 'turun', 'jumlah', 0, 1, 1, 0, 0.5, 'layanan'],
            ['L1.1', 'L1', 'Tenaga medis bersertifikat kompetensi', '%', 'naik', 'terakhir', 1, 95, 92, 0.002, 0.01, 'sdm'],
        ], 'ksb.');

        return [
            'company' => ['code' => 'KSB', 'name' => 'Klinik Sehat Bersama (fiktif)', 'industry' => 'Layanan kesehatan', 'city' => 'Bandung',
                'description' => 'Perusahaan fiktif untuk demo multi-perusahaan. Semua angka rekaan.'],
            'periods' => [['code' => '2026', 'year' => 2026, 'status' => 'berjalan', 'is_active' => true, 'notes' => 'Data fiktif.']],
            'users' => [
                ['Admin KSB (fiktif)', 'ksb.admin@contoh.local', 'Admin Sistem', 'Manajemen', ['company-admin']],
                ['Direktur KSB (fiktif)', 'ksb.direktur@contoh.local', 'Direktur Klinik', 'Direksi', ['pimpinan']],
                ['Keuangan KSB (fiktif)', 'ksb.keuangan@contoh.local', 'Kepala Keuangan', 'Keuangan', ['keuangan']],
                ['Kepala Layanan KSB (fiktif)', 'ksb.layanan@contoh.local', 'Kepala Pelayanan Medis', 'Pelayanan', ['kepala-unit']],
                ['Kepala SDM KSB (fiktif)', 'ksb.sdm@contoh.local', 'Kepala SDM', 'SDM', ['kepala-unit']],
            ],
            'imports' => [
                ['ksb-2026.xlsx', [
                    'Jati Diri' => [['visi', 'Klinik keluarga pilihan utama di Bandung Raya.'], ['misi', 'Layanan cepat, ramah, dan aman untuk setiap pasien.']],
                    'Sasaran' => [
                        ['K1', 'Keuangan', 'Pertumbuhan pendapatan yang sehat', null, null, 1, 'ksb.keuangan@contoh.local'],
                        ['C1', 'Kepuasan Pelanggan', 'Pasien puas dan setia', null, null, 1, 'ksb.layanan@contoh.local'],
                        ['P1', 'Proses Internal', 'Layanan klinis yang aman & sesuai standar', null, null, 1, 'ksb.layanan@contoh.local'],
                        ['L1', 'SDM & Pembelajaran', 'Tenaga medis kompeten', null, null, 1, 'ksb.sdm@contoh.local'],
                    ],
                    'Relasi Sasaran' => [['L1', 'P1', 3, null], ['P1', 'C1', 3, null], ['C1', 'K1', 2, null]],
                    'KPI' => $kpis, 'Target' => $targets, 'Realisasi' => $approved,
                    'Inisiatif' => [
                        ['KSB-I1', 'Pendaftaran online & antrean digital', 'C1', null, 'ksb.layanan@contoh.local', '2026-01-15', '2026-09-30', 'berjalan', 4, 5, 2, 3, 120000000, null, 'Waktu tunggu < 30 menit'],
                    ],
                ], true],
                ['ksb-realisasi-sep-2026.xlsx', ['Realisasi' => $draft], false],
            ],
        ];
    }
}
