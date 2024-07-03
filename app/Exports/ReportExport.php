<?php

namespace App\Exports;

use App\Models\Report;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithEvents, WithStrictNullComparison, WithStyles
{
    private $query, $type, $start, $end;

    public function __construct($query, $type, $start, $end)
    {
        $this->query = $query;
        $this->type = $type;
        $this->start = $start;
        $this->end = $end;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:I1');
                $event->sheet->getDelegate()->getStyle('A1:I2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    public function headings(): array
    {
        $headers = [
            'No.',
            'Kode Suku Cadang',
            'Nama Suku Cadang',
            'Harga Satuan',
            'Stok Saat Ini',
            $this->type === \App\Models\IncomingItem::class ? 'Jumlah Masuk' : 'Jumlah Keluar',
            'Total Harga',
            'Tipe',
            $this->type === \App\Models\IncomingItem::class ? 'Tanggal Masuk' : 'Tanggal Keluar',
        ];

        return [
            [
                'Laporan ' . ($this->type === \App\Models\IncomingItem::class ? 'Barang Masuk' : 'Barang Keluar') . ' | ' . Carbon::parse($this->start)->format('d M Y') . ' - ' . Carbon::parse($this->end)->format('d M Y'),
            ],
            $headers,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['align' => ['center' => true]],
            2 => ['align' => ['center' => true]],
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->query->map(function ($report, $key) {
            return [
                $key + 1,
                $report->sparePart->code,
                $report->sparePart->name,
                $report->sparePart->current_price,
                $report->sparePart->stock,
                $report->quantity,
                $report->total_price,
                $this->type === \App\Models\IncomingItem::class ? 'Barang Masuk' : 'Barang Keluar',
                $this->type === \App\Models\IncomingItem::class ? Carbon::parse($report->incoming_at)->format('d M Y') : Carbon::parse($report->outgoing_at)->format('d M Y'),
            ];
        });
    }
}
