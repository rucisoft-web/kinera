<?php

namespace Tests\Feature;

use App\Filament\Pages\CapaianKinerjaBlu;
use App\Filament\Resources\PerformanceContracts\PerformanceContractResource;
use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\Kpi;
use App\Models\PerformanceContract;
use App\Models\User;
use App\Services\BluContractService;
use Carbon\CarbonImmutable;
use Tests\TestCase;

/** Kontrak Kinerja Pemimpin BLU (PER-6/PB/2025) di atas data demo UIN Sunan Kalijaga. */
class BluContractTest extends TestCase
{
    protected Company $company;

    /** @var array<int,AnalysisPeriod> */
    protected array $p = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\MaturityQuestionSeeder::class);
        $this->artisan('kinera:demo', ['perusahaan' => 'UINSUKA', '--password' => 'Contoh-2026!', '--force' => true]);
        $this->company = Company::where('code', 'UINSUKA')->firstOrFail();
        $this->p = AnalysisPeriod::where('company_id', $this->company->id)->get()->keyBy('year')->all();
    }

    public function test_demo_contracts_follow_the_regulation_cycle(): void
    {
        $this->useContext($this->p[2025]);
        $this->assertSame(['digantikan', 'ditandatangani'], PerformanceContract::orderBy('version')->pluck('status')->all());
        $this->assertSame('adendum', PerformanceContract::where('version', 1)->value('kind'));
        $this->assertSame(['layanan', 'keuangan', 'penugasan'], Kpi::distinct()->pluck('aspect')->sortBy(fn ($a) => array_search($a, array_keys(Kpi::ASPECTS)))->values()->all());

        $this->useContext($this->p[2026]);
        $proposal = PerformanceContract::where('version', 1)->firstOrFail();
        $this->assertSame(['perubahan', 'diusulkan'], [$proposal->kind, $proposal->status]);
        $this->assertSame(['C1.1'], $proposal->loweredCodes());
        $this->assertSame(0, app(BluContractService::class)->activeContract()->version, 'usulan belum berlaku sebelum ditandatangani');
    }

    public function test_2025_achievements_show_the_incentive_conditions_from_the_audited_report(): void
    {
        $this->useContext($this->p[2025]);
        $svc = app(BluContractService::class);
        $a = $svc->achievements(12);
        $row = fn (string $code) => collect($a['rows'])->first(fn ($r) => $r['kpi']->code === $code);

        $this->assertEqualsWithDelta(99.48, $row('F1.1')['raw'], 0.01);          // PNBP 219.502 / 220.654 juta
        $this->assertEquals(120, $row('F2.1')['recognized']);                     // EBITDA melampaui target, dibatasi capaian maksimum
        $this->assertSame(['F1.3'], collect($a['below_floor'])->map(fn ($r) => $r['kpi']->code)->all());

        $checks = collect($svc->incentiveChecks($a))->pluck('status', 'key');
        $this->assertSame('gagal', $checks['c']);
        $this->assertSame('gagal', $checks['d']);
        $this->assertSame('ok', $checks['e']);
        $this->assertSame('ok', $checks['f']);
    }

    public function test_deadlines_and_lowered_target_cap(): void
    {
        $this->useContext($this->p[2026]);
        $svc = app(BluContractService::class);
        $deadlines = collect($svc->deadlines(null, CarbonImmutable::parse('2026-10-27')))->keyBy('key');

        $this->assertSame('2026-01-23', $deadlines['kesepakatan']['due']->toDateString(), '25 Januari 2026 hari Minggu → hari kerja sebelumnya');
        $this->assertSame('2026-10-21', $deadlines['lapor9']['due']->toDateString(), '15 hari kerja setelah 30 September');
        $this->assertSame('lewat tenggat', $deadlines['lapor9']['status']);
        $this->assertSame('tepat waktu', $deadlines['lapor6']['status']);
        $this->assertSame('tepat waktu', $deadlines['perubahan_usul']['status']);

        $proposal = PerformanceContract::where('version', 1)->firstOrFail();
        $proposal->update(['status' => 'ditandatangani', 'dg_signed_at' => '2026-11-20']);
        $this->assertSame('digantikan', PerformanceContract::where('version', 0)->value('status'));
        $c11 = collect($svc->achievements(9)['rows'])->first(fn ($r) => $r['kpi']->code === 'C1.1');
        $this->assertTrue($c11['lowered']);
        $this->assertEquals(100, $c11['cap']);
    }

    public function test_pages_and_printouts_render_with_the_demo_disclaimer(): void
    {
        $user = User::where('email', 'uinsuka.rektor@contoh.local')->firstOrFail();
        $this->actingAs($user);
        $this->useContext($this->p[2026]);
        $contract = PerformanceContract::where('version', 0)->firstOrFail();

        $this->get(PerformanceContractResource::getUrl('index'))->assertOk()->assertSee('Kontrak Kinerja');
        $this->get(PerformanceContractResource::getUrl('view', ['record' => $contract]))->assertOk()->assertSee('Definisi Operasional');
        $this->get(CapaianKinerjaBlu::getUrl())->assertOk()->assertSee('Pasal 14')->assertSee('F1.3');

        foreach (['kontrak' => 'KONTRAK KINERJA', 'berita-acara' => 'BERITA ACARA', 'definisi-operasional' => 'Metode pengujian', 'capaian' => 'Capaian', 'pernyataan' => 'SURAT PERNYATAAN'] as $doc => $text) {
            $this->get(route('blu.print', ['doc' => $doc, 'contract' => $contract->id]))->assertOk()->assertSee($text)->assertSee('DATA DEMO');
        }
        $this->get(route('blu.print', ['doc' => 'lain', 'contract' => $contract->id]))->assertNotFound();
    }
}
