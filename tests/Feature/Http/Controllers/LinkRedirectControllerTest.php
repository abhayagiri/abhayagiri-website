<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkRedirectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRedirect()
    {
        $response = $this->get('/somewhere/unknown');
        $response->assertNotFound();

        factory(Redirect::class)->create([
            'from' => 'somewhere/unknown',
            'to' => json_encode(['type' => 'Basic', 'url' => 'about']),
        ]);

        $response = $this->get('/somewhere/unknown');
        $response->assertRedirect(url('about'));
    }
}
