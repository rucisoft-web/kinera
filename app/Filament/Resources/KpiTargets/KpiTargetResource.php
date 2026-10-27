<?php

namespace App\Filament\Resources\KpiTargets;

use App\Filament\Resources\KpiTargets\Pages\ListKpiTargets;
use App\Filament\Resources\KpiTargets\Pages\CreateKpiTarget;
use App\Filament\Resources\KpiTargets\Pages\EditKpiTarget;
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

class KpiTargetResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\KpiTarget::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-viewfinder-circle';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Target IK';

    protected static ?string $pluralModelLabel = 'Target IK';

    protected static ?string $navigationLabel = 'Target IK';

    protected static ?string $navigationGroupKey = 'karma_phala';

    protected static ?string $viewPermission = 'kpi.view';

    protected static ?string $editPermission = 'kpi.edit';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(3)->schema([
                Select::make('kpi_id')->label('IK')->options(fn () => Models\Kpi::orderBy('code')->get()->mapWithKeys(fn ($k) => [$k->id => $k->label]))->required()->searchable(),
                Select::make('month')->label('Bulan')->options(Terms::months())->required()->native(false),
                TextInput::make('target')->label('Target')->numeric()->required(),
                Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kpi.code')->label('Kode')->formatStateUsing(fn ($state) => Fmt::shortCode($state))->sortable(),
                TextColumn::make('kpi.name')->label('IK')->wrap()->searchable(),
                TextColumn::make('month')->label('Bulan')->formatStateUsing(fn ($state) => Terms::monthShort($state))->sortable(),
                TextColumn::make('target')->label('Target')->formatStateUsing(fn ($state, $record) => Fmt::kpiValue($state, $record->kpi?->unit, $record->kpi?->decimals ?? 0))->alignEnd(),
            ])
            ->filters([
                SelectFilter::make('kpi_id')->label('IK')->options(fn () => Models\Kpi::orderBy('code')->get()->mapWithKeys(fn ($k) => [$k->id => $k->label]))->searchable(),
                SelectFilter::make('month')->label('Bulan')->options(Terms::months()),
            ])
            ->defaultSort('kpi_id', 'asc')
            ->recordActions([
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
            'index' => ListKpiTargets::route('/'),
            'create' => CreateKpiTarget::route('/create'),
            'edit' => EditKpiTarget::route('/{record}/edit'),
        ];
    }

}
