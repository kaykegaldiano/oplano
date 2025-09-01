<?php

namespace App\Filament\Admin\Resources\EnrollmentResource\Schemas;

use App\Enums\EnrollmentStatus;
use App\Models\ClassModel;
use App\Models\Student;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Aluno')
                    ->options(Student::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

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
                    ->label('Motivo da cancelamento')
                    ->visible(fn($get) => $get('status') === EnrollmentStatus::Canceled),
            ])->columns(2);
    }
}
