<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangKeluarResource\Pages;
use App\Filament\Resources\BarangKeluarResource\RelationManagers;
use App\Models\OutgoingItem;
use App\States\Status\Activated;
use App\States\Status\Cancelled;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BarangKeluarResource extends Resource
{
    protected static ?string $model = OutgoingItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Barang Keluar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Barang Keluar')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Select::make('spare_part_id')
                                    ->label('Suku Cadang')
                                    ->options(function (?Model $record) {

                                        return \App\Models\SparePart::where('stock', '>', 0)->get()->mapWithKeys(function ($sparePart) {
                                            return [
                                                $sparePart->id => $sparePart->name . ' - ' . $sparePart->code,
                                            ];
                                        })->toArray();
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->minValue(1)
                                    ->rules([
                                        function (callable $get) {
                                            return function (string $attribute, $value, $fail) use ($get) {
                                                $sparePart = \App\Models\SparePart::find($get('spare_part_id'));
                                                if ($value > $sparePart->stock) {
                                                    $fail('Stok tidak mencukupi.');
                                                }

                                                if ($value < 1) {
                                                    $fail('Jumlah tidak boleh kurang dari 1.');
                                                }
                                            };
                                        },
                                    ])
                                    ->numeric()
                                    ->required(),
                                Forms\Components\DatePicker::make('outgoing_at')
                                    ->label('Tanggal Keluar')
                                    ->native(false)
                                    ->maxDate(now())
                                    ->required(),
                                Forms\Components\Textarea::make('note')
                                    ->label('Catatan'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sparePart.code')
                    ->label('Kode Suku Cadang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sparePart.name')
                    ->label('Suku Cadang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sparePart.current_price')
                    ->label('Harga Satuan')
                    ->formatStateUsing(fn($record) => $record->sparePart->current_price ? 'Rp ' . number_format($record->sparePart->current_price, 0, ',', '.') : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->formatStateUsing(fn($record) => $record->total_price ? 'Rp ' . number_format($record->total_price, 0, ',', '.') : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('outgoing_at')
                    ->label('Tanggal Keluar')
                    ->formatStateUsing(fn($record) => Carbon::parse($record->outgoing_at)->locale('id_ID')->isoFormat('LL'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Activated::$name => 'success',
                        Cancelled::$name => 'danger',
                    })
            ])
            ->filters([
                //
            ])
            ->defaultSort('outgoing_at', 'desc')
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
            'index' => Pages\ListBarangKeluars::route('/'),
            'create' => Pages\CreateBarangKeluar::route('/create'),
            'view' => Pages\ViewBarangKeluar::route('/{record}/view'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }
}
