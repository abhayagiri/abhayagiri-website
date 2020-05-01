<?php

namespace Tests\Unit\Utilities;

use App\Utilities\ImageCache;
use App\Utilities\ImageCacheServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use League\Glide\Filesystem\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ImageCacheTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->server = $this->createMock(ImageCacheServer::class);
        $request = $this->createMock(Request::class);
        $this->imageCache = new ImageCache($this->server, $request);
    }

    public function testGetImageResponse()
    {
        $this->server->expects($this->once())
                     ->method('setResponseFactory');
        $this->server->expects($this->once())
                     ->method('getImageResponse')
                     ->with('a/b', [])
                     ->willReturn(new Response());
        $this->assertInstanceOf(
            Response::class,
            $this->imageCache->getImageResponse('a/b')
        );
    }

    public function testGetImageResponseWithFallback()
    {
        Config::set('imagecache.fallbackImage', 'fallback.jpg');
        $this->server->expects($this->exactly(2))
                     ->method('getImageResponse')
                     ->willReturnCallback(function ($path, $params) {
                         if ($path === 'fallback.jpg') {
                             return new Response();
                         } else {
                             throw new FileNotFoundException();
                         }
                     });
        $this->assertInstanceOf(
            Response::class,
            $this->imageCache->getImageResponse('a/b', ['w' => 50])
        );
    }
}
