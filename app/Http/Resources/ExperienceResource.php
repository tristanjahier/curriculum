<?php

namespace App\Http\Resources;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin Experience
 */
class ExperienceResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => isset($this->description)
                ? Str::of($this->description)->markdown()->sanitizeHtml()->toString()
                : null,
            'company' => $this->company,
            'location' => $this->location,
            'started_at' => $this->started_at->format('Y-m'),
            'ended_at' => $this->ended_at?->format('Y-m'),
        ];
    }
}
