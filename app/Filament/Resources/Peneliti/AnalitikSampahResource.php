<?php

namespace App\Filament\Resources\Peneliti;

use App\Filament\Resources\Peneliti\AnalitikSampahResource\Pages;
use App\Filament\Resources\Peneliti\AnalitikSampahResource\RelationManagers;
use App\Models\CatatanSampah;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;

class AnalitikSampahResource extends Resource
{
    protected static ?string $model = CatatanSampah::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static string | \UnitEnum | null $navigationGroup = 'Analitik';

    protected static ?string $navigationLabel = 'Analitik Sampah';

    protected static ?string $modelLabel = 'Analitik Sampah';

    protected static ?string $pluralModelLabel = 'Analitik Sampah';

    protected static ?string $recordTitleAttribute = 'jenis_terdeteksi';
    
    public static function canView(?\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        // Log untuk debugging
        if (auth()->check()) {
            \Log::info('AnalitikSampahResource canView check', [
                'user_role' => auth()->user()->role,
                'is_allowed' => in_array(auth()->user()->role, ['peneliti', 'admin'])
            ]);
        }
        
        // Hanya peneliti dan admin yang bisa mengakses resource ini
        return auth()->check() && in_array(auth()->user()->role, ['peneliti', 'admin']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
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
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'organik' => 'success',
                        'plastik' => 'info',
                        'kertas' => 'warning',
                        'logam' => 'gray',
                        'residu' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('volume_terdeteksi_liter')
                    ->label('Volume (L)')
                    ->numeric()
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()),
                Tables\Columns\TextColumn::make('berat_kg')
                    ->label('Berat (kg)')
                    ->numeric()
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()),
                Tables\Columns\TextColumn::make('waktu_setoran')
                    ->label('Waktu Setor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_divalidasi')
                    ->label('Validasi')
                    ->boolean(),
                Tables\Columns\TextColumn::make('points_diberikan')
                    ->label('Points')
                    ->numeric()
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kecamatan')
                    ->relationship('kecamatan', 'nama')
                    ->label('Kecamatan')
                    ->multiple(),
                Tables\Filters\SelectFilter::make('jenis_terdeteksi')
                    ->label('Jenis Sampah')
                    ->options([
                        'organik' => 'Organik',
                        'plastik' => 'Plastik',
                        'kertas' => 'Kertas',
                        'logam' => 'Logam',
                        'residu' => 'Residu',
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('waktu_setoran')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai'),
                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_mulai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('waktu_setoran', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_selesai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('waktu_setoran', '<=', $date),
                            );
                    }),
                Tables\Filters\TernaryFilter::make('is_divalidasi')
                    ->label('Status Validasi')
                    ->placeholder('Semua Data')
                    ->trueLabel('Sudah Divalidasi')
                    ->falseLabel('Belum Divalidasi'),
            ])
            ->headerActions([
                // Export functionality akan ditambahkan nanti
            ])
            ->actions([
                // Tidak ada action khusus - hanya untuk viewing
            ])
            ->bulkActions([
                // Tidak ada bulk actions untuk analytics
            ])
            ->defaultSort('waktu_setoran', 'desc')
            ->poll('30s'); // Auto refresh setiap 30 detik
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
            'index' => Pages\ListAnalitikSampah::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        // Hanya pengguna dengan role peneliti atau admin yang bisa mengakses
        return auth()->check() && in_array(auth()->user()->role ?? '', ['peneliti', 'admin']);
    }

    public static function canCreate(): bool
    {
        // Peneliti tidak bisa membuat catatan sampah baru
        return false;
    }

    public static function canEdit($record): bool
    {
        // Peneliti tidak bisa mengedit catatan sampah
        return false;
    }

    public static function canDelete($record): bool
    {
        // Peneliti tidak bisa menghapus catatan sampah
        return false;
    }
}