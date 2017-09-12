<?php

namespace App\Models\Traits;

/**
 * Note: thist trait also requires LocalDateTimeTrait.
 */
trait LocalPostedAtTrait
{
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
