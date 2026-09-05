<?php

use App\Models\CurriculumVitae;
use App\Models\Experience;
use App\Models\Person;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('homepage is accessible to guests', function () {
    $this->actingAsGuest()->get(route('home'))->assertOk();
});

test('homepage is accessible to authenticated users', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('home'))->assertOk();
});

describe('Inertia prop defaultCv', function () {
    test('is null when there are no CVs', function () {
        $response = $this->get(route('home'));

        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Home')->where('defaultCv', null));
    });

    test('is null when no CV is set as default', function () {
        CurriculumVitae::factory()->asDefault(false)->create();

        $response = $this->get(route('home'));

        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Home')->where('defaultCv', null));
    });

    test('is present when a CV exists, is set as default and published', function () {
        CurriculumVitae::factory()->asDefault()->published()->create();

        $response = $this->get(route('home'));

        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Home')->whereNotNull('defaultCv'));
    });

    test('exposes strictly its public properties', function () {
        $cv = CurriculumVitae::factory()
            ->asDefault()
            ->published()
            ->recycle(Person::factory()->create())
            ->has(Experience::factory()->count(3))
            ->create();

        $response = $this->get(route('home'));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('defaultCv', fn (AssertableInertia $page) => $page
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
});
