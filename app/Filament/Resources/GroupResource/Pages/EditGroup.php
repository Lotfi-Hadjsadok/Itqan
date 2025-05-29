<?php

namespace App\Filament\Resources\GroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\GroupResource;
use App\Filament\Resources\GroupResource\Pages\ManageSeances;

class EditGroup extends EditRecord
{
    protected static string $resource = GroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        if (auth()->user()->hasRole('admin') || auth()->user()->teacher) {
            return parent::getFormActions(); // show submit button
        }

        return []; // hide for non-admins
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ManageSeances::class,
        ]);
    }
}
