<?php

namespace App\Filament\Resources\Companies;

use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\ViewCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
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

class CompanyResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\Company::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Satker BLU';

    protected static ?string $pluralModelLabel = 'Satker BLU';

    protected static ?string $navigationLabel = 'Satker BLU';

    protected static ?string $navigationGroupKey = 'Administrasi';

    protected static ?string $viewPermission = 'system.admin';

    protected static ?string $editPermission = 'system.admin';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('code')->label('Kode')->required()->unique(ignoreRecord: true),
                TextInput::make('name')->label('Nama')->required(),
                TextInput::make('legal_name')->label('Nama legal'),
                TextInput::make('industry')->label('Rumpun layanan'),
                TextInput::make('address')->label('Alamat')->columnSpanFull(),
                TextInput::make('city')->label('Kota'),
                TextInput::make('phone')->label('Telepon'),
                TextInput::make('email')->label('Email')->email(),
                TextInput::make('website')->label('Website')->url(),
                Toggle::make('is_active')->label('Aktif')->default(true),
                Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),
            ]),
            Section::make('Identitas BLU & penandatangan Kontrak Kinerja')->columns(2)->collapsible()
                ->description('Dipakai pada cetakan Kontrak Kinerja, Berita Acara, dan Surat Pernyataan (PER-6/PB/2025). Kosongkan bila belum ada; cetakan menampilkan titik-titik.')
                ->schema([
                    Select::make('blu_field')->label('Bidang layanan BLU')->options(['kesehatan' => 'Layanan kesehatan', 'pendidikan' => 'Layanan pendidikan', 'lainnya' => 'Layanan lainnya'])->native(false),
                    TextInput::make('ministry')->label('Kementerian Negara/Lembaga'),
                    TextInput::make('technical_supervisor')->label('Pembina Teknis (unit eselon I)'),
                    DatePicker::make('blu_since')->label('Menerapkan PK-BLU sejak'),
                    TextInput::make('leader_title')->label('Jabatan Pemimpin BLU')->placeholder('Rektor / Direktur Utama / Kepala'),
                    TextInput::make('leader_name')->label('Nama Pemimpin BLU'),
                    TextInput::make('leader_nip')->label('NIP Pemimpin BLU'),
                    TextInput::make('remuneration_decree')->label('Keputusan remunerasi'),
                    TextInput::make('dg_name')->label('Nama Direktur Jenderal Perbendaharaan'),
                    TextInput::make('dg_nip')->label('NIP Direktur Jenderal Perbendaharaan'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Kode')->weight('bold'),
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('city')->label('Kota'),
                TextColumn::make('periods_count')->label('Perioda')->counts('periods'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
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
            RelationManagers\PeriodsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'view' => ViewCompany::route('/{record}'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }
    /** Satker BLU (klien) dibuat dan dikelola oleh Super Admin. */
    public static function shouldRegisterNavigation(): bool
    {
        return (bool) Auth::user()?->isSuperAdmin();
    }

    public static function canViewAny(): bool
    {
        return (bool) Auth::user()?->isSuperAdmin();
    }

    public static function canCreate(): bool
    {
        return (bool) Auth::user()?->isSuperAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return (bool) Auth::user()?->isSuperAdmin();
    }
}
