<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class PlaylistGroupCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.playlist-groups.index'));
        $response
            ->assertOk()
            ->assertSee('Add playlist group');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.playlist-groups.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
