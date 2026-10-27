<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

/** Panduan implementasi Ruci BSC di sebuah satker BLU: rencana proyek lengkap dari prasyarat sampai serah terima. */
class PanduanImplementasi extends Page
{
    protected static ?string $title = 'Panduan Implementasi';

    protected static ?string $navigationLabel = 'Panduan Implementasi';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|\UnitEnum|null $navigationGroup = 'Panduan';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'panduan-implementasi';

    protected string $view = 'filament.pages.panduan-implementasi';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    protected function getViewData(): array
    {
        return [
            'workshopUrl' => config('dhastram.workshop_url'),
            'workshopContact' => config('dhastram.workshop_contact'),
        ];
    }
}
