<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class ContactOptionCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('crud.contact-options.index'));
        $response
            ->assertOk()
            ->assertSee('Add contact option');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('crud.contact-options.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
