<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class SubjectGroupCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.subject-groups.index'));
        $response
            ->assertOk()
            ->assertSee('Add subject group');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('admin.subject-groups.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
