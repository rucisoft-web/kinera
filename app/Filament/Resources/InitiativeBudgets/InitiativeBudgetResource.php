<?php

namespace App\Filament\Resources\InitiativeBudgets;

use App\Filament\Resources\InitiativeBudgets\Pages\ListInitiativeBudgets;
use App\Filament\Resources\InitiativeBudgets\Pages\CreateInitiativeBudget;
use App\Filament\Resources\InitiativeBudgets\Pages\ViewInitiativeBudget;
use App\Filament\Resources\InitiativeBudgets\Pages\EditInitiativeBudget;
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

class InitiativeBudgetResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\InitiativeBudget::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Anggaran Inisiatif';

    protected static ?string $pluralModelLabel = 'Anggaran Inisiatif';

    protected static ?string $navigationLabel = 'Anggaran Inisiatif';

    protected static ?string $navigationGroupKey = 'punarbhawa';

    protected static ?string $viewPermission = 'initiative.view';

    protected static ?string $editPermission = 'initiative.edit';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
             Section::make()->columns(2)->schema([
                 Select::make('strategic_initiative_id')->label('Inisiatif')->relationship('initiative', 'title')->required()->searchable()->preload()->columnSpanFull()->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),

            TextInput::make('item')->label('Item anggaran')->required()->columnSpanFull()->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            Select::make('category')->label('Kategori')->options(['capex' => 'Capex', 'opex' => 'Opex'])->native(false)->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            TextInput::make('funding_source')->label('Sumber dana')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            F::money('planned_amount', 'Rencana')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            F::money('realized_amount', 'Realisasi'),
            DatePicker::make('realization_date')->label('Tanggal realisasi'),
            Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),

             ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('initiative.code')->label('Inisiatif')->formatStateUsing(fn ($state) => Fmt::shortCode($state))->description(fn ($record) => \Illuminate\Support\Str::limit($record->initiative?->title, 40)),
                TextColumn::make('item')->label('Item')->wrap()->searchable(),
                TextColumn::make('category')->label('Kat.')->badge(),
                TextColumn::make('planned_amount')->label('Rencana')->formatStateUsing(fn ($state) => Fmt::rp($state))->alignEnd()->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->formatStateUsing(fn ($state) => Fmt::rp($state))),
                TextColumn::make('realized_amount')->label('Realisasi')->formatStateUsing(fn ($state) => Fmt::rp($state))->alignEnd()->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->formatStateUsing(fn ($state) => Fmt::rp($state))),
                TextColumn::make('funding_source')->label('Sumber')->toggleable(),
                TextColumn::make('verifier.name')->label('Verifikasi')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('strategic_initiative_id')->label('Inisiatif')->relationship('initiative', 'title'),
                SelectFilter::make('category')->options(['capex' => 'Capex', 'opex' => 'Opex']),
            ])
            ->defaultSort('strategic_initiative_id', 'asc')
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
            'index' => ListInitiativeBudgets::route('/'),
            'create' => CreateInitiativeBudget::route('/create'),
            'view' => ViewInitiativeBudget::route('/{record}'),
            'edit' => EditInitiativeBudget::route('/{record}/edit'),
        ];
    }
    public static function canEdit(Model $record): bool
    {
        return static::user()?->hasAnyPermission(['initiative.edit', 'initiative.budget']) ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return static::allows('initiative.edit');
    }
}
