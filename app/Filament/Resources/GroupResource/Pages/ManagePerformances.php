<?php

namespace App\Filament\Resources\GroupResource\Pages;

use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use App\Enums\PerformanceType;
use App\Filament\Resources\GroupResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Pages\ManageRelatedRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ManagePerformances extends ManageRelatedRecords
{
    protected static string $resource = GroupResource::class;
    protected static string $relationship = 'performances';

    public function getTitle(): string
    {
        return __('Performances');
    }

    public function getModelLabel(): string
    {
        return __('Performance');
    }

    public function getPluralModelLabel(): string
    {
        return __('Performances');
    }

    protected function customQuery(Builder $query): Builder
    {
        if (auth()->user()->student) {
            return $query->where('student_id', auth()->user()->student->id);
        }

        return $query;
    }

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->hasRole('student');
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $this->customQuery($query))
            ->columns([
                Tables\Columns\TextColumn::make('student.user.name')
                    ->label(__('Student'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('performance_type')
                    ->formatStateUsing(fn($state) => PerformanceType::from($state)->getLabel())
                    ->label(__('Performance type'))
                    ->badge()
                    ->color(fn($state) => $state == PerformanceType::MEMORIZATION->value ? 'success' : 'warning')
                    ->searchable(),
                Tables\Columns\TextColumn::make('performance_value')
                    ->label(__('Performance value'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('performance_comment')
                    ->label(__('Performance comment'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_present')
                    ->label(__('Is present'))
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('seance.created_at')
                    ->label(__('Date of seance'))
                    ->dateTime('d-m-Y h:i')
                    ->sortable(),
            ]);
    }
}
