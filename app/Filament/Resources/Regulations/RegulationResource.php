<?php

namespace App\Filament\Resources\Regulations;

use App\Filament\Resources\Regulations\Pages\ListRegulations;
use App\Filament\Resources\Regulations\Pages\CreateRegulation;
use App\Filament\Resources\Regulations\Pages\ViewRegulation;
use App\Filament\Resources\Regulations\Pages\EditRegulation;
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

class RegulationResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\Regulation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Regulasi';

    protected static ?string $pluralModelLabel = 'Analisis Regulasi';

    protected static ?string $navigationLabel = 'Analisis Regulasi';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')->label('Regulasi / kewajiban')->required()->columnSpanFull(),
                TextInput::make('issuer')->label('Penerbit / instansi'),
                TextInput::make('reference_no')->label('Dasar hukum'),
                TextInput::make('category')->label('Kategori'),
                DatePicker::make('effective_date')->label('Berlaku sejak'),
                Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
                Textarea::make('impact')->label('Dampak bagi satker BLU')->rows(3)->columnSpanFull(),
                Select::make('compliance_status')->label('Status kepatuhan')->options(['patuh' => 'Patuh', 'sebagian' => 'Sebagian', 'belum' => 'Belum patuh', 'dipantau' => 'Dipantau'])->native(false),
                F::user('owner_id'),
                DatePicker::make('due_date')->label('Tenggat'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Regulasi')->searchable()->wrap()->description(fn ($record) => $record->reference_no),
                TextColumn::make('category')->label('Kategori')->badge(),
                F::statusColumn('compliance_status', ['patuh' => 'Patuh', 'sebagian' => 'Sebagian', 'belum' => 'Belum patuh', 'dipantau' => 'Dipantau'], ['patuh' => 'success', 'sebagian' => 'warning', 'belum' => 'danger', 'dipantau' => 'info'], 'Kepatuhan'),
                TextColumn::make('owner.name')->label('PIC'),
                TextColumn::make('due_date')->label('Tenggat')->date('d M Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('compliance_status')->label('Kepatuhan')->options(['patuh' => 'Patuh', 'sebagian' => 'Sebagian', 'belum' => 'Belum patuh', 'dipantau' => 'Dipantau']),
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
            'index' => ListRegulations::route('/'),
            'create' => CreateRegulation::route('/create'),
            'view' => ViewRegulation::route('/{record}'),
            'edit' => EditRegulation::route('/{record}/edit'),
        ];
    }

}
