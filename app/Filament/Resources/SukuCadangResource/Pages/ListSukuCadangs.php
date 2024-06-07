<?php

namespace App\Filament\Resources\SukuCadangResource\Pages;

use App\Filament\Resources\SukuCadangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSukuCadangs extends ListRecords
{
    protected static string $resource = SukuCadangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
