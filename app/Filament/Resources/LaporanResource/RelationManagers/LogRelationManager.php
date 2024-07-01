<?php

namespace App\Filament\Resources\LaporanResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogRelationManager extends RelationManager
{
    protected static string $relationship = 'log';

    protected static ?string $title = 'Aktivitas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                ->label('Deskripsi'),
            Tables\Columns\TextColumn::make('causer.name')
                ->label('User'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Aktivitas')
                ->formatStateUsing(fn ($record) => Carbon::parse($record->created_at)->locale('id_ID')->isoFormat('LLLL')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
