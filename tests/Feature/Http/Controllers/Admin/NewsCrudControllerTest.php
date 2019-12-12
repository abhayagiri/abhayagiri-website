<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class NewsCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.news.index'));
        $response
            ->assertOk()
            ->assertSee('Add news');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.news.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
