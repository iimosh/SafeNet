<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Име')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Е-маил')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Улога')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin'   => 'danger',
                        'parent'  => 'warning',
                        'student' => 'info',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin'   => 'Администратор',
                        'parent'  => 'Родител',
                        'student' => 'Ученик',
                        default   => $state,
                    }),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Потврден')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создаден')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin'   => 'Администратор',
                        'parent'  => 'Родител',
                        'student' => 'Ученик',
                    ]),
            ])
            ->actions([])
            ->defaultSort('created_at', 'desc');
    }
}
