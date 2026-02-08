<?php

namespace App\Filament\Resources\Options\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            Select::make('question_id')
                ->relationship('question', 'question_text')
                ->required(),

            TextInput::make('option_text')
                ->required()
                ->maxLength(255),

            TextInput::make('risk_points')
                ->numeric()
                ->required()
                ->minValue(0)
                ->maxValue(100),

        ]);
    }
}
