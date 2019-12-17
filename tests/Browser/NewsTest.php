<?php

namespace Tests\Browser;

use App\Models\News;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\NewsPage;
use Tests\DuskBrowser as Browser;
use Tests\DuskTestCase;

class NewsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testNewsSearchEnglish()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new NewsPage)
                    ->waitUntilLoaded()
                    ->type('@searchInput', 'March 31st')
                    ->waitUntilLoaded()
                    ->assertSee('Winter Retreat')
                    ->type('@searchInput', 'มีนาคม')
                    ->waitUntilLoaded()
                    ->assertSee('Winter Retreat');
        });
    }

    public function testNewsSearchThai()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/th/news')
                    ->waitUntilLoaded()
                    ->on(new NewsPage)
                    ->type('@searchInput', 'March 31st')
                    ->waitUntilLoaded()
                    ->assertSee('การเข้ากรรมฐานฤดูหนาว')
                    ->type('@searchInput', 'มีนาคม')
                    ->waitUntilLoaded()
                    ->assertSee('การเข้ากรรมฐานฤดูหนาว');
        });
    }

    public function testNonStandardUrl()
    {
        factory(News::class)->create([
            'title_en' => "Somkid's Photography",
            'body_en' => 'a professional photographer',
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit(new NewsPage) 
                    ->waitUntilLoaded()
                    ->type('@searchInput', 'somkid')
                    ->waitUntilLoaded()
                    ->assertSee('Somkid')
                    ->assertSee('a professional photographer')
                    ->clickLink("Somkid's Photography")
                    ->waitUntilLoaded()
                    ->assertSee('a professional photographer');
        });
    }
}
