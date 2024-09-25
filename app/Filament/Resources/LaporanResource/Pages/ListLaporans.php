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
                ->label('Cetak Laporan')
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
                        ->displayFormat('DD/MM/YYYY')
                        ->format('d/m/Y')
                ])
                ->action(function (array $data) {
                    $date = explode(' - ', $data['date']);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]));
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]));

                    $tableFilters = [];
                    if ($data['reportable_type'] === \App\Models\IncomingItem::class) {
                        $tableFilters = \App\Models\IncomingItem::where('incoming_at', '>=', $startDate)
                            ->where('incoming_at', '<=', $endDate)
                            ->whereState('status', \App\States\Status\Activated::class)
                            ->get();
                    } elseif ($data['reportable_type'] === \App\Models\OutgoingItem::class) {
                        $tableFilters = \App\Models\OutgoingItem::where('outgoing_at', '>=', $startDate)
                            ->where('outgoing_at', '<=', $endDate)
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
                        $export = new \App\Exports\ReportExport($tableFilters, $data['reportable_type'], $startDate, $endDate);
                        $name = $data['reportable_type'] === \App\Models\IncomingItem::class ? 'barang_masuk' : 'barang_keluar';
                        return \Maatwebsite\Excel\Facades\Excel::download($export, 'laporan_' . $name . '_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.xlsx');
                    }

                })
        ];
    }

    public function getFilteredSortedTableQuery(): Builder
    {
        return parent::getFilteredSortedTableQuery();
    }
}
