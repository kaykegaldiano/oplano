<?php

namespace App\Filament\Monitor\Resources\ClassResource\RelationManagers;

use App\Enums\EnrollmentStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';
    protected static ?string $title = 'Matrículas';
    protected static ?string $modelLabel = 'Matrícula';

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
                        'success' => EnrollmentStatus::Active,
                        'warning' => EnrollmentStatus::Pending,
                        'info' => EnrollmentStatus::Completed,
                        'danger' => EnrollmentStatus::Canceled,
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
