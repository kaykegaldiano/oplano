<?php

namespace App\Filament\Cs\Resources\ClassResource\Pages;

use App\Filament\Cs\Resources\ClassResource\ClassResource;
use Filament\Resources\Pages\EditRecord;

class EditClass extends EditRecord
{
    protected static string $resource = ClassResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
