<?php

namespace App\Models\Traits;

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
     * @param Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query
            ->where($this->getTable() . '.draft', '=', false)
            ->where($this->getTable() . '.posted_at', '<', now());
    }

    /**
     * Return a scope orderded by posted_at.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopePostOrdered($query)
    {
        return $query
            ->orderBy($this->getTable() . '.posted_at', 'desc');
    }

    /**
     * Returns the local time from posted_at.
     *
     * @return string or null
     */
    public function getLocalPostedAtAttribute()
    {
        return $this->getLocalDateTimeFrom('posted_at');
    }

    /**
     * Return whether or not this is not-draft and posted_at is not in the
     * future.
     *
     * @return bool
     */
    public function isPublic()
    {
        return !$this->draft && $this->posted_at &&
            ($this->posted_at < now());
    }

    /**
     * Sets posted_at from local time.
     *
     * @param Carbon|string $value
     * @return string
     */
    public function setLocalPostedAtAttribute($value)
    {
        return $this->setLocalDateTimeTo('posted_at', $value);
    }
}
