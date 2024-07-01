<?php

namespace App\Filament\Resources\BarangMasukResource\Pages;

use App\Filament\Resources\BarangMasukResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBarangMasuk extends CreateRecord
{
    protected static string $resource = BarangMasukResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return \DB::transaction(function () use ($data) {
            $record = new ($this->getModel())($data);

            if ($tenant = Filament::getTenant()) {
                return $this->associateRecordWithTenant($record, $tenant);
            }

            $sparePart = \App\Models\SparePart::find($data['spare_part_id']);
            $sparePart->stock += $data['quantity'];
            $sparePart->save();

            $record->total_price = $record->quantity * $sparePart->current_price;
            $record->save();

            activity()
                ->performedOn($sparePart)
                ->causedBy(auth()->user())
                ->log('Mengupdate stok suku cadang dari barang masuk');


            activity()
                ->performedOn($record)
                ->causedBy(auth()->user())
                ->log('Membuat data barang masuk');

            return $record;
        });
    }
}
