<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class PlaylistCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.playlists.index'));
        $response
            ->assertOk()
            ->assertSee('Add playlist');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.playlists.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
