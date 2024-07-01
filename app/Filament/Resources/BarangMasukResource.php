<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangMasukResource\Pages;
use App\Filament\Resources\BarangMasukResource\RelationManagers;
use App\Models\IncomingItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BarangMasukResource extends Resource
{
    protected static ?string $model = IncomingItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Barang Masuk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Barang Masuk')
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\Select::make('spare_part_id')
                                ->label('Suku Cadang')
                                ->options(function (?Model $record) {
                                    // $member = User::whereDoesntHave('shop')->member();
                                    // if (isset($record)) {
                                    //     $member->orWhere('id', $record->member_id);
                                    // }
                                    // return $member->pluck('name', 'id');

                                    return \App\Models\SparePart::pluck('name', 'id');
                                })
                                ->required()
                                ->searchable()
                                ->preload(),
                            Forms\Components\TextInput::make('quantity')
                                ->label('Jumlah')
                                ->numeric()
                                ->required(),
                            Forms\Components\DatePicker::make('incoming_at')
                                ->label('Tanggal Masuk')
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
                Tables\Columns\TextColumn::make('sparePart.name')
                    ->label('Suku Cadang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('actual_price')
                    ->label('Harga Satuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->searchable(),
                Tables\Columns\TextColumn::make('incoming_at')
                    ->label('Tanggal Masuk')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangMasuks::route('/'),
            'create' => Pages\CreateBarangMasuk::route('/create'),
            'edit' => Pages\EditBarangMasuk::route('/{record}/edit'),
        ];
    }
}
