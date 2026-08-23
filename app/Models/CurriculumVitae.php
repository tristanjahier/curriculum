<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class CurriculumVitae extends Model
{
    protected $table = 'curricula_vitae';

    protected $guarded = ['is_default'];

    protected function casts(): array
    {
        return [
            'show_photo' => 'boolean',
            'show_age' => 'boolean',
            'show_residence' => 'boolean',
            'show_phone' => 'boolean',
            'show_email' => 'boolean',
            'is_default' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Person, $this>
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function default(Builder $query): void
    {
        $query->where('is_default', true);
    }

    public function setAsDefault(): void
    {
        DB::transaction(function () {
            // Default CV uniqueness is constrained by the database.
            $currentDefaultCv = static::query()->default()->lockForUpdate()->first();

            if (isset($currentDefaultCv)) {
                if ($currentDefaultCv->is($this)) {
                    return;
                }

                $currentDefaultCv->removeAsDefault();
            }

            $this->forceFill(['is_default' => true])->save();
        });
    }

    public function removeAsDefault(): void
    {
        $this->forceFill(['is_default' => false])->save();
    }

    public static function findDefault(): ?static
    {
        return static::query()->default()->first();
    }
}
