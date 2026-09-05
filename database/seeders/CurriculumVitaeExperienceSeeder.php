<?php

namespace Database\Seeders;

use App\Models\CurriculumVitae;
use App\Models\Experience;
use App\Models\Person;
use Illuminate\Database\Seeder;

class CurriculumVitaeExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tristan = Person::where(['first_name' => 'Tristan', 'last_name' => 'Jahier'])->firstOrFail();

        $experiences = Experience::whereBelongsTo($tristan)->get();

        $mainCv = CurriculumVitae::where(['person_id' => $tristan->id, 'slug' => 'mon-beau-cv'])->firstOrFail();
        $mainCv->experiences()->syncWithoutDetaching($experiences);

        $comprehensiveCv = CurriculumVitae::where(['person_id' => $tristan->id, 'slug' => 'comprehensive'])->firstOrFail();
        $comprehensiveCv->experiences()->syncWithoutDetaching($experiences);
    }
}
