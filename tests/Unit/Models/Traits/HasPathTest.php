<?php

namespace Tests\Unit\Models\Traits;

use App\Models\Traits\HasPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
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
}
