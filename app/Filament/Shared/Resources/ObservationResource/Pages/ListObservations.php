<?php

namespace App\Filament\Shared\Resources\ObservationResource\Pages;

use App\Filament\Shared\Resources\ObservationResource\ObservationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListObservations extends ListRecords
{
    protected static string $resource = ObservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nova Observação'),
        ];
    }
}
