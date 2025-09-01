<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ClassResource\Pages\CreateClass;
use App\Filament\Admin\Resources\ClassResource\Pages\EditClass;
use App\Filament\Admin\Resources\ClassResource\RelationManagers\EnrollmentsRelationManager;
use App\Filament\Admin\Resources\ClassResource\Pages;
use App\Models\ClassModel;
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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassResource extends Resource
{
    protected static ?string $model = ClassModel::class;

    protected static ?string $slug = 'classes';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Turmas';

    protected static string|null|\UnitEnum $navigationGroup = 'Acadêmico';

    protected static ?string $modelLabel = 'Aula';

    public static function form(Schema $schema): Schema
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

                Select::make('status')->options([
                    'planned' => 'Planejada',
                    'ongoing' => 'Em andamento',
                    'finished' => 'Finalizada',
                    'canceled' => 'Cancelada',
                ])->default('planned')->required(),

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
                    ->options([
                        'online' => 'Online',
                        'presential' => 'Presencial',
                        'hybrid' => 'Híbrida',
                    ])
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->label('Código'),

                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nome'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'planned',
                        'success' => 'ongoing',
                        'gray' => 'finished',
                        'danger' => 'canceled',
                    ]),

                TextColumn::make('start_date')
                    ->date('d/m/Y')
                    ->label('Data inicial'),

                TextColumn::make('end_date')
                    ->date('d/m/Y')
                    ->label('Data final'),
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
            'index' => Pages\ListClasses::route('/'),
            'create' => CreateClass::route('/create'),
            'edit' => EditClass::route('/{record}/edit'),
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
        return [];
    }
}
