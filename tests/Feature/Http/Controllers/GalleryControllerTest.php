<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $this->seed();

        $response = $this->get(route('gallery.index'));
        $response->assertOk()
                 ->assertSee('Gallery')
                 ->assertSee('Winter to Spring');

        $response = $this->get(route('th.gallery.index'));
        $response->assertOk()
                 ->assertSee('รูปถ่าย')
                 ->assertSee('ฤดูหนาวถึงฤดูใบไม้ผลิ');
    }

    public function testShow()
    {
        $this->seed();
        $album = Album::find(1);

        $response = $this->get(route('gallery.show', $album));
        $response->assertOk()
                 ->assertSee('Winter to Spring')
                 ->assertSee('Spring returns')
                 ->assertSee('Kitten');

        $response = $this->get(route('th.gallery.show', $album));
        $response->assertOk()
                 ->assertSee('ฤดูหนาวถึงฤดูใบไม้ผลิ')
                 ->assertSee('ฤดูใบไม้ผลิกลับมา')
                 ->assertSee('ลูกแมว');
    }
}
