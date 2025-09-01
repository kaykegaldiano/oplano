<?php

namespace App\Filament\Monitor\Resources\ClassResource;

use App\Filament\Monitor\Resources\ClassResource\Pages\ListModels;
use App\Filament\Monitor\Resources\ClassResource\Pages\ViewClass;
use App\Filament\Monitor\Resources\ClassResource\RelationManagers\EnrollmentsRelationManager;
use App\Filament\Monitor\Resources\ClassResource\Schemas\ClassForm;
use App\Filament\Monitor\Resources\ClassResource\Tables\ClassTable;
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

    protected static ?string $navigationLabel = 'Minhas Turmas';

    protected static string|null|\UnitEnum $navigationGroup = 'Acadêmico';

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
            'index' => ListModels::route('/'),
            'view' => ViewClass::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        $classIds = $user?->monitoredClasses()->pluck('classes.id') ?? collect();

        return $query->whereIn('id', $classIds);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
