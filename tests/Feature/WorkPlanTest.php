<?php

namespace Tests\Feature;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\WorkPlanDraft;
use App\Services\Ai\ClaudeWriter;
use App\Services\WorkPlanService;
use App\Support\BscContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Tests\TestCase;

class WorkPlanTest extends TestCase
{
    use RefreshDatabase;

    private AnalysisPeriod $period;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.anthropic.key' => null]);
        $this->seed(\Database\Seeders\BscRolePermissionSeeder::class);
        $this->artisan('kinera:demo', ['perusahaan' => 'UINSUKA', '--password' => 'Contoh-2026!', '--force' => true]);
        $company = Company::where('code', 'UINSUKA')->firstOrFail();
        $this->period = AnalysisPeriod::where('company_id', $company->id)->where('is_active', true)->firstOrFail();
        $this->actingAs(\App\Models\User::where('email', 'uinsuka.rektor@contoh.local')->firstOrFail());
        app(BscContext::class)->set($company->id, $this->period->id);
    }

    public function test_sample_mode_builds_a_printable_work_plan_without_api_key(): void
    {
        $svc = app(WorkPlanService::class);
        $this->assertFalse($svc->aiAvailable());

        $draft = $svc->generate($this->period->year + 1, null, [], auth()->user());
        $this->assertSame(['contoh', 'selesai'], [$draft->mode, $draft->status]);
        $this->assertCount(9, $draft->content['ai']['program']);
        foreach ($draft->content['ai']['program'] as $p) {
            $this->assertNotEmpty($p['kegiatan']);
        }

        $this->get(route('workplan.print', $draft))->assertOk()
            ->assertSee('RENCANA KERJA')->assertSee('Jadwal Pelaksanaan')->assertSee('mode contoh tanpa AI');
        $this->get(route('workplan.print', ['draft' => $draft, 'format' => 'word']))->assertOk()
            ->assertDownload('Rencana-Kerja-uinsuka-'.($this->period->year + 1).'-'.$draft->id.'.doc');
    }

    public function test_claude_mode_sends_kinera_facts_and_attachments(): void
    {
        config(['services.anthropic.key' => 'test-key']);
        $fake = new class extends ClaudeWriter
        {
            public array $content = [];

            public function write(string $system, array $content, array $schema): array
            {
                $this->content = $content;

                return ['model' => 'claude-opus-5-5', 'input_tokens' => 1000, 'output_tokens' => 500, 'data' => [
                    'ringkasan' => 'Ringkasan uji.', 'latar_belakang' => 'Latar uji.', 'maksud_tujuan' => 'Tujuan uji.',
                    'dasar_hukum' => [], 'arah_kebijakan' => ['Arah uji'],
                    'program' => [['kode_inisiatif' => 'UIN-26-01', 'nama' => 'Program WCU', 'uraian' => 'Uraian.', 'sasaran_kode' => ['P1'],
                        'kegiatan' => [['nama' => 'Kegiatan uji dari lampiran', 'keluaran' => 'Dokumen', 'iku_kode' => ['P1.1', 'XX9'], 'triwulan' => [2, 3], 'penanggung_jawab' => 'Wakil Rektor']]]],
                    'risiko' => [['risiko' => 'Risiko uji', 'mitigasi' => 'Mitigasi uji']],
                    'pemantauan' => 'Pantau.', 'penutup' => 'Tutup.', 'sumber_rujukan' => ['arahan.txt'],
                ]];
            }
        };
        $this->app->instance(ClaudeWriter::class, $fake);

        $files = [
            UploadedFile::fake()->createWithContent('arahan.txt', 'Fokus akreditasi internasional.'),
            UploadedFile::fake()->createWithContent('rkat.pdf', "%PDF-1.4\n%%EOF"),
        ];
        $draft = app(WorkPlanService::class)->generate($this->period->year, 'Hemat 10%', $files, auth()->user());

        $this->assertSame(['ai', 'selesai', 1500], [$draft->mode, $draft->status, $draft->input_tokens + $draft->output_tokens]);
        $this->assertSame(['arahan.txt', 'rkat.pdf'], array_column($draft->attachments, 'name'));
        $this->assertSame('text', $fake->content[0]['source']['type']);
        $this->assertSame('application/pdf', $fake->content[1]['source']['mediaType']);
        $this->assertStringContainsString('"kode": "UIN-26-01"', $fake->content[2]['text']);
        $this->assertStringContainsString('Hemat 10%', $fake->content[2]['text']);

        $this->get(route('workplan.print', $draft))->assertOk()
            ->assertSee('Kegiatan uji dari lampiran')->assertSee('disusun dengan bantuan Claude', false)->assertDontSee('XX9');
    }

    public function test_failed_claude_call_is_recorded_and_not_printable(): void
    {
        config(['services.anthropic.key' => 'test-key']);
        $this->app->instance(ClaudeWriter::class, new class extends ClaudeWriter
        {
            public function write(string $system, array $content, array $schema): array
            {
                throw new RuntimeException('Batas kuota tercapai');
            }
        });

        $before = WorkPlanDraft::count(); // demo sudah membawa contoh draf
        $draft = app(WorkPlanService::class)->generate($this->period->year, null, [], auth()->user());
        $this->assertSame('gagal', $draft->status);
        $this->assertSame('Batas kuota tercapai', $draft->error);
        $this->get(route('workplan.print', $draft))->assertNotFound();
        $this->assertSame($before + 1, WorkPlanDraft::count());
    }

    public function test_schema_is_strict_for_structured_outputs(): void
    {
        $walk = function (array $s) use (&$walk) {
            if (($s['type'] ?? null) === 'object') {
                $this->assertFalse($s['additionalProperties']);
                $this->assertSame(array_keys($s['properties']), $s['required']);
                array_map($walk, $s['properties']);
            }
            if (($s['type'] ?? null) === 'array') {
                $walk($s['items']);
            }
        };
        $walk(app(WorkPlanService::class)->schema());
    }
}
