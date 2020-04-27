<?php

namespace Tests\Unit\Models\Traits;

use App\Models\Traits\HasPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class HasPathTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new class() extends Model {
            use HasPath;
            protected $table = 'peoples';
        };
        Route::get('peoples/{id}', ['as' => 'peoples.show']);
        Route::get('th/peoples/{id}', ['as' => 'th.peoples.show']);
    }

    public function testAccessor()
    {
        $this->model->id = 4;
        $this->model->slug = 'george';
        $this->assertEquals('/peoples/4-george', $this->model->path);
        App::setLocale('th');
        $this->assertEquals('/th/peoples/4-george', $this->model->path);
    }

    public function testGetPath()
    {
        $this->assertNull($this->model->getPath());
        $this->model->id = 123;
        $this->assertEquals('/peoples/123', $this->model->getPath());
        $this->model->slug = 'fred';
        $this->assertEquals('/peoples/123-fred', $this->model->getPath());
        $this->assertEquals('/peoples/123-fred', $this->model->getPath('en'));
        $this->assertEquals('/th/peoples/123-fred', $this->model->getPath('th'));
        App::setLocale('th');
        $this->assertEquals('/th/peoples/123-fred', $this->model->getPath());
    }

    public function testWithSlug()
    {
        URL::forceRootUrl('http://local.test');
        $this->model->id = 3;
        $this->assertEquals('/peoples/3', $this->model->getPath('en', true));
        $this->assertEquals('/peoples/3', $this->model->getPath('en', false));
        $this->assertEquals('http://local.test/peoples/3', $this->model->getUrl());
        $this->model->slug = 'abc';
        $this->assertEquals('/peoples/3-abc', $this->model->getPath());
        $this->assertEquals('/th/peoples/3-abc', $this->model->getPath('th', true));
        $this->assertEquals('/th/peoples/3', $this->model->getPath('th', false));
        $this->assertEquals('http://local.test/th/peoples/3', $this->model->getUrl('th', false));
    }
}
