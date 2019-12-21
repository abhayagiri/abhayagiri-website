<?php

namespace Tests\Unit\Http\View;

use App\Http\View\Pages;
use Mockery;
use Illuminate\Http\Request;
use Tests\TestCase;

// TODO Mock the pages.json (pages.php) input data and test against that.

class PagesTest extends TestCase
{
    public function testAll()
    {
        $this->assertEquals(12, $this->pages('/')->all()->count());
        $this->assertEquals(1, $this->pages('/')->all()->where('current', true)->count());
        $this->assertEquals(12, $this->pages('/th/reflections')->all()->count());
        $this->assertEquals(1, $this->pages('/th/reflections')->all()->where('current', true)->count());
    }

    public function testCurrent()
    {
        $current = $this->pages('/th/visiting/directions')->current();
        $this->assertEquals('visiting', $current->slug);
        $this->assertEquals('/visiting', $current->path);
        $this->assertEquals('เยี่ยม', $current->title);
        $this->assertEquals('legacy', $current->type);
        $this->assertEquals('fa-road', $current->icon);
    }

    public function testGetActive()
    {
        $this->assertTrue($this->pages('/')->get('home')->active);
        $this->assertFalse($this->pages('/')->get('news')->active);
        $this->assertTrue($this->pages('/home')->get('home')->active);
        $this->assertFalse($this->pages('/home')->get('about')->active);
        $this->assertTrue($this->pages('/th')->get('home')->active);
        $this->assertFalse($this->pages('/th')->get('talks')->active);

        $this->assertFalse($this->pages('/news')->get('home')->active);
        $this->assertTrue($this->pages('/news')->get('news')->active);
        $this->assertTrue($this->pages('/th/news')->get('news')->active);
        $this->assertTrue($this->pages('/th/news/1/2/3')->get('news')->active);
        $this->assertFalse($this->pages('/news')->get('talks')->active);

        $this->assertTrue($this->pages('news')->get('news')->active);
        $this->assertTrue($this->pages('news/123')->get('news')->active);
    }

    public function testLng()
    {
        $this->assertEquals('en', $this->pages('/')->lng());
        $this->assertEquals('th', $this->pages('/th')->lng());
        $this->assertEquals('en', $this->pages('/home')->lng());
        $this->assertEquals('th', $this->pages('/th/home')->lng());
        $this->assertEquals('en', $this->pages('/abcd')->lng());
        $this->assertEquals('en', $this->pages('/talks/foo/bar')->lng());
    }

    public function testOtherLngData()
    {
        $this->assertEquals('th', $this->pages('/')->otherLngData()->lng);
        $this->assertEquals('flag-th', $this->pages('/a/b/c')->otherLngData()->cssFlag);
        $this->assertEquals('thai', $this->pages('/news/123')->otherLngData()->transKey);
        $this->assertEquals('en', $this->pages('/th')->otherLngData()->lng);
        $this->assertEquals('flag-us', $this->pages('/th/home/')->otherLngData()->cssFlag);
        $this->assertEquals('english', $this->pages('/th/gallery')->otherLngData()->transKey);
        $this->assertEquals('/th/news/456', $this->pages('/news/456')->otherLngData()->path);
        $this->assertEquals('/th/news?page=3', $this->pages('/news', 'page=3')->otherLngData()->path);
    }

    public function testSlug()
    {
        $this->assertEquals('home', $this->pages('/th')->slug());
        $this->assertEquals('home', $this->pages('/home')->slug());
        $this->assertEquals('home', $this->pages('/th/home')->slug());
        $this->assertEquals('home', $this->pages('/abcd')->slug());
        $this->assertEquals('talks', $this->pages('/talks/foo/bar')->slug());
    }

    protected function pages($path, $queryString = null)
    {
        $pages = Mockery::mock(Pages::class, [])->makePartial();
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('path')->andReturn($path);
        $request->shouldReceive('getQueryString')->andReturn($queryString);
        $pages->shouldReceive('request')->andReturn($request);
        return $pages;
    }
}
