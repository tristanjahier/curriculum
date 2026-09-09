<?php

namespace Database\Seeders;

use App\Models\CurriculumVitae;
use App\Models\Education;
use App\Models\Person;
use Illuminate\Database\Seeder;

class CurriculumVitaeEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tristan = Person::where(['first_name' => 'Tristan', 'last_name' => 'Jahier'])->firstOrFail();

        $education = Education::whereBelongsTo($tristan)->get();

        $mainCv = CurriculumVitae::where(['person_id' => $tristan->id, 'slug' => 'mon-beau-cv'])->firstOrFail();
        $mainCv->education()->syncWithoutDetaching($education);

        $comprehensiveCv = CurriculumVitae::where(['person_id' => $tristan->id, 'slug' => 'comprehensive'])->firstOrFail();
        $comprehensiveCv->education()->syncWithoutDetaching($education);

        $drustan = Person::where(['first_name' => 'Drustan', 'last_name' => 'Jaegger'])->firstOrFail();

        $spyCv = CurriculumVitae::where(['person_id' => $drustan->id, 'slug' => 'senior-spy'])->firstOrFail();
        $spyCv->education()->syncWithoutDetaching(Education::whereBelongsTo($drustan)->get());
    }
}
