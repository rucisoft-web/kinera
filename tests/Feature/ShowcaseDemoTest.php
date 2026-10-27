<?php

namespace Tests\Feature;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\Kpi;
use App\Models\ObjectiveRelation;
use App\Models\StrategicInitiative;
use App\Models\StrategicObjective;
use App\Services\KpiScoringService;
use App\Support\DhastramContext;
use Tests\TestCase;

class ShowcaseDemoTest extends TestCase
{
    public static function demos(): array
    {
        return ['Diamond Food' => ['DFI', 15, 10], 'Hermina' => ['HMN', 16, 10], 'Medco' => ['MEDC', 15, 10], 'Pakuwon' => ['PWON', 15, 10], 'Kimia Farma' => ['KAEF', 14, 10], 'Semen Indonesia' => ['SMGR', 14, 10], 'MAP' => ['MAPI', 14, 10], 'GoTo' => ['GOTO', 14, 10], 'Astra' => ['ASII', 14, 10], 'BCA' => ['BBCA', 14, 10], 'Telkom' => ['TLKM', 14, 10], 'Blue Bird' => ['BIRD', 14, 10], 'SMBC Indonesia' => ['BTPN', 14, 10], 'Mandiri Taspen' => ['BMTP', 14, 10]];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('demos')]
    public function test_demo_command_builds_two_periods_with_a_full_strategy_map(string $code, int $objectives, int $initiatives): void
    {
        $this->assertSame(0, $this->artisan('rucibsc:demo', ['perusahaan' => $code, '--password' => 'Contoh-2026!', '--force' => true]));
        $this->assertSame(1, $this->artisan('rucibsc:demo', ['perusahaan' => $code, '--force' => true])); // data ada, tanpa --reset
        $this->assertSame(0, $this->artisan('rucibsc:demo', ['perusahaan' => $code, '--reset' => true, '--force' => true]));

        $company = Company::where('code', $code)->firstOrFail();
        $this->assertTrue((bool) $company->is_demo);
        $this->assertStringContainsString('DATA DEMO', \App\Support\DemoNotice::for($company));
        $this->assertStringContainsString('Laporan Tahunan 2025', \App\Support\DemoNotice::for($company));
        $this->assertLessThanOrEqual(100, mb_strlen((string) $company->industry), 'companies.industry maks 100 karakter');
        $periods = AnalysisPeriod::where('company_id', $company->id)->orderBy('year')->get();
        $this->assertSame(['ditutup', 'berjalan'], $periods->pluck('status')->all());
        $this->assertTrue((bool) $periods[1]->is_active);

        app(DhastramContext::class)->set($company->id, $periods[1]->id);
        $this->assertSame($objectives, StrategicObjective::count());
        $this->assertSame(['C', 'F', 'L', 'P'], StrategicObjective::pluck('code')->map(fn ($c) => $c[0])->unique()->sort()->values()->all());
        $this->assertGreaterThanOrEqual(15, ObjectiveRelation::count());
        $this->assertSame($initiatives, StrategicInitiative::count());

        $scoring = app(KpiScoringService::class);
        $status = Kpi::with('targets', 'actuals')->get()->map(fn ($k) => $scoring->ytd($k)['status'])->countBy();
        $this->assertGreaterThan(0, $status['hijau'] ?? 0);
        $this->assertGreaterThan(0, $status['kuning'] ?? 0);
        $this->assertGreaterThan(0, $status['merah'] ?? 0);
        $this->assertSame(0, $status[''] ?? 0, 'setiap KPI 2026 punya realisasi');
    }

    public function test_old_diamond_command_name_still_works_and_unknown_code_is_rejected(): void
    {
        $this->assertSame(0, $this->artisan('rucibsc:demo-diamond', ['--password' => 'Contoh-2026!', '--force' => true]));
        $this->assertTrue(Company::where('code', 'DFI')->exists());
        $this->assertSame(1, $this->artisan('rucibsc:demo', ['perusahaan' => 'XYZ', '--force' => true]));
    }

    public function test_kpi_is_only_expected_in_its_reporting_months(): void
    {
        $due = fn (string $freq) => collect(range(1, 12))->filter(fn ($m) => (new Kpi(['frequency' => $freq]))->isDueIn($m))->values()->all();

        $this->assertSame(range(1, 12), $due('bulanan'));
        $this->assertSame([3, 6, 9, 12], $due('kuartalan'));
        $this->assertSame([6, 12], $due('semesteran'));
        $this->assertSame([12], $due('tahunan'));
    }
}
