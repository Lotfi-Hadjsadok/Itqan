<?php

namespace App\Filament\Resources\GroupRessourceResource\RelationManagers;

use App\Enums\PerformanceType;
use Filament\Forms;
use Filament\Tables;
use App\Models\Seance;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SeanceResource;
use App\Filament\Resources\PerformanceResource;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SeancesRelationManager extends RelationManager
{
    use InteractsWithForms;

    public $performanceType;
    protected static string $relationship = 'seances';

    public static function getModelLabel(): string
    {
        return __('Seance');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Seances');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Seances');
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('performanceType')
                    ->label(__('Performance type'))
                    ->options(PerformanceType::class)
                    ->required()
                    ->dehydrated(false)
                    ->afterStateUpdated(fn($state) => $this->performanceType = $state),
            ]);
    }

    protected function customQuery(Builder $query): Builder
    {
        if (auth()->user()->hasRole('admin')) {
            return $query;
        }

        if (auth()->user()->student) {
            return $query->whereHas('performances', function ($q) {
                $q->where('performances.student_id', auth()->user()->student->id);
            });
        }

        return $query->orderBy('created_at', 'desc');
    }


    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $this->customQuery($query))
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('H:i d/m/Y')
                    ->label(__('Date of seance')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(auth()->user()->hasRole('admin') || auth()->user()->teacher)
                    ->after(function (Model $record, RelationManager $livewire) {
                        $group = $livewire->ownerRecord;
                        $seance = $record;
                        $studentIds = $group->students()->pluck('students.id');
                        $seance->performances()->createMany($studentIds->map(fn($studentId) => [
                            'student_id' => $studentId,
                            'seance_id' => $seance->id,
                            'performance_type' => $this->performanceType,
                            'is_present' => false,
                        ]));

                        $livewire->redirect(SeanceResource::getUrl('performances', ['record' => $seance]));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn(Seance $record): string => SeanceResource::getUrl('performances', ['record' => $record]))
                    ->icon('heroicon-o-pencil')
                    ->color('success')
                    ->label(auth()->user()->hasRole('admin') || auth()->user()->teacher ? __('Performances') : __('View')),
                Tables\Actions\DeleteAction::make()
                    ->visible(auth()->user()->hasRole('admin') || auth()->user()->teacher),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
