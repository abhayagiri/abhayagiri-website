<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\HomePage;
use Tests\DuskBrowser as Browser;
use Tests\DuskTestCase;

class BasicsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testHomePageEnglish()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                    ->waitUntilLoaded()
                    ->assertTitleContains('Abhayagiri Monastery')
                    ->assertSee('News');
        });
    }

    public function testHomePageThai()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/th')
                    ->waitUntilLoaded()
                    ->on(new HomePage)
                    ->assertTitleContains('วัดป่าอภัยคีรี')
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
                    ->assertSee('Winter Retreat')
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
                    ->click('#header-language')
                    ->waitUntilLoaded()
                    ->assertSee('ข่าว')
                    ->assertSee('ปฏิทิน')
                    ->click('.btn[href="/th/news"]')
                    ->waitUntilLoaded()
                    ->assertSee('การเข้ากรรมฐานฤดูหนาว')
                    ->click('.btn[href="/th/visiting/directions"]')
                    ->waitUntilLoaded()
                    ->assertSee('เส้นทางมาวัด')
                    ->assertSee('16201 Tomki Road');
        });
    }
}
