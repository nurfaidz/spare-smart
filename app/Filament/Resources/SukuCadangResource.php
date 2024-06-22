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
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Suku Cadang')
                                ->required(),
                            Forms\Components\TextInput::make('code')
                                ->label('Kode Suku Cadang')
                                ->unique(ignoreRecord: true)
                                ->required(),
                            Forms\Components\TextInput::make('price')
                                ->label('Harga')
                                ->numeric()
                                ->required(),
                            Forms\Components\Select::make('brand_id')
                                ->label('Merk')
                                ->options(
                                    fn () => \App\Models\Brand::pluck('name', 'id')
                                )
                                ->required(),
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListSukuCadangs::route('/'),
            'create' => Pages\CreateSukuCadang::route('/create'),
            'edit' => Pages\EditSukuCadang::route('/{record}/edit'),
        ];
    }
}
