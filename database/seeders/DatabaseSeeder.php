<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Produksi (APP_ENV=production): hanya peran/izin & pertanyaan kematangan; buat Super Admin dengan rucibsc:create-admin.
     * Lokal/staging: ditambah perusahaan contoh (DemoSeeder), atau di produksi bila RUCI_SEED_DEMO=true.
     */
    public function run(): void
    {
        $this->call([
            BscRolePermissionSeeder::class,
            MaturityQuestionSeeder::class,
        ]);

        if (! app()->isProduction() || config('bsc.seed_demo')) {
            $this->call(DemoSeeder::class);
        }
    }
}
