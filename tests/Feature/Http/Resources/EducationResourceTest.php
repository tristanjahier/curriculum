<?php

use App\Http\Resources\EducationResource;
use App\Models\Education;
use Illuminate\Http\Request;

function getSerializedEducationResource(Education $edu): array
{
    $resource = new EducationResource($edu);
    $request = Request::create('/'); // the HTTP request does not matter for this resource.

    return $resource->response($request)->getData(true);
}

test('exposes strictly the public properties', function () {
    $education = Education::factory()->make();
    $serialized = getSerializedEducationResource($education);

    // Assert only the presence of the description. Its rendering is tested below.
    expect($serialized)->toHaveKey('description');
    unset($serialized['description']);

    expect($serialized)->toEqualCanonicalizing([
        'id' => $education->id,
        'title' => $education->title,
        'institution' => $education->institution,
        'location' => $education->location,
        'started_at' => $education->started_at->format('Y-m'),
        'ended_at' => $education->ended_at?->format('Y-m'),
    ]);
})->repeat(10);

test('renders the description from Markdown', function () {
    $education = Education::factory()->make(['description' => 'Some **bold** text.']);

    expect(getSerializedEducationResource($education)['description'])
        ->toBe("<p>Some <strong>bold</strong> text.</p>\n");
});

test('renders a null description as null', function () {
    $education = Education::factory()->make(['description' => null]);

    expect(getSerializedEducationResource($education)['description'])->toBeNull();
});

test('sanitizes HTML in the description', function (string $description, string $forbidden) {
    $education = Education::factory()->make(['description' => $description]);

    expect(getSerializedEducationResource($education)['description'])->not->toContain($forbidden);
})->with([
    'inline event handler' => ['Hello <img src=x onerror="alert(1)">', 'onerror'],
    'javascript: link' => ["[click me](javascript:alert('xss'))", 'javascript:'],
]);
