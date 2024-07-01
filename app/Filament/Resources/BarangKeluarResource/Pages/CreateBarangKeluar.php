<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use App\Filament\Resources\BarangKeluarResource;
use App\Models\Report;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBarangKeluar extends CreateRecord
{
    protected static string $resource = BarangKeluarResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return \DB::transaction(function () use ($data) {
            $record = new ($this->getModel())($data);

            if ($tenant = Filament::getTenant()) {
                return $this->associateRecordWithTenant($record, $tenant);
            }

            $sparePart = \App\Models\SparePart::find($data['spare_part_id']);
            $sparePart->stock -= $data['quantity'];
            $sparePart->save();

            $record->total_price = $record->quantity * $sparePart->current_price;
            $record->save();

            $report = Report::create([
                'reportable_id' => $record->id,
                'reportable_type' => get_class($record),
            ]);

            activity()
                ->performedOn($report)
                ->causedBy(auth()->user())
                ->log('Membuat laporan barang keluar');

            activity()
                ->performedOn($sparePart)
                ->causedBy(auth()->user())
                ->log('Mengupdate stok suku cadang dari barang keluar');

            activity()
                ->performedOn($record)
                ->causedBy(auth()->user())
                ->log('Membuat data barang keluar');

            return $record;
        });
    }
}
