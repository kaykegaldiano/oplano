<?php

namespace App\Filament\Shared\Resources\ObservationResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ObservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('body')
                    ->label('Observação')
                    ->rows(6)
                    ->required()
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Toggle::make('pinned')
                    ->label('Fixar no topo')
                    ->default(false),
            ])->columns(1);
    }
}
