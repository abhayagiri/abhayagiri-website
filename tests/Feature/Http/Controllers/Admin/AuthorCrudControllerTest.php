<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class AuthorCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.authors.index'));
        $response
            ->assertOk()
            ->assertSee('Add author');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.authors.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
