<?php

namespace App\Models;

use Database\Factories\ExperienceFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use RuntimeException;

class Experience extends Model
{
    /** @use HasFactory<ExperienceFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'ended_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (self $experience): void {
            if ($experience->isDirty('person_id') && $experience->curriculaVitae()->exists()) {
                throw new RuntimeException('An experience held by a CV cannot be moved to another person.');
            }
        });
    }

    /**
     * @return BelongsTo<Person, $this>
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * @return BelongsToMany<CurriculumVitae, $this, CurriculumVitaeExperience>
     */
    public function curriculaVitae(): BelongsToMany
    {
        return $this->belongsToMany(CurriculumVitae::class, CurriculumVitaeExperience::class)->withTimestamps();
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function isOngoing(): Attribute
    {
        return Attribute::get(fn () => $this->ended_at === null);
    }
}
