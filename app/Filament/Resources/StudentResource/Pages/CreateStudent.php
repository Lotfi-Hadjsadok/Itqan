<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StudentResource;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userData = $data['user'];
        unset($data['user']);

        $user = User::create($userData)->assignRole('student', 'teacher', 'admin');


        $data['user_id'] = $user->id;

        return $data;
    }
}
