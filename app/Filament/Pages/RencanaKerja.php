<?php

namespace App\Filament\Pages;

use App\Models\WorkPlanDraft;
use App\Services\WorkPlanService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\WithFileUploads;

/** Menyusun Draf Rencana Kerja tahunan dari isian Kinera dan lampiran, dengan Claude (atau mode contoh tanpa kunci API). */
class RencanaKerja extends Page
{
    use WithFileUploads;

    protected static ?string $title = 'Rencana Kerja (AI)';

    protected static ?string $navigationLabel = 'Rencana Kerja (AI)';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|\UnitEnum|null $navigationGroup = 'Umum';

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'rencana-kerja';

    protected string $view = 'filament.pages.rencana-kerja';

    public ?int $year = null;

    public string $instructions = '';

    /** @var array<int,\Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $files = [];

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('report.view');
    }

    public function mount(WorkPlanService $svc): void
    {
        $this->year = $svc->years()[1];
    }

    public function canGenerate(): bool
    {
        return (bool) auth()->user()?->hasPermission('report.export');
    }

    public function generate(WorkPlanService $svc): void
    {
        abort_unless($this->canGenerate(), 403);
        $this->validate([
            'year' => 'required|integer|in:'.implode(',', $svc->years()),
            'instructions' => 'nullable|string|max:3000',
            'files' => 'array|max:'.WorkPlanService::MAX_FILES,
            'files.*' => 'file|max:'.WorkPlanService::MAX_KB.'|mimes:'.implode(',', WorkPlanService::MIMES),
        ], [
            'files.max' => 'Maksimal '.WorkPlanService::MAX_FILES.' lampiran.',
            'files.*.mimes' => 'Lampiran harus PDF, DOCX, TXT, MD, atau CSV.',
            'files.*.max' => 'Ukuran lampiran maksimal 10 MB.',
        ]);

        @set_time_limit(300);
        $draft = $svc->generate((int) $this->year, trim($this->instructions), $this->files, auth()->user());
        $this->reset('files');

        $draft->status === 'selesai'
            ? Notification::make()->success()->title('Draf Rencana Kerja '.$draft->year.' selesai disusun')->send()
            : Notification::make()->danger()->title('Penyusunan gagal')->body($draft->error)->persistent()->send();
    }

    public function removeFile(int $i): void
    {
        array_splice($this->files, $i, 1);
    }

    protected function getViewData(): array
    {
        $svc = app(WorkPlanService::class);

        return [
            'years' => $svc->years(),
            'ai' => $svc->aiAvailable(),
            'canGenerate' => $this->canGenerate(),
            'drafts' => WorkPlanDraft::with('creator')->latest()->limit(10)->get(),
        ];
    }
}
