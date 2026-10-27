<?php

namespace App\Filament\Resources\IndustryForces;

use App\Filament\Resources\IndustryForces\Pages\ListIndustryForces;
use App\Filament\Resources\IndustryForces\Pages\CreateIndustryForce;
use App\Filament\Resources\IndustryForces\Pages\ViewIndustryForce;
use App\Filament\Resources\IndustryForces\Pages\EditIndustryForce;
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

class IndustryForceResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\IndustryForce::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Kekuatan Industri';

    protected static ?string $pluralModelLabel = 'Analisis Industri';

    protected static ?string $navigationLabel = 'Analisis Industri';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                Select::make('force')->label('Kekuatan (Porter)')->options(Models\IndustryForce::FORCES)->required()->native(false),
                F::scale('intensity', 'Intensitas'),
                TextInput::make('title')->label('Judul')->required()->columnSpanFull(),
                Textarea::make('description')->label('Uraian')->rows(4)->columnSpanFull(),
                Textarea::make('implication')->label('Implikasi')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('force')->label('Kekuatan')->formatStateUsing(fn ($state) => Models\IndustryForce::FORCES[$state] ?? $state)->badge(),
                TextColumn::make('title')->label('Judul')->wrap()->searchable(),
                TextColumn::make('intensity')->label('Intensitas')->badge()->color(fn ($state) => $state >= 4 ? 'danger' : ($state >= 3 ? 'warning' : 'success')),
                TextColumn::make('implication')->label('Implikasi')->limit(70)->wrap()->toggleable(),
            ])
            ->filters([

            ])
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
            'index' => ListIndustryForces::route('/'),
            'create' => CreateIndustryForce::route('/create'),
            'view' => ViewIndustryForce::route('/{record}'),
            'edit' => EditIndustryForce::route('/{record}/edit'),
        ];
    }

}
