<?php

namespace App\Filament\Resources\EnvironmentScans;

use App\Filament\Resources\EnvironmentScans\Pages\ListEnvironmentScans;
use App\Filament\Resources\EnvironmentScans\Pages\CreateEnvironmentScan;
use App\Filament\Resources\EnvironmentScans\Pages\ViewEnvironmentScan;
use App\Filament\Resources\EnvironmentScans\Pages\EditEnvironmentScan;
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

class EnvironmentScanResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\EnvironmentScan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-asia-australia';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Pemindaian Lingkungan';

    protected static ?string $pluralModelLabel = 'Pemindaian Lingkungan';

    protected static ?string $navigationLabel = 'Pemindaian Lingkungan';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')->label('Judul')->required()->columnSpanFull(),
                DatePicker::make('scan_date')->label('Tanggal')->required()->default(now()),
                Select::make('scope')->label('Lingkup')->options(['internal' => 'Internal', 'eksternal' => 'Eksternal'])->required()->native(false),
                TextInput::make('source')->label('Sumber dokumen')->columnSpanFull(),
                Textarea::make('summary')->label('Ringkasan temuan')->rows(5)->columnSpanFull(),
                Textarea::make('implications')->label('Implikasi strategis')->rows(4)->columnSpanFull(),
                Select::make('status')->label('Status')->options(['aktif' => 'Aktif', 'arsip' => 'Arsip'])->default('aktif')->native(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('scan_date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('title')->label('Judul')->searchable()->wrap()->limit(80),
                TextColumn::make('scope')->label('Lingkup')->badge()->color(fn ($state) => $state === 'internal' ? 'info' : 'warning'),
                TextColumn::make('source')->label('Sumber')->limit(40)->toggleable(),
            ])
            ->filters([
                SelectFilter::make('scope')->label('Lingkup')->options(['internal' => 'Internal', 'eksternal' => 'Eksternal']),
            ])
            ->defaultSort('scan_date', 'desc')
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
            'index' => ListEnvironmentScans::route('/'),
            'create' => CreateEnvironmentScan::route('/create'),
            'view' => ViewEnvironmentScan::route('/{record}'),
            'edit' => EditEnvironmentScan::route('/{record}/edit'),
        ];
    }

}
