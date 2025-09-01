<?php

namespace App\Filament\Admin\Resources\StudentResource\Schemas;

use App\Models\Student;
use App\Services\IbgeService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        $ibge = app(IbgeService::class);

        return $schema
            ->components([
                Section::make('Dados do Aluno')->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('document')
                        ->label('CPF')
                        ->unique(ignoreRecord: true),

                    DatePicker::make('birth_date')->label('Data de Nascimento'),

                    TextInput::make('phone')->label('Telefone'),

                    TextInput::make('email')
                        ->label('E-mail')
                        ->email(),
                ])->columns(2),

                Section::make('Endereço')->schema([
                    TextInput::make('address.street')->label('Rua'),
                    TextInput::make('address.number')->label('Número'),
                    TextInput::make('address.district')->label('Bairro'),
                    TextInput::make('address.zip_code')->label('CEP'),

                    Select::make('address.state_ibge_id')
                        ->label('Estado')
                        ->options(collect($ibge->getStates())->pluck('nome', 'id'))
                        ->reactive()
                        ->afterStateUpdated(function (callable $set) {
                            $set('address.city_ibge_id', null);
                        })
                        ->required(),

                    Select::make('address.city_ibge_id')
                        ->label('Cidade')
                        ->options(function (callable $get) use ($ibge) {
                            $stateId = $get('address.state_ibge_id');
                            if (!$stateId) {
                                return [];
                            }
                            return collect($ibge->getCitiesByState((int)$stateId))->pluck('nome', 'id');
                        })
                        ->required(),
                ])->columns(2),

                TextEntry::make('created_by')
                    ->label('Criado Por')
                    ->state(fn(?Student $record): string => $record?->created_by?->name ?? '-'),

                TextEntry::make('updated_by')
                    ->label('Atualizado Por')
                    ->state(fn(?Student $record): string => $record?->updated_by?->name ?? '-'),

                TextEntry::make('created_at')
                    ->label('Criado Em')
                    ->state(fn(?Student $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Última Atualização Em')
                    ->state(fn(?Student $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }
}
