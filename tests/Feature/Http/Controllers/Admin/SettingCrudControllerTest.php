<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SettingCrudControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.settings.index'));
        $response->assertOk();
    }

    public function xtestSearch()
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
        $setting = Setting::findByKey('home.news.count');
        $setting->update(['value' => 2]);

        $this->actingAsAdmin()
             ->get(route('admin.settings.edit', $setting))
             ->assertOk();

        // The following resetApp() is needed to workaround controller/request
        // caching issue in Backpack. For some reason, $this->crud->request is
        // cached from the previous request.
        $this->resetApp();

        $data = [
            'id' => $setting->id,
            'value' => 5,
        ];
        $this->actingAsAdmin()
             ->put(route('admin.settings.update', $setting), $data)
             ->assertRedirect(route('admin.settings.edit', $setting));

        $this->assertEquals(5, Setting::findByKey('home.news.count')->value);
    }

    public function testUpdateMediaFIle()
    {
        $setting = Setting::findByKey('talks.default_image_file');
        $setting->update(['value' => null]);

        $this->actingAsAdmin()
             ->get(route('admin.settings.edit', $setting))
             ->assertOk();

        // The following resetApp() is needed to workaround controller/request
        // caching issue in Backpack. For some reason, $this->crud->request is
        // cached from the previous request.
        $this->resetApp();

        $data = [
            'id' => $setting->id,
            'value' => 'media/foo/bar',
        ];
        $this->actingAsAdmin()
             ->put(route('admin.settings.update', $setting), $data)
             ->assertRedirect(route('admin.settings.edit', $setting));

        $this->assertEquals('foo/bar',
                            Setting::findByKey('talks.default_image_file')->value);
    }
}
