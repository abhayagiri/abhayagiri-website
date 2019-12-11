<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class LegacyControllerTest extends TestCase
{
    public function testBooks()
    {
        $response = $this->get('/books');
        $response
            ->assertOk()
            ->assertSee('Books');

        $response = $this->get('/th/books');
        $response
            ->assertOk()
            ->assertSee('หนังสือ');

        $response = $this->post('/books/cart/608');
        $response
            ->assertOk();

        $response = $this->get('/books/request');
        $response
            ->assertOk()
            ->assertSee('Shipping Information');

        $response = $this->get('/th/books/request');
        $response
            ->assertOk()
            ->assertSee('ข้อมูลการจัดส่ง');
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

    public function testNews()
    {
        $response = $this->get('/news');
        $response
            ->assertOk()
            ->assertSee('News');

        $response = $this->get('/th/news');
        $response
            ->assertOk()
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
