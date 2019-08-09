<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\View\Composers\PageComposer;

// TODO Mock the pages.json (pages.php) input data and test against that.

class PageComposerTest extends TestCase
{
    public function testGetMenuActive()
    {
        $comp = new PageComposer;
        $this->assertTrue($comp->getPageMenu('/')['home']->active);
        $this->assertFalse($comp->getPageMenu('/')['news']->active);
        $this->assertTrue($comp->getPageMenu('/home')['home']->active);
        $this->assertFalse($comp->getPageMenu('/home')['about']->active);
        $this->assertTrue($comp->getPageMenu('/th')['home']->active);
        $this->assertFalse($comp->getPageMenu('/th')['talks']->active);

        $this->assertFalse($comp->getPageMenu('/news')['home']->active);
        $this->assertTrue($comp->getPageMenu('/news')['news']->active);
        $this->assertTrue($comp->getPageMenu('/th/news')['news']->active);
        $this->assertTrue($comp->getPageMenu('/th/news/1/2/3')['news']->active);
        $this->assertFalse($comp->getPageMenu('/news')['talks']->active);

        $this->assertTrue($comp->getPageMenu('news')['news']->active);
        $this->assertTrue($comp->getPageMenu('news/123')['news']->active);
    }

    public function testGetMenuTitle()
    {
        $comp = new PageComposer;
        $this->assertEquals('Home', $comp->getPageMenu('/')['home']->title);
        $this->assertEquals('หน้าหลัก', $comp->getPageMenu('/th')['home']->title);
    }

    public function testGetPageSlug()
    {
        $comp = new PageComposer;
        $this->assertEquals('home', $comp->getPageSlug('/'));
        $this->assertEquals('home', $comp->getPageSlug('/th'));
        $this->assertEquals('home', $comp->getPageSlug('/home'));
        $this->assertEquals('home', $comp->getPageSlug('/th/home'));
        $this->assertEquals('home', $comp->getPageSlug('/abcd'));
        $this->assertEquals('talks', $comp->getPageSlug('/talks/foo/bar'));
    }
}
