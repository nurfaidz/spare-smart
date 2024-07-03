<?php

namespace App\Filament\Resources\LaporanResource\Pages;

use App\Filament\Resources\LaporanResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Resources\Pages\ViewRecord;

class ViewLaporan extends ViewRecord
{
    protected static string $resource = LaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Laporan')
                    ->schema([
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('reportable.sparePart.code')
                                    ->label('Kode Suku Cadang'),
                                Infolists\Components\TextEntry::make('reportable.sparePart.name')
                                    ->label('Nama Suku Cadang'),
                                Infolists\Components\TextEntry::make('reportable.sparePart.brand.name')
                                    ->label('Brand'),
                                Infolists\Components\TextEntry::make('reportable.sparePart.current_price')
                                    ->label('Harga Satuan Saat Ini')
                                    ->formatStateUsing(fn ($record) => $record->reportable->sparePart->current_price ? 'Rp ' . number_format($record->reportable->sparePart->current_price, 0, ',', '.') : '-'),
                                Infolists\Components\TextEntry::make('reportable.sparePart.stock')
                                    ->label('Stok Saat Ini'),
                                Infolists\Components\TextEntry::make('reportable.quantity')
                                    ->label('Jumlah'),
                                Infolists\Components\TextEntry::make('reportable.total_price')
                                    ->label('Total Harga')
                                    ->formatStateUsing(fn ($record) => $record->reportable->total_price ? 'Rp ' . number_format($record->reportable->total_price, 0, ',', '.') : '-'),
                                Infolists\Components\TextEntry::make('reportable_id')
                                    ->label('Tipe')
                                    ->formatStateUsing(function ($record) {
                                        if ($record->reportable_type === \App\Models\IncomingItem::class) {
                                            return 'Barang Masuk';
                                        } elseif ($record->reportable_type === \App\Models\OutgoingItem::class) {
                                            return 'Barang Keluar';
                                        }
                                    }),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Tanggal')
                                    ->formatStateUsing(function ($record) {
                                        if ($record->reportable_type === \App\Models\IncomingItem::class) {
                                            return Carbon::parse($record->reportable->incoming_at)->locale('id_ID')->isoFormat('LL');
                                        } elseif ($record->reportable_type === \App\Models\OutgoingItem::class) {
                                            return Carbon::parse($record->reportable->outgoing_at)->locale('id_ID')->isoFormat('LL');
                                        }
                                    }),
                            ]),
                    ]),
            ]);
    }
}
