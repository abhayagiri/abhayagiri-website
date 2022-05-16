<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * This trait is for models with the posted_at and draft attributes.
 *
 * This trait also requires LocalDateTimeTrait.
 */
trait PostedAtTrait
{
    /**
     * Return a scope culled by not-draft and posted_at not in future.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query
            ->where($this->getTable() . '.draft', '=', false)
            ->where($this->getTable() . '.posted_at', '<', now());
    }

    /**
     * Return a scope orderded by posted_at descending.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePostedAtOrder(Builder $query): Builder
    {
        return $query->orderBy($this->getTable() . '.posted_at', 'desc');
    }

    /**
     * Returns the local time from posted_at.
     *
     * @return Carbon|null
     */
    public function getPostedAtAttribute($postedAt): ?Carbon
    {
        if ($postedAt === null) {
            return null;
        }

        return Carbon::parse($postedAt, 'UTC')->setTimezone('America/Los_Angeles');
    }

    /**
     * Return whether or not this is not-draft and posted_at is not in the
     * future.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        $postedAt = Carbon::parse($this->getOriginal('posted_at'));

        return !$this->draft && $postedAt && $postedAt->lt(now());
    }

    /**
     * Sets posted_at from local time.
     *
     * @param  Carbon|string  $value
     *
     * @return void
     */
    public function setPostedAtAttribute($value): void
    {
        $this->setLocalDateTimeTo('posted_at', $value);
    }

    /**
     * Return whether or not this was modified (at least 2 days) after the
     * original posted_at date.
     *
     * @param  Carbon|null  $minDate
     *
     * @return bool
     */
    public function wasUpdatedAfterPosting(?Carbon $minDate = null): bool
    {
        $postedAt = $this->getOriginal('posted_at');

        if ($postedAt !== null) {
            $postedAt = Carbon::parse($postedAt);
        }

        if ($this->updated_at && $postedAt && $postedAt->diffInDays($this->updated_at) > 2) {
            // TODO this is a hardcoded date due to an import that occured on
            // this date.
            if (!$minDate) {
                $minDate = Carbon::parse('2 months ago');
            }
            return $postedAt->greaterThan($minDate);
        }

        return false;
    }
}
