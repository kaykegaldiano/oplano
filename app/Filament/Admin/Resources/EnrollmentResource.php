<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EnrollmentResource\Pages;
use App\Models\ClassModel;
use App\Models\Enrollment;
use App\Models\Student;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $slug = 'enrollments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Academic';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->options(Student::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('class_id')
                    ->label('Class')
                    ->options(ClassModel::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->options([
                        'active' => 'Ativa',
                        'pending' => 'Pendente',
                        'completed' => 'Concluída',
                        'canceled' => 'Cancelada',
                    ])->default('active')->required(),

                Textarea::make('cancel_reason')
                    ->visible(fn($get) => $get('status') === 'canceled'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('class.code')
                    ->label('Code')
                    ->sortable(),

                TextColumn::make('class.name')
                    ->label('Class')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'pending',
                        'info' => 'completed',
                        'danger' => 'canceled',
                    ]),

                TextColumn::make('enrolled_at')
                    ->label('Enrolled Date')
                    ->date('d/m/Y'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                ])
            ])
            ->recordActions([
                EditAction::make()->visible(fn() => $user->isAdmin() || $user->isCS()),
                DeleteAction::make()->visible(fn() => $user->isAdmin() || $user->isCS()),
                Action::make('complete')
                    ->label('Mark as Completed')
                    ->visible(fn() => $user->isMonitor())
                    ->requiresConfirmation()
                    ->action(function (Enrollment $record) {
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now()
                        ]);
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])->visible(fn() => $user->isAdmin() || $user->isCS()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EnrollmentResource\Pages\ListEnrollments::route('/'),
            'create' => EnrollmentResource\Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['student', 'class']);
        $user = auth()->user();

        if ($user && $user->isMonitor()) {
            $classIds = $user->monitoredClasses()->pluck('classes.id');
            $query->whereIn('class_id', $classIds);
        }

        return $query;
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['student']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['student.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->student) {
            $details['Student'] = $record->student->name;
        }

        return $details;
    }
}
