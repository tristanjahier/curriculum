<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::where('email', 'admin@localhost')->doesntExist()) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@localhost',
                'password' => Hash::make('12345678'),
            ]);
        }
    }
}
