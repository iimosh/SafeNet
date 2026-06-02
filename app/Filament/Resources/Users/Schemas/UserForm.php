<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            TextInput::make('name')
                ->label('Име')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Е-маил')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            Select::make('role')
                ->label('Улога')
                ->options([
                    'student' => 'Ученик',
                    'parent'  => 'Родител',
                    'admin'   => 'Администратор',
                ])
                ->required(),

            DateTimePicker::make('email_verified_at')
                ->label('Потврдена е-маил адреса')
                ->seconds(false)
                ->nullable(),

            TextInput::make('password')
                ->label('Нова лозинка')
                ->password()
                ->revealable()
                ->dehydrated(fn ($state) => filled($state))
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->minLength(10)
                ->helperText('Остави празно за да ја задржиш постоечката лозинка.')
                ->nullable(),

        ]);
    }
}
