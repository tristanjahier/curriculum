<?php

namespace Database\Seeders;

use App\Models\CurriculumVitae;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class CurriculumVitaeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tristan = Person::where(['first_name' => 'Tristan', 'last_name' => 'Jahier'])->firstOrFail();

        CurriculumVitae::firstOrCreate(
            [
                'name' => 'Mon Beau CV',
                'slug' => 'mon-beau-cv',
            ], [
                'person_id' => $tristan->id,
                'show_photo' => true,
                'show_age' => true,
                'show_residence' => true,
                'show_phone' => false,
                'show_email' => false,
                'headline' => 'Ingénieur & développeur',
                'summary' => "Développeur web full-stack, à l'aise sur toute la chaîne : du modèle de données à l'interface. 10 ans passés à concevoir, livrer et faire évoluer des applications internes au contact direct des utilisateurs. Autonome, rigoureux, attaché au code testé et maintenable.",
                'published_at' => Date::make('2026-08-17 18:00'),
            ]
        )->setAsDefault();

        CurriculumVitae::firstOrCreate(
            [
                'name' => 'CV complet',
                'slug' => 'comprehensive',
            ], [
                'person_id' => $tristan->id,
                'show_photo' => false,
                'show_age' => false,
                'show_residence' => true,
                'show_phone' => false,
                'show_email' => false,
                'headline' => 'Ingénieur en informatique',
                'published_at' => null,
            ]
        );
    }
}
