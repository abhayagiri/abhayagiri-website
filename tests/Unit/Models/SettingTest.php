<?php

namespace Tests\Unit;

use App\Models\Setting;
use App\Models\Setting\SettingNotFoundException;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function testGetByKey()
    {
        Config::set('settings', [
            'foo' => [
                'bar' => [
                    'type' => 'number',
                    'default_value' => 5,
                ],
            ],
        ]);
        Setting::truncate();
        Setting::resetCache();
        $this->assertEquals(0, Setting::count());
        $this->assertEquals(5, Setting::getByKey('foo.bar')->value);
        $this->assertEquals(1, Setting::count());
        $this->assertEquals(5, Setting::getByKey('foo.bar')->value);
        $this->assertEquals(1, Setting::count());
        try {
            $this->assertNull(Setting::getByKey('foo.baz'));
            $this->assertTrue(false);
        } catch (SettingNotFoundException $e) {
            //
        }
        $this->assertEquals(1, Setting::count());
    }

    public function testSync()
    {
        Config::set('settings', [
            'foo' => [
                'bar' => [
                    'type' => 'number',
                    'default_value' => 5,
                ],
                'baz' => [
                    'type' => 'string',
                    'default_value' => 'abc',
                ],
            ],
        ]);
        Setting::truncate();
        Setting::resetCache();
        $this->assertEquals(0, Setting::count());
        $log = Setting::sync();
        $this->assertEquals(2, count($log));
        $this->assertEquals(2, Setting::count());
        $this->assertEquals(5, Setting::getByKey('foo.bar')->value);
        $this->assertEquals('abc', Setting::getByKey('foo.baz')->value);
    }
}
