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
                    $record->note_cancellation = $data['note'];
                    $record->status = \App\States\Status\Cancelled::class;
                    $record->save();

                    $record->sparePart->stock -= $record->quantity;
                    $record->sparePart->save();

                    $record->delete();

                    activity()
                    ->performedOn($record)
                    ->causedBy(auth()->user())
                    ->log('Membatalkan barang masuk');

                    activity()
                    ->performedOn($record->sparePart)
                    ->causedBy(auth()->user())
                    ->log('Mengupdate stok suku cadang dari barang masuk');

                    Notification::make()
                        ->success()
                        ->title('Barang Masuk telah dibatalkan!')
                        ->send();

                    redirect()->route('filament.admin.resources.barang-masuks.index');

                })
                ->hidden(fn (IncomingItem $record) => $record->status->equals(\App\States\Status\Cancelled::class)),
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
                                Infolists\Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        \App\States\Status\Activated::$name => 'success',
                                        \App\States\Status\Cancelled::$name => 'danger',
                                    }),
                                Infolists\Components\TextEntry::make('note_cancellation')
                                    ->label('Alasan Pembatalan')
                                    ->hidden(fn (IncomingItem $record) => !$record->status->equals(\App\States\Status\Cancelled::class)),
                            ]),
                        ]),
            ]);
    }
}
