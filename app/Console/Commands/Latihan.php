<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\MaturityQuestion;
use App\Models\Role;
use App\Models\User;
use App\Support\BscContext;
use Database\Seeders\BscRolePermissionSeeder;
use Database\Seeders\MaturityQuestionSeeder;
use Database\Seeders\UinSukaSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Instansi latihan workshop per kelompok (LAT01, LAT02, ...): salinan demo UIN Sunan Kalijaga atau instansi kosong,
 * masing-masing dengan akun per peran (lat01.rektor, lat01.perencanaan, ...) yang bisa mengisi data tanpa mengubah demo utama.
 */
class Latihan extends Command
{
    protected $signature = 'kinera:latihan
        {kelompok=5 : Jumlah kelompok latihan (1–30)}
        {--password= : Kata sandi semua akun latihan (bila kosong dibuat acak dan ditampilkan)}
        {--kosong : Buat instansi kosong (hanya akun & perioda 2026) untuk latihan menyusun strategi dari nol}
        {--hapus : Hapus semua instansi & akun latihan tanpa membuat yang baru}
        {--force : Lewati konfirmasi}';

    protected $description = 'Siapkan instansi latihan workshop per kelompok (LAT01..): salinan demo UIN Sunan Kalijaga atau instansi kosong; jalankan ulang untuk mereset';

    public function handle(): int
    {
        $existing = Company::where('code', 'like', 'LAT%')->where('is_demo', true)->get();
        $n = (int) $this->argument('kelompok');
        if (! $this->option('hapus') && ($n < 1 || $n > 30)) {
            $this->error('Jumlah kelompok harus 1–30.');

            return self::FAILURE;
        }
        if ($existing->isNotEmpty() && ! $this->option('force')
            && ! $this->confirm('Instansi latihan '.$existing->pluck('code')->join(', ').' beserta isiannya akan dihapus. Lanjutkan?')) {
            return self::FAILURE;
        }

        User::where('email', 'like', 'lat%.%@contoh.local')->get()->each->delete();
        $existing->each->delete(); // tabel data instansi memakai cascadeOnDelete
        if ($this->option('hapus')) {
            $this->info('Instansi dan akun latihan dihapus.');

            return self::SUCCESS;
        }

        if (! Role::exists()) {
            $this->call('db:seed', ['--class' => BscRolePermissionSeeder::class, '--force' => true]);
        }
        if (! MaturityQuestion::exists()) {
            $this->call('db:seed', ['--class' => MaturityQuestionSeeder::class, '--force' => true]);
        }

        $password = $this->option('password') ?: Str::password(12, symbols: false);
        $empty = (bool) $this->option('kosong');
        $bar = $this->output->createProgressBar($n);
        for ($i = 1; $i <= $n; $i++) {
            $code = sprintf('LAT%02d', $i);
            DB::transaction(function () use ($code, $i, $password, $empty) {
                $seeder = (new UinSukaSeeder($password))->asPractice($code, sprintf('Latihan Kelompok %02d', $i));
                $empty ? $seeder->runEmpty() : $seeder->run();
            });
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
        app(BscContext::class)->clear();

        $this->info($n.' instansi latihan siap ('.($empty ? 'kosong, perioda 2026' : 'salinan demo UIN Sunan Kalijaga, perioda 2025 & 2026').').');
        $this->table(['Kelompok', 'Akun utama (perencanaan)', 'Akun lain'], collect(range(1, $n))->map(fn ($i) => [
            sprintf('LAT%02d', $i), sprintf('lat%02d.perencanaan@contoh.local', $i), sprintf('lat%02d.rektor, .wr2, .wr1, .lppm, .ti, .dewas, ...', $i),
        ])->all());
        if (! $this->option('password')) {
            $this->warn('Kata sandi semua akun latihan: '.$password);
        }
        $this->line('Reset sebelum sesi berikutnya: jalankan perintah yang sama lagi. Hapus setelah workshop: php artisan kinera:latihan --hapus --force');

        return self::SUCCESS;
    }
}
