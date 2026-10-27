<?php

namespace App\Filament\Pages;

use App\Models\DataImport;
use App\Services\Import\BscImporter;
use App\Services\Import\BscTemplate;
use App\Services\Import\ImportResult;
use App\Support\BscContext;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/** Upload workbook Excel untuk mengisi data satker BLU & perioda yang sedang aktif. */
class ImportData extends Page
{
    use WithFileUploads;

    protected static ?string $title = 'Import Data Satker BLU';

    protected static ?string $navigationLabel = 'Import Data';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static string|\UnitEnum|null $navigationGroup = 'Administrasi';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'import-data';

    protected string $view = 'filament.pages.import-data';

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null */
    public $file = null;

    public bool $approveActuals = false;

    /** Hasil pratinjau terakhir (ringkasan & masalah), dikosongkan saat file berganti. */
    public ?array $preview = null;

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('import.manage');
    }

    public function updatedFile(): void
    {
        $this->preview = null;
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        $file = app(BscTemplate::class)->blank(storage_path('app/private/exports/template-kinera-'.Str::random(6).'.xlsx'));

        return response()->download($file, 'template-kinera.xlsx')->deleteFileAfterSend();
    }

    public function downloadCurrent(): BinaryFileResponse
    {
        $period = app(BscContext::class)->period();
        abort_unless($period, 404);
        $name = Str::slug($period->company->code.'-'.$period->name).'.xlsx';
        $file = app(BscTemplate::class)->export(storage_path('app/private/exports/'.Str::random(8).'-'.$name), $period);

        return response()->download($file, $name)->deleteFileAfterSend();
    }

    public function previewImport(): void
    {
        $result = $this->runImport(true);
        $this->preview = $result ? $this->toArray($result) : null;
    }

    public function processImport(): void
    {
        $result = $this->runImport(false);
        if (! $result) {
            return;
        }
        $this->preview = $this->toArray($result);
        $this->reset('file');
        $note = Notification::make()->title('Import selesai: '.$result->rowsOk().' baris diproses, '.$result->rowsFailed().' baris gagal');
        ($result->rowsFailed() ? $note->warning() : $note->success())->send();
    }

    public function rollback(int $importId): void
    {
        $import = DataImport::where('company_id', app(BscContext::class)->companyId())->findOrFail($importId);
        if (! $import->canRollback()) {
            Notification::make()->title('Hanya import terakhir yang belum dibatalkan yang bisa dibatalkan.')->danger()->send();

            return;
        }
        app(BscImporter::class)->rollback($import, auth()->user());
        $this->preview = null;
        Notification::make()->title('Import '.$import->file_name.' dibatalkan; data dikembalikan seperti sebelum upload.')->success()->send();
    }

    protected function runImport(bool $dryRun): ?ImportResult
    {
        $this->validate(['file' => 'required|file|max:10240|mimes:xlsx'], [
            'file.required' => 'Pilih file Excel (.xlsx) terlebih dulu.',
            'file.mimes' => 'File harus berformat Excel .xlsx (unduh template di atas).',
        ]);
        $period = app(BscContext::class)->period();
        if (! $period) {
            Notification::make()->title('Belum ada perioda aktif untuk satker BLU ini. Buat perioda di menu Satker BLU.')->danger()->send();

            return null;
        }
        $name = $this->file->getClientOriginalName();
        $options = ['approve_actuals' => $this->approveActuals, 'file_name' => $name];
        if (! $dryRun) {
            $options['file_path'] = $this->file->storeAs('imports/'.$period->company->code, now()->format('Ymd_His').'_'.Str::slug(pathinfo($name, PATHINFO_FILENAME)).'.xlsx', 'local');
        }

        return app(BscImporter::class)->run($this->file->getRealPath(), $period, auth()->user(), $options, $dryRun);
    }

    protected function toArray(ImportResult $r): array
    {
        return ['dry' => $r->dryRun, 'summary' => $r->summary, 'issues' => array_slice($r->issues, 0, 300), 'issues_total' => count($r->issues),
            'ok' => $r->rowsOk(), 'failed' => $r->rowsFailed()];
    }

    protected function getViewData(): array
    {
        $ctx = app(BscContext::class);

        return [
            'company' => $ctx->company(),
            'period' => $ctx->period(),
            'canApprove' => auth()->user()->hasPermission('kpi.approve_actual'),
            'history' => DataImport::with('user')->where('company_id', $ctx->companyId())->where('analysis_period_id', $ctx->periodId())
                ->latest('id')->limit(15)->get(),
        ];
    }
}
