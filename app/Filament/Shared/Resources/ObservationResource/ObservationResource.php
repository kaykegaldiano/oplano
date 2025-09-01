<?php

namespace App\Filament\Shared\Resources\ObservationResource;

use App\Filament\Shared\Resources\ObservationResource\Pages\CreateObservation;
use App\Filament\Shared\Resources\ObservationResource\Pages\EditObservation;
use App\Filament\Shared\Resources\ObservationResource\Pages\ListObservations;
use App\Filament\Shared\Resources\ObservationResource\Schemas\ObservationForm;
use App\Filament\Shared\Resources\ObservationResource\Tables\ObservationsTable;
use App\Models\Observation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ObservationResource extends Resource
{
    protected static ?string $model = Observation::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;
    protected static ?string $navigationLabel = 'Observações';
    protected static ?string $modelLabel = 'Observação';
    protected static ?string $pluralModelLabel = 'Observações';
    protected static string|UnitEnum|null $navigationGroup = 'Global';

    public static function form(Schema $schema): Schema
    {
        return ObservationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ObservationsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListObservations::route('/'),
            'create' => CreateObservation::route('/create'),
            'edit' => EditObservation::route('/{record}/edit'),
        ];
    }
}
