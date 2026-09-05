<?php

namespace Database\Factories;

use App\Models\Experience;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraph(nbSentences: 4),
            'company' => fake()->company(),
            'location' => fake()->city(),
            'started_at' => fake()->dateTime(),
            'ended_at' => fn (array $attributes) => fake()
                ->optional(weight: 0.8)
                ->dateTimeBetween($attributes['started_at'], 'now'),
            'person_id' => Person::factory(),
        ];
    }
}
