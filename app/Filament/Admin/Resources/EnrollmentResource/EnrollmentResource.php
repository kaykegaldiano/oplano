<?php

namespace App\Filament\Admin\Resources\EnrollmentResource;

use App\Filament\Admin\Resources\EnrollmentResource\Pages\CreateEnrollment;
use App\Filament\Admin\Resources\EnrollmentResource\Pages\EditEnrollment;
use App\Filament\Admin\Resources\EnrollmentResource\Pages\ListEnrollments;
use App\Filament\Admin\Resources\EnrollmentResource\Schemas\EnrollmentForm;
use App\Filament\Admin\Resources\EnrollmentResource\Tables\EnrollmentsTable;
use App\Models\Enrollment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;
    protected static ?string $slug = 'enrollments';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentCheck;
    protected static ?string $navigationLabel = 'Matrículas';
    protected static ?string $modelLabel = 'Matrícula';

    public static function form(Schema $schema): Schema
    {
        return EnrollmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnrollmentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEnrollments::route('/'),
            'create' => CreateEnrollment::route('/create'),
            'edit' => EditEnrollment::route('/{record}/edit'),
        ];
    }
}
