<?php

namespace App\Filament\Resources\CatatanSampahResource\Pages;

use App\Filament\Resources\CatatanSampahResource;
use App\Models\Pengguna;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatatanSampah extends EditRecord
{
    protected static string $resource = CatatanSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        
        // Jika admin baru saja memvalidasi, set poin = 1  
        if ($record->is_divalidasi && $record->wasChanged('is_divalidasi')) {
            $record->update(['points_diberikan' => 1]);
            
            // Update total poin pengguna
            $pengguna = Pengguna::find($record->pengguna_id);
            if ($pengguna) {
                $totalPoin = $pengguna->catatanSampah()
                    ->where('is_divalidasi', true)
                    ->sum('points_diberikan');
                $pengguna->update(['points' => $totalPoin]);
            }
        }
    }
}