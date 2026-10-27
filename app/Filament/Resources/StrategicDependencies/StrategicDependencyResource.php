<?php

namespace App\Filament\Resources\StrategicDependencies;

use App\Filament\Resources\StrategicDependencies\Pages\ListStrategicDependencies;
use App\Filament\Resources\StrategicDependencies\Pages\CreateStrategicDependency;
use App\Filament\Resources\StrategicDependencies\Pages\ViewStrategicDependency;
use App\Filament\Resources\StrategicDependencies\Pages\EditStrategicDependency;
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

class StrategicDependencyResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\StrategicDependency::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Ketergantungan Strategis';

    protected static ?string $pluralModelLabel = 'Ketergantungan Strategis';

    protected static ?string $navigationLabel = 'Ketergantungan Strategis';

    protected static ?string $navigationGroupKey = 'moksa';

    protected static ?string $viewPermission = 'maturity.view';

    protected static ?string $editPermission = 'maturity.assess';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')->label('Ketergantungan')->required()->columnSpanFull(),
                Select::make('dependency_type')->label('Jenis')->options(['internal' => 'Internal', 'eksternal' => 'Eksternal'])->native(false),
                TextInput::make('depends_on')->label('Bergantung pada')->required(),
                Select::make('strategic_initiative_id')->label('Inisiatif')->relationship('initiative', 'title')->searchable()->preload(),
                Select::make('strategic_objective_id')->label('Sasaran')->relationship('objective', 'title')->searchable()->preload(),
                Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
                F::user('owner_id'),
                Select::make('risk_level')->label('Risiko')->options(['rendah' => 'Rendah', 'sedang' => 'Sedang', 'tinggi' => 'Tinggi'])->native(false),
                Textarea::make('mitigation')->label('Mitigasi')->rows(3)->columnSpanFull(),
                Select::make('status')->options(['aktif' => 'Aktif', 'teratasi' => 'Teratasi'])->native(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Ketergantungan')->wrap()->searchable()->description(fn ($record) => 'Bergantung pada: '.$record->depends_on),
                TextColumn::make('initiative.code')->label('Inisiatif')->formatStateUsing(fn ($state) => Fmt::shortCode($state)),
                TextColumn::make('risk_level')->label('Risiko')->badge()->color(fn ($state) => match ($state) { 'tinggi' => 'danger', 'sedang' => 'warning', default => 'gray' }),
                TextColumn::make('owner.name')->label('PIC'),
                TextColumn::make('status')->badge(),
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
            'index' => ListStrategicDependencies::route('/'),
            'create' => CreateStrategicDependency::route('/create'),
            'view' => ViewStrategicDependency::route('/{record}'),
            'edit' => EditStrategicDependency::route('/{record}/edit'),
        ];
    }

}
