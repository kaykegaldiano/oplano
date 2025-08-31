<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use App\Models\ClassModel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('class_id')
                    ->label('Class')
                    ->options(ClassModel::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->options(['active' => 'Active', 'pending' => 'Pending', 'completed' => 'Completed', 'canceled' => 'Canceled'])
                    ->default('active')
                    ->required(),

                Textarea::make('cancel_reason')
                    ->visible(fn($get) => $get('status') === 'canceled')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('class.name')
            ->columns([
                TextColumn::make('class.code')
                    ->label('Code')
                    ->sortable(),

                TextColumn::make('class.name')
                    ->label('Class')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'pending',
                        'danger' => 'canceled',
                        'info' => 'completed',
                    ]),

                TextColumn::make('enrolled_at')
                    ->label('Enrolled Date')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
