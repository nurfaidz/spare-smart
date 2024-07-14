<?php

namespace App\Filament\Widgets;

use App\Models\IncomingItem;
use App\Models\OutgoingItem;
use Filament\Widgets\ChartWidget;

class ChartBarang extends ChartWidget
{
    protected static ?string $heading = 'Chart Barang';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {

        $shortMonths = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei',
            6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt',
            11 => 'Nov', 12 => 'Des'
        ];

        $incomingItems = IncomingItem::query()
            ->selectRaw('MONTH(incoming_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $outgoingItems = OutgoingItem::query()
            ->selectRaw('MONTH(outgoing_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $months = array_unique(array_merge(array_keys($incomingItems), array_keys($outgoingItems)));
        sort($months);

        $labels = array_map(function($month) use ($shortMonths) {
            return $shortMonths[$month];
        }, $months);

        $incomingData = [];
        $outgoingData = [];

        foreach ($months as $month) {
            $incomingData[] = $incomingItems[$month] ?? 0;
            $outgoingData[] = $outgoingItems[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Barang Masuk',
                    'data' => $incomingData,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Barang Keluar',
                    'data' => $outgoingData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
