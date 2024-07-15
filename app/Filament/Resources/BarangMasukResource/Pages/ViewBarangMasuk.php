<?php

namespace App\Filament\Resources\BarangMasukResource\Pages;

use App\Filament\Resources\BarangMasukResource;
use App\Models\IncomingItem;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewBarangMasuk extends ViewRecord
{
    protected static string $resource = BarangMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cancel')
                ->label('Batalkan Barang Masuk')
                ->form([
                    Forms\Components\TextArea::make('note')
                        ->label('Alasan Pembatalan')
                        ->required(),
                ])
                ->action(function (array $data, IncomingItem $record): void {
                    $record->note = $data['note'];
                    $record->save();

                    $record->sparePart->stock -= $record->quantity;
                    $record->sparePart->save();

                    $record->delete();

                    Notification::make()
                        ->success()
                        ->title('Barang Masuk telah dibatalkan!')
                        ->send();

                    redirect()->route('filament.admin.resources.barang-masuks.index');

                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Barang Masuk')
                    ->schema([
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('sparePart.name')
                                    ->label('Suku Cadang'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Jumlah'),
                                Infolists\Components\TextEntry::make('incoming_at')
                                    ->label('Tanggal Masuk')
                                    ->formatStateUsing(function ($record) {
                                        return Carbon::parse($record->incoming_at)->locale('id_ID')->isoFormat('LL');
                                    }),
                                Infolists\Components\TextEntry::make('note')
                                    ->label('Catatan'),
                            ]),
                        ]),
            ]);
    }
}
