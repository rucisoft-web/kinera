<?php

namespace App\Filament\Pages;

use App\Services\InitiativePortfolioService;
use App\Support\Terms;
use Filament\Pages\Page;

class PortofolioInisiatif extends Page
{
    protected static ?string $title = 'Portofolio Inisiatif Strategis';

    protected static ?string $navigationLabel = 'Portofolio Inisiatif';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'portofolio-inisiatif';

    protected string $view = 'filament.pages.portofolio-inisiatif';

    public static function getNavigationGroup(): ?string
    {
        return Terms::get('punarbhawa');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('initiative.view');
    }

    protected function getViewData(): array
    {
        $rows = app(InitiativePortfolioService::class)->portfolio();

        return ['rows' => $rows, 'quadrants' => InitiativePortfolioService::QUADRANTS];
    }
}
