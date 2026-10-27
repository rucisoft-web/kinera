<?php

namespace App\Filament\Resources\PestelAnalyses;

use App\Filament\Resources\PestelAnalyses\Pages\ListPestelAnalyses;
use App\Filament\Resources\PestelAnalyses\Pages\CreatePestelAnalysis;
use App\Filament\Resources\PestelAnalyses\Pages\ViewPestelAnalysis;
use App\Filament\Resources\PestelAnalyses\Pages\EditPestelAnalysis;
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

class PestelAnalysisResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\PestelAnalysis::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Faktor PESTEL';

    protected static ?string $pluralModelLabel = 'PESTEL';

    protected static ?string $navigationLabel = 'PESTEL';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                Select::make('factor')->label('Faktor')->options(Models\PestelAnalysis::FACTORS)->required()->native(false),
                Select::make('impact_type')->label('Sifat')->options(['peluang' => 'Peluang', 'ancaman' => 'Ancaman'])->required()->native(false),
                TextInput::make('title')->label('Judul')->required()->columnSpanFull(),
                Textarea::make('description')->label('Uraian')->rows(4)->columnSpanFull(),
                F::scale('impact_level', 'Tingkat dampak'),
                F::scale('likelihood', 'Kemungkinan'),
                Select::make('trend')->label('Tren')->options(['naik' => 'Naik', 'stabil' => 'Stabil', 'turun' => 'Turun'])->native(false),
                TextInput::make('source')->label('Sumber'),
                Textarea::make('response')->label('Respons strategis')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('factor')->label('Faktor')->badge()->formatStateUsing(fn ($state) => Models\PestelAnalysis::FACTORS[$state] ?? $state)->sortable(),
                TextColumn::make('title')->label('Judul')->searchable()->wrap(),
                TextColumn::make('impact_type')->label('Sifat')->badge()->color(fn ($state) => $state === 'peluang' ? 'success' : 'danger'),
                TextColumn::make('impact_level')->label('Dampak')->alignCenter()->sortable(),
                TextColumn::make('likelihood')->label('Kemungkinan')->alignCenter()->sortable(),
                TextColumn::make('score')->label('Skor')->state(fn ($record) => $record->score)->badge()->color(fn ($state) => $state >= 16 ? 'danger' : ($state >= 9 ? 'warning' : 'gray')),
                TextColumn::make('trend')->label('Tren'),
            ])
            ->filters([
                SelectFilter::make('factor')->label('Faktor')->options(Models\PestelAnalysis::FACTORS),
                SelectFilter::make('impact_type')->label('Sifat')->options(['peluang' => 'Peluang', 'ancaman' => 'Ancaman']),
            ])
            ->defaultSort('factor', 'asc')
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
            'index' => ListPestelAnalyses::route('/'),
            'create' => CreatePestelAnalysis::route('/create'),
            'view' => ViewPestelAnalysis::route('/{record}'),
            'edit' => EditPestelAnalysis::route('/{record}/edit'),
        ];
    }

}
