<?php

namespace App\Filament\Resources\GroupRessourceResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Seance;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PerformanceResource;
use App\Filament\Resources\SeanceResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SeancesRelationManager extends RelationManager
{
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
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('seance_id')
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
                    ->after(function (Model $record, RelationManager $livewire) {
                        $group = $livewire->ownerRecord;
                        $seance = $record;
                        $studentIds = $group->students()->pluck('students.id');
                        $seance->performances()->createMany($studentIds->map(fn($studentId) => [
                            'student_id' => $studentId,
                            'seance_id' => $seance->id,
                        ]));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn(Seance $record): string => SeanceResource::getUrl('performances', ['record' => $record]))
                    ->icon('heroicon-o-pencil')
                    ->color('success')
                    ->label(__('Performances')),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
