<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggunaResource\Pages;
use App\Filament\Resources\PenggunaResource\RelationManagers;
use App\Models\Pengguna;
use App\Models\Kecamatan;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;

class PenggunaResource extends Resource
{
    protected static ?string $model = Pengguna::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static string | \UnitEnum | null $navigationGroup = 'Data Master';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Pengguna';
    
    public static function canViewAny(): bool
    {
        // Hanya admin yang bisa mengakses resource ini
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canView(?\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        // Log untuk debugging
        if (auth()->check()) {
            \Log::info('PenggunaResource canView check', [
                'user_role' => auth()->user()->role,
                'is_admin' => auth()->user()->role === 'admin'
            ]);
        }
        
        // Hanya admin yang bisa mengakses resource ini
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canCreate(): bool
    {
        // Hanya admin yang bisa membuat pengguna baru
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canEdit($record): bool
    {
        // Hanya admin yang bisa mengedit pengguna
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canDelete($record): bool
    {
        // Hanya admin yang bisa menghapus pengguna
        return auth()->check() && auth()->user()->role === 'admin';
    }

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Pengguna')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->disabledOn('edit'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabledOn('edit'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->confirmed(),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->label('Konfirmasi Password')
                            ->requiredWith('password'),
                    ])
                    ->columns(2),

                \Filament\Schemas\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->options([
                                'user' => 'User',
                                'peneliti' => 'Peneliti',
                                'admin' => 'Admin',
                            ])
                            ->required()
                            ->native(false)
                            ->disabledOn('edit'),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull()
                            ->disabledOn('edit'),
                        Forms\Components\Select::make('kecamatan_id')
                            ->label('Kecamatan')
                            ->relationship('kecamatan', 'nama')
                            ->searchable()
                            ->preload()
                            ->disabledOn('edit'),
                        Forms\Components\TextInput::make('points')
                            ->required()
                            ->integer()
                            ->default(0)
                            ->disabledOn('edit'),
                        Forms\Components\TextInput::make('streak_days')
                            ->required()
                            ->integer()
                            ->default(0)
                            ->disabledOn('edit'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('kecamatan.nama')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'user' => 'User',
                        'peneliti' => 'Peneliti',
                        'admin' => 'Admin',
                    ])
                    ->native(false),
            ])
            ->recordActions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggunas::route('/'),
            'create' => Pages\CreatePengguna::route('/create'),
            'edit' => Pages\EditPengguna::route('/{record}/edit'),
        ];
    }

    public static function getRecordTitle(?Model $record): string|null
    {
        return $record?->nama ?? 'Pengguna';
    }
}