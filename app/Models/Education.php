<?php

namespace App\Models;

use Database\Factories\EducationFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use RuntimeException;

class Education extends Model
{
    /** @use HasFactory<EducationFactory> */
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
        static::updating(function (self $education): void {
            if ($education->isDirty('person_id') && $education->curriculaVitae()->exists()) {
                throw new RuntimeException('An education entry held by a CV cannot be moved to another person.');
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
     * @return BelongsToMany<CurriculumVitae, $this, CurriculumVitaeEducation>
     */
    public function curriculaVitae(): BelongsToMany
    {
        return $this->belongsToMany(CurriculumVitae::class, CurriculumVitaeEducation::class)->withTimestamps();
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function isOngoing(): Attribute
    {
        return Attribute::get(fn () => $this->ended_at === null);
    }
}
