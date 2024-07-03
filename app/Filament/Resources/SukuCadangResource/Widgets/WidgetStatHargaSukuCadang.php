<?php

namespace App\Filament\Resources\SukuCadangResource\Widgets;

use App\Models\Report;
use App\Models\SparePart;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WidgetStatHargaSukuCadang extends BaseWidget
{
    public SparePart $record;

    protected function getStats(): array
    {
        $query = SparePart::where('id', $this->record->getKey())->first();
        $oldPrice = $query->prices()->latest()->first()->price ?? 0;

        return [
            BaseWidget\Stat::make('Harga Saat Ini', 'Rp ' . number_format($this->record->current_price, 0, ',', '.')),
            BaseWidget\Stat::make('Harga Terakhir', 'Rp ' . number_format($oldPrice, 0, ',', '.')),
        ];
    }
}
