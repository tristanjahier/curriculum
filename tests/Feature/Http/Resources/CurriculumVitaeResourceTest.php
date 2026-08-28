<?php

use App\Http\Resources\CurriculumVitaeResource;
use App\Models\CurriculumVitae;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

function getSerializedCvResource(CurriculumVitae $cv): array
{
    $resource = new CurriculumVitaeResource($cv);
    $request = Request::create('/'); // the HTTP request does not matter for this resource.

    return $resource->resolve($request);
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
