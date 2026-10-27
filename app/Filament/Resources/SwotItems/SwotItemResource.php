<?php

namespace App\Filament\Resources\SwotItems;

use App\Filament\Resources\SwotItems\Pages\ListSwotItems;
use App\Filament\Resources\SwotItems\Pages\CreateSwotItem;
use App\Filament\Resources\SwotItems\Pages\ViewSwotItem;
use App\Filament\Resources\SwotItems\Pages\EditSwotItem;
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

class SwotItemResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\SwotItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected static ?int $navigationSort = 9;

    protected static ?string $modelLabel = 'Butir SWOT';

    protected static ?string $pluralModelLabel = 'SWOT';

    protected static ?string $navigationLabel = 'SWOT';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                Select::make('type')->label('Jenis')->options(Models\SwotItem::TYPES)->required()->native(false),
                TextInput::make('sort')->label('Urutan')->numeric()->default(0),
                TextInput::make('title')->label('Butir')->required()->columnSpanFull(),
                Textarea::make('description')->label('Bukti / uraian')->rows(3)->columnSpanFull(),
                TextInput::make('weight')->label('Bobot (0–1)')->numeric()->step(0.01),
                Select::make('rating')->label('Rating (1–4)')->options([1 => '1', 2 => '2', 3 => '3', 4 => '4'])->native(false),
                TextInput::make('source')->label('Sumber')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')->label('Jenis')->badge()->formatStateUsing(fn ($state) => Models\SwotItem::TYPES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) { 'S' => 'success', 'W' => 'warning', 'O' => 'info', 'T' => 'danger', default => 'gray' })->sortable(),
                TextColumn::make('title')->label('Butir')->searchable()->wrap()->description(fn ($record) => $record->description),
                TextColumn::make('weight')->label('Bobot'),
                TextColumn::make('rating')->label('Rating'),
                TextColumn::make('weighted')->label('Skor')->state(fn ($record) => $record->weight && $record->rating ? number_format($record->weight * $record->rating, 2) : '-'),
            ])
            ->filters([
                SelectFilter::make('type')->label('Jenis')->options(Models\SwotItem::TYPES),
            ])
            ->defaultSort('sort', 'asc')
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
            'index' => ListSwotItems::route('/'),
            'create' => CreateSwotItem::route('/create'),
            'view' => ViewSwotItem::route('/{record}'),
            'edit' => EditSwotItem::route('/{record}/edit'),
        ];
    }

}
