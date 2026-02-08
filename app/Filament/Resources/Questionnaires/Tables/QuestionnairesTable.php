<?php

namespace App\Filament\Resources\Questionnaires\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class QuestionnairesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([])
            ->bulkActions([]);
    }
}
