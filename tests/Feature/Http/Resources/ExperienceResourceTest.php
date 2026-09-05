<?php

use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\Request;

function getSerializedResource(Experience $exp): array
{
    $resource = new ExperienceResource($exp);
    $request = Request::create('/'); // the HTTP request does not matter for this resource.

    return $resource->response($request)->getData(true);
}

test('exposes strictly the public properties', function () {
    $experience = Experience::factory()->make();
    $serialized = getSerializedResource($experience);

    // Assert only the presence of the description. Its rendering is tested below.
    expect($serialized)->toHaveKey('description');
    unset($serialized['description']);

    expect($serialized)->toEqualCanonicalizing([
        'id' => $experience->id,
        'title' => $experience->title,
        'company' => $experience->company,
        'location' => $experience->location,
        'started_at' => $experience->started_at->format('Y-m'),
        'ended_at' => $experience->ended_at?->format('Y-m'),
    ]);
})->repeat(10);

test('renders the description from Markdown', function () {
    $experience = Experience::factory()->make(['description' => 'Some **bold** text.']);

    expect(getSerializedResource($experience)['description'])
        ->toBe("<p>Some <strong>bold</strong> text.</p>\n");
});

test('renders a null description as null', function () {
    $experience = Experience::factory()->make(['description' => null]);

    expect(getSerializedResource($experience)['description'])->toBeNull();
});

test('sanitizes HTML in the description', function (string $description, string $forbidden) {
    $experience = Experience::factory()->make(['description' => $description]);

    expect(getSerializedResource($experience)['description'])->not->toContain($forbidden);
})->with([
    'inline event handler' => ['Hello <img src=x onerror="alert(1)">', 'onerror'],
    'javascript: link' => ["[click me](javascript:alert('xss'))", 'javascript:'],
]);
