<?php

namespace App\Filament\Resources\SukuCadangResource\Pages;

use App\Filament\Resources\SukuCadangResource;
use App\Models\SparePartPrice;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSukuCadang extends EditRecord
{
    protected static string $resource = SukuCadangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(function () {
                    if ($this->record->prices->count() > 0) {
                        $this->record->prices->each->delete();
                    }

                    $this->record->delete();

                    return redirect(SukuCadangResource::getUrl());
                })
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        SparePartPrice::create([
            'spare_part_id' => $record->id,
            'price' => $record->current_price,
        ]);

        $record->update($data);

        activity()
            ->performedOn($record)
            ->causedBy(auth()->user())
            ->log('Mengupdate data suku cadang');

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SukuCadangResource\Widgets\WidgetStatHargaSukuCadang::class,
        ];
    }
}
