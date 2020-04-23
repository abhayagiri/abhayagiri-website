<?php

namespace Tests\Unit\Models;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Google_Service_Calendar_Events;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Spatie\GoogleCalendar\GoogleCalendar;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->calendar = new Calendar(
            Carbon::parse('2020-04-10T03:30:00-07:00'),
            Carbon::parse('2020-04-15T22:00:00Z')
        );
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function testFilterEvents()
    {
        $events = $this->calendar->filterEvents($this->createEvents([
            '2020-04-10T05:00:00-07:00',
            '2020-04-20T00:00:00-07:00',
        ]));
        $this->assertEquals(1, $events->count());
        $this->assertEquals(
            '2020-04-10T05:00:00-07:00',
            $events[0]->getStartTime()->toW3cString()
        );
    }

    public function testGetEnd()
    {
        $this->assertEquals(
            '2020-04-15T15:00:00-07:00',
            $this->calendar->getEnd()->toW3cString()
        );
    }

    public function testGetEndOfCalendar()
    {
        $this->assertEquals(
            '2020-05-02T23:59:59-07:00',
            $this->calendar->getEndOfCalendar()->toW3cString()
        );
    }

    public function testGetEndOfMonth()
    {
        $this->assertEquals(
            '2020-04-30T23:59:59-07:00',
            $this->calendar->getEndOfMonth()->toW3cString()
        );
    }

    public function testGetEvents()
    {
        $calendar = $this->getMockBuilder(Calendar::class)
                         ->setConstructorArgs([
                             Carbon::parse('2020-04-23T05:00:00-07:00'),
                             Carbon::parse('2020-04-28T12:00:00-07:00')
                         ])
                         ->setMethods(['getRawEvents', 'getCacheTimeout'])
                         ->getMock();
        $events = $this->createEvents([
            '2020-04-23', '2020-04-25', '2020-04-27',
        ]);
        $calendar->method('getRawEvents')
                 ->willReturn($events);
        $calendar->method('getCacheTimeout')
                 ->willReturn(100);

        Cache::shouldReceive('get')
            ->once()
            ->with('calendar-events-20200423120000-20200428190000')
            ->andReturn(null);
        Cache::shouldReceive('put')
            ->once()
            ->with(
                'calendar-events-20200423120000-20200428190000',
                Mockery::type(Collection::class),
                100
            );
        $events = $calendar->getEvents();
        $this->assertEquals(3, $events->count());
    }

    public function testGetLastMonthPath()
    {
        $this->assertEquals(
            '/calendar/2020/3',
            $this->calendar->getLastMonthPath()
        );
        $this->assertEquals(
            '/th/calendar/2020/3',
            $this->calendar->getLastMonthPath('th')
        );
    }

    public function testGetNextMonthPath()
    {
        $this->assertEquals(
            '/calendar/2020/5',
            $this->calendar->getNextMonthPath()
        );
        $this->assertEquals(
            '/th/calendar/2020/5',
            $this->calendar->getNextMonthPath('th')
        );
    }

    public function testGetStart()
    {
        $this->assertEquals(
            '2020-04-10T03:30:00-07:00',
            $this->calendar->getStart()->toW3cString()
        );
    }

    public function testGetStartOfCalendar()
    {
        $this->assertEquals(
            '2020-03-29T00:00:00-07:00',
            $this->calendar->getStartOfCalendar()->toW3cString()
        );
    }

    public function testGetStartOfMonth()
    {
        $this->assertEquals(
            '2020-04-01T00:00:00-07:00',
            $this->calendar->getStartOfMonth()->toW3cString()
        );
    }

    public function testGetThisMonthPath()
    {
        $this->assertEquals(
            '/calendar/2020/4',
            $this->calendar->getThisMonthPath()
        );
        $this->assertEquals(
            '/th/calendar/2020/4',
            $this->calendar->getThisMonthPath('th')
        );
    }

    public function testGroupByDay()
    {
        $events = $this->createEvents([
            '2020-04-11T05:00:00-07:00',
            '2020-04-11T10:00:00-07:00',
            '2020-04-20T00:00:00-07:00',
        ]);
        $this->assertEquals(collect([
            [Carbon::parse('2020-04-10T00:00:00-07:00'), collect([])],
            [Carbon::parse('2020-04-11T00:00:00-07:00'), collect([
                $events[0], $events[1],
            ])],
            [Carbon::parse('2020-04-12T00:00:00-07:00'), collect([])],
            [Carbon::parse('2020-04-13T00:00:00-07:00'), collect([])],
            [Carbon::parse('2020-04-14T00:00:00-07:00'), collect([])],
            [Carbon::parse('2020-04-15T00:00:00-07:00'), collect([])],
        ]), $this->calendar->groupByDay($events));
    }

    public function testInRange()
    {
        $this->assertFalse(
            $this->calendar->inRange(Carbon::parse('2020-04-09T00:00:00Z'))
        );
        $this->assertTrue(
            $this->calendar->inRange(Carbon::parse('2020-04-10T03:30:00-07:00'))
        );
        $this->assertTrue(
            $this->calendar->inRange(Carbon::parse('2020-04-15T21:00:00Z'))
        );
        $this->assertFalse(
            $this->calendar->inRange(Carbon::parse('2020-04-16T00:00:00-07:00'))
        );
    }

    public function testCreateFromCalendarMonth()
    {
        $calendar = Calendar::createFromCalendarMonth(2020, 4);
        $this->assertEquals(
            '2020-03-29T00:00:00-07:00',
            $calendar->getStart()->toW3cString()
        );
        $this->assertEquals(
            '2020-05-02T23:59:59-07:00',
            $calendar->getEnd()->toW3cString()
        );
    }

    public function testCreateFromDay()
    {
        $calendar = Calendar::createFromDay(2020, 4, 23);
        $this->assertEquals(
            '2020-04-23T00:00:00-07:00',
            $calendar->getStart()->toW3cString()
        );
        $this->assertEquals(
            '2020-04-23T23:59:59-07:00',
            $calendar->getEnd()->toW3cString()
        );
    }

    public function testCreateFromMonth()
    {
        $calendar = Calendar::createFromMonth(2020, 4);
        $this->assertEquals(
            '2020-04-01T00:00:00-07:00',
            $calendar->getStart()->toW3cString()
        );
        $this->assertEquals(
            '2020-04-30T23:59:59-07:00',
            $calendar->getEnd()->toW3cString()
        );
    }

    public function testCreateFromUpcomingWeek()
    {
        Carbon::setTestNow('2020-04-04T10:00:00-07:00');
        $calendar = Calendar::createFromUpcomingWeek();
        $this->assertEquals(
            '2020-04-04T00:00:00-07:00',
            $calendar->getStart()->toW3cString()
        );
        $this->assertEquals(
            '2020-04-11T23:59:59-07:00',
            $calendar->getEnd()->toW3cString()
        );
    }

    public function testIsToday()
    {
        Carbon::setTestNow('2020-04-04T10:00:00-07:00');
        $this->assertEquals(
            '2020-04-04T00:00:00-07:00',
            Calendar::getToday()->toW3cString()
        );
    }

    protected function createEvent(string $date): CalendarEvent
    {
        $event = new CalendarEvent();
        $event->startDateTime = Carbon::parse($date);
        return $event;
    }

    protected function createEvents(array $dates): Collection
    {
        return collect($dates)->map(function ($date) {
            return $this->createEvent($date);
        });
    }
}
