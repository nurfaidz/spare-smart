<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use App\Filament\Resources\BarangKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangKeluar extends EditRecord
{
    protected static string $resource = BarangKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
