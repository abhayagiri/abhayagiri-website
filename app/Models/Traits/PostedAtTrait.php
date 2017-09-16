<?php

namespace App\Models\Traits;

use Carbon\Carbon;

/**
 * This trait is for models with the posted_at and draft attributes.
 *
 * This trait also requires LocalDateTimeTrait.
 */
trait PostedAtTrait
{
    public function scopePublic($query)
    {
        return $query
            ->where($this->getTable() . '.draft', '=', false)
            ->where($this->getTable() . '.posted_at', '<', Carbon::now());
    }

    public function scopeLatest($query)
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
