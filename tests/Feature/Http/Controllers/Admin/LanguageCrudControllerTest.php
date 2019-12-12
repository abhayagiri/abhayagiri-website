<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class LanguageCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.languages.index'));
        $response
            ->assertOk()
            ->assertSee('Add language');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.languages.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
