<?php

namespace App\Console\Commands;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\PermissionCatalog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class Doctor extends Command
{
    protected $signature = 'kinera:doctor';

    protected $description = 'Pemeriksaan konfigurasi, basis data, izin & peran Kinera';

    public function handle(): int
    {
        $rows = [];
        $add = function (string $item, bool $ok, string $info = '') use (&$rows) {
            $rows[] = [$ok ? '<fg=green>OK</>' : '<fg=red>PERIKSA</>', $item, $info];
        };
        $add('PHP >= 8.3', version_compare(PHP_VERSION, '8.3.0', '>='), PHP_VERSION);
        foreach (['pdo_mysql', 'mbstring', 'intl', 'gd', 'zip', 'bcmath', 'fileinfo', 'openssl'] as $ext) {
            $add('Ekstensi '.$ext, extension_loaded($ext), extension_loaded($ext) ? '' : 'aktifkan di php.ini (Laragon: Menu › PHP › Extensions)');
        }
        $add('APP_KEY terisi', filled(config('app.key')));
        $add('APP_DEBUG mati di produksi', ! (app()->isProduction() && config('app.debug')), 'APP_ENV='.app()->environment().', APP_DEBUG='.(config('app.debug') ? 'true' : 'false'));
        $add('Mode demo mati', ! config('bsc.demo_enabled'));
        $add('Zona waktu Asia/Jakarta', config('app.timezone') === 'Asia/Jakarta', config('app.timezone'));
        $add('Locale id', config('app.locale') === 'id', config('app.locale'));
        $add('Istilah (KINERA_TERMS)', in_array(config('bsc.terms'), ['blu', 'umum'], true), config('bsc.terms'));
        try {
            DB::connection()->getPdo();
            $add('Koneksi basis data', true, config('database.default').' / '.DB::connection()->getDatabaseName());
        } catch (Throwable $e) {
            $add('Koneksi basis data', false, $e->getMessage());
            $this->table(['Status', 'Pemeriksaan', 'Keterangan'], $rows);

            return self::FAILURE;
        }
        $add('Storage dapat ditulis', is_writable(storage_path('app')) && is_writable(storage_path('logs')));
        $add('Aset Filament terpublikasi', file_exists(public_path('js/filament/filament/app.js')), 'php artisan filament:assets');
        $add('Stylesheet tema Kamasan', file_exists(public_path('css/arah.css')));
        $missing = array_diff(array_keys(PermissionCatalog::PERMISSIONS), Permission::pluck('slug')->all());
        $add('Izin lengkap ('.count(PermissionCatalog::PERMISSIONS).')', $missing === [], $missing ? 'kurang: '.implode(', ', $missing).' → db:seed --class=BscRolePermissionSeeder' : '');
        foreach (['super-admin', 'company-admin', 'admin-sistem', 'pengawas', 'pimpinan', 'strategy-office', 'keuangan', 'kepala-unit', 'pelaksana', 'viewer'] as $slug) {
            $r = Role::where('slug', $slug)->withCount('permissions')->first();
            $add('Peran '.$slug, (bool) $r, $r ? $r->permissions_count.' izin' : 'belum ada');
        }
        $add('Super Admin tersedia', User::whereHas('roles', fn ($q) => $q->where('slug', 'super-admin'))->exists(), 'php artisan kinera:create-admin');
        $companies = Company::count();
        $add('Satker BLU', $companies >= 1, $companies.' satker BLU ('.Company::pluck('code')->join(', ').')');
        $add('Perioda aktif', AnalysisPeriod::where('is_active', true)->exists(), AnalysisPeriod::where('is_active', true)->pluck('name')->join(', '));
        $noRole = User::where('is_active', true)->whereDoesntHave('roles')->pluck('email');
        $add('Pengguna aktif punya peran', $noRole->isEmpty(), $noRole->join(', '));
        $demo = Company::where('is_demo', true)->count() + User::where('email', 'like', '%@contoh.local')->count();
        $add('Tidak ada data contoh (wajib di produksi)', $demo === 0 || ! app()->isProduction(), $demo ? 'ada satker BLU/akun contoh → php artisan kinera:hapus-contoh' : '');
        $add('Jadwal (cron) untuk pengingat', true, '* * * * * php artisan schedule:run');
        $this->table(['Status', 'Pemeriksaan', 'Keterangan'], $rows);

        return self::SUCCESS;
    }
}
