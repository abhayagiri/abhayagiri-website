<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Setting;
//use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SettingCrudControllerTest extends TestCase
{
    // See resetApp() below
    //use DatabaseTransactions;

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
        $this->withSetting('home.news.count', function($setting) {

            $setting->update(['value' => 2]);

            $this->actingAsAdmin()
                 ->get(route('admin.settings.edit', $setting))
                 ->assertOk();

            $this->resetApp(); // See below

            $data = [
                'id' => $setting->id,
                'value' => 5,
            ];
            $this->actingAsAdmin()
                 ->put(route('admin.settings.update', $setting), $data)
                 ->assertRedirect(route('admin.settings.edit', $setting));

            $this->assertEquals(5, $setting->fresh()->value);

        });
    }

    public function testUpdateMediaFile()
    {
        $this->withSetting('talks.default_image_file', function($setting) {

            $setting->update(['value' => 'no/where']);

            $this->actingAsAdmin()
                 ->get(route('admin.settings.edit', $setting))
                 ->assertOk();

            $this->resetApp(); // See below

            $data = [
                'id' => $setting->id,
                'value' => 'media/foo/bar',
            ];
            $this->actingAsAdmin()
                 ->put(route('admin.settings.update', $setting), $data)
                 ->assertRedirect(route('admin.settings.edit', $setting));

            $this->assertEquals('foo/bar', $setting->fresh()->value);

        });
    }

    /**
     * WORKAROUND: Reset the appllcation in the testcase.
     *
     * This is needed by some tests to work around caching issues in Backpack
     * For some reason, $controller->crud->request is being cached from the
     * previous request.
     *
     * @return void
     */
    protected function resetApp(): void
    {
        $this->app = $this->createApplication();
    }

    /**
     * WORKAROUND: Avoid using database transaction and manually wrap modifying
     * a Setting.
     *
     * @param  string  $key
     * @param  callable  $callback
     * @return void
     */
    protected function withSetting(string $key, callable $callback): void
    {
        $setting = Setting::findByKey($key);
        $oldValue = $setting->value;
        try {
            $callback($setting);
        } finally {
            $setting->update(['value' => $oldValue]);
        }
    }
}
