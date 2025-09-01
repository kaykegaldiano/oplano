<?php

namespace App\Filament\Monitor\Resources\EnrollmentResource;

use App\Filament\Monitor\Resources\EnrollmentResource\Pages\ListEnrollments;
use App\Filament\Monitor\Resources\EnrollmentResource\Schemas\EnrollmentForm;
use App\Filament\Monitor\Resources\EnrollmentResource\Tables\EnrollmentsTable;
use App\Models\Enrollment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;
    protected static ?string $slug = 'enrollments';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static ?string $navigationLabel = 'Minhas Matrículas';
    protected static ?string $modelLabel = 'Matrícula';


    public static function form(Schema $schema): Schema
    {
        return EnrollmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnrollmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['student', 'class']);
        $user = auth()->user();
        $classIds = $user?->monitoredClasses()->pluck('classes.id') ?? collect();
        return $query->whereIn('class_id', $classIds);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEnrollments::route('/'),
        ];
    }
}
