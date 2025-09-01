<?php

namespace App\Filament\Admin\Resources\EnrollmentResource\Pages;

use App\Filament\Admin\Resources\EnrollmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        return array_filter([
            $user->isAdmin() || $user->isCS() ? CreateAction::make() : null,
        ]);
    }
}
