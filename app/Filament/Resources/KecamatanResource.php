<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KecamatanResource\Pages;
use App\Filament\Resources\KecamatanResource\RelationManagers;
use App\Models\Kecamatan;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;

class KecamatanResource extends Resource
{
    protected static ?string $model = Kecamatan::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-map-pin';

    protected static string | \UnitEnum | null $navigationGroup = 'Data Master';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Kecamatan';

    protected static ?string $modelLabel = 'Kecamatan';

    protected static ?string $pluralModelLabel = 'Kecamatan';

    public static function canViewAny(): bool
    {
        // Hanya admin yang bisa mengakses resource ini
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canView(?\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        // Log untuk debugging
        if (auth()->check()) {
            \Log::info('KecamatanResource canView check', [
                'user_role' => auth()->user()->role,
                'is_admin' => auth()->user()->role === 'admin'
            ]);
        }
        
        // Hanya admin yang bisa mengakses resource ini
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canCreate(): bool
    {
        // Hanya admin yang bisa membuat kecamatan baru
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canEdit($record): bool
    {
        // Hanya admin yang bisa mengedit kecamatan
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canDelete($record): bool
    {
        // Hanya admin yang bisa menghapus kecamatan
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Kecamatan')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kecamatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListKecamatans::route('/'),
            'create' => Pages\CreateKecamatan::route('/create'),
            'edit' => Pages\EditKecamatan::route('/{record}/edit'),
        ];
    }
}