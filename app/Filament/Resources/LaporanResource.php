<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Filament\Resources\LaporanResource\RelationManagers;
use App\Models\Report;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class LaporanResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reportable.sparePart.code')
                    ->label('Kode Suku Cadang')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHasMorph('reportable', '*', function (Builder $builderQuery) use ($search) {
                            return $builderQuery->whereHas('sparePart', function (Builder $sparePartQuery) use ($search) {
                                return $sparePartQuery->where('code', 'like', "%{$search}%");
                            });
                        });
                    }),
                Tables\Columns\TextColumn::make('reportable.sparePart.name')
                    ->label('Suku Cadang')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHasMorph('reportable', '*', function (Builder $builderQuery) use ($search) {
                            return $builderQuery->whereHas('sparePart', function (Builder $sparePartQuery) use ($search) {
                                return $sparePartQuery->where('name', 'like', "%{$search}%");
                            });
                        });
                    }),
                Tables\Columns\TextColumn::make('reportable_id')
                    ->label('Tipe Laporan')
                    ->formatStateUsing(function ($record) {
                        if ($record->reportable_type === \App\Models\IncomingItem::class) {
                            return 'Barang Masuk';
                        } elseif ($record->reportable_type === \App\Models\OutgoingItem::class) {
                            return 'Barang Keluar';
                        }
                    }),
                Tables\Columns\TextColumn::make('reportable.quantity')
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('reportable.total_price')
                    ->label('Total Harga')
                    ->formatStateUsing(fn ($record) => $record->reportable->total_price ? 'Rp ' . number_format($record->reportable->total_price, 0, ',', '.') : '-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->formatStateUsing(function ($record) {
                        if ($record->reportable_type === \App\Models\IncomingItem::class) {
                            return Carbon::parse($record->reportable->incoming_at)->locale('id_ID')->isoFormat('LL');
                        } elseif ($record->reportable_type === \App\Models\OutgoingItem::class) {
                            return Carbon::parse($record->reportable->outgoing_at)->locale('id_ID')->isoFormat('LL');
                        }
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('fromDate')
                        ->label('Tanggal Awal')
                        ->translateLabel()
                        ->native(false),
                    Forms\Components\DatePicker::make('untilDate')
                        ->label('Tanggal Akhir')
                        ->translateLabel()
                        ->native(false),
                ])->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['fromDate'],
                            fn (Builder $query, $date): Builder => $query->where('created_at', '>=', Carbon::parse($date)->startOfDay()),
                        )
                        ->when(
                            $data['untilDate'],
                            fn (Builder $query, $date): Builder => $query->where('created_at', '<=', Carbon::parse($date)->endOfDay()),
                        );
                }),
                Tables\Filters\SelectFilter::make('reportable_type')
                ->label('Tipe Laporan')
                ->options([
                    \App\Models\IncomingItem::class => 'Barang Masuk',
                    \App\Models\OutgoingItem::class => 'Barang Keluar',
                ]),
            ])
            ->filtersApplyAction(
                fn (Action $action) => $action
                    ->label('Terapkan')
                    ->button(),
            )
            ->deferFilters()
            // ->filtersFormColumns()
            // ->filtersFormWidth('2xl')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->deferLoading()
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LogRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
            'view' => Pages\ViewLaporan::route('/{record}'),
        ];
    }
}
