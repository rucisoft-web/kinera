<?php

namespace App\Filament\Resources\AtmanProfiles;

use App\Filament\Resources\AtmanProfiles\Pages\ListAtmanProfiles;
use App\Filament\Resources\AtmanProfiles\Pages\CreateAtmanProfile;
use App\Filament\Resources\AtmanProfiles\Pages\ViewAtmanProfile;
use App\Filament\Resources\AtmanProfiles\Pages\EditAtmanProfile;
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

class AtmanProfileResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\AtmanProfile::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Jati Diri Organisasi';

    protected static ?string $pluralModelLabel = 'Jati Diri Organisasi';

    protected static ?string $navigationLabel = 'Jati Diri Organisasi';

    protected static ?string $navigationGroupKey = 'atman';

    protected static ?string $viewPermission = 'atman.view';

    protected static ?string $editPermission = 'atman.edit';

    protected static ?string $recordTitleAttribute = 'tagline';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Tabs::make('Jati diri')->columnSpanFull()->tabs([
                Tab::make('Visi & Misi')->schema([
                    Textarea::make('vision')->label('Visi')->rows(3),
                    Textarea::make('mission')->label('Misi')->rows(7)->helperText('Satu butir per baris.'),
                    Textarea::make('purpose')->label('Tujuan / purpose')->rows(2),
                ]),
                Tab::make('Merek & Proposisi')->schema([
                    TextInput::make('tagline')->label('Tagline'),
                    Textarea::make('brand_promise')->label('Janji merek')->rows(2),
                    Textarea::make('value_proposition')->label('Proposisi nilai')->rows(3),
                ]),
                Tab::make('Budaya & Sejarah')->schema([
                    Textarea::make('culture_statement')->label('Pernyataan budaya')->rows(3),
                    Textarea::make('history')->label('Sejarah singkat')->rows(5),
                ]),
                Tab::make('Pengesahan')->schema([
                    F::user('approved_by', 'Disahkan oleh'),
                    DateTimePicker::make('approved_at')->label('Tanggal pengesahan'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('analysisPeriod.name')->label('Perioda'),
                TextColumn::make('vision')->label('Visi')->wrap()->limit(160),
                TextColumn::make('tagline')->label('Tagline'),
                TextColumn::make('approved_at')->label('Disahkan')->date('d M Y')->placeholder('Belum disahkan'),
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
            RelationManagers\CoreValuesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAtmanProfiles::route('/'),
            'create' => CreateAtmanProfile::route('/create'),
            'view' => ViewAtmanProfile::route('/{record}'),
            'edit' => EditAtmanProfile::route('/{record}/edit'),
        ];
    }

}
