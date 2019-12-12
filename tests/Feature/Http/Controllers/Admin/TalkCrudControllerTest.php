<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class TalkCrudControllerTest extends TestCase
{
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
