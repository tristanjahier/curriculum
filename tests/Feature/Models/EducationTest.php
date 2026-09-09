<?php

use App\Models\CurriculumVitae;
use App\Models\Education;
use App\Models\Person;

test('an education entry held by a CV cannot be moved to another person', function () {
    $person = Person::factory()->create();
    $education = Education::factory()->for($person)->create();
    CurriculumVitae::factory()->for($person)->create()->education()->attach($education);

    $education->person_id = Person::factory()->create()->getKey();

    expect(fn () => $education->save())
        ->toThrow(RuntimeException::class, 'An education entry held by a CV cannot be moved to another person.');

    expect($education->fresh()->person_id)->toBe($person->getKey());
});

test('an education entry held by no CV can be moved to another person', function () {
    $education = Education::factory()->create();
    $otherPerson = Person::factory()->create();

    $education->update(['person_id' => $otherPerson->getKey()]);

    expect($education->fresh()->person_id)->toBe($otherPerson->getKey());
});
