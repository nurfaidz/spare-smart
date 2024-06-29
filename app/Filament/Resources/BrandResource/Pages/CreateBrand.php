<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = strtoupper($data['name']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return \DB::transaction(function () use ($data) {
            $record = new ($this->getModel())($data);

            if ($tenant = Filament::getTenant()) {
                return $this->associateRecordWithTenant($record, $tenant);
            }

            $record->save();

            activity()
                ->performedOn($record)
                ->causedBy(auth()->user())
                ->log('Membuat data brand');
        });
    }
}
