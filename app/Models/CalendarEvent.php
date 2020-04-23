<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Spatie\GoogleCalendar\Event;

class CalendarEvent extends Event
{
    /**
     * Return the date of this event.
     *
     * @return \Carbon\Carbon|null
     */
    public function getDate(): ?Carbon
    {
        if ($this->startDate) {
            // Assume startDate is set to UTC, shift to website timezone.
            return $this->startDate->copy()
                        ->shiftTimezone(static::getLocalTimeZone())
                        ->startOfDay();
        } elseif ($this->startDateTime) {
            return $this->startDateTime->copy()
                        ->setTimezone(static::getLocalTimeZone())
                        ->startOfDay();
        } else {
            return null;
        }
    }

    /**
     * Return the end time of this event.
     *
     * @return \Carbon\Carbon|null
     */
    public function getEndTime(): ?Carbon
    {
        $value = $this->endDateTime;
        if ($value) {
            return $value->copy()
                         ->setTimezone(static::getLocalTimeZone());
        } else {
            return null;
        }
    }

    /**
     * Return the path to the calendar day of this event.
     *
     * @param  string  $lng
     *
     * @return string|null
     */
    public function getPath(?string $lng = null): ?string
    {
        $date = $this->getDate();
        if (!$date) {
            return null;
        }
        if ($lng === null) {
            $lng = App::getLocale();
        }
        $routePrefix = $lng === 'th' ? 'th.' : '';
        return route(
            $routePrefix . 'calendar.day',
            [$date->year, $date->month, $date->day],
            false
        );
    }

    /**
     * Return the start time of this event.
     *
     * @return \Carbon\Carbon|null
     */
    public function getStartTime(): ?Carbon
    {
        $value = $this->startDateTime;
        if ($value) {
            return $value->copy()
                         ->setTimezone(static::getLocalTimeZone());
        } else {
            return null;
        }
    }

    /**
     * Return the time of this event.
     *
     * @return \Carbon\Carbon|null
     */
    public function getTime(): ?Carbon
    {
        $value = $this->getStartTime();
        if (!$value) {
            $value = $this->getDate();
        }
        return $value;
    }

    /**
    * Return a compact time range of this event, or null if an all day event.
    *
    * @return string|null
    */
    public function getTimeRange(): ?string
    {
        $start = $this->getStartTime();
        $end = $this->getEndTime();
        if ($start && $end) {
            $startFormat = 'g' . (($start->minute == 0) ? '' : ':i') .
                ((intval($start->hour / 12) == intval($end->hour / 12)) ? '' : 'a');
            $endFormat = 'g' . (($end->minute == 0) ? '' : ':i') . 'a';
            return $start->format($startFormat) . '-' . $end->format($endFormat);
        } else {
            return null;
        }
    }

    /**
     * Return the local timezone of the website.
     *
     * @return string
     */
    protected static function getLocalTimeZone(): string
    {
        return Config::get('abhayagiri.human_timezone');
    }
}
