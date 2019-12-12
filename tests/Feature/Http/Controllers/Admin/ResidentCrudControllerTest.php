<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class ResidentCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.residents.index'));
        $response
            ->assertOk()
            ->assertSee('Add resident');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.residents.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
