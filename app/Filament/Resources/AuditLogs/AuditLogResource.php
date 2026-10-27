<?php

namespace App\Filament\Resources\AuditLogs;

use App\Filament\Resources\AuditLogs\Pages\ListAuditLogs;
use App\Filament\Resources\AuditLogs\Pages\ViewAuditLog;
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

class AuditLogResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\AuditLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-finger-print';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Log Audit';

    protected static ?string $pluralModelLabel = 'Log Audit';

    protected static ?string $navigationLabel = 'Log Audit';

    protected static ?string $navigationGroupKey = 'Administrasi';

    protected static ?string $viewPermission = 'audit.view';

    protected static ?string $editPermission = null;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(3)->schema([
                Placeholder::make('when')->label('Waktu')->content(fn ($record) => $record?->created_at?->format('d/m/Y H:i:s')),
                Placeholder::make('who')->label('Pengguna')->content(fn ($record) => $record?->user?->name ?? 'sistem'),
                Placeholder::make('what')->label('Kejadian')->content(fn ($record) => $record?->event.' — '.class_basename((string) $record?->auditable_type).' #'.$record?->auditable_id.' '.$record?->auditable_label),
                Placeholder::make('old')->label('Nilai lama')->content(fn ($record) => new \Illuminate\Support\HtmlString('<pre style="white-space:pre-wrap;font-size:.75rem">'.e(json_encode($record?->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)).'</pre>'))->columnSpanFull(),
                Placeholder::make('new')->label('Nilai baru')->content(fn ($record) => new \Illuminate\Support\HtmlString('<pre style="white-space:pre-wrap;font-size:.75rem">'.e(json_encode($record?->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)).'</pre>'))->columnSpanFull(),
                Placeholder::make('ip')->label('IP / URL')->content(fn ($record) => $record?->ip_address.' · '.$record?->url)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Waktu')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('user.name')->label('Pengguna')->placeholder('sistem')->searchable(),
                TextColumn::make('event')->label('Kejadian')->badge()->color(fn ($state) => match ($state) { 'dibuat' => 'success', 'dihapus' => 'danger', default => 'warning' }),
                TextColumn::make('auditable_type')->label('Data')->formatStateUsing(fn ($state) => class_basename($state)),
                TextColumn::make('auditable_label')->label('Label')->limit(40),
                TextColumn::make('ip_address')->label('IP')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('event')->options(['dibuat' => 'Dibuat', 'diubah' => 'Diubah', 'dihapus' => 'Dihapus']),
                SelectFilter::make('user_id')->label('Pengguna')->relationship('user', 'name'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([

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
            'index' => ListAuditLogs::route('/'),
            'view' => ViewAuditLog::route('/{record}'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $q = parent::getEloquentQuery();
        $ctx = app(\App\Support\DhastramContext::class);

        return Auth::user()?->isSuperAdmin() ? $q : $q->where(fn ($w) => $w->whereNull('company_id')->orWhere('company_id', $ctx->companyId()));
    }
}
