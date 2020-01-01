<?php

namespace App\Utilities;

trait DateTimeTrait
{
    /**
     * Convert an ISO 8601 duration to seconds. This only works with durations that
     * have no year, month, week or day designators.
     *
     * This returns null if $duration is unparseable.
     *
     * @see https://en.wikipedia.org/wiki/ISO_8601#Time_intervals
     * @see https://gist.github.com/webinista/08a03133759bb3e6acbfa1d900c0f93d
     *
     * @param  string  $duration
     *
     * @return mixed
     */
    public static function iso8601DurationToSeconds($duration)
    {
        try {
            $interval = new \DateInterval($duration);
        } catch (\Exception $e) {
            return null;
        }
        if ($interval->y || $interval->m || $interval->d) {
            return null;
        }
        return ($interval->h * 60 * 60) + ($interval->i * 60) + $interval->s;
    }
}
