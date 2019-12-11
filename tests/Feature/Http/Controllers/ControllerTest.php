<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class ControllerTest extends TestCase
{
    public function testChunkHash()
    {
        $response = $this->get('/');
        $response
            ->assertOk()
            ->assertHeader('app-chunk-hash');
    }
}
