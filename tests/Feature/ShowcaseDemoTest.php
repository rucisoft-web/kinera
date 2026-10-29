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
            'UIN Sunan Kalijaga' => ['UINSUKA', 14, 9, 'Renstra UIN Sunan Kalijaga 2025–2029'],
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

    public function test_demo_seeder_can_run_again_and_only_adds_missing_demos(): void
    {
        $this->seed(\Database\Seeders\BscRolePermissionSeeder::class);
        $this->seed(\Database\Seeders\MaturityQuestionSeeder::class);
        $this->artisan('kinera:demo', ['perusahaan' => 'UINSUKA', '--password' => 'Contoh-2026!', '--force' => true]);

        $this->seed(\Database\Seeders\DemoSeeder::class);
        $this->seed(\Database\Seeders\DemoSeeder::class);

        $this->assertSame(['UINSUKA'], Company::orderBy('code')->pluck('code')->all());
        $this->assertSame(2, AnalysisPeriod::count());
    }

    public function test_blu_module_is_hidden_for_non_blu_instansi(): void
    {
        $this->seed(\Database\Seeders\BscRolePermissionSeeder::class);
        $this->artisan('kinera:demo', ['perusahaan' => 'UINSUKA', '--password' => 'Contoh-2026!', '--force' => true]);
        $company = Company::where('code', 'UINSUKA')->firstOrFail();
        $this->actingAs(\App\Models\User::where('email', 'uinsuka.rektor@contoh.local')->firstOrFail());
        app(BscContext::class)->set($company->id, AnalysisPeriod::where('company_id', $company->id)->where('is_active', true)->value('id'));

        foreach ([true, false] as $blu) {
            $company->update(['blu_field' => $blu ? 'pendidikan' : null, 'blu_since' => null]);
            app(BscContext::class)->clear();
            app(BscContext::class)->set($company->id, AnalysisPeriod::where('company_id', $company->id)->where('is_active', true)->value('id'));
            $this->assertSame($blu, \App\Support\BluModule::enabled());
            $this->assertSame($blu, \App\Filament\Pages\CapaianKinerjaBlu::canAccess());
            $this->assertSame($blu, \App\Filament\Resources\PerformanceContracts\PerformanceContractResource::canViewAny());
        }
    }

    public function test_renstra_draft_prints_and_downloads_as_word(): void
    {
        $this->seed(\Database\Seeders\BscRolePermissionSeeder::class);
        $this->artisan('kinera:demo', ['perusahaan' => 'UINSUKA', '--password' => 'Contoh-2026!', '--force' => true]);
        $company = Company::where('code', 'UINSUKA')->firstOrFail();
        $period = AnalysisPeriod::where('company_id', $company->id)->where('is_active', true)->firstOrFail();
        $this->actingAs(\App\Models\User::where('email', 'uinsuka.rektor@contoh.local')->firstOrFail());
        app(BscContext::class)->set($company->id, $period->id);

        $svc = app(\App\Services\RenstraDraftService::class);
        $d = $svc->build($period->year - 1);
        $this->assertSame(range($period->year - 1, $period->year + 3), $d['years']);
        $this->assertCount(14, $d['objectives']);
        foreach ($d['kpiRows'] as $row) {
            $this->assertEquals(round((float) $row['kpi']->annual_target, (int) $row['kpi']->decimals), $row['targets'][$period->year], $row['kpi']->code);
            if ($row['kpi']->unit === '%') {
                $this->assertLessThanOrEqual(100, max(array_filter($row['targets'], 'is_numeric')));
            }
        }

        $this->get(route('renstra.print', ['mulai' => $period->year - 1]))->assertOk()
            ->assertSee('RENCANA STRATEGIS')->assertSee('Bab IV')->assertSee('Lampiran 2')->assertSee('DATA DEMO');
        $this->get(route('renstra.print', ['format' => 'word']))->assertOk()
            ->assertHeader('Content-Type', 'application/msword; charset=UTF-8')
            ->assertDownload('Draf-Renstra-uinsuka-'.$period->year.'-'.($period->year + 4).'.doc');
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
