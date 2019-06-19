<?php

namespace Tests\Browser;

use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BasicsTest extends DuskTestCase
{
    public function testHomePageEnglish()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                    ->waitUntilLoaded()
                    ->assertTitleContains('Abhayagiri Buddhist Monastery')
                    ->assertSee('News');
        });
    }

    public function testHomePageThai()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/th')
                    ->waitUntilLoaded()
                    ->on(new HomePage)
                    ->assertTitleContains('Abhayagiri Buddhist Monastery')
                    ->assertSee('ข่าว');
        });
    }

    public function testFindingDirectionsEnglish()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                    ->waitUntilLoaded()
                    ->assertSee('News')
                    ->assertSee('Calendar')
                    ->click('.btn[href="/news"]')
                    ->waitUntilLoaded()
                    ->assertSee('back to top')
                    ->click('.btn[href="/visiting/directions"]')
                    ->waitUntilLoaded()
                    ->assertSee('Directions')
                    ->assertSee('16201 Tomki Road');
        });
    }

    public function testFindingDirectionsThai()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                    ->waitUntilLoaded()
                    ->click('#link-language')
                    ->waitUntilLoaded()
                    ->assertSee('ข่าว')
                    ->assertSee('ปฏิทิน')
                    ->click('.btn[href="/th/news"]')
                    ->waitUntilLoaded()
                    ->assertSee('กลับสู่ด้านบน')
                    ->click('.btn[href="/th/visiting/directions"]')
                    ->waitUntilLoaded()
                    ->assertSee('เส้นทางมาวัด')
                    ->assertSee('16201 Tomki Road');
        });
    }
}
