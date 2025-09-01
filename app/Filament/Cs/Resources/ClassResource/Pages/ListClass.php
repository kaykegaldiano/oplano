<?php

namespace App\Filament\Cs\Resources\ClassResource\Pages;

use App\Filament\Cs\Resources\ClassResource\ClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClass extends ListRecords
{
    protected static string $resource = ClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
