<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TeacherResource;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['user']['password'] == '') {
            unset($data['user']['password']);
        }
        $userData = $data['user'];
        unset($data['user']);
        $this->record->user->update($userData);
        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user']['name'] = $this->record->user?->name ?? '';
        $data['user']['email'] = $this->record->user?->email ?? '';
        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
