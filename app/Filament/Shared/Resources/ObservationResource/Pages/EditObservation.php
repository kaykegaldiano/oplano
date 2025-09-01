<?php

namespace App\Filament\Shared\Resources\ObservationResource\Pages;

use App\Filament\Shared\Resources\ObservationResource\ObservationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditObservation extends EditRecord
{
    protected static string $resource = ObservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->requiresConfirmation(),
        ];
    }
}
