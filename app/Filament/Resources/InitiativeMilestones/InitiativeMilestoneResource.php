<?php

namespace App\Filament\Resources\InitiativeMilestones;

use App\Filament\Resources\InitiativeMilestones\Pages\ListInitiativeMilestones;
use App\Filament\Resources\InitiativeMilestones\Pages\CreateInitiativeMilestone;
use App\Filament\Resources\InitiativeMilestones\Pages\ViewInitiativeMilestone;
use App\Filament\Resources\InitiativeMilestones\Pages\EditInitiativeMilestone;
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

class InitiativeMilestoneResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\InitiativeMilestone::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-flag';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Milestone Inisiatif';

    protected static ?string $pluralModelLabel = 'Milestone Inisiatif';

    protected static ?string $navigationLabel = 'Milestone Inisiatif';

    protected static ?string $navigationGroupKey = 'punarbhawa';

    protected static ?string $viewPermission = 'initiative.view';

    protected static ?string $editPermission = 'initiative.edit';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                Select::make('strategic_initiative_id')->label('Inisiatif')->options(fn () => \App\Filament\Resources\InitiativeActivities\InitiativeActivityResource::myInitiatives()->mapWithKeys(fn ($i) => [$i->id => $i->label]))->required()->searchable()->columnSpanFull()
                    ->disabled(fn (string $operation) => $operation !== 'create'),
                TextInput::make('name')->label('Milestone')->required()->columnSpanFull()->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
                DatePicker::make('due_date')->label('Tenggat')->required()->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
                F::user('owner_id', 'PIC')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
                TextInput::make('weight')->label('Bobot')->numeric()->default(1)->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
                Toggle::make('is_achieved')->label('Tercapai'),
                DatePicker::make('achieved_at')->label('Tanggal tercapai'),
                Textarea::make('evidence')->label('Bukti')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('initiative.code')->label('Inisiatif')->formatStateUsing(fn ($state) => Fmt::shortCode($state))->tooltip(fn ($record) => $record->initiative?->title)->sortable(),
                TextColumn::make('name')->label('Milestone')->wrap()->searchable(),
                TextColumn::make('owner.name')->label('PIC'),
                TextColumn::make('due_date')->label('Tenggat')->date('d M Y')->sortable()->color(fn ($record) => ! $record->is_achieved && $record->due_date->isPast() ? 'danger' : null),
                IconColumn::make('is_achieved')->label('Tercapai')->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_achieved')->label('Tercapai'),
                Filter::make('due30')->label('Jatuh tempo 30 hari')->query(fn (Builder $query) => $query->where('is_achieved', false)->whereDate('due_date', '<=', today()->addDays(30))),
            ])
            ->defaultSort('due_date', 'asc')
            ->recordActions([
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInitiativeMilestones::route('/'),
            'create' => CreateInitiativeMilestone::route('/create'),
            'view' => ViewInitiativeMilestone::route('/{record}'),
            'edit' => EditInitiativeMilestone::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return static::allows('initiative.edit');
    }

    public static function canEdit(Model $record): bool
    {
        return \App\Filament\Resources\InitiativeActivities\InitiativeActivityResource::canUpdateProgress($record);
    }

    public static function canDelete(Model $record): bool
    {
        return static::allows('initiative.edit');
    }
}
