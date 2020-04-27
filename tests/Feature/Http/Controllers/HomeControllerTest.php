<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testIndex()
    {
        $response = $this->get(route('home.index'));
        $response->assertOk()
                 ->assertSee('View Full Calendar');

        $response = $this->get(route('th.home.index'));
        $response->assertOk()
                 ->assertSee('ดูปฏิทินแบบเต็ม');
    }
}
