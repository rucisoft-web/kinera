<?php

namespace App\Filament\Resources\InitiativeActivities;

use App\Filament\Resources\InitiativeActivities\Pages\ListInitiativeActivities;
use App\Filament\Resources\InitiativeActivities\Pages\CreateInitiativeActivity;
use App\Filament\Resources\InitiativeActivities\Pages\ViewInitiativeActivity;
use App\Filament\Resources\InitiativeActivities\Pages\EditInitiativeActivity;
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

class InitiativeActivityResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\InitiativeActivity::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Aktivitas Inisiatif';

    protected static ?string $pluralModelLabel = 'Aktivitas Inisiatif';

    protected static ?string $navigationLabel = 'Aktivitas Inisiatif';

    protected static ?string $navigationGroupKey = 'punarbhawa';

    protected static ?string $viewPermission = 'initiative.view';

    protected static ?string $editPermission = 'initiative.edit';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
             Section::make()->columns(2)->schema([
                 Select::make('strategic_initiative_id')->label('Inisiatif')->options(fn () => static::myInitiatives()->mapWithKeys(fn ($i) => [$i->id => $i->label]))->required()->searchable()->columnSpanFull()
                     ->disabled(fn (string $operation) => $operation !== 'create'),

            TextInput::make('name')->label('Aktivitas')->required()->columnSpanFull()->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            F::user('owner_id', 'PIC aktivitas')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            DatePicker::make('start_date')->label('Mulai')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            DatePicker::make('due_date')->label('Tenggat')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            TextInput::make('weight')->label('Bobot')->numeric()->default(1)->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            TextInput::make('progress')->label('Progres (%)')->numeric()->minValue(0)->maxValue(100)->required()->suffix('%'),
            Textarea::make('last_update_note')->label('Catatan perkembangan')->rows(2)->columnSpanFull(),

             ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('initiative.code')->label('Inisiatif')->formatStateUsing(fn ($state) => Fmt::shortCode($state))->sortable()->tooltip(fn ($record) => $record->initiative?->title),
                TextColumn::make('name')->label('Aktivitas')->wrap()->searchable()->description(fn ($record) => $record->last_update_note),
                TextColumn::make('owner.name')->label('PIC'),
                TextColumn::make('due_date')->label('Tenggat')->date('d M Y')->sortable(),
                TextColumn::make('progress')->label('Progres')->formatStateUsing(fn ($state) => Fmt::num($state).'%')->alignEnd()->sortable(),
                F::statusColumn('status', ['belum' => 'Belum mulai', 'berjalan' => 'Berjalan', 'terlambat' => 'Terlambat', 'selesai' => 'Selesai'], ['belum' => 'gray', 'berjalan' => 'info', 'terlambat' => 'danger', 'selesai' => 'success']),
                TextColumn::make('progress_updated_at')->label('Diperbarui')->since()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(['belum' => 'Belum mulai', 'berjalan' => 'Berjalan', 'terlambat' => 'Terlambat', 'selesai' => 'Selesai']),
                SelectFilter::make('strategic_initiative_id')->label('Inisiatif')->relationship('initiative', 'title'),
                Filter::make('mine')->label('Aktivitas saya')->query(fn (Builder $query) => $query->where(fn ($w) => $w->where('owner_id', Auth::id())->orWhereHas('initiative', fn ($i) => $i->where('owner_id', Auth::id())))),
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
            'index' => ListInitiativeActivities::route('/'),
            'create' => CreateInitiativeActivity::route('/create'),
            'view' => ViewInitiativeActivity::route('/{record}'),
            'edit' => EditInitiativeActivity::route('/{record}/edit'),
        ];
    }
    public static function canUpdateProgress($record): bool
    {
        $user = Auth::user();
        if (! $user) { return false; }
        if ($user->hasPermission('initiative.edit') && $user->managesAllData()) { return true; }
        if (! $user->hasPermission('initiative.update_progress') && ! $user->hasPermission('initiative.edit')) { return false; }
        if ($user->managesAllData()) { return true; }
        $initiative = $record instanceof Models\StrategicInitiative ? $record : $record->initiative;

        return (int) $record->owner_id === (int) $user->id || (int) $initiative?->owner_id === (int) $user->id;
    }

    public static function myInitiatives()
    {
        $user = Auth::user();

        return Models\StrategicInitiative::when(! ($user?->managesAllData() || $user?->hasPermission('initiative.edit')), fn ($q) => $q->where('owner_id', $user?->id))->orderBy('code')->get();
    }

     public static function canCreate(): bool
     {
         return static::allows('initiative.edit');
     }

     public static function canEdit(Model $record): bool
     {
         return static::canUpdateProgress($record);
     }

     public static function canDelete(Model $record): bool
     {
         return static::allows('initiative.edit');
     }
}
