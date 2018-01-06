<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Setting;

class SettingTest extends TestCase
{
    public function testValueMediaPath()
    {
        $setting = new Setting;

        $setting->value_media_path = 'media/some/path';
        $this->assertEquals('some/path', $setting->value);
        $this->assertEquals('/media/some/path', $setting->value_media_path);
        $this->assertEquals('http://localhost:8000/media/some/path', $setting->value_media_url);

        $setting->value_media_path = '../evil/path';
        $this->assertNull($setting->value);
        $this->assertNull($setting->value_media_path);
        $this->assertNull($setting->value_media_url);
    }
}
