<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Talk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TalkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testImage()
    {
        $this->withoutImageCreation();
        $talk = factory(Talk::class)->create(['id' => 123]);
        $response = $this->get(route('talks.image', [$talk, 'icon', 'webp']));
        $response->assertOk();
    }

    public function testIndex()
    {
        $response = $this->get(route('talks.index'));
        $response->assertOk()
                 ->assertSee('Talks');

        $response = $this->get(route('th.talks.index'));
        $response->assertOk()
                 ->assertSee('เสียงธรรม');
    }
}
