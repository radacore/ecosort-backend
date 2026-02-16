<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PanduanResource\Pages;
use App\Models\Panduan;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PanduanResource extends Resource
{
    protected static ?string $model = Panduan::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Panduan';

    protected static ?string $modelLabel = 'Panduan';

    protected static ?string $pluralModelLabel = 'Panduan';

    protected static string | \UnitEnum | null $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canView(?\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Panduan')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ikon')
                            ->label('Ikon (Emoji)')
                            ->required()
                            ->maxLength(10)
                            ->default('📋')
                            ->helperText('Masukkan emoji, contoh: 🍃, 🫙, 🗑️, ⚠️'),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('konten')
                            ->label('Konten (satu item per baris)')
                            ->required()
                            ->rows(8)
                            ->columnSpanFull()
                            ->helperText('Tulis satu item per baris. Contoh: Sisa makanan, Daun kering, Kulit buah')
                            ->formatStateUsing(function ($state) {
                                if (is_array($state)) {
                                    return implode("\n", $state);
                                }
                                return $state;
                            })
                            ->dehydrateStateUsing(function ($state) {
                                if (is_string($state)) {
                                    return array_values(array_filter(
                                        array_map('trim', explode("\n", $state)),
                                        fn ($item) => $item !== ''
                                    ));
                                }
                                return $state;
                            }),
                        Forms\Components\TextInput::make('urutan')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->helperText('Urutan tampil (angka kecil tampil lebih dulu)'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ikon')
                    ->label('Ikon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('urutan', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPanduans::route('/'),
            'create' => Pages\CreatePanduan::route('/create'),
            'edit' => Pages\EditPanduan::route('/{record}/edit'),
        ];
    }
}
