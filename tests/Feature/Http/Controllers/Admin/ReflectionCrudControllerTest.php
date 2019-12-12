<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class ReflectionCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.reflections.index'));
        $response
            ->assertOk()
            ->assertSee('Add reflection');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.reflections.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
