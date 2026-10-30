<?php

namespace App\Filament\Resources\PerformanceReviews;

use App\Filament\Resources\PerformanceReviews\Pages\ListPerformanceReviews;
use App\Filament\Resources\PerformanceReviews\Pages\CreatePerformanceReview;
use App\Filament\Resources\PerformanceReviews\Pages\ViewPerformanceReview;
use App\Filament\Resources\PerformanceReviews\Pages\EditPerformanceReview;
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

class PerformanceReviewResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\PerformanceReview::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 8;

    protected static ?string $modelLabel = 'Tinjauan Kinerja';

    protected static ?string $pluralModelLabel = 'Tinjauan Kinerja';

    protected static ?string $navigationLabel = 'Tinjauan Kinerja';

    protected static ?string $navigationGroupKey = 'karma_phala';

    protected static ?string $viewPermission = 'review.view';

    protected static ?string $editPermission = 'review.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Tinjauan')->columns(3)->schema([
                TextInput::make('title')->label('Judul')->required()->columnSpan(2),
                Select::make('review_type')->label('Jenis')->options(Models\PerformanceReview::TYPES)->required()->native(false),
                DatePicker::make('review_date')->label('Tanggal')->required()->default(now()),
                TextInput::make('period_label')->label('Perioda yang ditinjau'),
                TextInput::make('venue')->label('Tempat'),
                TextInput::make('chair')->label('Pimpinan rapat'),
                Textarea::make('participants')->label('Peserta')->rows(2)->columnSpan(2),
                Textarea::make('summary')->label('Ringkasan / uraian')->rows(8)->columnSpanFull(),
                Textarea::make('decisions')->label('Keputusan & arahan')->rows(6)->columnSpanFull(),
                Textarea::make('follow_up')->label('Tindak lanjut')->rows(3)->columnSpanFull(),
                Select::make('status')->options(['draf' => 'Draf', 'final' => 'Final'])->native(false),
                F::user('recorded_by', 'Notulis'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('review_date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('title')->label('Tinjauan')->wrap()->searchable()->description(fn ($record) => $record->period_label),
                TextColumn::make('review_type')->label('Jenis')->badge()->formatStateUsing(fn ($state) => Models\PerformanceReview::TYPES[$state] ?? $state),
                TextColumn::make('findings_count')->label('Temuan')->counts('findings'),
                TextColumn::make('notes_count')->label('Catatan Pengawas')->counts('notes'),
                F::statusColumn('status', ['draf' => 'Draf', 'final' => 'Final'], ['draf' => 'warning', 'final' => 'success']),
            ])
            ->filters([
                SelectFilter::make('review_type')->label('Jenis')->options(Models\PerformanceReview::TYPES),
            ])
            ->defaultSort('review_date', 'desc')
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
            RelationManagers\NotesRelationManager::class,
            RelationManagers\FindingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPerformanceReviews::route('/'),
            'create' => CreatePerformanceReview::route('/create'),
            'view' => ViewPerformanceReview::route('/{record}'),
            'edit' => EditPerformanceReview::route('/{record}/edit'),
        ];
    }

}
