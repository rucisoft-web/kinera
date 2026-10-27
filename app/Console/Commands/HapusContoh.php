<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use Illuminate\Console\Command;

/** Menghapus perusahaan contoh (is_demo) beserta seluruh datanya dan akun @contoh.local, sebelum go-live. */
class HapusContoh extends Command
{
    protected $signature = 'rucibsc:hapus-contoh {--force : Lewati konfirmasi}';

    protected $description = 'Hapus perusahaan contoh (Diamond Food, Hermina, Medco, Pakuwon, Kimia Farma, Semen Indonesia, MAP, GoTo, Astra, BCA, Telkom, Blue Bird, SMBC Indonesia, Mandiri Taspen, NLP, KSB) dan akun contoh sebelum dipakai produksi';

    public function handle(): int
    {
        $companies = Company::where('is_demo', true)->get();
        $users = User::where('email', 'like', '%@contoh.local')->get();
        if ($companies->isEmpty() && $users->isEmpty()) {
            $this->info('Tidak ada data contoh.');

            return self::SUCCESS;
        }
        $this->line('Perusahaan contoh: '.$companies->pluck('code')->join(', '));
        $this->line('Akun contoh: '.$users->count());
        if (! $this->option('force') && ! $this->confirm('Hapus permanen semua data di atas? Pastikan basis data sudah dicadangkan.')) {
            return self::FAILURE;
        }
        $users->each->delete();
        $companies->each->delete(); // tabel data perusahaan memakai cascadeOnDelete
        $this->info('Data contoh dihapus.');

        return self::SUCCESS;
    }
}
