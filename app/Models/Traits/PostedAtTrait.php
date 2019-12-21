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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query
            ->where($this->getTable() . '.draft', '=', false)
            ->where($this->getTable() . '.posted_at', '<', now());
    }

    /**
     * Return a scope orderded by posted_at.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePostOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy($this->getTable() . '.posted_at', 'desc');
    }

    /**
     * Returns the local time from posted_at.
     *
     * @return null|string
     */
    public function getLocalPostedAtAttribute(): ?string
    {
        return $this->getLocalDateTimeFrom('posted_at');
    }

    /**
     * Return whether or not this is not-draft and posted_at is not in the
     * future.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return !$this->draft && $this->posted_at &&
            ($this->posted_at < now());
    }

    /**
     * Sets posted_at from local time.
     *
     * @param  Carbon|string  $value
     * @return string
     */
    public function setLocalPostedAtAttribute($value): string
    {
        return $this->setLocalDateTimeTo('posted_at', $value);
    }

    /**
     * Return whether or not this was modified (at least 2 days) after the
     * original posted_at date.
     *
     * @return bool
     */
    public function wasUpdatedAfterPosting(): bool
    {
        if ($this->updated_at && $this->posted_at &&
            ($this->posted_at->diffInDays($this->updated_at) > 2)) {
            // TODO this is a hardcoded date due to an import that occured on
            // this date.
            $minDate = new Carbon('2017-09-15T06:19:58.000000Z');
            return $this->posted_at->greaterThan($minDate);
        } else {
            return false;
        }
    }
}
