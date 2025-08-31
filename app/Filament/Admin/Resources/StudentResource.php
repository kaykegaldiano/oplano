<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentResource\RelationManagers\EnrollmentsRelationManager;
use App\Filament\Admin\Resources\StudentResource\Pages;
use App\Models\Student;
use App\Services\IbgeService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $slug = 'students';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|null|\UnitEnum $navigationGroup = 'Academic';

    public static function form(Schema $schema): Schema
    {
        $ibge = app(IbgeService::class);

        return $schema
            ->components([
                Section::make('Student Data')->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('document')->label('CPF')->unique(ignoreRecord: true),

                    DatePicker::make('birth_date'),

                    TextInput::make('phone'),

                    TextInput::make('email')->email(),
                ])->columns(2),

                Section::make('Address')->schema([
                    TextInput::make('address.street')->label('Street'),
                    TextInput::make('address.number')->label('Number'),
                    TextInput::make('address.district')->label('District'),
                    TextInput::make('address.zip_code')->label('Zip Code'),

                    Select::make('address.state_ibge_id')
                        ->label('State')
                        ->options(collect($ibge->getStates())->pluck('nome', 'id'))
                        ->reactive()
                        ->afterStateUpdated(function (callable $set) {
                            $set('address.city_ibge_id', null);
                        })
                        ->required(),

                    Select::make('address.city_ibge_id')
                        ->label('City')
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
                    ->state(fn(?Student $record): string => $record?->created_by?->name ?? '-'),

                TextEntry::make('updated_by')
                    ->state(fn(?Student $record): string => $record?->updated_by?->name ?? '-'),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->state(fn(?Student $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->state(fn(?Student $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('document')->label('CPF')->toggleable(),

                TextColumn::make('birth_date')
                    ->date('d/m/Y'),

                TextColumn::make('phone'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_by'),

                TextColumn::make('updated_by'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => StudentResource\Pages\ListStudents::route('/'),
            'create' => StudentResource\Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
