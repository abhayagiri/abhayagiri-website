<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get(route('gallery.index'));
        $response->assertOk()
                 ->assertSee('Gallery');

        $response = $this->get(route('th.gallery.index'));
        $response->assertOk()
                 ->assertSee('รูปถ่าย');
    }
}
