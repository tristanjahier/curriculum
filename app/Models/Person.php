<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Date;

/**
 * @property-read string $full_name
 * @property-read ?CarbonImmutable $born_at
 * @property-read ?int $age
 */
class Person extends Model
{
    /** @use HasFactory<PersonFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * @return HasMany<CurriculumVitae, $this>
     */
    public function curriculaVitae(): HasMany
    {
        return $this->hasMany(CurriculumVitae::class);
    }

    /**
     * @return Attribute<non-falsy-string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(fn () => $this->first_name.' '.$this->last_name);
    }

    /**
     * @return Attribute<?CarbonImmutable, never>
     */
    protected function bornAt(): Attribute
    {
        // @phpstan-ignore return.type (false positive)
        return Attribute::get(function ($value, array $attributes) {
            if (blank($attributes['birth_datetime'] ?? null) || blank($attributes['birth_timezone'] ?? null)) {
                return null;
            }

            return Date::make($attributes['birth_datetime'], $attributes['birth_timezone']);
        })->withoutObjectCaching();
    }

    /**
     * @return Attribute<?int, never>
     */
    protected function age(): Attribute
    {
        // @phpstan-ignore return.type (false positive)
        return Attribute::get(
            fn () => ($bornAt = $this->born_at) !== null ? (int) $bornAt->diffInYears() : null
        );
    }
}
