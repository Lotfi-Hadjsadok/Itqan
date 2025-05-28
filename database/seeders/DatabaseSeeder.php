<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'teacher']);
        Role::firstOrCreate(['name' => 'student']);

        User::factory()->create([
            'name' => 'Admin',
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ])->assignRole('admin');

        $teacher = User::factory()->create([
            'name' => 'Teacher',
            'first_name' => 'Teacher',
            'last_name' => 'Teacher',
            'email' => 'teacher@teacher.com',
            'password' => Hash::make('teacher'),
        ])->assignRole('teacher');

        Teacher::create([
            'user_id' => $teacher->id,
        ]);

        $student = User::factory()->create([
            'name' => 'Student',
            'first_name' => 'Student',
            'last_name' => 'Student',
            'email' => 'student@student.com',
            'password' => Hash::make('student'),
        ])->assignRole('student');

        Student::create([
            'user_id' => $student->id,
        ]);
    }
}
