<?php

namespace App\Filament\Monitor\Resources\EnrollmentResource\Pages;

use App\Filament\Monitor\Resources\EnrollmentResource\EnrollmentResource;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
