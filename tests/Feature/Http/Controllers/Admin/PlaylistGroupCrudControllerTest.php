<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class PlaylistGroupCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.playlist-groups.index'));
        $response
            ->assertOk()
            ->assertSee('Add playlist group');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('admin.playlist-groups.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
