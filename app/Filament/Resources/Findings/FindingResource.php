<?php

namespace App\Filament\Resources\Findings;

use App\Filament\Resources\Findings\Pages\ListFindings;
use App\Filament\Resources\Findings\Pages\CreateFinding;
use App\Filament\Resources\Findings\Pages\ViewFinding;
use App\Filament\Resources\Findings\Pages\EditFinding;
use App\Filament\Concerns\HasBscPermissions;
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

class FindingResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\Finding::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Temuan';

    protected static ?string $pluralModelLabel = 'Temuan';

    protected static ?string $navigationLabel = 'Temuan';

    protected static ?string $navigationGroupKey = 'punarbhawa';

    protected static ?string $viewPermission = 'finding.view';

    protected static ?string $editPermission = 'finding.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Temuan')->columns(3)->schema([
                TextInput::make('title')->label('Temuan')->required()->columnSpan(2),
                Select::make('severity')->label('Tingkat')->options(Models\Finding::SEVERITIES)->default('sedang')->native(false),
                Textarea::make('description')->label('Uraian')->rows(4)->columnSpanFull(),
                Select::make('source')->label('Sumber')->options(Models\Finding::SOURCES)->default('tinjauan')->native(false),
                Select::make('performance_review_id')->label('Tinjauan asal')->relationship('review', 'title')->searchable()->preload(),
                F::perspective(),
                F::money('amount', 'Nilai dampak'),
                Select::make('kpi_id')->label('IK terkait')->options(fn () => Models\Kpi::orderBy('code')->get()->mapWithKeys(fn ($k) => [$k->id => $k->label]))->searchable(),
                Select::make('strategic_initiative_id')->label('Inisiatif terkait')->relationship('initiative', 'title')->searchable()->preload(),
            ]),
            Section::make('Penutupan')->columns(3)->visibleOn(['view'])->schema([
                Placeholder::make('st')->label('Status')->content(fn ($record) => Models\Finding::STATUSES[$record?->status] ?? '-'),
                Placeholder::make('by')->label('Diajukan')->content(fn ($record) => ($record?->raiser?->name ?? '-').' · '.$record?->raised_at?->format('d/m/Y')),
                Placeholder::make('cl')->label('Ditutup')->content(fn ($record) => $record?->closed_at ? $record->closer?->name.' · '.$record->closed_at->format('d/m/Y') : '-'),
                Placeholder::make('cn')->label('Catatan penutupan')->content(fn ($record) => $record?->closure_note ?? '-')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('title')->label('Temuan')->wrap()->searchable()->description(fn ($record) => Models\Finding::SOURCES[$record->source] ?? $record->source),
                TextColumn::make('severity')->label('Tingkat')->badge()->color(fn ($state) => in_array($state, ['tinggi', 'kritis']) ? 'danger' : ($state === 'sedang' ? 'warning' : 'gray')),
                TextColumn::make('amount')->label('Nilai')->formatStateUsing(fn ($state) => $state ? Fmt::rp($state) : '-')->alignEnd()->toggleable(),
                TextColumn::make('corrective_actions_count')->label('Tindakan')->counts('correctiveActions'),
                F::statusColumn('status', Models\Finding::STATUSES, ['terbuka' => 'danger', 'dalam_tindak_lanjut' => 'warning', 'siap_ditutup' => 'info', 'ditutup' => 'success']),
                TextColumn::make('raised_at')->label('Tanggal')->date('d M Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(Models\Finding::STATUSES),
                SelectFilter::make('severity')->label('Tingkat')->options(Models\Finding::SEVERITIES),
                SelectFilter::make('source')->label('Sumber')->options(Models\Finding::SOURCES),
            ])
            ->defaultSort('raised_at', 'desc')
            ->recordActions([
                Action::make('tutup')->label('Tutup temuan')->icon('heroicon-o-check-circle')->color('success')
                    ->visible(fn ($record) => $record->status !== 'ditutup' && Auth::user()?->hasPermission('finding.close'))
                    ->disabled(fn ($record) => ! $record->allActionsDone())
                    ->tooltip(fn ($record) => $record->allActionsDone() ? null : 'Semua tindakan korektif harus selesai')
                    ->form([Textarea::make('closure_note')->label('Catatan penutupan')->required()->rows(3)])
                    ->action(function ($record, array $data) {
                        if (! $record->allActionsDone()) { Notification::make()->title('Masih ada tindakan korektif yang belum selesai')->danger()->send(); return; }
                        $record->update(['status' => 'ditutup', 'closed_by' => Auth::id(), 'closed_at' => now(), 'closure_note' => $data['closure_note']]);
                        Notification::make()->title('Temuan ditutup')->success()->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CorrectiveActionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFindings::route('/'),
            'create' => CreateFinding::route('/create'),
            'view' => ViewFinding::route('/{record}'),
            'edit' => EditFinding::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return static::user()?->hasAnyPermission(['finding.create', 'finding.edit']) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        $user = static::user();
        if (! $user || $record->status === 'ditutup' && ! $user->hasPermission('finding.close')) { return false; }

        return $user->hasPermission('finding.edit') && ($user->managesAllData() || (int) $record->raised_by === (int) $user->id);
    }

    public static function canDelete(Model $record): bool
    {
        return (bool) static::user()?->managesAllData();
    }
}
