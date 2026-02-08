<?php

namespace App\Filament\Resources\Options\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class OptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question.question_text')
                    ->label('Question')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('option_text')
                    ->label('Option')
                    ->searchable(),

                Tables\Columns\TextColumn::make('risk_points')
                    ->label('Points')
                    ->sortable(),
            ])
            ->actions([]);
    }
}
