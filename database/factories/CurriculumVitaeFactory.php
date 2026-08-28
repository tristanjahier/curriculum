<?php

namespace Database\Factories;

use App\Models\CurriculumVitae;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CurriculumVitae>
 */
class CurriculumVitaeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->jobTitle(),
            'slug' => fn (array $attributes) => Str::slug($attributes['name']),
            'person_id' => Person::factory(),
            'headline' => fake()->jobTitle(),
            'summary' => fake()->paragraph(nbSentences: 5),
            'show_photo' => fake()->boolean(70),
            'show_age' => fake()->boolean(70),
            'show_residence' => fake()->boolean(70),
            'show_phone' => fake()->boolean(40),
            'show_email' => fake()->boolean(50),
            'is_default' => false,
            'published_at' => fake()->optional(weight: 0.7, default: null)->dateTime(),
        ];
    }

    public function asDefault(bool $asDefault = true): static
    {
        return $this->state(fn () => ['is_default' => $asDefault]);
    }

    public function published(): static
    {
        return $this->state(fn () => ['published_at' => fake()->dateTimeBetween('-5 years', '-2 minutes')]);
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['published_at' => null]);
    }

    public function publishedInFuture(): static
    {
        return $this->state(fn () => ['published_at' => fake()->dateTimeBetween('+1 day', '+1 year')]);
    }
}
