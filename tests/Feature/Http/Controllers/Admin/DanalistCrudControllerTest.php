<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class DanalistCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.danalist.index'));
        $response
            ->assertOk()
            ->assertSee('Add dana wishlist');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('admin.danalist.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
