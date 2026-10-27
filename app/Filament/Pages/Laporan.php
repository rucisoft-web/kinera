<?php

namespace App\Filament\Pages;

use App\Services\BscReportService;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

class Laporan extends Page
{
    protected static ?string $title = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string|\UnitEnum|null $navigationGroup = 'Umum';

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'laporan';

    protected string $view = 'filament.pages.laporan';

    #[Url]
    public string $r = 'scorecard';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('report.view');
    }

    public function setReport(string $r): void
    {
        $this->r = array_key_exists($r, BscReportService::REPORTS) ? $r : 'scorecard';
    }

    protected function getViewData(): array
    {
        $svc = app(BscReportService::class);
        $r = array_key_exists($this->r, BscReportService::REPORTS) ? $this->r : 'scorecard';

        return [
            'reports' => BscReportService::REPORTS,
            'current' => $r,
            'data' => match ($r) {
                'scorecard' => $svc->scorecard(),
                'objectives' => $svc->objectives(),
                'initiatives' => $svc->initiatives(),
                'findings' => $svc->findings(),
            },
            'canExport' => auth()->user()->hasPermission('report.export'),
        ];
    }
}
