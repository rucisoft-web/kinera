<?php

namespace App\Filament\Pages;

use App\Services\OperationalWorkflowService;
use Filament\Pages\Page;

class AlurKerja extends Page
{
    protected static ?string $title = 'Alur Kerja Kinera';

    protected static ?string $navigationLabel = 'Alur Kerja Kinera';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static string|\UnitEnum|null $navigationGroup = 'Umum';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'alur-kerja';

    protected string $view = 'filament.pages.alur-kerja';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('dashboard.view');
    }

    protected function getViewData(): array
    {
        $svc = app(OperationalWorkflowService::class);

        return ['rhythm' => $svc->rhythm(), 'cycle' => $svc->cycle()];
    }
}
