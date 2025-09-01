<?php

namespace App\Filament\Monitor\Resources\ClassResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';
    protected static ?string $title = 'Matrículas';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Aluno')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'pending',
                        'info' => 'completed',
                        'danger' => 'canceled',
                    ]),

                TextColumn::make('enrolled_at')->date('d/m/Y')->label('Matriculado em'),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
