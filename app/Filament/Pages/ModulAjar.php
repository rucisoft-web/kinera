<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

/** Modul ajar Strategic Leadership Lab: tujuan, konsep, studi kasus demo, lembar kerja di akun latihan, diskusi, dan kuis per sesi workshop. */
class ModulAjar extends Page
{
    protected static ?string $title = 'Modul Ajar BSC';

    protected static ?string $navigationLabel = 'Modul Ajar BSC';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Panduan';

    protected static ?int $navigationSort = 6;

    protected static ?string $slug = 'modul-ajar';

    protected string $view = 'filament.pages.modul-ajar';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    protected function getViewData(): array
    {
        return ['sessions' => require resource_path('modul/sesi.php')];
    }
}
