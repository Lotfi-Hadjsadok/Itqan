<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Group;
use App\Models\Student;
use App\Models\Teacher;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\Pages\ManageSeances;
use App\Filament\Resources\GroupResource\Pages\ManagePerformances;
use App\Filament\Resources\GroupRessourceResource\RelationManagers\SeancesRelationManager;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getNavigationGroup(): ?string
    {
        return __('Tools');
    }

    public static function getNavigationBadge(): ?string
    {
        return auth()->user()->hasRole('admin') ? static::getModel()::count() : null;
    }


    public static function getModelLabel(): string
    {
        return __('Group');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Groups');
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user && $user->hasRole('admin')) {
            return $query;
        }

        if ($user && $user->teacher) {
            return $query->where('teacher_id', $user->teacher->id);
        }

        if ($user && $user->student) {
            return $query->whereHas('students', function ($q) use ($user) {
                $q->where('students.id', $user->student->id);
            });
        }

        return $query->whereRaw('1 = 0');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Group name'))
                    ->required()
                    ->visible(auth()->user()->hasRole(roles: 'admin'))
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher')
                    ->getOptionLabelFromRecordUsing(fn(Teacher $record) => $record->user->name)
                    ->preload()
                    ->searchable()
                    ->visible(auth()->user()->hasRole(roles: 'admin'))
                    ->label(__('The teacher'))
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Select::make('students')
                    ->relationship('students')
                    ->visible(auth()->user()->hasRole(roles: 'admin') || auth()->user()->teacher)
                    ->getOptionLabelFromRecordUsing(fn(Student $record) => $record->user->name)
                    ->multiple()
                    ->columnSpanFull()
                    ->preload()
                    ->searchable()
                    ->label(__('Students'))
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn(Model $record): string => auth()->user()->hasRole('student') ?
                    GroupResource::getUrl('performances', [
                        'record' => $record
                    ]) : GroupResource::getUrl('edit', ['record' => $record])
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Group name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__('Created at'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('Updated at')),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(auth()->user()->hasRole(roles: 'admin') || auth()->user()->teacher),
                Tables\Actions\Action::make('performances')
                    ->url(fn(Group $record): string => GroupResource::getUrl('performances', ['record' => $record]))
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->label(__('Performances'))
                    ->visible(auth()->user()->hasRole(roles: 'student')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(auth()->user()->hasRole(roles: 'admin') || auth()->user()->teacher),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [SeancesRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
            'seances' => ManageSeances::route('/{record}/seances'),
            'performances' => ManagePerformances::route('/{record}/performances'),
        ];
    }
}
