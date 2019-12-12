<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class SubpageCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.subpages.index'));
        $response
            ->assertOk()
            ->assertSee('Add subpage');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.subpages.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
