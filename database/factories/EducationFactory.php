<?php

namespace Database\Factories;

use App\Models\Education;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->fakeTitle(),
            'description' => fake()->paragraph(nbSentences: 3),
            'institution' => $this->fakeInstitution(),
            'location' => fake()->city(),
            'started_at' => fake()->dateTime(),
            'ended_at' => fn (array $attributes) => fake()
                ->optional(weight: 0.8)
                ->dateTimeBetween($attributes['started_at'], 'now'),
            'person_id' => Person::factory(),
        ];
    }

    /**
     * Build a qualification title out of the naming patterns real diplomas tend to follow.
     */
    private function fakeTitle(): string
    {
        $levels = ['BSc in', 'BA in', 'MSc in', 'MA in', 'PhD in', 'Diploma in', 'Bachelor of', 'Master of'];

        $subjects = [
            'Computer Science', 'Software Engineering', 'Applied Mathematics', 'Physics',
            'Electrical Engineering', 'Mechanical Engineering', 'Civil Engineering',
            'Molecular Biology', 'Graphic Design', 'Business Administration', 'Economics',
            'Political Science', 'Psychology', 'History of Art',
        ];

        return fake()->randomElement($levels).' '.fake()->randomElement($subjects);
    }

    /**
     * Build an institution name out of the naming patterns real schools tend to follow.
     */
    private function fakeInstitution(): string
    {
        return fake()->randomElement([
            fn (): string => 'University of '.fake()->city(),
            fn (): string => fake()->lastName().' University',
            fn (): string => fake()->city().' Institute of Technology',
            fn (): string => fake()->city().' Community College',
            fn (): string => fake()->city().' State University',
        ])();
    }
}
