<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class SubjectCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.subjects.index'));
        $response
            ->assertOk()
            ->assertSee('Add subject');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('admin.subjects.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
