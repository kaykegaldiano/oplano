<?php

namespace App\Filament\Admin\Resources\EnrollmentResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                        'success' => 'active',
                        'warning' => 'pending',
                        'info' => 'completed',
                        'danger' => 'canceled',
                    ]),

                TextColumn::make('enrolled_at')
                    ->label('Matriculado em')
                    ->date('d/m/Y'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'active' => 'Ativo',
                    'pending' => 'Pendente',
                    'completed' => 'Concluído',
                    'canceled' => 'Cancelado',
                ])
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
