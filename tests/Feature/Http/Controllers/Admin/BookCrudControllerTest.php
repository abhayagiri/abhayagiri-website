<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;

class BookCrudControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.books.index'));
        $response
            ->assertOk()
            ->assertSee('Add book');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('admin.books.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }
}
