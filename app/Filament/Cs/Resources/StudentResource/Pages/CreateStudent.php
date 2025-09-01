<?php

namespace App\Filament\Cs\Resources\StudentResource\Pages;

use App\Filament\Cs\Resources\StudentResource\StudentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
}
