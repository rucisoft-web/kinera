<?php

namespace Database\Seeders;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\UserContext;
use App\Support\BscContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Satker BLU contoh untuk demo & pelatihan (is_demo = true, hapus dengan `php artisan rucibsc:hapus-contoh`).
 *
 * - UINSUKA: UIN Sunan Kalijaga Yogyakarta — demo lengkap 2025 (LK Audited & Perjanjian Kinerja 2025) + proyeksi 2026,
 *   termasuk Kontrak Kinerja Pemimpin BLU beserta adendum & usulan perubahannya. Lihat UinSukaSeeder.
 * - Demo bertanda is_demo dan menampilkan peringatan "data demo" (App\Support\DemoNotice): hanya untuk keperluan
 *   memaksimalkan penyajian fitur-fitur perangkat lunak, bukan data resmi.
 */
class DemoSeeder extends Seeder
{
    public const PASSWORD = 'Contoh-2026!';

    public const ADMIN_EMAIL = 'admin@rucibsc.local';

    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => self::ADMIN_EMAIL], [
            'name' => 'Super Admin Rucisoft', 'job_title' => 'Konsultan Rucisoft', 'department' => 'Rucisoft', 'is_active' => true,
            'password' => Hash::make(config('bsc.demo_admin_password')), 'must_change_password' => false, 'email_verified_at' => now(),
        ]);
        $this->assign($admin, 'super-admin', null);

        (new UinSukaSeeder(self::PASSWORD))->run();

        UserContext::updateOrCreate(['user_id' => $admin->id], [
            'company_id' => Company::where('code', 'UINSUKA')->value('id'),
            'analysis_period_id' => AnalysisPeriod::whereHas('company', fn ($q) => $q->where('code', 'UINSUKA'))->where('is_active', true)->value('id'),
        ]);
        app(BscContext::class)->clear();
    }

    protected function assign(User $user, string $slug, ?int $companyId): void
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        if (! $user->roles()->where('roles.id', $role->id)->wherePivot('company_id', $companyId)->exists()) {
            $user->roles()->attach($role->id, ['company_id' => $companyId]);
        }
    }
}
