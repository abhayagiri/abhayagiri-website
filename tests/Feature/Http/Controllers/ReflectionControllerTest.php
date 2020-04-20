<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Reflection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReflectionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testImage()
    {
        $this->withoutImageCreation();
        $reflection = factory(Reflection::class)->create(['id' => 123]);
        $response = $this->get(route('reflections.image', [$reflection, 'icon', 'webp']));
        $response->assertOk();
    }

    public function testIndex()
    {
        $response = $this->get(route('reflections.index'));
        $response->assertOk()
                 ->assertSee('Reflections');

        $response = $this->get(route('th.reflections.index'));
        $response->assertOk()
                 ->assertSee('แง่ธรรม');
    }

    public function testShow()
    {
        $this->seed();
        $reflection = factory(Reflection::class)->create(['id' => 123]);

        $response = $this->get(route('reflections.show', $reflection));
        $response->assertOk()
                 ->assertSee($reflection->title);

        $response = $this->get(route('th.reflections.show', $reflection));
        $response->assertOk()
                 ->assertSee($reflection->title);
    }
}
