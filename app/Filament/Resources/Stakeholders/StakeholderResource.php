<?php

namespace App\Filament\Resources\Stakeholders;

use App\Filament\Resources\Stakeholders\Pages\ListStakeholders;
use App\Filament\Resources\Stakeholders\Pages\CreateStakeholder;
use App\Filament\Resources\Stakeholders\Pages\ViewStakeholder;
use App\Filament\Resources\Stakeholders\Pages\EditStakeholder;
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

class StakeholderResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\Stakeholder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 7;

    protected static ?string $modelLabel = 'Pemangku Kepentingan';

    protected static ?string $pluralModelLabel = 'Pemangku Kepentingan';

    protected static ?string $navigationLabel = 'Pemangku Kepentingan';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('name')->label('Nama')->required()->columnSpanFull(),
                Select::make('type')->label('Jenis')->options(['internal' => 'Internal', 'eksternal' => 'Eksternal'])->native(false),
                TextInput::make('category')->label('Kategori'),
                F::scale('influence', 'Pengaruh'),
                F::scale('interest', 'Kepentingan'),
                Textarea::make('expectations')->label('Harapan')->rows(3)->columnSpanFull(),
                Textarea::make('engagement_strategy')->label('Strategi keterlibatan')->rows(3)->columnSpanFull(),
                TextInput::make('contact_person')->label('Kontak / PIC internal'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Pemangku kepentingan')->searchable()->wrap(),
                TextColumn::make('type')->label('Jenis')->badge(),
                TextColumn::make('influence')->label('Pengaruh')->alignCenter()->sortable(),
                TextColumn::make('interest')->label('Kepentingan')->alignCenter()->sortable(),
                TextColumn::make('quadrant')->label('Pendekatan')->state(fn ($record) => $record->quadrant)->badge()->color(fn ($state) => $state === 'Kelola dekat' ? 'danger' : 'gray'),
                TextColumn::make('contact_person')->label('PIC')->toggleable(),
            ])
            ->filters([

            ])
            ->defaultSort('influence', 'desc')
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
            'index' => ListStakeholders::route('/'),
            'create' => CreateStakeholder::route('/create'),
            'view' => ViewStakeholder::route('/{record}'),
            'edit' => EditStakeholder::route('/{record}/edit'),
        ];
    }

}
