<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Person;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tristan = Person::where(['first_name' => 'Tristan', 'last_name' => 'Jahier'])->firstOrFail();

        Education::firstOrCreate([
            'title' => 'DUT en Informatique',
            'person_id' => $tristan->getKey(),
        ], [
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae lorem at leo euismod accumsan.',
            'institution' => 'IUT de Vannes',
            'location' => 'Vannes',
            'started_at' => '2009-09-01',
            'ended_at' => '2011-06-01',
        ]);

        Education::firstOrCreate([
            'title' => 'Diplôme d\'Ingénieur en Informatique',
            'person_id' => $tristan->getKey(),
        ], [
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae lorem at leo euismod accumsan.',
            'institution' => 'ESIR, Université de Rennes 1',
            'location' => 'Rennes',
            'started_at' => '2011-09-01',
            'ended_at' => '2014-09-01',
        ]);

        $drustan = Person::where(['first_name' => 'Drustan', 'last_name' => 'Jaegger'])->firstOrFail();

        Education::firstOrCreate([
            'title' => 'MSc in Applied Discretion',
            'person_id' => $drustan->getKey(),
        ], [
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae lorem at leo euismod accumsan.',
            'institution' => '<redacted>',
            'location' => null,
            'started_at' => '2001-09-01',
            'ended_at' => '2005-06-01',
        ]);
    }
}
