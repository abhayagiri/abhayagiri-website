<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.languages.index'));
        $response
            ->assertOk()
            ->assertSee('Add language');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(
                             route('admin.languages.search'),
                             ['length' => 10]
                         );
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
