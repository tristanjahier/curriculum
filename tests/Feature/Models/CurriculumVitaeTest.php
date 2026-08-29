<?php

use App\Models\CurriculumVitae;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

test('isPublished() checks that published_at is set and not in the future', function () {
    $cv = CurriculumVitae::factory()->unpublished()->make();

    expect($cv->isPublished())->toBeFalse();

    $cv = CurriculumVitae::factory()->publishedInFuture()->make();

    expect($cv->isPublished())->toBeFalse();

    $cv = CurriculumVitae::factory()->published()->make();

    expect($cv->isPublished())->toBeTrue();
});

test('publish() sets the published_at timestamp to "now"', function () {
    $cv = CurriculumVitae::factory()->unpublished()->create();

    expect($cv->isPublished())->toBeFalse();

    $publicationTime = CarbonImmutable::parse('2026-08-28 21:38:37', 'UTC');
    $this->travelTo($publicationTime);
    $cv->publish();

    $cv = $cv->fresh();
    expect($cv->published_at)->toBeInstanceOf(CarbonInterface::class);
    expect($cv->published_at->toIso8601String())->toBe($publicationTime->toIso8601String());
    expect($cv->isPublished())->toBeTrue();
});

test('unpublish() unsets the published_at timestamp', function () {
    $cv = CurriculumVitae::factory()->published()->create();

    expect($cv->isPublished())->toBeTrue();

    $cv->unpublish();

    $cv = $cv->fresh();
    expect($cv->published_at)->toBeNull();
});

test('unpublish() also removes the CV as default', function () {
    $cv = CurriculumVitae::factory()->published()->asDefault()->create();

    expect($cv->isPublished())->toBeTrue();
    expect($cv->is_default)->toBeTrue();

    $cv->unpublish();

    $cv = $cv->fresh();
    expect($cv->published_at)->toBeNull();
    expect($cv->is_default)->toBeFalse();
});

test('unpublish() clears is_default even if the instance was loaded before it became default', function () {
    $cv = CurriculumVitae::factory()->published()->asDefault(false)->create();
    $concurrent = CurriculumVitae::find($cv->getKey());
    $concurrent->setAsDefault(); // $cv becomes stale

    $cv->unpublish();

    $cv = $cv->fresh();
    expect($cv->is_default)->toBeFalse();
    expect($cv->published_at)->toBeNull();
});

test('setAsDefault() sets the CV as default', function () {
    $cv = CurriculumVitae::factory()->published()->asDefault(false)->create();

    expect($cv->is_default)->toBeFalse();

    $cv->setAsDefault();

    expect($cv->is_default)->toBeTrue();
    expect($cv->isDirty('is_default'))->toBeFalse();
    expect($cv->fresh()->is_default)->toBeTrue();
});

test('setAsDefault() removes the former default CV as default', function () {
    $defaultCv = CurriculumVitae::factory()->published()->asDefault()->create();
    $cv = CurriculumVitae::factory()->published()->asDefault(false)->create();

    expect($defaultCv->is_default)->toBeTrue();

    $cv->setAsDefault();

    $defaultCv = $defaultCv->fresh();
    expect($defaultCv->is_default)->toBeFalse();
});

test('setAsDefault() throws if the CV is unpublished', function () {
    $cv = CurriculumVitae::factory()->unpublished()->asDefault(false)->create();

    expect(fn () => $cv->setAsDefault())
        ->toThrow(RuntimeException::class, 'An unpublished CV cannot be set as the default.');
});

test('setAsDefault() sets the CV as default even if the instance in-memory state says it already is', function () {
    $cv = CurriculumVitae::factory()->published()->asDefault(true)->create();
    $otherCv = CurriculumVitae::factory()->published()->asDefault(false)->create();

    $otherCv->setAsDefault(); // $cv becomes stale
    $cv->setAsDefault();

    $realDefault = CurriculumVitae::findDefault();
    expect($realDefault)->not->toBeNull();
    expect($cv->is($realDefault))->toBeTrue();
});

test('removeAsDefault() removes the CV as default', function () {
    $cv = CurriculumVitae::factory()->published()->asDefault()->create();

    expect($cv->is_default)->toBeTrue();

    $cv->removeAsDefault();

    $cv = $cv->fresh();
    expect($cv->is_default)->toBeFalse();
});

test('removeAsDefault() clears is_default even if the instance was loaded before it became default', function () {
    $cv = CurriculumVitae::factory()->published()->asDefault(false)->create();
    $concurrent = CurriculumVitae::find($cv->getKey());
    $concurrent->setAsDefault(); // $cv becomes stale

    $cv->removeAsDefault();

    expect($cv->fresh()->is_default)->toBeFalse();
});

test('findDefault() returns the default CV or null if none', function () {
    $cv1 = CurriculumVitae::factory()->published()->asDefault(false)->create();
    $cv2 = CurriculumVitae::factory()->published()->asDefault()->create();
    $cv3 = CurriculumVitae::factory()->unpublished()->asDefault(false)->create();

    $result = CurriculumVitae::findDefault();

    expect($result->is($cv2))->toBeTrue();

    $result->removeAsDefault();

    expect(CurriculumVitae::findDefault())->toBeNull();
});
