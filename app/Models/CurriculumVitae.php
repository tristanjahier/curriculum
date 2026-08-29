<?php

namespace App\Models;

use Database\Factories\CurriculumVitaeFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CurriculumVitae extends Model
{
    /** @use HasFactory<CurriculumVitaeFactory> */
    use HasFactory;

    protected $table = 'curricula_vitae';

    protected $guarded = ['is_default'];

    protected $with = ['person'];

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

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isNowOrPast();
    }

    public function publish(): void
    {
        $this->update(['published_at' => now()]);
    }

    public function unpublish(): void
    {
        // Set is_default to false because an unpublished CV cannot be the default.
        $this->forceFill(['published_at' => null, 'is_default' => false])->save();
    }

    public function setAsDefault(): void
    {
        $throwUnpublishedException = fn () => throw new RuntimeException('An unpublished CV cannot be set as the default.');

        if (! $this->isPublished()) {
            $throwUnpublishedException();
        }

        $updated = DB::transaction(function () use ($throwUnpublishedException) {
            // Acquire an advisory lock (transaction-scoped) to prevent a race condition with another call
            // to `setAsDefault()`. The lock key is arbitrary and was picked randomly.
            // https://www.postgresql.org/docs/current/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
            DB::statement('SELECT pg_advisory_xact_lock(?)', [65346117]);

            // $this may be stale and we need to ensure the CV is still published before running the next queries.
            // `lockForUpdate()` ensures that $fresh will not be altered by a concurrent request for the duration
            // of the transaction.
            $fresh = static::query()->lockForUpdate()->whereKey($this->getKey())->firstOrFail();

            if (! $fresh->isPublished()) {
                $throwUnpublishedException();
            }

            static::query()->default()->whereKeyNot($fresh->getKey())->update(['is_default' => false]);

            $fresh->forceFill(['is_default' => true])->save();

            return $fresh;
        });

        // Synchronize $this with its up-to-date counterpart.
        $this->is_default = $updated->is_default;
        $this->updated_at = $updated->updated_at;
        $this->syncOriginalAttributes(['is_default', 'updated_at']);
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
