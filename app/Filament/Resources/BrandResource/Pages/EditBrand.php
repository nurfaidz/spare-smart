<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(function () {

                    if ($this->record->spareParts->count() > 0) {
                        Notification::make()
                            ->title('Gagal menghapus data')
                            ->body('Brand ini masih memiliki data suku cadang yang terkait.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $this->record->delete();

                    Notification::make()
                        ->title('Berhasil menghapus data')
                        ->body('Data brand berhasil dihapus.')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.brands.index');
                })
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['name'] = strtoupper($data['name']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        activity()
            ->performedOn($record)
            ->causedBy(auth()->user())
            ->log('Mengubah data brand');

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
