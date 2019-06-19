<?php

namespace Tests\Browser;

use Tests\Browser\Pages\NewsPage;
use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;

class NewsTest extends DuskTestCase
{
    public function testNewsSearchEnglish()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new NewsPage)
                    ->waitUntilLoaded()
                    ->type('@searchInput', 'birthday cake')
                    ->waitUntilLoaded()
                    ->assertSee('Born On a Four')
                    ->assertSee('Rik Center was at the monastery')
                    ->type('@searchInput', 'ในเดือนพฤษภาคมปีนี้')
                    ->waitUntilLoaded()
                    ->assertSee('Ajahn Dtun to Visit Abhayagiri');
        });
    }

    public function testNewsSearchThai()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/th/news')
                    ->waitUntilLoaded()
                    ->on(new NewsPage)
                    ->type('@searchInput', 'birthday cake')
                    ->waitUntilLoaded()
                    ->assertSee('Born On a Four')
                    ->assertSee('Rik Center was at the monastery')
                    ->type('@searchInput', 'ในเดือนพฤษภาคมปีนี้')
                    ->waitUntilLoaded()
                    ->assertDontSee('Ajahn Dtun to Visit Abhayagiri')
                    ->assertSee('พระอาจารย์ตั๋นจะมาเยี่ยมวัดอภัยคีรี')
                    ->assertSee('ในเดือนพฤษภาคมปีนี้');
        });
    }

    public function testNonStandardUrl()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new NewsPage) 
                    ->waitUntilLoaded()
                    ->type('@searchInput', 'somkid')
                    ->waitUntilLoaded()
                    ->assertSee('Somkid')
                    ->assertSee('a professional photographer')
                    ->clickLink("Somkid's Photography")
                    ->waitUntilLoaded()
                    ->assertSee('a professional photographer')
                    ->visit('/news/somkid%27s-photography')
                    ->waitUntilLoaded()
                    ->assertSee('a professional photographer');
        });
    }
}
