<?php

namespace App\Filament\Pages;

use App\Support\Terms;
use Filament\Pages\Page;

/** Penjelasan Kinera untuk pimpinan instansi pemerintah dan perguruan tinggi negeri, dengan ajakan ikut Workshop. */
class KenaliKinera extends Page
{
    protected static ?string $title = 'Kenali Kinera';

    protected static ?string $navigationLabel = 'Kenali Kinera';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-light-bulb';

    protected static string|\UnitEnum|null $navigationGroup = 'Panduan';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'kenali-kinera';

    protected string $view = 'filament.pages.kenali-kinera';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    protected function getViewData(): array
    {
        $c = config('bsc.cycle');

        return [
            'workshopUrl' => \App\Filament\Pages\WorkshopKinera::registerUrl(),
            'workshopContact' => config('bsc.workshop_contact'),
            'cycle' => [
                ['Tgl '.$c['reminder_day'], 'Pengingat', 'Sistem mengingatkan setiap penanggung jawab IK yang realisasinya belum masuk.'],
                ['Tgl '.$c['input_day'], 'Input', 'Penanggung jawab IK mengisi realisasi bulan lalu beserta bukti dan penjelasannya.'],
                ['Tgl '.$c['verify_day'], 'Verifikasi', 'Keuangan mencocokkan angka IK aspek keuangan dengan laporan keuangan.'],
                ['Tgl '.$c['approve_day'], 'Persetujuan', 'Pimpinan menyetujui; angka terkunci dan skor dihitung ulang otomatis.'],
                ['Tgl '.$c['meeting_day'], 'Rapat', 'Rapat manajemen membahas sasaran merah/kuning, memutuskan tindakan korektif.'],
            ],
            'phases' => collect(Terms::PHASES)->map(fn ($k) => Terms::get($k))->all(),
            'perspectives' => collect(['keuangan', 'pelanggan', 'proses', 'sdm'])
                ->mapWithKeys(fn ($k) => [$k => ['label' => Terms::get($k), 'color' => Terms::perspectiveColor($k)]])->all(),
        ];
    }
}
