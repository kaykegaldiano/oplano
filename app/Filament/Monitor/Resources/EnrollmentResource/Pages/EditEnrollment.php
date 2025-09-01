<?php

namespace App\Filament\Monitor\Resources\EnrollmentResource\Pages;

use App\Filament\Monitor\Resources\EnrollmentResource\EnrollmentResource;
use Filament\Resources\Pages\EditRecord;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
