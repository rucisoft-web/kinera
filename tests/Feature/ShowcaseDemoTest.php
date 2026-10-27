<?php

namespace Tests\Feature;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\Kpi;
use App\Models\ObjectiveRelation;
use App\Models\StrategicInitiative;
use App\Models\StrategicObjective;
use App\Services\KpiScoringService;
use App\Support\BscContext;
use Tests\TestCase;

class ShowcaseDemoTest extends TestCase
{
    public static function demos(): array
    {
        return [
            'UIN Sunan Kalijaga' => ['UINSUKA', 12, 6, 'Audited TA 2025'],
            'UIN Raden Fatah' => ['UINRF', 12, 6, 'LKjIP 2025'],
            'UIN Suska Riau' => ['UINSUSKA', 12, 6, 'Kontrak Kinerja'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('demos')]
    public function test_demo_command_builds_two_periods_with_a_full_strategy_map(string $code, int $objectives, int $initiatives, string $source): void
    {
        $this->assertSame(0, $this->artisan('kinera:demo', ['perusahaan' => $code, '--password' => 'Contoh-2026!', '--force' => true]));
        $this->assertSame(1, $this->artisan('kinera:demo', ['perusahaan' => $code, '--force' => true])); // data ada, tanpa --reset
        $this->assertSame(0, $this->artisan('kinera:demo', ['perusahaan' => $code, '--reset' => true, '--force' => true]));

        $company = Company::where('code', $code)->firstOrFail();
        $this->assertTrue((bool) $company->is_demo);
        $this->assertStringContainsString('DATA DEMO', \App\Support\DemoNotice::for($company));
        $this->assertStringContainsString($source, \App\Support\DemoNotice::for($company) ?? '');
        $this->assertStringContainsString('memaksimalkan penyajian fitur', \App\Support\DemoNotice::for($company));
        $this->assertLessThanOrEqual(100, mb_strlen((string) $company->industry), 'companies.industry maks 100 karakter');
        $periods = AnalysisPeriod::where('company_id', $company->id)->orderBy('year')->get();
        $this->assertSame(['ditutup', 'berjalan'], $periods->pluck('status')->all());
        $this->assertTrue((bool) $periods[1]->is_active);

        app(BscContext::class)->set($company->id, $periods[1]->id);
        $this->assertSame($objectives, StrategicObjective::count());
        $this->assertSame(['C', 'F', 'L', 'P'], StrategicObjective::pluck('code')->map(fn ($c) => $c[0])->unique()->sort()->values()->all());
        $this->assertGreaterThanOrEqual(15, ObjectiveRelation::count());
        $this->assertSame($initiatives, StrategicInitiative::count());

        $scoring = app(KpiScoringService::class);
        $status = Kpi::with('targets', 'actuals')->where('frequency', '!=', 'tahunan')->get()->map(fn ($k) => $scoring->ytd($k)['status'])->countBy();
        $this->assertGreaterThan(0, $status['hijau'] ?? 0);
        $this->assertGreaterThan(0, $status['kuning'] ?? 0);
        $this->assertGreaterThan(0, $status['merah'] ?? 0);
        $this->assertSame(0, $status[''] ?? 0, 'setiap IK triwulanan 2026 punya realisasi');
    }

    public function test_alias_command_works_and_unknown_code_is_rejected(): void
    {
        $this->assertSame(0, $this->artisan('kinera:demo-uin', ['--password' => 'Contoh-2026!', '--force' => true]));
        $this->assertTrue(Company::where('code', 'UINSUKA')->exists());
        $this->assertSame(1, $this->artisan('kinera:demo', ['perusahaan' => 'XYZ', '--force' => true]));
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
