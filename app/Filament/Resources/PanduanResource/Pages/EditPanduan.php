<?php

namespace App\Filament\Resources\PanduanResource\Pages;

use App\Filament\Resources\PanduanResource;
use Filament\Resources\Pages\EditRecord;

class EditPanduan extends EditRecord
{
    protected static string $resource = PanduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
