<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testChunkHash()
    {
        $this->seed();
        $response = $this->get('/');
        $response
            ->assertOk()
            ->assertHeader('app-chunk-hash');
    }
}
