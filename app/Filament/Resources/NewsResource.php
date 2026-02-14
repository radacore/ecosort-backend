<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';

    protected static string | \UnitEnum | null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Berita';

    protected static ?string $modelLabel = 'Berita';

    protected static ?string $pluralModelLabel = 'Berita';

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
            ->columns(2)
            ->components([
                Section::make('Informasi Berita')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Berita')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kategori')
                            ->label('Kategori')
                            ->required()
                            ->maxLength(100),
                    ])
                    ->columnSpan(1),

                Section::make('Gambar Unggulan')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_path')
                            ->label('Foto')
                            ->image()
                            ->disk('public')
                            ->directory('news-images')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Unggah gambar (maksimal 2MB).'),
                    ])
                    ->columnSpan(1),

                Section::make('Konten')
                    ->schema([
                        Forms\Components\RichEditor::make('konten')
                            ->label('Konten')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Berita')
                    ->schema([
                        TextEntry::make('judul')
                            ->label('Judul Berita')
                            ->weight(\Filament\Support\Enums\FontWeight::Bold)
                            ->size(\Filament\Support\Enums\TextSize::Large),
                        TextEntry::make('kategori')
                            ->label('Kategori')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('created_at')
                            ->label('Tanggal Diterbitkan')
                            ->dateTime('d F Y, H:i')
                            ->icon('heroicon-m-calendar'),
                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d F Y, H:i')
                            ->icon('heroicon-m-clock')
                            ->color('gray'),
                    ])
                    ->columnSpan(1),

                \Filament\Schemas\Components\Section::make('Gambar Unggulan')
                    ->schema([
                        \Filament\Infolists\Components\ImageEntry::make('foto_url')
                            ->hiddenLabel()
                            ->state(fn ($record) => $record->foto_path ? asset('storage/' . $record->foto_path) : null)
                            ->imageHeight(300)
                            ->alignCenter()
                            ->extraImgAttributes([
                                'class' => 'rounded-xl shadow-lg border-2 border-white',
                                'style' => 'max-width: 100%; height: auto; object-fit: contain;',
                            ]),
                    ])
                    ->columnSpan(1),

                \Filament\Schemas\Components\Section::make('Konten Berita')
                    ->schema([
                        TextEntry::make('konten')
                            ->hiddenLabel()
                            ->html()
                            ->prose()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_url')
                    ->label('Foto')
                    ->state(fn ($record) => $record->foto_path ? asset('storage/' . $record->foto_path) : null)
                    ->square()
                    ->size(60)
                    ->extraImgAttributes(['class' => 'rounded-lg shadow-sm border border-gray-200']),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                    ->wrap(),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diterbitkan')
                    ->date('d M Y')
                    ->sortable()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->check() || auth()->user()->role !== 'admin') {
                    $query->where('id', '<', 0);
                }
            })
            ->filters([])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'view' => Pages\ViewNews::route('/{record}'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
