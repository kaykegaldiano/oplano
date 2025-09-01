<?php

namespace App\Filament\Admin\Resources\ClassResource\Pages;

use App\Filament\Admin\Resources\ClassResource\ClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClassModels extends ListRecords
{
    protected static string $resource = ClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
