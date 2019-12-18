<?php

namespace Tests\Unit\Http\View;

use App\Http\View\Breadcrumbs;
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
        $breadcrumbs = Mockery::mock(Breadcrumbs::class, [])->makePartial();
        $breadcrumbs->shouldReceive('currentPage')->andReturn((object) [
            'slug' => 'talks',
            'title' => 'Talks',
            'path' => '/talks',
        ]);
        $breadcrumbs->addBreadcrumb('baz', '/baz');
        $breadcrumbs->addPageBreadcrumbs();
        $this->assertEquals(3, $breadcrumbs->count());
        $this->assertEquals('Home', $breadcrumbs[0]->title);
        $this->assertEquals('/', $breadcrumbs[0]->path);
        $this->assertTrue($breadcrumbs[0]->link);
        $this->assertEquals('Talks', $breadcrumbs[1]->title);
        $this->assertEquals('/talks', $breadcrumbs[1]->path);
        $this->assertTrue($breadcrumbs[1]->link);
        $this->assertEquals('baz', $breadcrumbs[2]->title);
        $this->assertEquals('/baz', $breadcrumbs[2]->path);
        $this->assertFalse($breadcrumbs[2]->link);
    }
}
