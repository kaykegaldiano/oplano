<?php

namespace App\Filament\Monitor\Resources\ClassResource\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClassTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->sortable()
                    ->label('Código'),

                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nome'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'planned',
                        'success' => 'ongoing',
                        'gray' => 'finished',
                        'danger' => 'canceled',
                    ]),

                TextColumn::make('start_date')
                    ->date('d/m/Y')
                    ->label('Data inicial'),

                TextColumn::make('end_date')
                    ->date('d/m/Y')
                    ->label('Data final'),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
