<?php

use App\Http\Resources\CurriculumVitaeResource;
use App\Models\CurriculumVitae;
use App\Models\Experience;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

function getSerializedCvResource(CurriculumVitae $cv): array
{
    $resource = new CurriculumVitaeResource($cv);
    $request = Request::create('/'); // the HTTP request does not matter for this resource.

    return $resource->response($request)->getData(true);
}

test('exposes strictly the public properties', function () {
    $cv = CurriculumVitae::factory()->make();
    $serialized = getSerializedCvResource($cv);

    $expected = [
        'slug' => $cv->slug,
        'headline' => $cv->headline,
        'summary' => $cv->summary,
        'person' => [
            'first_name' => $cv->person->first_name,
            'last_name' => $cv->person->last_name,
            'full_name' => $cv->person->full_name,
        ],
    ];

    if ($cv->show_age) {
        $expected['person']['age'] = $cv->person->age;
    }

    if ($cv->show_residence) {
        $expected['person']['residence'] = $cv->person->residence;
    }

    if ($cv->show_phone) {
        $expected['person']['phone'] = $cv->person->phone;
    }

    if ($cv->show_email) {
        $expected['person']['email'] = $cv->person->email;
    }

    // Assert only the presence of experiences. Their shape is tested in a dedicated test.
    expect($serialized)->toHaveKey('experiences');
    unset($serialized['experiences']);

    expect(Arr::sortRecursive($serialized))
        ->toBeArray()
        ->toBe(Arr::sortRecursive($expected));
})->repeat(10);

test('does not expose person.age when show_age is false', function () {
    $cv = CurriculumVitae::factory()->make(['show_age' => false]);
    $serialized = getSerializedCvResource($cv);

    expect($serialized)->not->toHaveKey('person.age');
});

test('does not expose person.residence when show_residence is false', function () {
    $cv = CurriculumVitae::factory()->make(['show_residence' => false]);
    $serialized = getSerializedCvResource($cv);

    expect($serialized)->not->toHaveKey('person.residence');
});

test('does not expose person.phone when show_phone is false', function () {
    $cv = CurriculumVitae::factory()->make(['show_phone' => false]);
    $serialized = getSerializedCvResource($cv);

    expect($serialized)->not->toHaveKey('person.phone');
});

test('does not expose person.email when show_email is false', function () {
    $cv = CurriculumVitae::factory()->make(['show_email' => false]);
    $serialized = getSerializedCvResource($cv);

    expect($serialized)->not->toHaveKey('person.email');
});

test('sorts experiences properly', function () {
    $cv = CurriculumVitae::factory()
        ->recycle(Person::factory()->create())
        ->hasAttached(Experience::factory()->count(4)->sequence(
            ['started_at' => '2015-02-01', 'ended_at' => '2025-07-01'],
            ['started_at' => '2014-05-01', 'ended_at' => null],
            ['started_at' => '2019-09-01', 'ended_at' => '2020-03-01'],
            ['started_at' => '2016-10-01', 'ended_at' => null],
        ))
        ->create();

    $serialized = getSerializedCvResource($cv);

    expect($serialized)->toHaveKey('experiences');
    expect($serialized['experiences'])->toHaveCount(4);
    expect($serialized['experiences'][0])->toHaveKey('started_at', '2016-10')->toHaveKey('ended_at', null);
    expect($serialized['experiences'][1])->toHaveKey('started_at', '2014-05')->toHaveKey('ended_at', null);
    expect($serialized['experiences'][2])->toHaveKey('started_at', '2015-02')->toHaveKey('ended_at', '2025-07');
    expect($serialized['experiences'][3])->toHaveKey('started_at', '2019-09')->toHaveKey('ended_at', '2020-03');
});
