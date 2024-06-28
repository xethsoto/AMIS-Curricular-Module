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
    }
}
