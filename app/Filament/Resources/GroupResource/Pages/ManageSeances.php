<?php

namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use App\Filament\Resources\SeanceResource\Pages;
use Filament\Resources\Pages\ManageRecords;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Pages\ManageRelatedRecords;
use App\Filament\Resources\SeanceResource;
use App\Models\Seance;

class ManageSeances extends ManageRelatedRecords
{
    protected static string $resource = GroupResource::class;
    protected static string $relationship = 'seances';

    public  function getModelLabel(): string
    {
        return __('Seance');
    }

    public  function getPluralModelLabel(): string
    {
        return __('Seances');
    }

    public function getTitle(): string
    {
        return __('Seances');
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    function table(Table $table): Table
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn(Seance $record): string => SeanceResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [];
    }
}
