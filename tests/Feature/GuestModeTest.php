<?php

namespace Tests\Feature;

use App\Filament\Resources\Kpis\KpiResource;
use App\Models\Company;
use App\Models\User;
use App\Models\UserContext;
use App\Support\GuestMode;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class GuestModeTest extends TestCase
{
    public function test_guest_entry_is_hidden_when_disabled(): void
    {
        config(['bsc.guest_login' => false]);

        $this->get('/masuk-tamu')->assertNotFound();
        $this->get('/admin/login')->assertOk()->assertDontSee('Masuk sebagai Tamu');
        $this->assertNull(User::where('email', GuestMode::email())->first());
    }

    public function test_one_click_guest_login_opens_the_demo_read_only(): void
    {
        config(['bsc.guest_login' => true]);
        [$other] = $this->company('AAA');
        [$dfi, $period] = $this->company('UINSUKA');

        $this->get('/admin/login')->assertOk()->assertSee('Masuk sebagai Tamu');
        $this->get('/masuk-tamu')->assertRedirect('/admin');

        $guest = User::where('email', GuestMode::email())->firstOrFail();
        $this->assertAuthenticatedAs($guest);
        $this->assertTrue($guest->isGuestVisitor());
        $this->assertSame($dfi->id, session('bsc.company_id'));
        $this->assertSame($period->id, session('bsc.period_id'));
        $this->assertSame(0, UserContext::where('user_id', $guest->id)->count(), 'konteks Tamu hanya di sesi');
        $this->assertEqualsCanonicalizing([$other->id, $dfi->id], $guest->accessibleCompanyIds());

        $this->get('/admin')->assertOk()->assertSee('Mode Tamu');

        // masuk lagi tidak menggandakan akun/peran
        $this->get('/masuk-tamu')->assertRedirect('/admin');
        $this->assertSame(1, User::where('email', GuestMode::email())->count());
        $this->assertSame(1, $guest->fresh()->roles()->count());
    }

    public function test_guest_can_view_but_never_change_data(): void
    {
        config(['bsc.guest_login' => true]);
        [$company, $period] = $this->company('UINSUKA');
        $guest = GuestMode::user();
        $this->actingAs($guest);
        $this->useContext($period);

        $this->assertTrue($guest->hasPermission('kpi.view'));
        $this->assertTrue($guest->hasPermission('strategy.map'));
        foreach (['kpi.edit', 'kpi.input_actual', 'report.export', 'audit.view', 'system.admin'] as $perm) {
            $this->assertFalse($guest->hasPermission($perm), $perm);
        }
        $this->assertTrue(KpiResource::canViewAny());
        $this->assertFalse(KpiResource::canCreate());

        $this->assertBlocked(fn () => $company->update(['name' => 'Diubah tamu']));
        $this->assertBlocked(fn () => Company::create(['code' => 'XXX', 'name' => 'Baru', 'is_active' => true]));
        $this->assertBlocked(fn () => $company->delete());
        $this->assertBlocked(fn () => $guest->update(['name' => 'Ganti nama']));
        $this->assertSame('PT UINSUKA', $company->fresh()->name);

        // pemeliharaan sesi tetap jalan
        $guest->refresh()->forceFill(['remember_token' => 'abc'])->save();
        $this->assertSame('abc', $guest->fresh()->remember_token);
    }

    public function test_landing_page_offers_current_year_demos_and_guides(): void
    {
        config(['bsc.guest_login' => true]);
        [$other] = $this->company('AAA');
        [$uin, $uinNow] = $this->company('UINSUKA');
        $uin->forceFill(['is_demo' => true])->save();
        \App\Models\AnalysisPeriod::create(['company_id' => $uin->id, 'code' => '2025', 'name' => 'Tahun 2025', 'year' => 2025,
            'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'status' => 'ditutup', 'is_active' => false]);
        \Illuminate\Support\Facades\Cache::flush();

        $this->get('/')->assertOk()
            ->assertSee('Masalah yang sering dihadapi')
            ->assertSee('Solusi Kinera')
            ->assertSee('Cara memperoleh Kinera')
            ->assertSee('Workshop 2 hari')
            ->assertSee('Pusat Studi Manajemen Perguruan Tinggi')
            ->assertSee('files/brosur-kinera-workshop-2-hari.pdf', false)
            ->assertSee('https://wa.me/6281199892939', false)
            ->assertSee('Data demo tahun berjalan 2026')
            ->assertSee('Panduan Kinera')
            ->assertSee('UIN Sunan Kalijaga Yogyakarta')
            ->assertSee('memaksimalkan penyajian fitur')
            ->assertSee(route('arah.guest', ['perusahaan' => 'UINSUKA']), false);
        $this->get('/admin/login')->assertOk()->assertSee('Pilih instansi contoh')->assertSee('UIN Sunan Kalijaga Yogyakarta (UINSUKA)');

        $this->get(route('arah.guest', ['perusahaan' => 'uinsuka']))->assertRedirect('/admin');
        $this->assertSame($uin->id, session('bsc.company_id'));
        $this->assertSame($uinNow->id, session('bsc.period_id'));

        // kode yang bukan instansi contoh kembali ke satker pembuka
        $this->get(route('arah.guest', ['perusahaan' => 'XYZ', 'ke' => 'panduan']))
            ->assertRedirect(\App\Filament\Pages\PanduanPengguna::getUrl());
        $this->assertSame($uin->id, session('bsc.company_id'));

        auth()->logout();
        config(['bsc.guest_login' => false]);
        $this->get('/')->assertRedirect('/admin');
    }

    public function test_kenali_link_opens_the_introduction_page(): void
    {
        config(['bsc.guest_login' => true]);
        $this->company('UINSUKA');

        $this->get('/kenali')->assertRedirect(route('arah.guest', ['ke' => 'kenali']));
        $this->get(route('arah.guest', ['ke' => 'kenali']))->assertRedirect(\App\Filament\Pages\KenaliKinera::getUrl());
        $this->get(\App\Filament\Pages\KenaliKinera::getUrl())->assertOk()
            ->assertSee('Strategi yang dipahami semua orang')
            ->assertSee('Workshop Kinera');

        auth()->logout();
        config(['bsc.guest_login' => false]);
        $this->get('/kenali')->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_guide_pages_are_open_to_every_user(): void
    {
        config(['bsc.guest_login' => true]);
        $this->company('UINSUKA');
        $this->get('/masuk-tamu');

        $this->get(\App\Filament\Pages\PanduanPengguna::getUrl())->assertOk()
            ->assertSee('Dewan Pengawas')->assertSee('Menyetujui realisasi IK')->assertSee('images/panduan/kepala-input.jpg', false);
        $this->get(\App\Filament\Pages\PanduanImplementasi::getUrl())->assertOk()
            ->assertSee('Manajemen risiko')->assertSee('Gerbang mutu')->assertSee('Steering Committee');
        $this->get(\App\Filament\Pages\WorkshopKinera::getUrl())->assertOk()
            ->assertSee('Rundown')->assertSee('Hari 1')->assertSee('Hari 2')->assertSee('Sesi 8')
            ->assertSee('sambutan Ketua PSMPT UIN Sunan Kalijaga')->assertSee('Strategic Leadership Lab')->assertSee('Akses Kinera 3 bulan')->assertSee('files/brosur-kinera-workshop-2-hari.pdf', false)
            ->assertSee('https://wa.me/6281199892939', false)->assertDontSee('Minggu pertama');
    }

    public function test_workshop_register_button_prefers_configured_url(): void
    {
        config(['bsc.workshop_url' => null, 'bsc.contact_whatsapp' => '6281199892939']);
        $this->assertStringStartsWith('https://wa.me/6281199892939?text=', \App\Filament\Pages\WorkshopKinera::registerUrl());

        config(['bsc.workshop_url' => 'https://rucisoft.id/daftar']);
        $this->assertSame('https://rucisoft.id/daftar', \App\Filament\Pages\WorkshopKinera::registerUrl());
    }

    public function test_regular_users_are_not_affected(): void
    {
        [$company, $period] = $this->company('UINSUKA');
        $admin = $this->user('admin@dfi.test', 'company-admin', $company);
        $this->actingAs($admin);
        $this->useContext($period);

        $company->update(['name' => 'PT Diamond']);
        $this->assertSame('PT Diamond', $company->fresh()->name);
        $this->assertFalse($admin->isGuestVisitor());
    }

    private function assertBlocked(callable $write): void
    {
        try {
            $write();
            $this->fail('penulisan oleh Tamu seharusnya ditolak');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }
    }

    public function test_help_link_and_topic_pages(): void
    {
        config(['bsc.guest_login' => true]);
        $this->company('UINSUKA');
        $this->get('/masuk-tamu');

        $this->get(\App\Filament\Resources\PestelAnalyses\PestelAnalysisResource::getUrl('index'))->assertOk()
            ->assertSee('admin/bantuan?topik=pestel', false);
        foreach (array_keys(\App\Support\Bantuan::registry()) as $key) {
            $this->assertTrue(\App\Support\Bantuan::exists($key), $key);
            $this->get(\App\Filament\Pages\BantuanPengisian::urlFor($key))->assertOk()->assertSee('Teori singkat')->assertSee('images/bantuan/'.$key.'.jpg', false);
        }
        $this->get(\App\Filament\Pages\BantuanPengisian::getUrl())->assertOk()->assertSee('Analisis Industri');
    }
}
