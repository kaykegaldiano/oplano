<?php

namespace App\Filament\Admin\Resources\ClassResource\Tables;

use App\Enums\ClassStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ClassTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->label('Código'),

                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nome'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => ClassStatus::Planned,
                        'success' => ClassStatus::Ongoing,
                        'gray' => ClassStatus::Finished,
                        'danger' => ClassStatus::Canceled,
                    ]),

                TextColumn::make('start_date')
                    ->date('d/m/Y')
                    ->label('Data inicial'),

                TextColumn::make('end_date')
                    ->date('d/m/Y')
                    ->label('Data final'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
