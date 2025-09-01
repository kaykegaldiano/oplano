<?php

namespace App\Filament\Admin\Resources\StudentResource\RelationManagers;

use App\Enums\EnrollmentStatus;
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
    protected static ?string $title = 'Matrículas';
    protected static ?string $modelLabel = 'Matrícula';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('class_id')
                    ->label('Turma')
                    ->options(ClassModel::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->options(EnrollmentStatus::options())
                    ->default(EnrollmentStatus::Active)
                    ->required(),

                Textarea::make('cancel_reason')
                    ->label('Motivo do cancelamento')
                    ->visible(fn($get) => $get('status') === EnrollmentStatus::Canceled)
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
                        'success' => EnrollmentStatus::Active,
                        'warning' => EnrollmentStatus::Pending,
                        'danger' => EnrollmentStatus::Canceled,
                        'info' => EnrollmentStatus::Completed,
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
