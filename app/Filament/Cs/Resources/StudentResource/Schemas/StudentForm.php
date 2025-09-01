<?php

namespace App\Filament\Cs\Resources\StudentResource\Schemas;

use App\Services\IbgeService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        $ibge = app(IbgeService::class);

        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),

                TextInput::make('document')
                    ->label('CPF')
                    ->unique(ignoreRecord: true),

                DatePicker::make('birth_date')->label('Data de Nascimento'),

                TextInput::make('email')
                    ->label('E-mail')
                    ->email(),

                TextInput::make('phone')->label('Telefone'),

                Section::make('Endereço')->schema([
                    TextInput::make('address.street')->label('Rua'),
                    TextInput::make('address.number')->label('Número'),
                    TextInput::make('address.district')->label('Bairro'),
                    TextInput::make('address.zip_code')->label('CEP'),
                    Select::make('address.state_ibge_id')
                        ->label('Estado')
                        ->options(collect($ibge->getStates())->pluck('nome', 'id'))
                        ->reactive()
                        ->afterStateUpdated(fn($set) => $set('address.city_ibge_id', null))
                        ->required(),
                    Select::make('address.city_ibge_id')
                        ->label('Cidade')
                        ->options(function (callable $get) use ($ibge) {
                            $uf = $get('address.state_ibge_id');
                            return $uf ? collect($ibge->getCitiesByState((int)$uf))->pluck('nome', 'id') : [];
                        })
                        ->required(),
                ])->columns(2),
            ])->columns(2);
    }
}
