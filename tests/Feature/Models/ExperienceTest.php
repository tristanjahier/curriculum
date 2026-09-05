<?php

use App\Models\CurriculumVitae;
use App\Models\Experience;
use App\Models\Person;

test('an experience held by a CV cannot be moved to another person', function () {
    $person = Person::factory()->create();
    $experience = Experience::factory()->for($person)->create();
    CurriculumVitae::factory()->for($person)->create()->experiences()->attach($experience);

    $experience->person_id = Person::factory()->create()->getKey();

    expect(fn () => $experience->save())
        ->toThrow(RuntimeException::class, 'An experience held by a CV cannot be moved to another person.');

    expect($experience->fresh()->person_id)->toBe($person->getKey());
});

test('an experience held by no CV can be moved to another person', function () {
    $experience = Experience::factory()->create();
    $otherPerson = Person::factory()->create();

    $experience->update(['person_id' => $otherPerson->getKey()]);

    expect($experience->fresh()->person_id)->toBe($otherPerson->getKey());
});
