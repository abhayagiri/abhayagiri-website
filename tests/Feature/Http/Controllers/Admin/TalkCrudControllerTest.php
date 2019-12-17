<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TalkCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.talks.index'));
        $response
            ->assertOk()
            ->assertSee('Add talk');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('admin.talks.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
