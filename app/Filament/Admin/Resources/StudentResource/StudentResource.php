<?php

namespace App\Filament\Admin\Resources\StudentResource;

use App\Filament\Admin\Resources\StudentResource\Pages\CreateStudent;
use App\Filament\Admin\Resources\StudentResource\Pages\EditStudent;
use App\Filament\Admin\Resources\StudentResource\Pages\ListStudents;
use App\Filament\Admin\Resources\StudentResource\RelationManagers\EnrollmentsRelationManager;
use App\Filament\Admin\Resources\StudentResource\Schemas\StudentForm;
use App\Filament\Admin\Resources\StudentResource\Tables\StudentsTable;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $slug = 'students';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static ?string $navigationLabel = 'Alunos';
    protected static ?string $modelLabel = 'Aluno';

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
