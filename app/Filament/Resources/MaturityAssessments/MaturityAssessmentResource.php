<?php

namespace App\Filament\Resources\MaturityAssessments;

use App\Filament\Resources\MaturityAssessments\Pages\ListMaturityAssessments;
use App\Filament\Resources\MaturityAssessments\Pages\CreateMaturityAssessment;
use App\Filament\Resources\MaturityAssessments\Pages\ViewMaturityAssessment;
use App\Filament\Resources\MaturityAssessments\Pages\EditMaturityAssessment;
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

class MaturityAssessmentResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\MaturityAssessment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Penilaian Kematangan';

    protected static ?string $pluralModelLabel = 'Kematangan Manajemen Strategi';

    protected static ?string $navigationLabel = 'Kematangan Manajemen Strategi';

    protected static ?string $navigationGroupKey = 'moksa';

    protected static ?string $viewPermission = 'maturity.view';

    protected static ?string $editPermission = 'maturity.assess';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(3)->schema([
                TextInput::make('title')->label('Judul penilaian')->required()->columnSpan(2),
                DatePicker::make('assessment_date')->label('Tanggal')->required()->default(now()),
                F::user('assessor_id', 'Penilai / fasilitator'),
                Select::make('status')->options(['draf' => 'Draf', 'final' => 'Final'])->native(false),
                Placeholder::make('skor')->label('Skor keseluruhan')->content(fn ($record) => $record?->overall_score ? Fmt::num($record->overall_score, 2).' — level '.$record->level.' ('.app(\App\Services\MaturityScoringService::class)->levelName($record->level).')' : 'Dihitung dari jawaban'),
                Textarea::make('notes')->label('Catatan')->rows(3)->columnSpanFull(),
            ]),
            Section::make('Skor per fase')->visibleOn('view')->schema([
                Placeholder::make('phases')->label('')->content(function ($record) {
                    if (! $record) { return '-'; }
                    $rows = collect(app(\App\Services\MaturityScoringService::class)->byPhase($record))->map(fn ($p) => '<span class="arah-badge arah-biru" style="margin-right:.4rem">'.e($p['label']).': '.Fmt::num($p['score'], 2).'</span>')->join('');
                    return new \Illuminate\Support\HtmlString($rows);
                }),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('assessment_date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('title')->label('Penilaian')->wrap(),
                TextColumn::make('overall_score')->label('Skor')->formatStateUsing(fn ($state) => Fmt::num($state, 2)),
                TextColumn::make('level')->label('Level')->formatStateUsing(fn ($state) => $state.' — '.app(\App\Services\MaturityScoringService::class)->levelName($state))->badge(),
                TextColumn::make('status')->badge(),
            ])
            ->filters([

            ])
            ->defaultSort('assessment_date', 'desc')
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
            RelationManagers\AnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMaturityAssessments::route('/'),
            'create' => CreateMaturityAssessment::route('/create'),
            'view' => ViewMaturityAssessment::route('/{record}'),
            'edit' => EditMaturityAssessment::route('/{record}/edit'),
        ];
    }

}
