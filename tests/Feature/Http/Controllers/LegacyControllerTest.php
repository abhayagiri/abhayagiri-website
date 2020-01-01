<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testCalendar()
    {
        $response = $this->get('/calendar');
        $response
            ->assertOk()
            ->assertSee('Calendar');

        $response = $this->get('/th/calendar');
        $response
            ->assertOk()
            ->assertSee('ปฏิทิน');
    }

    public function testHome()
    {
        $response = $this->get('/');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri')
            ->assertSee('News');

        $response = $this->get('/home');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri')
            ->assertSee('News');

        $response = $this->get('/th');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri')
            ->assertSee('ข่าว');

        $response = $this->get('/th/home');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri')
            ->assertSee('ข่าว');
    }

    public function testReflections()
    {
        $response = $this->get('/reflections');
        $response
            ->assertOk()
            ->assertSee('News');

        $response = $this->get('/th/reflections');
        $response
            ->assertOk()
            ->assertSee('แง่ธรรม');
    }
}
