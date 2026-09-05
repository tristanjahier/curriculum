<?php

use App\Models\CurriculumVitae;
use App\Models\Experience;
use App\Models\Person;
use Inertia\Testing\AssertableInertia;

describe('show', function () {
    test('renders a published CV with exactly its public properties', function () {
        $cv = CurriculumVitae::factory()
            ->published()
            ->recycle(Person::factory()->create())
            ->has(Experience::factory()->count(3))
            ->create();

        $response = $this->get(route('cv.show', $cv));

        $response->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('CurriculumVitae/Show')
                ->has('cv', fn (AssertableInertia $page) => $page
                    ->where('slug', $cv->slug)
                    ->where('headline', $cv->headline)
                    ->where('summary', $cv->summary)
                    ->has('person', fn (AssertableInertia $page) => $page
                        ->where('first_name', $cv->person->first_name)
                        ->where('last_name', $cv->person->last_name)
                        ->where('full_name', $cv->person->full_name)
                        ->when($cv->show_age,
                            fn () => $page->where('age', $cv->person->age),
                            fn () => $page->missing('age'))
                        ->when($cv->show_residence,
                            fn () => $page->where('residence', $cv->person->residence),
                            fn () => $page->missing('residence'))
                        ->when($cv->show_phone,
                            fn () => $page->where('phone', $cv->person->phone),
                            fn () => $page->missing('phone'))
                        ->when($cv->show_email,
                            fn () => $page->where('email', $cv->person->email),
                            fn () => $page->missing('email'))
                    )
                    ->has('experiences', $cv->experiences->count(), fn (AssertableInertia $page) => $page
                        ->has('id')
                        ->has('title')
                        ->has('description')
                        ->has('company')
                        ->has('location')
                        ->has('started_at')
                        ->has('ended_at')
                    )
                )
            );
    })->repeat(10);

    test('responds 404 Not Found for an unpublished CV', function () {
        $cv = CurriculumVitae::factory()->unpublished()->create();

        $this->get(route('cv.show', $cv))->assertNotFound();
    });

    test('responds 404 Not Found for a not yet published CV', function () {
        $cv = CurriculumVitae::factory()->publishedInFuture()->create();

        $this->get(route('cv.show', $cv))->assertNotFound();
    });

    test('responds 404 Not Found for a non-existing slug', function () {
        $this->get(route('cv.show', 'totocaca'))->assertNotFound();
    });
});
