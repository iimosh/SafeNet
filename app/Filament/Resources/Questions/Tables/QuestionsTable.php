<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('questionnaire.title')
                    ->label('Questionnaire')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('question_text')
                    ->label('Question')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->sortable(),
            ])
            ->actions([]);
    }
}
