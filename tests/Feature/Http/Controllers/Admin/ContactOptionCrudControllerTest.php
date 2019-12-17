<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactOptionCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.contact-options.index'));
        $response
            ->assertOk()
            ->assertSee('Add contact option');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('admin.contact-options.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
