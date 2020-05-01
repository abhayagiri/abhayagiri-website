<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get(route('contact.index'));
        $response->assertOk()
                 ->assertSee('Contact');

        $response = $this->get(route('th.contact.index'));
        $response->assertOk()
                 ->assertSee('ติดต่อเรา');
    }
}
