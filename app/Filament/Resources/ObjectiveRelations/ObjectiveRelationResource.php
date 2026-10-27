<?php

namespace App\Filament\Resources\ObjectiveRelations;

use App\Filament\Resources\ObjectiveRelations\Pages\ListObjectiveRelations;
use App\Filament\Resources\ObjectiveRelations\Pages\CreateObjectiveRelation;
use App\Filament\Resources\ObjectiveRelations\Pages\EditObjectiveRelation;
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

class ObjectiveRelationResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\ObjectiveRelation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-share';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Relasi Sasaran';

    protected static ?string $pluralModelLabel = 'Relasi Sasaran';

    protected static ?string $navigationLabel = 'Relasi Sasaran';

    protected static ?string $navigationGroupKey = 'karma_phala';

    protected static ?string $viewPermission = 'strategy.view';

    protected static ?string $editPermission = 'strategy.edit';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Hubungan sebab–akibat (peta strategi)')->columns(2)->schema([
                Select::make('source_objective_id')->label('Sasaran penyebab')->options(fn () => Models\StrategicObjective::orderBy('sort')->get()->mapWithKeys(fn ($o) => [$o->id => $o->label]))->required()->searchable(),
                Select::make('target_objective_id')->label('Sasaran akibat')->options(fn () => Models\StrategicObjective::orderBy('sort')->get()->mapWithKeys(fn ($o) => [$o->id => $o->label]))->required()->searchable()->different('source_objective_id'),
                Select::make('strength')->label('Kekuatan hubungan')->options([1 => 'Lemah', 2 => 'Sedang', 3 => 'Kuat'])->default(2)->native(false),
                Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('source.title')->label('Penyebab')->wrap()->description(fn ($record) => Terms::perspective($record->source?->perspective)),
                TextColumn::make('arrow')->label('')->state('→'),
                TextColumn::make('target.title')->label('Akibat')->wrap()->description(fn ($record) => Terms::perspective($record->target?->perspective)),
                TextColumn::make('strength')->label('Kekuatan')->formatStateUsing(fn ($state) => [1 => 'Lemah', 2 => 'Sedang', 3 => 'Kuat'][$state] ?? $state)->badge(),
            ])
            ->filters([

            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => ListObjectiveRelations::route('/'),
            'create' => CreateObjectiveRelation::route('/create'),
            'edit' => EditObjectiveRelation::route('/{record}/edit'),
        ];
    }

}
