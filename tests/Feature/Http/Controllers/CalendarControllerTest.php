<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class CalendarControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('calendar.index'));
        $response->assertOk()
                 ->assertSee('Calendar');

        $response = $this->get(route('th.calendar.index'));
        $response->assertOk()
                 ->assertSee('ปฏิทิน');
    }

    public function testMonth()
    {
        $response = $this->get(route('calendar.month', [2020, 4]));
        $response->assertOk()
                 ->assertSee('Calendar');

        $response = $this->get(route('th.calendar.month', [2020, 4]));
        $response->assertOk()
                 ->assertSee('ปฏิทิน');
    }

    public function testDay()
    {
        $response = $this->get(route('calendar.day', [2020, 4, 20]));
        $response->assertOk()
                 ->assertSee('Calendar');

        $response = $this->get(route('th.calendar.day', [2020, 4, 20]));
        $response->assertOk()
                 ->assertSee('ปฏิทิน');
    }
}
