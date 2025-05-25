<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\School;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Component;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;



    public static function getModelLabel(): string
    {
        return __('Student');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Students');
    }


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make(__('User info'))
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label(__('Name'))
                            ->required(),
                        Forms\Components\TextInput::make('user.email')
                            ->label(__('Email'))
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('user.password')
                            ->label(__('Password'))
                            ->password()
                            ->required()
                            ->dehydrateStateUsing(fn($state) => bcrypt($state)),
                    ])->visible(function (string $operation) {
                        return $operation === 'create';
                    }),


                Forms\Components\TextInput::make('father_name')
                    ->label(__('Father Name'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('father_job')
                    ->label(__('Father Job'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('mother_job')
                    ->label(__('Mother Job'))
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birth_date')
                    ->label(__('Birth Date'))
                    ->native(false),
                Forms\Components\TextInput::make('birth_place')
                    ->label(__('Birth Place'))
                    ->maxLength(255),
                Forms\Components\Select::make('school_id')
                    ->label(__('School Name'))
                    ->relationship('school', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label(__('School Name'))
                            ->required(),
                    ])
                    ->searchable(),
                Forms\Components\TextInput::make('special_condition')
                    ->label(__('Special Condition'))
                    ->maxLength(255),
                Forms\Components\FileUpload::make('profile_image')
                    ->label(__('Profile Image'))
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('father_name')
                    ->label(__('Father Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label(__('Birth Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birth_place')
                    ->label(__('Birth Place'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('school.name')
                    ->label(__('School Name'))
                    ->searchable(),
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label(__('Profile Image'))
                    ->disk('public'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
