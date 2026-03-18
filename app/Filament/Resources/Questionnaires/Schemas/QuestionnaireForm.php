<?php

namespace App\Filament\Resources\Questionnaires\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class QuestionnaireForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->rows(4)
                ->columnSpanFull(),

            Select::make('target_role')
                ->label('For who?')
                ->options([
                    'student' => 'Student',
                    'parent'  => 'Parent',
                ])
                ->required()
                ->default('student'),
        ]);
    }
}
