<?php

namespace App\Filament\Pages;

use App\Services\StrategyMapService;
use Filament\Pages\Page;

class PetaStrategi extends Page
{
    protected static ?string $title = 'Peta Strategi';

    protected static ?string $navigationLabel = 'Peta Strategi';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static string|\UnitEnum|null $navigationGroup = 'Umum';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'peta-strategi';

    protected string $view = 'filament.pages.peta-strategi';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('strategy.map');
    }

    protected function getViewData(): array
    {
        return ['map' => app(StrategyMapService::class)->build()];
    }
}
