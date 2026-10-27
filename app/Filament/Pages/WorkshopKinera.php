<?php

namespace App\Filament\Pages;

use Carbon\CarbonImmutable;
use Filament\Pages\Page;

/** Rundown dan materi Workshop Kinera sehari, setiap hari Minggu pertama awal bulan. */
class WorkshopKinera extends Page
{
    protected static ?string $title = 'Workshop Kinera';

    protected static ?string $navigationLabel = 'Workshop Kinera';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Panduan';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'workshop-kinera';

    protected string $view = 'filament.pages.workshop-kinera';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    /** Minggu pertama tiap bulan, mulai dari bulan berjalan bila tanggalnya belum lewat. */
    public static function nextDates(int $count = 4, ?CarbonImmutable $today = null): array
    {
        $today ??= CarbonImmutable::now('Asia/Jakarta')->startOfDay();
        $dates = [];
        for ($month = $today->startOfMonth(); count($dates) < $count; $month = $month->addMonth()) {
            $sunday = $month->dayOfWeek === 0 ? $month : $month->next(CarbonImmutable::SUNDAY);
            if ($sunday->gte($today)) {
                $dates[] = $sunday;
            }
        }

        return $dates;
    }

    protected function getViewData(): array
    {
        return [
            'dates' => static::nextDates(),
            'workshopUrl' => config('bsc.workshop_url'),
            'workshopContact' => config('bsc.workshop_contact'),
        ];
    }
}
