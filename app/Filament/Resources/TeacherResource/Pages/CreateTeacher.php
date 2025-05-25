<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TeacherResource;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userData = $data['user'];
        unset($data['user']);

        $user = User::create($userData)->assignRole('teacher');


        $data['user_id'] = $user->id;

        return $data;
    }
}
