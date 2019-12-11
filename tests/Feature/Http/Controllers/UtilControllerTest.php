<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class UtilControllerTest extends TestCase
{
    public function testError()
    {
        $response = $this->get(route('error'));
        $response->assertStatus(500);
        $response->assertSeeText('500');
        $response->assertSeeText('Server Error');

        $response = $this->get(route('th.error'));
        $response->assertStatus(500);
        $response->assertSeeText('500');
        $response->assertDontSeeText('Server Error');

        $response = $this->get(route('error', [ 'code' => 401 ]));
        $response->assertUnauthorized();
        $response->assertSeeText('401');
    }

    public function testVersion()
    {
        $response = $this->get(route('version'));
        $response->assertOk();
        $response->assertJson(['basePath' => base_path()]);
    }
}
