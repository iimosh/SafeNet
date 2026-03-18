<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@safenet.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Student',
                'role' => 'student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $parent = User::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'name' => 'Test Parent',
                'role' => 'parent',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $parent->children()->syncWithoutDetaching([$student->id]);
    }
}
