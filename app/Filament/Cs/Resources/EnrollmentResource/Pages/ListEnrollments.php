<?php

namespace App\Filament\Cs\Resources\EnrollmentResource\Pages;

use App\Filament\Cs\Resources\EnrollmentResource\EnrollmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
