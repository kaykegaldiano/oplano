<?php

namespace App\Filament\Cs\Resources\EnrollmentResource\Pages;

use App\Filament\Cs\Resources\EnrollmentResource\EnrollmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
