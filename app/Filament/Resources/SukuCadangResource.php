<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SukuCadangResource\Pages;
use App\Filament\Resources\SukuCadangResource\RelationManagers;
use App\Models\SparePart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SukuCadangResource extends Resource
{
    protected static ?string $model = SparePart::class;

    protected static ?string $label = 'Suku Cadang';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Suku Cadang')
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\TextInput::make('code')
                                ->label('Kode Suku Cadang')
                                ->unique(ignoreRecord: true)
                                ->required(),
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Suku Cadang')
                                ->required(),
                            Forms\Components\Select::make('brand_id')
                                ->label('Merk')
                                ->options(
                                    fn () => \App\Models\Brand::pluck('name', 'id')
                                )
                                ->required(),
                            Forms\Components\TextInput::make('current_price')
                                ->label('Harga')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextArea::make('description')
                                ->label('Deskripsi'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand/Merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('current_price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($record) => $record->current_price ? 'Rp ' . number_format($record->current_price, 0, ',', '.') : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->formatStateUsing(fn ($record) => $record->stock ?? '-')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LogsRelationManager::class,
            RelationManagers\PricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSukuCadangs::route('/'),
            'create' => Pages\CreateSukuCadang::route('/create'),
            'edit' => Pages\EditSukuCadang::route('/{record}/edit'),
            'view' => Pages\ViewSukuCadang::route('/{record}/view'),
        ];
    }
}
