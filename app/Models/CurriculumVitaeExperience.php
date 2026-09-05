<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use RuntimeException;

class CurriculumVitaeExperience extends Pivot
{
    protected static function booted(): void
    {
        static::creating(function (self $pivot): void {
            $cvPersonId = CurriculumVitae::whereKey($pivot->curriculum_vitae_id)->value('person_id');
            $experiencePersonId = Experience::whereKey($pivot->experience_id)->value('person_id');

            if ($cvPersonId !== $experiencePersonId) {
                throw new RuntimeException('A CV cannot hold an experience belonging to another person.');
            }
        });
    }
}
