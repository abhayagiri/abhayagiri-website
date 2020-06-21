<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\PlaylistGroup;
use App\Models\Setting\ImageSetting;
use App\Models\Setting\MarkdownSetting;
use App\Models\Setting\NumberSetting;
use App\Models\Setting\PlaylistGroupSetting;
use App\Models\Setting\StringSetting;
use App\Models\Setting\TextSetting;
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
                         ->postJson(
                             route('admin.settings.search'),
                             ['length' => 10]
                         );
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }

    public function testUpdateImage()
    {
        $setting = ImageSetting::create([
            'key' => 'some_image',
            'value' => 'no/where',
        ]);

        $this->actingAsAdmin()
             ->get(route('admin.settings.edit', $setting))
             ->assertOk();

        $data = [
            'id' => $setting->id,
            'media_path' => 'media/foo/bar',
        ];
        $this->actingAsAdmin()
             ->put(route('admin.settings.update', $setting), $data)
             ->assertRedirect(route('admin.settings.edit', $setting));

        $this->assertEquals('foo/bar', $setting->fresh()->value);
    }

    public function testUpdateMarkdown()
    {
        $setting = MarkdownSetting::create([
            'key' => 'xyz',
            'text_en' => 'en',
            'text_th' => 'th',
        ]);

        $this->actingAsAdmin()
             ->get(route('admin.settings.edit', $setting))
             ->assertOk();

        $data = [
            'id' => $setting->id,
            'text_en' => 'abc',
            'text_th' => '123',
        ];
        $this->actingAsAdmin()
             ->put(route('admin.settings.update', $setting), $data)
             ->assertRedirect(route('admin.settings.edit', $setting));

        $setting->refresh();
        $this->assertEquals('abc', $setting->text_en);
        $this->assertEquals('123', $setting->text_th);
    }

    public function testUpdateNumber()
    {
        $setting = NumberSetting::create([
            'key' => 'abc',
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

    public function testUpdatePlaylistGroup()
    {
        $playlistGroup1 = factory(PlaylistGroup::class)->create();
        $playlistGroup2 = factory(PlaylistGroup::class)->create();
        $setting = PlaylistGroupSetting::create([
            'key' => 'pg',
            'value' => $playlistGroup1->id,
        ]);

        $this->actingAsAdmin()
             ->get(route('admin.settings.edit', $setting))
             ->assertOk();

        $data = [
            'id' => $setting->id,
            'value' => $playlistGroup2->id,
        ];
        $this->actingAsAdmin()
             ->put(route('admin.settings.update', $setting), $data)
             ->assertRedirect(route('admin.settings.edit', $setting));

        $this->assertEquals($playlistGroup2->id, $setting->fresh()->value);
    }

    public function testUpdateString()
    {
        $setting = StringSetting::create([
            'key' => 'def',
            'value' => 'old',
        ]);

        $this->actingAsAdmin()
             ->get(route('admin.settings.edit', $setting))
             ->assertOk();

        $data = [
            'id' => $setting->id,
            'value' => 'new',
        ];
        $this->actingAsAdmin()
             ->put(route('admin.settings.update', $setting), $data)
             ->assertRedirect(route('admin.settings.edit', $setting));

        $this->assertEquals('new', $setting->fresh()->value);
    }

    public function testUpdateText()
    {
        $setting = TextSetting::create([
            'key' => 'xyz',
            'text_en' => 'en',
            'text_th' => 'th',
        ]);

        $this->actingAsAdmin()
             ->get(route('admin.settings.edit', $setting))
             ->assertOk();

        $data = [
            'id' => $setting->id,
            'text_en' => 'abc',
            'text_th' => '123',
        ];
        $this->actingAsAdmin()
             ->put(route('admin.settings.update', $setting), $data)
             ->assertRedirect(route('admin.settings.edit', $setting));

        $setting->refresh();
        $this->assertEquals('abc', $setting->text_en);
        $this->assertEquals('123', $setting->text_th);
    }
}
