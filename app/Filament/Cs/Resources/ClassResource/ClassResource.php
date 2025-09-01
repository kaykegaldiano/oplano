<?php

namespace App\Filament\Cs\Resources\ClassResource;

use App\Filament\Cs\Resources\ClassResource\Pages\CreateClass;
use App\Filament\Cs\Resources\ClassResource\Pages\EditClass;
use App\Filament\Cs\Resources\ClassResource\Pages\ListClass;
use App\Filament\Cs\Resources\ClassResource\RelationManagers\EnrollmentsRelationManager;
use App\Filament\Cs\Resources\ClassResource\Schemas\ClassForm;
use App\Filament\Cs\Resources\ClassResource\Tables\ClassTable;
use App\Models\ClassModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassResource extends Resource
{
    protected static ?string $model = ClassModel::class;
    protected static ?string $slug = 'classes';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static ?string $navigationLabel = 'Turmas';
    protected static ?string $modelLabel = 'Aula';

    public static function form(Schema $schema): Schema
    {
        return ClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClassTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            EnrollmentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClass::route('/'),
            'create' => CreateClass::route('/create'),
            'edit' => EditClass::route('/{record}/edit'),
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
