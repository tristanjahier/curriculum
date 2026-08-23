<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
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

        $this->call([
            PersonSeeder::class,
            CurriculumVitaeSeeder::class,
        ]);
    }
}
