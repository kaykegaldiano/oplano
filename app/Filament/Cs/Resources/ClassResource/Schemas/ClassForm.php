<?php

namespace App\Filament\Cs\Resources\ClassResource\Schemas;

use App\Filament\Admin\Resources\ClassResource;
use Filament\Schemas\Schema;

class ClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return ClassResource\ClassResource::form($schema);
    }
}
