<?php

namespace App\Filament\Resources\StrategicIssues;

use App\Filament\Resources\StrategicIssues\Pages\ListStrategicIssues;
use App\Filament\Resources\StrategicIssues\Pages\CreateStrategicIssue;
use App\Filament\Resources\StrategicIssues\Pages\ViewStrategicIssue;
use App\Filament\Resources\StrategicIssues\Pages\EditStrategicIssue;
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

class StrategicIssueResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\StrategicIssue::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?int $navigationSort = 8;

    protected static ?string $modelLabel = 'Isu Strategis';

    protected static ?string $pluralModelLabel = 'Isu Strategis';

    protected static ?string $navigationLabel = 'Isu Strategis';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')->label('Isu')->required()->columnSpanFull(),
                Textarea::make('description')->label('Uraian')->rows(4)->columnSpanFull(),
                F::perspective(),
                F::user('owner_id'),
                F::scale('urgency', 'Urgensi'),
                F::scale('impact', 'Dampak'),
                Select::make('status')->label('Status')->options(['terbuka' => 'Terbuka', 'ditangani' => 'Ditangani', 'selesai' => 'Selesai'])->native(false),
                TextInput::make('source')->label('Sumber'),
                Select::make('stakeholders')->label('Pemangku kepentingan terkait')->relationship('stakeholders', 'name')->multiple()->preload()->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('title')->label('Isu')->searchable()->wrap(),
                F::perspectiveColumn(),
                TextColumn::make('priority_score')->label('Prioritas (U×D)')->state(fn ($record) => $record->priority_score)->badge()->color(fn ($state) => $state >= 20 ? 'danger' : ($state >= 12 ? 'warning' : 'gray'))->sortable(query: fn (Builder $query, string $direction) => $query->orderByRaw('(urgency * impact) '.$direction)),
                TextColumn::make('owner.name')->label('PIC'),
                F::statusColumn('status', ['terbuka' => 'Terbuka', 'ditangani' => 'Ditangani', 'selesai' => 'Selesai'], ['terbuka' => 'danger', 'ditangani' => 'warning', 'selesai' => 'success']),
            ])
            ->filters([
                SelectFilter::make('perspective')->label('Perspektif')->options(Terms::perspectives()),
                SelectFilter::make('status')->options(['terbuka' => 'Terbuka', 'ditangani' => 'Ditangani', 'selesai' => 'Selesai']),
            ])
            ->defaultSort('code', 'asc')
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
            'index' => ListStrategicIssues::route('/'),
            'create' => CreateStrategicIssue::route('/create'),
            'view' => ViewStrategicIssue::route('/{record}'),
            'edit' => EditStrategicIssue::route('/{record}/edit'),
        ];
    }

}
