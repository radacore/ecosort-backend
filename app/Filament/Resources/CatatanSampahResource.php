<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatatanSampahResource\Pages;
use App\Filament\Resources\CatatanSampahResource\RelationManagers;
use App\Models\CatatanSampah;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;

class CatatanSampahResource extends Resource
{
    protected static ?string $model = CatatanSampah::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-trash';

    protected static string | \UnitEnum | null $navigationGroup = 'Transaksi';

    protected static ?string $recordTitleAttribute = 'jenis_terdeteksi';

    protected static ?string $navigationLabel = 'Catatan Sampah';

    protected static ?string $modelLabel = 'Catatan Sampah';
    
    public static function canViewAny(): bool
    {
        // Hanya admin dan peneliti yang bisa mengakses resource ini
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'peneliti']);
    }
    
    public static function canView(\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        // Hanya admin dan peneliti yang bisa mengakses resource ini
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'peneliti']);
    }
    
    public static function canCreate(): bool
    {
        // Hanya admin yang bisa membuat catatan sampah baru
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canEdit($record): bool
    {
        // Hanya admin yang bisa mengedit catatan sampah
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    public static function canDelete($record): bool
    {
        // Hanya admin yang bisa menghapus catatan sampah
        return auth()->check() && auth()->user()->role === 'admin';
    }

    protected static ?string $pluralModelLabel = 'Catatan Sampah';

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Hanya admin yang bisa mengubah status validasi
        if (auth()->user()->role !== 'admin') {
            unset($data['is_divalidasi']);
            unset($data['points_diberikan']);
        } else {
            // Jika status validasi true, maka points_diberikan otomatis menjadi 1
            if (isset($data['is_divalidasi']) && $data['is_divalidasi']) {
                $data['points_diberikan'] = 1;
            }
        }
        
        return $data;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\Select::make('pengguna_id')
                            ->relationship('pengguna', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('kecamatan_id')
                            ->relationship('kecamatan', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),

                \Filament\Schemas\Components\Section::make('Detail Sampah')
                    ->schema([
                        Forms\Components\TextInput::make('jenis_terdeteksi')
                            ->label('Jenis Terdeteksi')
                            ->maxLength(255)
                            ->nullable(),
                        Forms\Components\TextInput::make('volume_terdeteksi_liter')
                            ->label('Volume Terdeteksi (L)')
                            ->numeric()
                            ->nullable(),
                        Forms\Components\TextInput::make('berat_kg')
                            ->label('Berat (kg)')
                            ->numeric()
                            ->nullable(),
                    ])
                    ->columns(3),

                \Filament\Schemas\Components\Section::make('Validasi dan Gambar')
                    ->schema([
                        Forms\Components\Placeholder::make('foto_display')
                            ->label('Foto')
                            ->content(function (callable $get) {
                                $path = $get('foto_path');
                                if ($path) {
                                    // Cek beberapa kemungkinan format path
                                    $url = $path; // Gunakan path langsung sebagai default
                                    
                                    // Jika path bukan URL lengkap, coba buat URL dari storage
                                    if (!str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
                                        // Jika path dimulai dengan storage, gunakan asset langsung
                                        if (str_starts_with($path, 'storage/')) {
                                            $url = asset($path);
                                        } 
                                        // Jika path tidak dimulai dengan storage, tambahkan ke asset
                                        else {
                                            $url = asset('storage/' . $path);
                                        }
                                    }
                                    
                                    return '<img src="' . $url . '" alt="Foto Sampah" class="max-w-md max-h-96 rounded-lg shadow object-cover">';
                                }
                                return '<span class="text-gray-500 italic">Tidak ada foto</span>';
                            })
                            ->html(),
                        Forms\Components\TextInput::make('foto_path')
                            ->label('Path Foto')
                            ->maxLength(255)
                            ->nullable()
                            ->hidden(),
                        Forms\Components\DateTimePicker::make('waktu_setoran')
                            ->label('Waktu Setoran')
                            ->nullable(),
                        Forms\Components\Toggle::make('is_divalidasi')
                            ->label('Divalidasi')
                            ->required()
                            ->disabled(fn () => auth()->user()->role !== 'admin'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengguna.nama')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kecamatan.nama')
                    ->label('Kecamatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_terdeteksi')
                    ->label('Jenis Sampah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume_terdeteksi_liter')
                    ->label('Volume (L)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('berat_kg')
                    ->label('Berat (kg)')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('waktu_setoran')
                    ->label('Waktu Setor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_divalidasi')
                    ->label('Validasi')
                    ->boolean(),
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
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                // Filter data berdasarkan role pengguna
                if (auth()->check() && auth()->user()->role === 'peneliti') {
                    // Peneliti bisa melihat semua data
                    return $query;
                } else if (auth()->check() && auth()->user()->role === 'admin') {
                    // Admin bisa melihat semua data
                    return $query;
                } else {
                    // Jika bukan admin atau peneliti, tidak menampilkan data
                    $query->where('id', '<', 0); // kondisi yang tidak mungkin terpenuhi
                }
            })
            ->filters([
                Tables\Filters\SelectFilter::make('kecamatan')
                    ->relationship('kecamatan', 'nama'),
                Tables\Filters\SelectFilter::make('is_divalidasi')
                    ->label('Status Validasi')
                    ->options([
                        '1' => 'Sudah Divalidasi',
                        '0' => 'Belum Divalidasi',
                    ])
                    ->native(false),
                Tables\Filters\Filter::make('waktu_setoran')
                    ->form([
                        Forms\Components\DatePicker::make('setoran_dari')
                            ->label('Setoran Dari'),
                        Forms\Components\DatePicker::make('setoran_sampai')
                            ->label('Setoran Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['setoran_dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('waktu_setoran', '>=', $date),
                            )
                            ->when(
                                $data['setoran_sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('waktu_setoran', '<=', $date),
                            );
                    }),
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
            'index' => Pages\ListCatatanSampah::route('/'),
            'create' => Pages\CreateCatatanSampah::route('/create'),
            'edit' => Pages\EditCatatanSampah::route('/{record}/edit'),
        ];
    }
}