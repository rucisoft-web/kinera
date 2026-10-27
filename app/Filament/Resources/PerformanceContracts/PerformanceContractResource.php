<?php

namespace App\Filament\Resources\PerformanceContracts;

use App\Filament\Concerns\HasBscPermissions;
use App\Filament\Resources\PerformanceContracts\Pages\CreatePerformanceContract;
use App\Filament\Resources\PerformanceContracts\Pages\EditPerformanceContract;
use App\Filament\Resources\PerformanceContracts\Pages\ListPerformanceContracts;
use App\Filament\Resources\PerformanceContracts\Pages\ViewPerformanceContract;
use App\Models\PerformanceContract;
use App\Services\BluContractService;
use App\Support\BscContext;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

/** Kontrak Kinerja Pemimpin BLU beserta perubahannya (PER-6/PB/2025 Bab II & Bab V). */
class PerformanceContractResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = PerformanceContract::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Kontrak Kinerja';

    protected static ?string $pluralModelLabel = 'Kontrak Kinerja';

    protected static ?string $navigationLabel = 'Perjanjian & Kontrak Kinerja';

    protected static ?string $navigationGroupKey = 'karma_phala';

    protected static ?string $viewPermission = 'kpi.view';

    protected static ?string $editPermission = 'kpi.edit';

    protected static ?string $recordTitleAttribute = 'number';

    public static function form(Schema $schema): Schema
    {
        $yn = [1 => 'Ya', 0 => 'Tidak'];

        return $schema->columns(1)->components([
            Section::make('Dokumen Kontrak Kinerja')->columns(3)->schema([
                Placeholder::make('versi')->label('Versi')->content(fn ($record) => $record ? $record->label : 'Diberikan otomatis: kontrak pertama perioda ini = versi awal, berikutnya = perubahan.'),
                Select::make('kind')->label('Jenis')->options(PerformanceContract::KINDS)->default('awal')->required()->native(false)->live(),
                Select::make('status')->label('Status')->options(PerformanceContract::STATUSES)->default('draf')->required()->native(false),
                TextInput::make('number')->label('Nomor Kontrak Kinerja'),
                Select::make('basis')->label('Dasar perubahan')->options(PerformanceContract::BASES)->native(false)
                    ->visible(fn (Get $get) => $get('kind') !== 'awal')->required(fn (Get $get) => $get('kind') !== 'awal')->columnSpan(2),
                Textarea::make('reason')->label('Alasan / hasil kajian perubahan')->rows(3)->columnSpanFull()->visible(fn (Get $get) => $get('kind') !== 'awal'),
                TextInput::make('technical_approval')->label('Surat persetujuan Pembina Teknis')->helperText('Pasal 5 ayat (10) & Pasal 19 ayat (3)')->columnSpan(2),
                DatePicker::make('proposed_at')->label('Tanggal usulan ke DJPb'),
                DatePicker::make('discussed_at')->label('Tanggal pembahasan (BA)'),
                TextInput::make('ba_number')->label('Nomor Berita Acara pembahasan'),
                TextInput::make('signed_place')->label('Tempat penandatanganan')->default('Jakarta'),
                DatePicker::make('leader_signed_at')->label('Ditandatangani pimpinan'),
                DatePicker::make('dg_signed_at')->label('Ditandatangani Dirjen Perbendaharaan'),
                Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
            ]),
            Section::make('Pernyataan syarat insentif (Pasal 14 ayat 4 huruf g–h)')->columns(3)->collapsed()->schema([
                Select::make('legal_case_free')->label('Bebas kasus hukum berputusan pengadilan tingkat pertama')->options($yn)->native(false),
                Select::make('other_requirements_met')->label('Persyaratan lain Pembina Teknis/Keuangan terpenuhi')->options($yn)->native(false),
                Textarea::make('incentive_notes')->label('Keterangan persyaratan lain')->rows(2),
            ]),
            Section::make('Indikator Kinerja, trajektori & Definisi Operasional')->visible(fn ($record) => $record !== null)->schema([
                Placeholder::make('indikator')->hiddenLabel()
                    ->content(fn ($record) => new HtmlString(view('blu.contract-indicators', ['contract' => $record])->render())),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('version')->label('Versi')->formatStateUsing(fn ($state) => $state === 0 ? 'Awal' : 'Ke-'.$state)->sortable(),
                TextColumn::make('kind')->label('Jenis')->formatStateUsing(fn ($state) => PerformanceContract::KINDS[$state] ?? $state)
                    ->description(fn ($record) => $record->basis ? (PerformanceContract::BASES[$record->basis] ?? null) : null)->wrap(),
                TextColumn::make('number')->label('Nomor')->placeholder('-')->searchable(),
                TextColumn::make('indicators')->label('Indikator')->state(fn ($record) => count($record->indicators ?? []).' IK')->alignCenter(),
                TextColumn::make('proposed_at')->label('Diusulkan')->date('d M Y')->placeholder('-'),
                TextColumn::make('dg_signed_at')->label('Ditandatangani')->date('d M Y')->placeholder('-'),
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn ($state) => PerformanceContract::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => PerformanceContract::STATUS_COLORS[$state] ?? 'gray'),
            ])
            ->defaultSort('version', 'desc')
            ->recordActions([ViewAction::make(), EditAction::make()]);
    }

    /** Ambil ulang Indikator Kinerja & target terbaru ke dalam kontrak yang masih draf/diusulkan. */
    public static function refreshAction(): Action
    {
        return Action::make('refresh')->label('Ambil target terbaru')->icon('heroicon-o-arrow-path')->color('gray')
            ->visible(fn ($record) => in_array($record->status, ['draf', 'diusulkan'], true))
            ->requiresConfirmation()->modalDescription('Potret Indikator Kinerja, target trajektori, dan Definisi Operasional diganti dengan isi menu Indikator Kinerja saat ini.')
            ->action(function (PerformanceContract $record) {
                $previous = $record->version > 0 ? PerformanceContract::where('status', 'ditandatangani')->where('version', '<', $record->version)->orderByDesc('version')->first() : null;
                $record->update(['indicators' => app(BluContractService::class)->snapshot($previous)]);
                Notification::make()->title('Indikator & target diperbarui')->success()->send();
            });
    }

    /** @return list<Action> */
    public static function printActions(): array
    {
        $docs = [
            'kontrak' => ['Kontrak Kinerja (Lampiran I)', 'heroicon-o-document-text'],
            'berita-acara' => ['Berita Acara pembahasan (Lampiran II)', 'heroicon-o-clipboard-document-check'],
            'definisi-operasional' => ['Definisi Operasional Indikator Kinerja', 'heroicon-o-book-open'],
            'capaian' => ['Perhitungan capaian (format Lampiran VI)', 'heroicon-o-calculator'],
            'pernyataan' => ['Surat pernyataan kebenaran perhitungan (Lampiran IV)', 'heroicon-o-pencil-square'],
        ];

        return collect($docs)->map(fn ($d, $key) => Action::make('cetak_'.str_replace('-', '_', $key))->label($d[0])->icon($d[1])
            ->url(fn ($record) => route('blu.print', ['doc' => $key, 'contract' => $record->id]), shouldOpenInNewTab: true))->values()->all();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPerformanceContracts::route('/'),
            'create' => CreatePerformanceContract::route('/create'),
            'view' => ViewPerformanceContract::route('/{record}'),
            'edit' => EditPerformanceContract::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        if (! app(BscContext::class)->periodId()) {
            return null;
        }
        $open = PerformanceContract::whereIn('status', ['draf', 'diusulkan', 'disepakati'])->count();

        return $open ? (string) $open : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
