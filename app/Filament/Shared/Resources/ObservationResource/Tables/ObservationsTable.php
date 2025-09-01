<?php

namespace App\Filament\Shared\Resources\ObservationResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ObservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('pinned')
                    ->boolean()
                    ->tooltip('Fixada')
                    ->alignCenter()
                    ->toggleable(),

                TextColumn::make('body')
                    ->label('Texto')
                    ->wrap()
                    ->limit(80)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('pinned')
                    ->label('Fixadas')
                    ->placeholder('Todas')
                    ->trueLabel('Somente fixadas')
                    ->falseLabel('Somente não fixadas')
                    ->queries(
                        true: fn(Builder $q) => $q->where('pinned', true),
                        false: fn(Builder $q) => $q->where('pinned', false),
                        blank: fn(Builder $q) => $q
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
