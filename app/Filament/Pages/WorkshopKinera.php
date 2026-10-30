<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

/** Rundown dan materi Workshop Kinera 2 hari bersama PSMPT UIN Sunan Kalijaga, sama dengan brosur. */
class WorkshopKinera extends Page
{
    protected static ?string $title = 'Workshop Kinera';

    protected static ?string $navigationLabel = 'Workshop Kinera';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Panduan';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'workshop-kinera';

    protected string $view = 'filament.pages.workshop-kinera';

    public const BROCHURE = 'files/brosur-kinera-workshop-2-hari.pdf';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    /** Tautan pendaftaran: KINERA_WORKSHOP_URL bila diisi, selain itu WhatsApp kontak dengan pesan siap kirim. */
    public static function registerUrl(): string
    {
        return config('bsc.workshop_url') ?: 'https://wa.me/'.config('bsc.contact_whatsapp').'?text='
            .rawurlencode('Halo Rucisoft, saya ingin mendaftar Workshop Kinera 2 hari bersama PSMPT UIN Sunan Kalijaga.');
    }

    protected function getViewData(): array
    {
        return [
            'registerUrl' => static::registerUrl(),
            'brochureUrl' => asset(static::BROCHURE),
            'workshopContact' => config('bsc.workshop_contact'),
            'whatsapp' => preg_replace('/^0(\d{3})(\d{4})(\d+)$/', '0$1-$2-$3', preg_replace('/^62/', '0', (string) config('bsc.contact_whatsapp'))),
            'email' => config('bsc.contact_email'),
            'place' => config('bsc.workshop_place'),
            'date' => config('bsc.workshop_date'),
            'fee' => config('bsc.workshop_fee'),
        ];
    }
}
