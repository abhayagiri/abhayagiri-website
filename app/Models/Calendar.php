<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;
use Spatie\GoogleCalendar\GoogleCalendar;

class Calendar
{
    /**
     * The maximum number of years from today to lookup in the calendar.
     *
     * @var int
     */
    const MAX_YEARS = 1;

    /**
     * The minimum year to lookup in the calendar.
     *
     * @var int
     */
    const MIN_YEAR = 2013;

    /**
     * The end date.
     *
     * @var \Carbon\Carbon
     */
    protected $end;

    /**
     * The start date.
     *
     * @var \Carbon\Carbon
     */
    protected $start;

    /**
     * Construct a new calendar.
     *
     * @param  \Carbon\Carbon  $start
     * @param  \Carbon\Carbon  $end
     */
    public function __construct(Carbon $start, Carbon $end)
    {
        $this->start = $start->copy()->setTimezone(static::getLocalTimeZone());
        $this->end = $end->copy()->setTimezone(static::getLocalTimeZone());
    }

    /**
     * Convinience getter.
     *
     * @return \Carbon\Carbon|null
     */
    public function __get(string $key): ?Carbon
    {
        $func = array_get([
            'end' => 'getEnd',
            'endOfCalendar' => 'getEndOfCalendar',
            'endOfMonth' => 'getEndOfMonth',
            'start' => 'getStart',
            'startOfCalendar' => 'getStartOfCalendar',
            'startOfMonth' => 'getStartOfMonth',
        ], $key);
        return $func ? $this->{$func}() : null;
    }

    /**
     * Return the events between start and end.
     *
     * @param  \Illuminate\Support\Collection  $events
     *
     * @return \Illuminate\Support\Collection
     */
    public function filterEvents(Collection $events): Collection
    {
        $start = $this->getStart();
        $end = $this->getEnd();
        return $events->filter(function ($event) use ($start, $end) {
            $date = $event->getTime();
            return $start <= $date && $date < $end;
        });
    }

    /**
     * Return the end date.
     *
     * @return \Carbon\Carbon
     */
    public function getEnd(): Carbon
    {
        return $this->end->copy();
    }

    /**
     * Return the end date of a display calendar "month."
     *
     * @return \Carbon\Carbon
     */
    public function getEndOfCalendar(): Carbon
    {
        $endOfMonth = $this->getEndOfMonth();
        return $endOfMonth->addDays(6 - $endOfMonth->dayOfWeek);
    }

    /**
     * Return the end of the month date.
     *
     * @return \Carbon\Carbon
     */
    public function getEndOfMonth(): Carbon
    {
        return $this->getEnd()->endOfMonth();
    }

    /**
     * Return a collection of CalendarEvents via Google Calendar API.
     *
     * This method will automatically cache events.
     *
     * If $force is true, then the cache will not used for retrieval.
     *
     * @param  bool  $force
     *
     * @return void
     */
    public function getEvents($force = false): Collection
    {
        $start = $this->getStart()->utc();
        $end = $this->getEnd()->utc();
        $key = 'calendar-events-' . $start->format('YmdHis') . '-' .
               $end->format('YmdHis');
        if ($force || !($events = Cache::get($key))) {
            $events = $this->getRawEvents($start, $end);
            //
            // $googleCalendar = $this->getGoogleCalendar();
            // $calendarId = $googleCalendar->getCalendarId();
            // $events = collect(
            //     $googleCalendar->listEvents($start, $end)
            // )->map(function ($googleEvent) use ($calendarId) {
            //     return CalendarEvent::createFromGoogleCalendarEvent(
            //         $googleEvent,
            //         $calendarId
            //     );
            // });
            if ($events !== null) {
                Cache::put($key, $events, $this->getCacheTimeout());
            } else {
                $events = collect();
            }
        }
        return $events;
    }

    /**
     * Return the path to previous calendar month.
     *
     * @param  string  $lng
     *
     * @return string|null
     */
    public function getLastMonthPath(?string $lng = null): string
    {
        $date = $this->getStartOfMonth()->subMonth();
        return $this->getMonthPath($date->year, $date->month, $lng);
    }

    /**
     * Return the path to next calendar month.
     *
     * @param  string  $lng
     *
     * @return string|null
     */
    public function getNextMonthPath(?string $lng = null): string
    {
        $date = $this->getStartOfMonth()->addMonth();
        return $this->getMonthPath($date->year, $date->month, $lng);
    }

    /**
     * Return the start date.
     *
     * @return \Carbon\Carbon
     */
    public function getStart(): Carbon
    {
        return $this->start->copy();
    }

    /**
     * Return the start date of the display calendar "month."
     *
     * @return \Carbon\Carbon
     */
    public function getStartOfCalendar(): Carbon
    {
        $startOfMonth = $this->getStartOfMonth();
        return $startOfMonth->addDays(0 - $startOfMonth->dayOfWeek);
    }

    /**
     * Return the start of the month date.
     *
     * @return \Carbon\Carbon
     */
    public function getStartOfMonth(): Carbon
    {
        return $this->getStart()->startOfMonth();
    }

    /**
     * Return the path to this calendar month.
     *
     * @param  string  $lng
     *
     * @return string|null
     */
    public function getThisMonthPath(?string $lng = null): string
    {
        $date = $this->getStartOfMonth();
        return $this->getMonthPath($date->year, $date->month, $lng);
    }

    /**
     * Return the events between start and end grouped by day.
     *
     * @param  \Illuminate\Support\Collection  $events
     *
     * @return \Illuminate\Support\Collection
     */
    public function groupByDay(Collection $events): Collection
    {
        $events = $events->mapToGroups(function ($event) {
            return [ $event->getDate()->format('Y-m-d') => $event ];
        });
        return collect(LazyCollection::make(function () {
            $date = $this->getStart()->startOfDay();
            $end = $this->getEnd();
            while ($date < $end) {
                yield $date;
                $date = $date->copy()->addDay();
            }
        })->map(function ($date) use ($events) {
            return [ $date, $events->get($date->format('Y-m-d'), collect()) ];
        }));
    }

    /**
     * Return whether or not $date is within $this->start and $this->end.
     *
     * @return bool
     */
    public function inRange(Carbon $date): bool
    {
        return $this->getStart() <= $date && $date < $this->getEnd();
    }

    /**
     * Return the cache timeout.
     *
     * @return int|null
     */
    protected function getCacheTimeout(): ?int
    {
        return Config::get('abhayagiri.calendar.cache_timeout');
    }

    /**
     * Return the path to the specified calendar month.
     *
     * @param  int  $year
     * @param  int  $month
     * @param  string  $lng
     *
     * @return string|null
     */
    protected function getMonthPath(
        int $year,
        int $month,
        ?string $lng = null
    ): string {
        if ($lng === null) {
            $lng = App::getLocale();
        }
        $routePrefix = $lng === 'th' ? 'th.' : '';
        return route($routePrefix . 'calendar.month', [$year, $month], false);
    }

    /**
     * Return the events from CalendarEvent::get().
     *
     * @return \Illuminate\Support\Collection|null
     */
    protected function getRawEvents(Carbon $start, Carbon $end): ?Collection
    {
        try {
            return CalendarEvent::get($start, $end, [
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ]);
        } catch (Exception $e) {
            Log::error('Could not get calendar events: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a Calendar for a particular displayed calendar month.
     *
     * @param  int  $year
     * @param  int  $month
     *
     * @return self
     */
    public static function createFromCalendarMonth(int $year, int $month): self
    {
        $calendar = static::createFromDay($year, $month, 1);
        return new static(
            $calendar->getStartOfCalendar(),
            $calendar->getEndOfCalendar()
        );
    }

    /**
     * Create a Calendar for a particular day.
     *
     * @param  int  $year
     * @param  int  $month
     * @param  int  $day
     *
     * @return self
     */
    public static function createFromDay(int $year, int $month, int $day): self
    {
        $maxYear = static::getToday()->year + static::MAX_YEARS;
        $year = min(max($year, static::MIN_YEAR), $maxYear);
        $month = min(max($month, 1), 12);
        $day = min(max($day, 1), 31);
        $start = Carbon::parse("$year-$month-$day")
                       ->shiftTimezone(static::getLocalTimeZone());
        $end = $start->copy()->endOfDay();
        return new static($start, $end);
    }

    /**
     * Create a Calendar for a particular month.
     *
     * @param  int  $year
     * @param  int  $month
     *
     * @return self
     */
    public static function createFromMonth(int $year, int $month): self
    {
        $calendar = static::createFromDay($year, $month, 1);
        return new static(
            $calendar->getStartOfMonth(),
            $calendar->getEndOfMonth()
        );
    }

    /**
     * Create a Calendar for the upcoming week.
     *
     * @return self
     */
    public static function createFromUpcomingWeek(): self
    {
        $start = static::getToday();
        $end = $start->copy()->addWeek()->endOfDay();
        return new static($start, $end);
    }

    /**
     * Return today in the timezone of the website.
     *
     * @return \Carbon\Carbon
     */
    public static function getToday(): Carbon
    {
        return Carbon::now()->setTimezone(static::getLocalTimeZone())
                            ->startOfDay();
    }

    /**
     * Return whether or not $date is today.
     *
     * @return bool
     */
    public static function isToday(Carbon $date): bool
    {
        $date = $date->copy()->setTimezone(static::getLocalTimeZone());
        return $date->format('Ymd') === static::getToday()->format('Ymd');
    }

    /**
     * Precache several common queries.
     *
     * @see \App\Console\Kernel@schedule()
     *
     * @return void
     */
    public static function preCacheEvents(): void
    {
        // Front page calendar query.
        static::createFromUpcomingWeek()->getEvents(true);
        // Current month calendar query + 2 months.
        $date = static::getToday()->startOfMonth();
        for ($i = 0; $i < 3; $i++) {
            static::createFromCalendarMonth($date->year, $date->month)
                ->getEvents(true);
            $date->addMonth();
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
