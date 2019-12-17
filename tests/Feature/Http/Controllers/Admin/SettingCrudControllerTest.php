<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.settings.index'));
        $response->assertOk();
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(route('admin.settings.search'),
                                    ['length' => 10]);
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }

    public function testUpdateNumber()
    {
        $setting = factory(Setting::class)->create([
            'key' => 'home.news.count',
            'value' => 2,
        ]);

        $this->actingAsAdmin()
             ->get(route('admin.settings.edit', $setting))
             ->assertOk();

        $data = [
            'id' => $setting->id,
            'value' => 5,
        ];
        $this->actingAsAdmin()
             ->put(route('admin.settings.update', $setting), $data)
             ->assertRedirect(route('admin.settings.edit', $setting));

        $this->assertEquals(5, $setting->fresh()->value);
    }

    public function testUpdateMediaFile()
    {
        $setting = factory(Setting::class)->create([
            'key' => 'talks.default_image_file',
            'value' => 'no/where',
        ]);

        $this->actingAsAdmin()
             ->get(route('admin.settings.edit', $setting))
             ->assertOk();

        $data = [
            'id' => $setting->id,
            'value' => 'media/foo/bar',
        ];
        $this->actingAsAdmin()
             ->put(route('admin.settings.update', $setting), $data)
             ->assertRedirect(route('admin.settings.edit', $setting));

        $this->assertEquals('foo/bar', $setting->fresh()->value);
    }
}
