<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Produksi (APP_ENV=production): hanya peran/izin & pertanyaan kematangan; buat Super Admin dengan dhastram:create-admin.
     * Lokal/staging: ditambah perusahaan contoh (DemoSeeder), atau di produksi bila RUCI_SEED_DEMO=true.
     */
    public function run(): void
    {
        $this->call([
            DhastramRolePermissionSeeder::class,
            MaturityQuestionSeeder::class,
        ]);

        if (! app()->isProduction() || config('dhastram.seed_demo')) {
            $this->call(DemoSeeder::class);
        }
    }
}
