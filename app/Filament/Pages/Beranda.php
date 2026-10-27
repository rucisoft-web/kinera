<?php

namespace App\Filament\Pages;

use App\Services\ExecutiveDashboardService;
use App\Services\HomeDashboardService;
use App\Support\BscContext;
use App\Support\Terms;
use Filament\Pages\Dashboard;

class Beranda extends Dashboard
{
    protected static ?string $title = 'Beranda';

    protected static ?string $navigationLabel = 'Beranda';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static string|\UnitEnum|null $navigationGroup = 'Umum';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.beranda';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('dashboard.view') || (bool) auth()->user();
    }

    public function getTitle(): string
    {
        return 'Beranda';
    }

    protected function getViewData(): array
    {
        $user = auth()->user();
        $ctx = app(BscContext::class);
        $canDash = $user->hasPermission('dashboard.view');

        return [
            'user' => $user,
            'company' => $ctx->company(),
            'period' => $ctx->period(),
            'monthName' => ($m = $ctx->lastClosedMonth()) ? Terms::months()[$m] : null,
            'tasks' => app(HomeDashboardService::class)->tasks($user),
            'exec' => $canDash ? app(ExecutiveDashboardService::class)->data() : null,
            'roles' => $user->rolesFor($ctx->companyId())->pluck('name')->join(', '),
        ];
    }
}
