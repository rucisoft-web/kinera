<?php

namespace App\Filament\Resources\CorrectiveActions;

use App\Filament\Resources\CorrectiveActions\Pages\ListCorrectiveActions;
use App\Filament\Resources\CorrectiveActions\Pages\CreateCorrectiveAction;
use App\Filament\Resources\CorrectiveActions\Pages\ViewCorrectiveAction;
use App\Filament\Resources\CorrectiveActions\Pages\EditCorrectiveAction;
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

class CorrectiveActionResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\CorrectiveAction::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Tindakan Korektif';

    protected static ?string $pluralModelLabel = 'Tindakan Korektif';

    protected static ?string $navigationLabel = 'Tindakan Korektif';

    protected static ?string $navigationGroupKey = 'punarbhawa';

    protected static ?string $viewPermission = 'finding.view';

    protected static ?string $editPermission = 'finding.edit';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
             Section::make()->columns(2)->schema([
                 Select::make('finding_id')->label('Temuan')->relationship('finding', 'title')->required()->searchable()->preload()->columnSpanFull()->disabled(fn (string $operation) => $operation !== 'create'),

            TextInput::make('title')->label('Tindakan korektif')->required()->columnSpanFull()->disabled(fn () => ! Auth::user()?->managesAllData() && ! Auth::user()?->hasPermission('finding.create')),
            F::user('owner_id', 'PIC')->required()->disabled(fn () => ! Auth::user()?->managesAllData()),
            DatePicker::make('due_date')->label('Tenggat')->disabled(fn () => ! Auth::user()?->managesAllData()),
            Select::make('status')->options(['belum' => 'Belum', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'])->default('belum')->native(false),
            TextInput::make('progress')->label('Progres (%)')->numeric()->minValue(0)->maxValue(100)->default(0),
            Textarea::make('description')->label('Uraian')->rows(2)->columnSpanFull(),
            Textarea::make('result_note')->label('Hasil / bukti')->rows(2)->columnSpanFull(),

             ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('finding.code')->label('Temuan')->formatStateUsing(fn ($state) => Fmt::shortCode($state))->tooltip(fn ($record) => $record->finding?->title),
                TextColumn::make('title')->label('Tindakan')->wrap()->searchable(),
                TextColumn::make('owner.name')->label('PIC'),
                TextColumn::make('due_date')->label('Tenggat')->date('d M Y')->sortable()->color(fn ($record) => $record->is_overdue ? 'danger' : null),
                TextColumn::make('progress')->label('Progres')->formatStateUsing(fn ($state) => $state.'%'),
                F::statusColumn('status', ['belum' => 'Belum', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'], ['belum' => 'gray', 'berjalan' => 'warning', 'selesai' => 'success']),
            ])
            ->filters([
                SelectFilter::make('status')->options(['belum' => 'Belum', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai']),
                Filter::make('mine')->label('Tindakan saya')->query(fn (Builder $query) => $query->where('owner_id', Auth::id())),
                Filter::make('overdue')->label('Lewat tenggat')->query(fn (Builder $query) => $query->where('status', '!=', 'selesai')->whereDate('due_date', '<', today())),
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
            'index' => ListCorrectiveActions::route('/'),
            'create' => CreateCorrectiveAction::route('/create'),
            'view' => ViewCorrectiveAction::route('/{record}'),
            'edit' => EditCorrectiveAction::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return (bool) (static::user()?->managesAllData() || static::user()?->hasPermission('finding.create'));
    }

    /** Pemilik tindakan korektif hanya mengubah miliknya; pengelola bebas. */
    public static function canEdit(Model $record): bool
    {
        $user = static::user();
        if (! $user || $record->finding?->status === 'ditutup') { return false; }

        return $user->managesAllData() || ($user->hasPermission('finding.edit') && (int) $record->owner_id === (int) $user->id);
    }

    public static function canDelete(Model $record): bool
    {
        return (bool) static::user()?->managesAllData();
    }
}
