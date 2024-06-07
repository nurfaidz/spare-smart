<?php

namespace App\Filament\Resources\SukuCadangResource\Pages;

use App\Filament\Resources\SukuCadangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSukuCadang extends EditRecord
{
    protected static string $resource = SukuCadangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
