<?php

namespace App\Filament\Resources\SukuCadangResource\Pages;

use App\Filament\Resources\SukuCadangResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSukuCadang extends CreateRecord
{
    protected static string $resource = SukuCadangResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['code'] = strtoupper($data['code']);

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
                ->log('Membuat data suku cadang');

            return $record;
        });
    }
}
