<?php

use App\Models\CurriculumVitae;
use Inertia\Testing\AssertableInertia;

describe('show', function () {
    test('renders a published CV with exactly its public properties', function () {
        $cv = CurriculumVitae::factory()->published()->create();

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
