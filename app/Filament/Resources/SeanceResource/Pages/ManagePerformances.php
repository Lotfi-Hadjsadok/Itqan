<?php

namespace App\Filament\Resources\SeanceResource\Pages;

use Filament\Tables;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\PerformanceType;
use App\Filament\Resources\SeanceResource;
use App\Filament\Resources\PerformanceResource;
use Illuminate\Contracts\Database\Query\Builder;
use Filament\Resources\Pages\ManageRelatedRecords;


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
            ->schema([]);
    }

    protected function customQuery(Builder $query): Builder
    {
        if (auth()->user()->student) {
            return $query->where('student_id', auth()->user()->student->id);
        }

        return $query;
    }

    function table(Table $table): Table
    {
        return $table
            ->paginated(!auth()->user()->hasRole('student'))
            ->modifyQueryUsing(fn(Builder $query) => $this->customQuery($query))
            ->columns([
                Tables\Columns\TextColumn::make('student.user.name')
                    ->label(__('The student'))
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_present')
                    ->label(__('Is present'))
                    ->sortable()
                    ->default(true),

                Tables\Columns\SelectColumn::make('performance_type')
                    ->label(__('Performance type'))
                    ->options(PerformanceType::class)
                    ->sortable(),

                Tables\Columns\TextInputColumn::make('performance_value')
                    ->label(__('Performance value'))


                    ->sortable(),
                Tables\Columns\TextInputColumn::make('performance_comment')
                    ->type('textarea')
                    ->label(__('Performance comment'))

            ])
            ->filters([])
            ->headerActions([])
            ->actions([
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
        return [];
    }
}
