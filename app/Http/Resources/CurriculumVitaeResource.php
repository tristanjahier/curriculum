<?php

namespace App\Http\Resources;

use App\Models\CurriculumVitae;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CurriculumVitae
 */
class CurriculumVitaeResource extends JsonResource
{
    /**
     * Disable the "data" wrap because this resource is consumed as an Inertia prop, not by an API.
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'headline' => $this->headline,
            'summary' => $this->summary,
            'person' => [
                'first_name' => $this->person->first_name,
                'last_name' => $this->person->last_name,
                'full_name' => $this->person->full_name,
                'age' => $this->when($this->show_age, fn () => $this->person->age),
                'residence' => $this->when($this->show_residence, fn () => $this->person->residence),
                'phone' => $this->when($this->show_phone, fn () => $this->person->phone),
                'email' => $this->when($this->show_email, fn () => $this->person->email),
            ],
            'experiences' => ExperienceResource::collection(
                $this->experiences->sortBy([
                    ['is_ongoing', 'desc'],
                    ['ended_at', 'desc'],
                    ['started_at', 'desc'],
                ])->values()
            ),
        ];
    }
}
