<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Person::firstOrCreate(
            [
                'first_name' => 'Tristan',
                'last_name' => 'Jahier',
            ],
            [
                'birth_datetime' => '1991-07-14 09:56:00',
                'birth_timezone' => 'Europe/Paris',
                'residence' => 'Rennes, France',
                'phone' => '+331 23 45 67 89',
                'email' => 'tristan.jahier@localhost',
            ]
        );

        Person::firstOrCreate(
            [
                'first_name' => 'Drustan',
                'last_name' => 'Jaegger',
            ],
            [
                'birth_datetime' => '1991-07-14 09:15:00',
                'birth_timezone' => 'Africa/Dakar',
                'residence' => '???',
                'phone' => null,
                'email' => 'drustanj@localhost',
            ]
        );
    }
}
