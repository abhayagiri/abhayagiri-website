<?php

namespace Tests\Unit\Models;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Tests\TestCase;

class CalendarEventTest extends TestCase
{
    public function testGetDate()
    {
        $event = new CalendarEvent();
        $this->assertNull($event->getDate());
        $event->startDate = Carbon::parse('2020-04-22T00:00:00Z');
        $this->assertEquals(
            '2020-04-21T00:00:00-07:00',
            $event->getDate()->toW3cString()
        );
        $event = new CalendarEvent();
        $event->startDateTime = Carbon::parse('2020-04-22T11:22:00Z');
        $this->assertEquals(
            '2020-04-22T00:00:00-07:00',
            $event->getDate()->toW3cString()
        );
    }

    public function testGetEndTime()
    {
        $event = new CalendarEvent();
        $this->assertNull($event->getEndTime());
        $event->endDateTime = Carbon::parse('2020-04-22T02:00:00Z');
        $this->assertInstanceOf(Carbon::class, $event->getEndTime());
        $this->assertEquals('pst', $event->getEndTime()->tz->getAbbr());
    }

    public function testGetPath()
    {
        $event = new CalendarEvent();
        $this->assertNull($event->getPath());
        $event->startDateTime = Carbon::parse('2020-04-22T02:00:00Z');
        $this->assertEquals('/calendar/2020/4/21', $event->getPath());
        $this->assertEquals('/th/calendar/2020/4/21', $event->getPath('th'));
    }

    public function testGetStartTime()
    {
        $event = new CalendarEvent();
        $this->assertNull($event->getStartTime());
        $event->startDateTime = Carbon::parse('2020-04-22T02:00:00Z');
        $this->assertInstanceOf(Carbon::class, $event->getStartTime());
        $this->assertEquals('pst', $event->getStartTime()->tz->getAbbr());
    }

    public function testGetTime()
    {
        $event = new CalendarEvent();
        $this->assertNull($event->getTime());
        $event->startDate = Carbon::parse('2020-04-22T00:00:00Z');
        $this->assertEquals(
            '2020-04-21T00:00:00-07:00',
            $event->getTime()->toW3cString()
        );
        $event = new CalendarEvent();
        $event->startDateTime = Carbon::parse('2020-04-22T00:00:00Z');
        $this->assertEquals(
            '2020-04-21T17:00:00-07:00',
            $event->getTime()->toW3cString()
        );
    }

    public function testGetTimeRange()
    {
        $event = new CalendarEvent();
        $this->assertNull($event->getTimeRange());

        $event->startDateTime = Carbon::parse('2020-04-22T03:00:00-07:00');
        $this->assertNull($event->getTimeRange());

        $event->endDateTime = Carbon::parse('2020-04-22T05:00:00-07:00');
        $this->assertEquals('3-5am', $event->getTimeRange());

        $event->startDateTime = Carbon::parse('2020-04-22T03:15:00-07:00');
        $event->endDateTime = Carbon::parse('2020-04-22T12:30:00-07:00');
        $this->assertEquals('3:15am-12:30pm', $event->getTimeRange());
    }
}
