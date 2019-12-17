<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->actingAsAdmin(true)
                         ->get(route('admin.users.index'));
        $response
            ->assertOk();
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin(true)
                         ->postJson(route('admin.users.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }

    public function testUnauthorized()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.users.index'));
        $response
            ->assertStatus(403);
    }
}
