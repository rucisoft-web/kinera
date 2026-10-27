<?php

namespace App\Filament\Resources\Competitors;

use App\Filament\Resources\Competitors\Pages\ListCompetitors;
use App\Filament\Resources\Competitors\Pages\CreateCompetitor;
use App\Filament\Resources\Competitors\Pages\ViewCompetitor;
use App\Filament\Resources\Competitors\Pages\EditCompetitor;
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

class CompetitorResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\Competitor::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-trophy';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Pesaing';

    protected static ?string $pluralModelLabel = 'Analisis Pesaing';

    protected static ?string $navigationLabel = 'Analisis Pesaing';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Profil')->columns(3)->schema([
                TextInput::make('name')->label('Nama pesaing')->required()->columnSpan(2),
                TextInput::make('category')->label('Kategori'),
                TextInput::make('rank')->label('Peringkat')->numeric(),
                TextInput::make('data_period')->label('Perioda data'),
                Toggle::make('is_self')->label('Satker BLU kita'),
                Textarea::make('strengths')->label('Kekuatan')->rows(2)->columnSpanFull(),
                Textarea::make('weaknesses')->label('Kelemahan')->rows(2)->columnSpanFull(),
            ]),
            Section::make('Kinerja')->columns(4)->schema([
                TextInput::make('revenue')->label('Pendapatan (Rp miliar)')->numeric(),
                TextInput::make('revenue_growth')->label('Pertumbuhan pendapatan %')->numeric(),
                TextInput::make('net_margin')->label('Marjin laba bersih %')->numeric(),
                TextInput::make('market_share')->label('Pangsa pasar %')->numeric(),
                Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
            ]),
            Section::make('Indikator perhotelan (opsional)')->columns(4)->collapsed()->schema([
                TextInput::make('room_count')->label('Kapasitas')->numeric(),
                TextInput::make('distance_km')->label('Jarak (km)')->numeric(),
                TextInput::make('room_available')->label('Room available')->numeric(),
                TextInput::make('room_sold')->label('Room sold')->numeric(),
                TextInput::make('occupancy')->label('Okupansi / utilisasi %')->numeric(),
                TextInput::make('arr')->label('Harga rata-rata (Rp)')->numeric(),
                TextInput::make('revpar')->label('RevPAR (Rp)')->numeric(),
                TextInput::make('rgi')->label('RGI')->numeric(),
                TextInput::make('mpi')->label('MPI')->numeric(),
                TextInput::make('ari')->label('ARI')->numeric(),
            ]),
            Section::make('Rating ulasan (opsional)')->columns(5)->collapsed()->schema([
                TextInput::make('rating_google')->label('Google /5')->numeric(),
                TextInput::make('rating_tripadvisor')->label('Tripadvisor /5')->numeric(),
                TextInput::make('rating_traveloka')->label('Traveloka /10')->numeric(),
                TextInput::make('rating_booking')->label('Booking /10')->numeric(),
                TextInput::make('rating_tiket')->label('Tiket.com /5')->numeric(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rank')->label('#')->sortable(),
                TextColumn::make('name')->label('Pesaing')->searchable()->weight(fn ($record) => $record->is_self ? 'bold' : null)->description(fn ($record) => $record->is_self ? 'Satker BLU kita' : $record->category),
                TextColumn::make('revenue')->label('Pendapatan (Rp M)')->formatStateUsing(fn ($state) => Fmt::num($state, 0))->sortable(),
                TextColumn::make('revenue_growth')->label('Tumbuh %')->formatStateUsing(fn ($state) => Fmt::num($state, 1))->badge()->color(fn ($state) => $state === null ? 'gray' : ($state >= 0 ? 'success' : 'danger'))->sortable(),
                TextColumn::make('net_margin')->label('Marjin bersih %')->formatStateUsing(fn ($state) => Fmt::num($state, 2))->sortable(),
                TextColumn::make('market_share')->label('Pangsa %')->formatStateUsing(fn ($state) => Fmt::num($state, 1))->sortable(),
                TextColumn::make('occupancy')->label('Okupansi %')->formatStateUsing(fn ($state) => Fmt::num($state, 2))->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('arr')->label('Harga rata-rata')->formatStateUsing(fn ($state) => Fmt::rp($state))->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('revpar')->label('RevPAR')->formatStateUsing(fn ($state) => Fmt::rp($state))->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rgi')->label('RGI')->badge()->color(fn ($state) => $state >= 1 ? 'success' : 'danger')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mpi')->label('MPI')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ari')->label('ARI')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rating_google')->label('Google')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rating_booking')->label('Booking')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('data_period')->label('Perioda')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->defaultSort('rank', 'asc')
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
            'index' => ListCompetitors::route('/'),
            'create' => CreateCompetitor::route('/create'),
            'view' => ViewCompetitor::route('/{record}'),
            'edit' => EditCompetitor::route('/{record}/edit'),
        ];
    }

}
