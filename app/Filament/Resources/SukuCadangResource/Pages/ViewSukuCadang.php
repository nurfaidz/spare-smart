<?php

namespace App\Filament\Resources\SukuCadangResource\Pages;

use App\Filament\Resources\SukuCadangResource;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Resources\Pages\ViewRecord;

class ViewSukuCadang extends ViewRecord
{
    protected static string $resource = SukuCadangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Suku Cadang')
                    ->schema([
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('code')
                                ->label('Kode Suku Cadang'),
                            Infolists\Components\TextEntry::make('name')
                                ->label('Nama Suku Cadang'),
                            Infolists\Components\TextEntry::make('brand.name')
                                ->label('Merk'),
                            Infolists\Components\TextEntry::make('stock')
                                ->label('Stok'),
                            Infolists\Components\TextEntry::make('current_price')
                                ->label('Harga'),
                            Infolists\Components\TextEntry::make('description')
                                ->label('Deskripsi'),
                            ]),
                    ]),
            ]);
    }
}
