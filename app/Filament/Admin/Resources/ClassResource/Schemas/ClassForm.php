<?php

namespace App\Filament\Admin\Resources\ClassResource\Schemas;

use App\Enums\ClassModality;
use App\Enums\ClassStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),

                TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->unique(ignoreRecord: true),

                Select::make('status')
                    ->options(ClassStatus::options())
                    ->default(ClassStatus::Planned)
                    ->required(),

                DatePicker::make('start_date')->label('Data inicial'),

                DatePicker::make('end_date')
                    ->label('Data final')
                    ->after('start_date'),

                TextInput::make('capacity')
                    ->label('Capacidade')
                    ->numeric()
                    ->minValue(1),

                Select::make('modality')
                    ->label('Modalidade')
                    ->options(ClassModality::options())
            ])->columns(2);
    }
}
