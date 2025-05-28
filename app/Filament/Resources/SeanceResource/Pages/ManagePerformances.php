<?php

namespace App\Filament\Resources\SeanceResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use App\Filament\Resources\SeanceResource;
use App\Filament\Resources\PerformanceResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Table;
use Filament\Tables;


class ManagePerformances extends ManageRelatedRecords
{
    protected static string $resource = SeanceResource::class;
    protected static string $relationship = 'performances';

    public function getTitle(): string
    {
        return __('Performances');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'id')
                    ->required(),
            ]);
    }


    function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.user.name')
                    ->label(__('The student'))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
