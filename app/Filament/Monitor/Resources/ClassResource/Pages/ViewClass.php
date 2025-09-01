<?php

namespace App\Filament\Monitor\Resources\ClassResource\Pages;

use App\Filament\Monitor\Resources\ClassResource\ClassResource;
use Filament\Resources\Pages\ViewRecord;

class ViewClass extends ViewRecord
{
    protected static string $resource = ClassResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
