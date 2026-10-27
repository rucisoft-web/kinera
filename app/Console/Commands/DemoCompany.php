<?php

namespace App\Console\Commands;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\MaturityQuestion;
use App\Models\Role;
use App\Models\User;
use App\Support\BscContext;
use Database\Seeders\BscRolePermissionSeeder;
use Database\Seeders\MaturityQuestionSeeder;
use Database\Seeders\ShowcaseSeeder;
use Database\Seeders\UinRadenFatahSeeder;
use Database\Seeders\UinSukaSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/** Memuat demo lengkap satu satker BLU (perioda 2025 + 2026) ke server yang sudah berjalan. */
class DemoCompany extends Command
{
    /** @var array<string,class-string<ShowcaseSeeder>> */
    public const DEMOS = ['UINSUKA' => UinSukaSeeder::class, 'UINRF' => UinRadenFatahSeeder::class];

    protected $signature = 'kinera:demo
        {perusahaan=UINSUKA : Kode demo satker BLU: UINSUKA (UIN Sunan Kalijaga Yogyakarta), UINRF (UIN Raden Fatah Palembang)}
        {--password= : Kata sandi akun contoh satker BLU itu (bila kosong dibuat acak dan ditampilkan)}
        {--reset : Hapus dulu data satker BLU itu untuk perioda 2025 & 2026 yang sudah ada}
        {--force : Lewati konfirmasi}';

    protected $description = 'Muat demo lengkap satker BLU (UIN Sunan Kalijaga, UIN Raden Fatah) (Strategy Map, Indikator Kinerja, Kontrak Kinerja, inisiatif, tinjauan) perioda 2025 & 2026';

    protected $aliases = ['kinera:demo-uin'];

    public function handle(): int
    {
        if (! Role::exists()) {
            $this->call('db:seed', ['--class' => BscRolePermissionSeeder::class, '--force' => true]);
        }
        if (! MaturityQuestion::exists()) {
            $this->call('db:seed', ['--class' => MaturityQuestionSeeder::class, '--force' => true]);
        }

        $code = strtoupper($this->argument('perusahaan'));
        $seeder = self::DEMOS[$code] ?? null;
        if (! $seeder) {
            $this->error('Kode demo tidak dikenal. Pilihan: '.implode(', ', array_keys(self::DEMOS)));

            return self::FAILURE;
        }
        $company = Company::where('code', $code)->first();
        $periods = $company ? AnalysisPeriod::where('company_id', $company->id)->whereIn('code', ['2025', '2026'])->get() : collect();
        if ($periods->isNotEmpty()) {
            if (! $this->option('reset')) {
                $this->error('Satker BLU '.$code.' sudah punya perioda '.$periods->pluck('code')->join(' & ').'. Jalankan ulang dengan --reset untuk menggantinya dengan data demo.');

                return self::FAILURE;
            }
            if (! $this->option('force') && ! $this->confirm('Semua data '.$code.' perioda '.$periods->pluck('code')->join(' & ').' akan dihapus dan diganti data demo. Lanjutkan?')) {
                return self::FAILURE;
            }
        }

        $password = $this->option('password') ?: Str::password(14, symbols: false);
        $company = DB::transaction(function () use ($periods, $password, $seeder) {
            $periods->each->delete(); // seluruh tabel data perioda memakai cascadeOnDelete

            return (new $seeder($password))->run();
        });
        app(BscContext::class)->clear();

        $this->info('Demo '.$company->name.' siap: perioda 2025 (ditutup) & 2026 (berjalan, bawaan).');
        $this->line('Akun contoh: '.User::where('email', 'like', strtolower($code).'.%@contoh.local')->orderBy('id')->pluck('email')->join(', '));
        if (! $this->option('password')) {
            $this->warn('Kata sandi akun contoh: '.$password);
        }
        $this->line('Hapus semua data contoh sebelum go-live dengan: php artisan kinera:hapus-contoh');

        return self::SUCCESS;
    }
}
