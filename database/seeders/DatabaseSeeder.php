<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(2)->create();

        User::factory()->create([
            'name' => 'admin1',
            'email' => 'admin1@email.com',
            'password' => Hash::make('admin1Passcode'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin2',
            'email' => 'admin2@email.com',
            'password' => Hash::make('admin2Passcode'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin3',
            'email' => 'admin3@email.com',
            'password' => Hash::make('admin3Passcode'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin4',
            'email' => 'admin4@email.com',
            'password' => Hash::make('admin4Passcode'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin5',
            'email' => 'admin5@email.com',
            'password' => Hash::make('admin5Passcode'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin6',
            'email' => 'admin6@email.com',
            'password' => Hash::make('admin6Passcode'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin7',
            'email' => 'admin7@email.com',
            'password' => Hash::make('admin7Passcode'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin8',
            'email' => 'admin8@email.com',
            'password' => Hash::make('admin8Passcode'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin9',
            'email' => 'admin9@email.com',
            'password' => Hash::make('admin9Passcode'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin10',
            'email' => 'admin10@email.com',
            'password' => Hash::make('admin10Passcode'),
            'type' => 'admin',
        ]);


    }
}
