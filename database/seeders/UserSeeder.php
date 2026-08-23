<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
                'password' => '$2y$12$y1zE1xnMcaPu8cf6F9rFb.CZMT.hHLrkKPRAsLBE8NPSUOIuaWqQi', // 12345678
            ]);
        }
    }
}
