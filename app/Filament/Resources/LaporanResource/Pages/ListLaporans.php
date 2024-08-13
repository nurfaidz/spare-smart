<?php

namespace App\Filament\Resources\LaporanResource\Pages;

use App\Filament\Resources\LaporanResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class ListLaporans extends ListRecords
{
    protected static string $resource = LaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Ekspor Laporan')
                ->modalHeading('Ekspor data hanya untuk laporan yang aktif.')
                ->form([
                    Forms\Components\Select::make('reportable_type')
                        ->label('Tipe Laporan')
                        ->options([
                            \App\Models\IncomingItem::class => 'Barang Masuk',
                            \App\Models\OutgoingItem::class => 'Barang Keluar',
                        ])
                        ->helperText('Pilih tipe laporan yang akan diekspor.')
                        ->required(),
                    DateRangePicker::make('date')
                        ->label('Tanggal Laporan')
                        ->maxDate(now())
                        ->required()
                        ->startDate(Carbon::now()->startOfMonth())
                        ->endDate(Carbon::now())
                        ->displayFormat('YYYY-MM-DD')
                        ->format('Y-m-d'),
                ])
                ->action(function (array $data) {
                    $date = explode(' - ', $data['date']);

                    $tableFilters = [];
                    if ($data['reportable_type'] === \App\Models\IncomingItem::class) {
                        $tableFilters = \App\Models\IncomingItem::where('incoming_at', '>=', Carbon::parse($date[0]))
                            ->where('incoming_at', '<=', Carbon::parse($date[1]))
                            ->whereState('status', \App\States\Status\Activated::class)
                            ->get();
                    } elseif ($data['reportable_type'] === \App\Models\OutgoingItem::class) {
                        $tableFilters = \App\Models\OutgoingItem::where('outgoing_at', '>=', Carbon::parse($date[0]))
                            ->where('outgoing_at', '<=', Carbon::parse($date[1]))
                            ->whereState('status', \App\States\Status\Activated::class)
                            ->get();
                    }

                    if ($tableFilters->isEmpty()) {
                        return Notification::make()
                            ->title('Tidak ada data')
                            ->body('Tidak ada data yang dapat diekspor.')
                            ->danger()
                            ->send();
                    } else {
                        $export = new \App\Exports\ReportExport($tableFilters, $data['reportable_type'], $date[0], $date[1]);
                        $name = $data['reportable_type'] === \App\Models\IncomingItem::class ? 'barang_masuk' : 'barang_keluar';
                        return \Maatwebsite\Excel\Facades\Excel::download($export, 'laporan_' . $name . '_' . Carbon::parse($date[0])->format('Y-m-d') . '_' . Carbon::parse($date[1])->format('Y-m-d') . '.xlsx');
                    }

                })
        ];
    }

    public function getFilteredSortedTableQuery(): Builder
    {
        return parent::getFilteredSortedTableQuery();
    }
}
