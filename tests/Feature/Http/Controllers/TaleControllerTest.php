<?php

namespace Tests\Feature;

use App\Models\Tale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get(route('tales.index'));
        $response->assertOk()
                 ->assertSee('Tales');

        $response = $this->get(route('th.tales.index'));
        $response->assertOk()
                 ->assertSee('เรื่องเล่า');
    }

    public function testShow()
    {
        $this->seed();
        $tale = Tale::find(1);

        $response = $this->get(route('tales.show', $tale));
        $response->assertOk()
                 ->assertSee($tale->title_en);

        $response = $this->get(route('th.tales.show', $tale));
        $response->assertOk()
                 ->assertSee($tale->title_th);
    }
}
