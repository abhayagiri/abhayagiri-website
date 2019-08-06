<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\View\Composers\NavMenuComposer;

// TODO Mock the pages.json (pages.php) input data and test against that.

class NavMenuComposerTest extends TestCase
{
    public function testGetNavMenuActive()
    {
        $comp = new NavMenuComposer;
        $this->assertTrue($comp->getNavMenu('/')[0]->active);
        $this->assertFalse($comp->getNavMenu('/')[1]->active);
        $this->assertTrue($comp->getNavMenu('/home')[0]->active);
        $this->assertFalse($comp->getNavMenu('/home')[2]->active);
        $this->assertTrue($comp->getNavMenu('/th')[0]->active);
        $this->assertFalse($comp->getNavMenu('/th')[3]->active);

        $this->assertFalse($comp->getNavMenu('/news')[0]->active);
        $this->assertTrue($comp->getNavMenu('/news')[1]->active);
        $this->assertTrue($comp->getNavMenu('/th/news')[1]->active);
        $this->assertTrue($comp->getNavMenu('/th/news/1/2/3')[1]->active);
        $this->assertFalse($comp->getNavMenu('/news')[2]->active);
    }

    public function testGetNavMenuTitle()
    {
        $comp = new NavMenuComposer;
        $this->assertEquals('Home', $comp->getNavMenu(null, 'en')[0]->title);
        $this->assertEquals('หน้าหลัก', $comp->getNavMenu(null, 'th')[0]->title);
    }
}
