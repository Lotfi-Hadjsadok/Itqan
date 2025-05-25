<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['user']['password']) && $data['user']['password'] == '') {
            unset($data['user']['password']);
        }
        if (isset($data['user'])) {
            $userData = $data['user'];
            unset($data['user']);
            $this->record->user->update($userData);
        }
        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user']['name'] = $this->record->user?->name ?? '';
        $data['user']['email'] = $this->record->user?->email ?? '';
        return $data;
    }
}
