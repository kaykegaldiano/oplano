<?php

namespace App\Filament\Monitor\Resources\StudentResource\Pages;

use App\Filament\Monitor\Resources\StudentResource\StudentResource;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
