<?php

namespace App\Filament\Pages;

use App\Services\ExecutiveDashboardService;
use App\Services\StrategicScoreService;
use App\Support\BscContext;
use Filament\Pages\Page;

class PusatKendali extends Page
{
    protected static ?string $title = 'Pusat Kendali Eksekutif';

    protected static ?string $navigationLabel = 'Pusat Kendali Eksekutif';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string|\UnitEnum|null $navigationGroup = 'Umum';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'pusat-kendali';

    protected string $view = 'filament.pages.pusat-kendali';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('dashboard.view');
    }

    protected function getViewData(): array
    {
        $data = app(ExecutiveDashboardService::class)->data();
        $last = app(BscContext::class)->lastClosedMonth();
        $data['trend'] = $last > 0 ? app(StrategicScoreService::class)->trend($last) : [];

        return $data;
    }
}
