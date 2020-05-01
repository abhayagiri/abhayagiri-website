<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testImage()
    {
        $this->withoutImageCreation();
        $news = factory(News::class)->create(['id' => 123]);
        $response = $this->get(route('news.image', [$news, 'icon', 'webp']));
        $response->assertOk();
    }

    public function testIndex()
    {
        $response = $this->get(route('news.index'));
        $response->assertOk()
                 ->assertSee('News');

        $response = $this->get(route('th.news.index'));
        $response->assertOk()
                 ->assertSee('ข่าว');
    }

    public function testShow()
    {
        $news = factory(News::class)->create(['id' => 123]);

        $response = $this->get(route('news.show', $news));
        $response->assertOk()
                 ->assertSee($news->title_en);

        $response = $this->get(route('th.news.show', $news));
        $response->assertOk()
                 ->assertSee($news->title_th);
    }
}
