<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\News;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testIndex()
    {
        $response = $this->get(route('home.index'));
        $response->assertOk()
                 ->assertSee('View Full Calendar');

        $response = $this->get(route('th.home.index'));
        $response->assertOk()
                 ->assertSee('ดูปฏิทินแบบเต็ม');
    }

    public function testNewsOrdering()
    {
        News::truncate();
        Setting::truncate();
        Setting::create([
            'type' => 'number',
            'key' => 'home.news_count',
            'value' => 1,
        ]);
        Setting::resetCache();
        factory(News::class)->create([
            'title_en' => 'very important news',
            'rank' => 1,
            'posted_at' => Carbon::parse('2020-01-01'),
        ]);
        factory(News::class)->create([
            'title_en' => 'less important news',
            'rank' => null,
            'posted_at' => Carbon::parse('2020-02-01'),
        ]);
        $response = $this->get(route('home.index'));
        $response->assertSee('very important news')
                 ->assertDontSee('less important news');
    }
}
