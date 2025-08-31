<?php

namespace App\Filament\Admin\Resources\ClassResource\Pages;

use App\Filament\Admin\Resources\ClassResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClass extends CreateRecord
{
    protected static string $resource = ClassResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
