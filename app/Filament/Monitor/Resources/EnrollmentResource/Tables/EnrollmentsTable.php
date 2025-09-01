<?php

namespace App\Filament\Monitor\Resources\EnrollmentResource\Tables;

use App\Models\Enrollment;
use Filament\Actions\Action;
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
                    ->label('Aluno')
                    ->searchable(),

                TextColumn::make('class.code')
                    ->label('Código'),

                TextColumn::make('class.name')
                    ->label('Turma')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'pending',
                        'info' => 'completed',
                        'danger' => 'canceled'
                    ]),

                TextColumn::make('enrolled_at')->date('d/m/Y')->label('Matrícula em'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('complete')
                    ->label('Marcar como Concluída')
                    ->visible(fn() => $user?->isMonitor())
                    ->requiresConfirmation()
                    ->action(function (Enrollment $record) {
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now()
                        ]);
                    })
            ])
            ->toolbarActions([]);
    }
}
