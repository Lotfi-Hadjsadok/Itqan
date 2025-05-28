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

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ManageSeances::class,
        ]);
    }
}
