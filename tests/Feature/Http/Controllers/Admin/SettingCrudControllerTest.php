<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class SettingCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.settings.index'));
        $response
            ->assertOk();
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.settings.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
