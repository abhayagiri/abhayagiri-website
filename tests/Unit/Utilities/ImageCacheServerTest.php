<?php

namespace Tests\Unit\Utilities;

use App\Utilities\ImageCacheServer;
use Illuminate\Support\Facades\Config;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Tests\TestCase;

class ImageCacheServerTest extends TestCase
{
    public function testCreateAppImageCache()
    {
        Config::set('imagecache.presets', ['a' => ['w' => 10]]);

        $server = new ImageCacheServer('local');
        $this->assertInstanceOf(Local::class, $server->getSource()->getAdapter());
        $this->assertInstanceOf(Local::class, $server->getCache()->getAdapter());
        $this->assertEquals(10, $server->getPresets()['a']['w']);

        $server = new ImageCacheServer('spaces');
        $this->assertInstanceOf(AwsS3Adapter::class, $server->getSource()->getAdapter());
    }
}
