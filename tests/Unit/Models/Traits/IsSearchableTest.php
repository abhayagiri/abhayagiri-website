<?php

namespace Tests\Unit\Models\Traits;

use App\Models\Traits\HasPath;
use App\Models\Traits\IsSearchable;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\App;
// use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class IsSearchableTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new class() extends Model {
            use IsSearchable;
            function getPath($lng = 'en') {
                return '/' . $lng . '/foos/' . $this->id;
            }
        };
        $this->model->id = 123;
        $this->model->title_en = 'abc';
        $this->model->title_th = 'xyz';
        $this->model->body_html_en = 'ABC';
        $this->model->body_html_th = 'XYZ';
        $this->author = new class() extends Model {};
        $this->author->id = 5;
        $this->author->title_en = 'sam';
        $this->author->title_th = 'nen';
        // Route::get('peoples/{id}', ['as' => 'peoples.show']);
        // Route::get('th/peoples/{id}', ['as' => 'th.peoples.show']);
    }

    public function testToSearchableArray()
    {
        $result = $this->model->toSearchableArray();
        $this->assertRegExp('/^is-searchable-test.php/', $result['type']);
        unset($result['type']);
        $this->assertEquals([
            'id' => 123,
            'text' => [
                'path_en' => '/en/foos/123',
                'path_th' => '/th/foos/123',
                'title_en' => 'abc',
                'title_th' => 'xyz',
                'body_en' => 'ABC',
                'body_th' => 'XYZ',
            ],
        ], $result);
    }

    public function testToSearchableArrayWithAuthor()
    {
        $this->model->author = $this->author;
        $result = $this->model->toSearchableArray();
        unset($result['type']);
        $this->assertEquals([
            'id' => 123,
            'text' => [
                'path_en' => '/en/foos/123',
                'path_th' => '/th/foos/123',
                'title_en' => 'abc',
                'title_th' => 'xyz',
                'body_en' => 'ABC',
                'body_th' => 'XYZ',
                'author_en' => 'sam',
                'author_th' => 'nen',
            ],
            'author_id' => 5,
        ], $result);
    }

    public function testSplitText()
    {
        $this->model->textSplitterMax = 10;
        $this->model->textSplitterMin = 5;
        $this->assertEquals([
            [
                'lng' => 'en',
                'path' => 'path_en',
                'title' => 'title_en',
                'body' => '1en 2en',
                'author' => 'author_en',
                'body_index' => 0,
            ],
            [
                'lng' => 'en',
                'path' => 'path_en',
                'title' => 'title_en',
                'body' => '3en 4en',
                'author' => 'author_en',
                'body_index' => 1,
            ],
            [
                'lng' => 'th',
                'path' => 'path_th',
                'title' => 'title_th',
                'body' => '1th 2th',
                'author' => 'author_th',
                'body_index' => 0,
            ],
            [
                'lng' => 'th',
                'path' => 'path_th',
                'title' => 'title_th',
                'body' => '3th 4th',
                'author' => 'author_th',
                'body_index' => 1,
            ],
        ], $this->model->splitText([
            'path_en' => 'path_en',
            'path_th' => 'path_th',
            'title_en' => 'title_en',
            'title_th' => 'title_th',
            'body_en' => '1en 2en 3en 4en',
            'body_th' => '1th 2th 3th 4th',
            'author_en' => 'author_en',
            'author_th' => 'author_th',
        ]));
    }
}
