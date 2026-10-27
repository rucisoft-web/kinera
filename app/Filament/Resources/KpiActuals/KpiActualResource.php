<?php

namespace App\Filament\Resources\KpiActuals;

use App\Filament\Resources\KpiActuals\Pages\ListKpiActuals;
use App\Filament\Resources\KpiActuals\Pages\CreateKpiActual;
use App\Filament\Resources\KpiActuals\Pages\ViewKpiActual;
use App\Filament\Resources\KpiActuals\Pages\EditKpiActual;
use App\Filament\Concerns\HasDhastramPermissions;
use App\Filament\Support\F;
use App\Support\Fmt;
use App\Support\Terms;
use App\Models;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class KpiActualResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\KpiActual::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 7;

    protected static ?string $modelLabel = 'Realisasi IK';

    protected static ?string $pluralModelLabel = 'Realisasi IK';

    protected static ?string $navigationLabel = 'Realisasi IK';

    protected static ?string $navigationGroupKey = 'karma_phala';

    protected static ?string $viewPermission = 'kpi.view';

    protected static ?string $editPermission = 'kpi.input_actual';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Realisasi')->columns(3)->schema([
                Select::make('kpi_id')->label('IK')->required()->searchable()->live()
                    ->options(fn () => static::inputableKpis()->mapWithKeys(fn ($k) => [$k->id => $k->label.' ('.$k->unit.')']))
                    ->disabled(fn (string $operation) => $operation !== 'create')->dehydrated(fn (string $operation) => $operation === 'create')->columnSpan(2),
                Select::make('month')->label('Bulan')->options(Terms::months())->required()->native(false)
                    ->disabled(fn (string $operation) => $operation !== 'create')->dehydrated(fn (string $operation) => $operation === 'create'),
                Placeholder::make('target_info')->label('Target bulan ini')->content(function (Get $get) {
                    $kpi = Models\Kpi::find($get('kpi_id'));
                    if (! $kpi || ! $get('month')) { return '-'; }
                    return Fmt::kpiValue($kpi->targetFor((int) $get('month')), $kpi->unit, $kpi->decimals).' · '.($kpi->direction === 'lower' ? 'makin kecil makin baik' : 'makin besar makin baik');
                }),
                TextInput::make('actual')->label('Nilai realisasi')->numeric()->required(),
                Toggle::make('is_projection')->label('Masih proyeksi / belum final')->visible(fn () => Auth::user()?->managesAllData()),
                Textarea::make('evidence')->label('Bukti / sumber angka')->rows(2)->columnSpanFull()->helperText('Mis. laporan keuangan bulan …, laporan sistem, notulen.'),
                FileUpload::make('evidence_path')->label('Lampiran bukti')->directory('bukti-kpi')->disk('local')->visibility('private')->downloadable()->columnSpanFull(),
                Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
            ]),
            Section::make('Status persetujuan')->columns(3)->visibleOn(['view', 'edit'])->schema([
                Placeholder::make('st')->label('Status')->content(fn ($record) => Models\KpiActual::STATUSES[$record?->status] ?? '-'),
                Placeholder::make('sub')->label('Diisi oleh')->content(fn ($record) => ($record?->submitter?->name ?? '-').($record?->submitted_at ? ' · '.$record->submitted_at->format('d/m/Y H:i') : '')),
                Placeholder::make('ver')->label('Diverifikasi')->content(fn ($record) => $record?->verified_at ? $record->verifier?->name.' · '.$record->verified_at->format('d/m/Y H:i') : '-'),
                Placeholder::make('app')->label('Disetujui')->content(fn ($record) => $record?->approved_at ? $record->approver?->name.' · '.$record->approved_at->format('d/m/Y H:i') : '-'),
                Placeholder::make('ret')->label('Catatan pengembalian')->content(fn ($record) => $record?->return_note ?? '-')->columnSpan(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kpi.code')->label('Kode')->formatStateUsing(fn ($state) => Fmt::shortCode($state))->sortable(),
                TextColumn::make('kpi.name')->label('IK')->wrap()->grow()->extraAttributes(['style' => 'min-width:16rem'])->searchable()->description(fn ($record) => $record->kpi?->owner?->name),
                TextColumn::make('month')->label('Bulan')->formatStateUsing(fn ($state) => Terms::monthShort($state))->sortable(),
                TextColumn::make('actual')->label('Realisasi')->formatStateUsing(fn ($state, $record) => Fmt::kpiValue($state, $record->kpi?->unit, $record->kpi?->decimals ?? 0))->alignEnd(),
                TextColumn::make('target')->label('Target')->state(fn ($record) => Fmt::kpiValue($record->target, $record->kpi?->unit, $record->kpi?->decimals ?? 0))->alignEnd(),
                F::scoreColumn('score')->state(fn ($record) => $record->score),
                F::statusColumn('status', Models\KpiActual::STATUSES, ['draf' => 'gray', 'terverifikasi' => 'info', 'disetujui' => 'success', 'dikembalikan' => 'danger']),
                IconColumn::make('is_projection')->label('Proyeksi')->boolean()->trueIcon('heroicon-o-exclamation-triangle')->trueColor('warning')->falseIcon('heroicon-o-document-check')->falseColor('success')->tooltip(fn ($record) => $record->notes),
                IconColumn::make('kpi.is_financial')->label('Keu.')->boolean()->falseIcon('heroicon-o-minus')->falseColor('gray')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(Models\KpiActual::STATUSES),
                SelectFilter::make('month')->label('Bulan')->options(Terms::months()),
                SelectFilter::make('kpi_id')->label('IK')->options(fn () => Models\Kpi::orderBy('code')->get()->mapWithKeys(fn ($k) => [$k->id => $k->label]))->searchable(),
                TernaryFilter::make('is_projection')->label('Proyeksi'),
                Filter::make('financial')->label('IK yang wajib verifikasi Keuangan')->query(fn (Builder $query) => $query->whereHas('kpi', fn ($k) => $k->where('is_financial', true))),
                Filter::make('mine')->label('IK milik saya')->query(fn (Builder $query) => $query->whereHas('kpi', fn ($k) => $k->where('owner_id', Auth::id()))),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['kpi.targets', 'kpi.owner']))
            ->defaultSort('month', 'desc')
            ->recordActions([
                Action::make('verifikasi')->label('Verifikasi')->iconButton()->tooltip('Verifikasi')->icon('heroicon-o-check-badge')->color('info')
                    ->visible(fn ($record) => static::approval()->canVerify(Auth::user(), $record))
                    ->form([Textarea::make('note')->label('Catatan verifikasi')->rows(2)])
                    ->action(function ($record, array $data) { static::approval()->verify(Auth::user(), $record, $data['note'] ?? null); Notification::make()->title('Realisasi terverifikasi')->success()->send(); }),
                Action::make('setujui')->label('Setujui')->iconButton()->tooltip('Setujui')->icon('heroicon-o-lock-closed')->color('success')->requiresConfirmation()
                    ->modalDescription('Setelah disetujui, realisasi terkunci. Koreksi hanya oleh Pimpinan dan tercatat di log audit.')
                    ->visible(fn ($record) => static::approval()->canApprove(Auth::user(), $record))
                    ->action(function ($record) { static::approval()->approve(Auth::user(), $record); Notification::make()->title('Realisasi disetujui & dikunci')->success()->send(); }),
                Action::make('kembalikan')->label('Kembalikan')->iconButton()->tooltip('Kembalikan')->icon('heroicon-o-arrow-uturn-left')->color('danger')
                    ->visible(fn ($record) => static::approval()->canReturn(Auth::user(), $record))
                    ->form([Textarea::make('note')->label('Alasan pengembalian')->required()->rows(3)])
                    ->action(function ($record, array $data) { static::approval()->return(Auth::user(), $record, $data['note']); Notification::make()->title('Realisasi dikembalikan ke penanggung jawab IK')->warning()->send(); }),
                ViewAction::make()->iconButton(),
                EditAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('verifikasi_massal')->label('Verifikasi terpilih')->icon('heroicon-o-check-badge')->color('info')->requiresConfirmation()
                        ->visible(fn () => Auth::user()?->hasPermission('kpi.verify_finance'))
                        ->action(function ($records) { $n = 0; foreach ($records as $r) { if (static::approval()->canVerify(Auth::user(), $r)) { static::approval()->verify(Auth::user(), $r); $n++; } } Notification::make()->title($n.' realisasi terverifikasi')->success()->send(); }),
                    BulkAction::make('setujui_massal')->label('Setujui terpilih')->icon('heroicon-o-lock-closed')->color('success')->requiresConfirmation()
                        ->visible(fn () => Auth::user()?->hasPermission('kpi.approve_actual'))
                        ->action(function ($records) { $n = 0; foreach ($records as $r) { if (static::approval()->canApprove(Auth::user(), $r)) { static::approval()->approve(Auth::user(), $r); $n++; } } Notification::make()->title($n.' realisasi disetujui')->body('Baris IK yang wajib verifikasi Keuangan yang belum diverifikasi dilewati.')->success()->send(); }),
                    DeleteBulkAction::make()->visible(fn () => Auth::user()?->managesAllData()),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKpiActuals::route('/'),
            'create' => CreateKpiActual::route('/create'),
            'view' => ViewKpiActual::route('/{record}'),
            'edit' => EditKpiActual::route('/{record}/edit'),
        ];
    }
    public static function approval(): \App\Services\KpiApprovalService
    {
        return app(\App\Services\KpiApprovalService::class);
    }

    /** IK yang boleh diisi pengguna: miliknya, kecuali pengelola. */
    public static function inputableKpis()
    {
        $user = Auth::user();

        return Models\Kpi::where('is_active', true)->when(! $user?->managesAllData(), fn ($q) => $q->where('owner_id', $user?->id))->orderBy('code')->get();
    }

    public static function canCreate(): bool
    {
        return static::allows('kpi.input_actual');
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        return $user !== null && static::approval()->canEdit($user, $record);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();

        return $user !== null && ! $record->isLocked() && ($user->managesAllData() || ($record->status !== 'terverifikasi' && $record->kpi?->isOwnedBy($user) && $user->hasPermission('kpi.input_actual')));
    }
}
