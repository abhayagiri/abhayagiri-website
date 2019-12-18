<?php

namespace Tests\Unit\Http\View;

use App\Http\View\Breadcrumbs;
use App\Http\View\Pages;
use Mockery;
use Tests\TestCase;

class BreadcrumbsTest extends TestCase
{
    public function testAddBreadcrumb()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addBreadcrumb('foo', '/foo');
        $this->assertEquals('foo', $breadcrumbs[0]->title);
        $this->assertEquals('/foo', $breadcrumbs[0]->path);
        $this->assertFalse($breadcrumbs[0]->link);
        $this->assertTrue($breadcrumbs[0]->last);
        $breadcrumbs->addBreadcrumb('bar');
        $this->assertTrue($breadcrumbs[0]->link);
        $this->assertFalse($breadcrumbs[0]->last);
        $this->assertEquals('bar', $breadcrumbs[1]->title);
        $this->assertNull($breadcrumbs[1]->path);
        $this->assertFalse($breadcrumbs[1]->link);
        $this->assertTrue($breadcrumbs[1]->last);
        $breadcrumbs->addBreadcrumb('baz', '/baz');
        $this->assertTrue($breadcrumbs[0]->link);
        $this->assertFalse($breadcrumbs[0]->last);
        $this->assertFalse($breadcrumbs[1]->link);
        $this->assertFalse($breadcrumbs[1]->last);
        $this->assertFalse($breadcrumbs[2]->link);
        $this->assertTrue($breadcrumbs[2]->last);
    }

    public function testAddPageBreadcrumb()
    {
        $breadcrumbs = new Breadcrumbs();
        $pages = Mockery::mock(Pages::class, [])->makePartial();
        $pages->shouldReceive('path')->andReturn('/talks/test');
        $breadcrumbs->addPageBreadcrumbs($pages->current());
        $this->assertEquals('Home', $breadcrumbs[0]->title);
        $this->assertEquals('/', $breadcrumbs[0]->path);
        $this->assertEquals('Talks', $breadcrumbs[1]->title);
        $this->assertEquals('/talks', $breadcrumbs[1]->path);
    }
}
