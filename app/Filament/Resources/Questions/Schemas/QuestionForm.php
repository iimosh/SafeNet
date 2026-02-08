<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            Select::make('questionnaire_id')
                ->relationship('questionnaire', 'title')
                ->required(),

            TextInput::make('question_text')
                ->required()
                ->maxLength(255),

            TextInput::make('category')
                ->required()
                ->maxLength(255),

        ]);
    }
}
