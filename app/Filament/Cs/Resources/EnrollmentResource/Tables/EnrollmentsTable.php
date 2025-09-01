<?php

namespace App\Filament\Cs\Resources\EnrollmentResource\Tables;

use App\Enums\EnrollmentStatus;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('ALuno')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('class.code')
                    ->label('Código')
                    ->sortable(),

                TextColumn::make('class.name')
                    ->label('Turma')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => EnrollmentStatus::Active,
                        'warning' => EnrollmentStatus::Pending,
                        'info' => EnrollmentStatus::Completed,
                        'danger' => EnrollmentStatus::Canceled,
                    ]),

                TextColumn::make('enrolled_at')
                    ->label('Matriculado em')
                    ->date('d/m/Y'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
