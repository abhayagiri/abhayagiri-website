<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class LegacyControllerTest extends TestCase
{
    public function testBooks()
    {
        $response = $this->get('/books');
        $response->assertOk();
        $response->assertSeeText('Books');

        $response = $this->get('/th/books');
        $response->assertOk();
        $response->assertSeeText('หนังสือ');
    }

    public function testCalendar()
    {
        $response = $this->get('/calendar');
        $response->assertOk();
        $response->assertSeeText('Calendar');

        $response = $this->get('/th/calendar');
        $response->assertOk();
        $response->assertSeeText('ปฏิทิน');
    }

    public function testHome()
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSeeText('Abhayagiri');
        $response->assertSeeText('News');

        $response = $this->get('/home');
        $response->assertOk();
        $response->assertSeeText('Abhayagiri');
        $response->assertSeeText('News');

        $response = $this->get('/th');
        $response->assertOk();
        $response->assertSeeText('Abhayagiri');
        $response->assertSeeText('ข่าว');

        $response = $this->get('/th/home');
        $response->assertOk();
        $response->assertSeeText('Abhayagiri');
        $response->assertSeeText('ข่าว');
    }

    public function testNews()
    {
        $response = $this->get('/news');
        $response->assertOk();
        $response->assertSeeText('News');

        $response = $this->get('/th/news');
        $response->assertOk();
        $response->assertSeeText('ข่าว');
    }

    public function testReflections()
    {
        $response = $this->get('/reflections');
        $response->assertOk();
        $response->assertSeeText('News');

        $response = $this->get('/th/reflections');
        $response->assertOk();
        $response->assertSeeText('แง่ธรรม');
    }
}
