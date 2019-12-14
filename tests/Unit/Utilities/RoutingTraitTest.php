<?php

namespace Tests\Unit\Utilities;

use App\Utilities\RoutingTrait;
use Tests\TestCase;

class RoutingTraitTest extends TestCase
{
    use RoutingTrait;

    public function testEnglishPaths()
    {
        $this->assertEquals('/', static::localizedPath('', 'en'));
        $this->assertEquals('/', static::localizedPath('/', 'en'));
        $this->assertEquals('/books', static::localizedPath('books', 'en'));
        $this->assertEquals('/books', static::localizedPath('/books', 'en'));
    }

    public function testThaiPaths()
    {
        $this->assertEquals('/th', static::localizedPath('', 'th'));
        $this->assertEquals('/th', static::localizedPath('/', 'th'));
        $this->assertEquals('/th/books', static::localizedPath('books', 'th'));
        $this->assertEquals('/th/books', static::localizedPath('/books', 'th'));
    }

    public function testEnglishPathsFromThaiPaths()
    {
        $this->assertEquals('/', static::localizedPath('/th', 'en'));
        $this->assertEquals('/', static::localizedPath('/th/', 'en'));
        $this->assertEquals('/books', static::localizedPath('/th/books', 'en'));
        $this->assertEquals('/books/123', static::localizedPath('/th/books/123', 'en'));
    }
}
