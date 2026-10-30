<?php

namespace App\Filament\Resources\Institutionalizations;

use App\Filament\Resources\Institutionalizations\Pages\ListInstitutionalizations;
use App\Filament\Resources\Institutionalizations\Pages\CreateInstitutionalization;
use App\Filament\Resources\Institutionalizations\Pages\ViewInstitutionalization;
use App\Filament\Resources\Institutionalizations\Pages\EditInstitutionalization;
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

class InstitutionalizationResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\Institutionalization::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Pelembagaan';

    protected static ?string $pluralModelLabel = 'Institusionalisasi';

    protected static ?string $navigationLabel = 'Institusionalisasi';

    protected static ?string $navigationGroupKey = 'moksa';

    protected static ?string $viewPermission = 'maturity.view';

    protected static ?string $editPermission = 'maturity.assess';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')->label('Mekanisme')->required()->columnSpanFull(),
                Select::make('mechanism_type')->label('Jenis')->options(['rapat' => 'Rapat rutin', 'kebijakan' => 'Kebijakan', 'sop' => 'SOP', 'sistem' => 'Sistem', 'pelatihan' => 'Pelatihan', 'budaya' => 'Budaya'])->required()->native(false),
                TextInput::make('frequency')->label('Frekuensi'),
                Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
                F::user('owner_id'),
                Select::make('status')->options(['rencana' => 'Rencana', 'berjalan' => 'Berjalan', 'melembaga' => 'Melembaga'])->native(false),
                DatePicker::make('started_at')->label('Mulai'),
                DatePicker::make('next_review_date')->label('Tinjauan berikutnya'),
                Textarea::make('evidence')->label('Bukti')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Mekanisme')->wrap()->searchable()->description(fn ($record) => $record->frequency),
                TextColumn::make('mechanism_type')->label('Jenis')->badge(),
                TextColumn::make('owner.name')->label('PIC'),
                F::statusColumn('status', ['rencana' => 'Rencana', 'berjalan' => 'Berjalan', 'melembaga' => 'Melembaga'], ['rencana' => 'gray', 'berjalan' => 'warning', 'melembaga' => 'success']),
                TextColumn::make('next_review_date')->label('Tinjauan berikutnya')->date('d M Y')->sortable(),
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
            'index' => ListInstitutionalizations::route('/'),
            'create' => CreateInstitutionalization::route('/create'),
            'view' => ViewInstitutionalization::route('/{record}'),
            'edit' => EditInstitutionalization::route('/{record}/edit'),
        ];
    }

}
