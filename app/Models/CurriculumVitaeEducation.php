<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use RuntimeException;

class CurriculumVitaeEducation extends Pivot
{
    protected static function booted(): void
    {
        static::creating(function (self $pivot): void {
            $cvPersonId = CurriculumVitae::whereKey($pivot->curriculum_vitae_id)->withoutEagerLoads()->value('person_id');
            $educationPersonId = Education::whereKey($pivot->education_id)->withoutEagerLoads()->value('person_id');

            if ($cvPersonId !== $educationPersonId) {
                throw new RuntimeException('A CV cannot hold an education entry belonging to another person.');
            }
        });
    }
}
