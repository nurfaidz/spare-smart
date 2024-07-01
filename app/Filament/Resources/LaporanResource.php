<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Filament\Resources\LaporanResource\RelationManagers;
use App\Models\Report;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Tables\Columns\TextColumn::make('reportable.sparePart.name')
                    ->label('Suku Cadang'),
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
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
