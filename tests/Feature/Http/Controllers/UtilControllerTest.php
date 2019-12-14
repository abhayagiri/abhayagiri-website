<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class UtilControllerTest extends TestCase
{
    public function testError()
    {
        $response = $this->get(route('error'));
        $response
            ->assertStatus(500)
            ->assertSee('500')
            ->assertSee('Server Error');

        $response = $this->get(route('th.error'));
        $response
            ->assertStatus(500)
            ->assertSee('500')
            ->assertDontSee('Server Error');

        $response = $this->get(route('error', [ 'code' => 401 ]));
        $response
            ->assertUnauthorized()
            ->assertSee('401');
    }

    public function testVersion()
    {
        $response = $this->get(route('version'));
        $response
            ->assertOk()
            ->assertJson(['basePath' => base_path()]);
    }
}
