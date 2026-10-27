<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use Illuminate\Console\Command;

/** Menghapus satker BLU contoh (is_demo) beserta seluruh datanya dan akun @contoh.local, sebelum go-live. */
class HapusContoh extends Command
{
    protected $signature = 'kinera:hapus-contoh {--force : Lewati konfirmasi}';

    protected $description = 'Hapus satker BLU contoh (UIN Sunan Kalijaga — data demo) dan akun contoh sebelum dipakai produksi';

    public function handle(): int
    {
        $companies = Company::where('is_demo', true)->get();
        $users = User::where('email', 'like', '%@contoh.local')->get();
        if ($companies->isEmpty() && $users->isEmpty()) {
            $this->info('Tidak ada data contoh.');

            return self::SUCCESS;
        }
        $this->line('Satker BLU contoh: '.$companies->pluck('code')->join(', '));
        $this->line('Akun contoh: '.$users->count());
        if (! $this->option('force') && ! $this->confirm('Hapus permanen semua data di atas? Pastikan basis data sudah dicadangkan.')) {
            return self::FAILURE;
        }
        $users->each->delete();
        $companies->each->delete(); // tabel data satker BLU memakai cascadeOnDelete
        $this->info('Data contoh dihapus.');

        return self::SUCCESS;
    }
}
