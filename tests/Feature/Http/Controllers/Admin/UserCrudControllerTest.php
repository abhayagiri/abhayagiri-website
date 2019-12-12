<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class UserCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin(true)
                         ->get(route('crud.users.index'));
        $response
            ->assertOk();
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin(true)
                         ->postJson(route('crud.users.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }

    public function testUnauthorized()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.users.index'));
        $response
            ->assertStatus(403);
    }
}
