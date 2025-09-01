<?php

namespace App\Filament\Monitor\Resources\StudentResource;

use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $slug = 'students';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Meus Alunos';

    protected static ?string $modelLabel = 'Aluno';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('document')
                    ->label('CPF'),

                TextColumn::make('birth_date')
                    ->label('Nascimento')
                    ->date('d/m/Y'),

                TextColumn::make('phone')
                    ->label('Telefone'),

                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        $classIds = $user?->monitoredClasses()->pluck('classes.id') ?? collect();

        return $query->whereHas('enrollments', function (Builder $q) use ($classIds) {
            $q->whereIn('class_id', $classIds);
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
        ];
    }
}
