<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Kpi;
use App\Models\User;
use Tests\TestCase;

class TeachingToolsTest extends TestCase
{
    public function test_practice_command_builds_and_removes_group_instances(): void
    {
        $this->assertSame(0, $this->artisan('kinera:latihan', ['kelompok' => 2, '--password' => 'Latihan-2026!', '--force' => true]));
        $this->assertSame(['LAT01', 'LAT02'], Company::where('code', 'like', 'LAT%')->orderBy('code')->pluck('code')->all());
        $lat = Company::where('code', 'LAT01')->firstOrFail();
        $this->assertTrue((bool) $lat->is_demo);
        $this->assertStringContainsString('Latihan Kelompok 01', $lat->name);
        $user = User::where('email', 'lat01.perencanaan@contoh.local')->firstOrFail();
        $this->assertTrue($user->companies()->whereKey($lat->id)->exists());
        $this->assertGreaterThan(30, Kpi::withoutGlobalScopes()->where('company_id', $lat->id)->count());

        $this->assertSame(0, $this->artisan('kinera:latihan', ['kelompok' => 1, '--kosong' => true, '--password' => 'Latihan-2026!', '--force' => true]));
        $this->assertSame(['LAT01'], Company::where('code', 'like', 'LAT%')->pluck('code')->all());
        $this->assertSame(0, Kpi::withoutGlobalScopes()->where('company_id', Company::where('code', 'LAT01')->value('id'))->count());

        $this->assertSame(0, $this->artisan('kinera:latihan', ['--hapus' => true, '--force' => true]));
        $this->assertFalse(Company::where('code', 'like', 'LAT%')->exists());
        $this->assertFalse(User::where('email', 'like', 'lat%.%@contoh.local')->exists());
    }

    public function test_demo_kpis_mix_lead_and_lag_and_module_page_opens(): void
    {
        $this->artisan('kinera:demo', ['perusahaan' => 'UINSUKA', '--password' => 'Contoh-2026!', '--reset' => true, '--force' => true]);
        $company = Company::where('code', 'UINSUKA')->firstOrFail();
        $types = Kpi::withoutGlobalScopes()->where('company_id', $company->id)->pluck('indicator_type', 'code');
        $this->assertSame('lead', $types['C1.2']);
        $this->assertSame('lag', $types['C1.1']);
        $this->assertSame('lag', $types['P1.1']);
        $this->assertSame('lead', $types['L1.2']);

        config(['bsc.guest_login' => true]);
        $this->get('/masuk-tamu');
        $this->get(\App\Filament\Pages\ModulAjar::getUrl())->assertOk()
            ->assertSee('Modul Ajar Balanced Scorecard')->assertSee('Anatomi scorecard')->assertSee('Lembar kerja kelompok')->assertSee('kinera:latihan');
    }
}
