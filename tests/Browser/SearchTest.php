<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskBrowser as Browser;
use Tests\DuskTestCase;

class SearchTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testAlgoliaSearch()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/news')
                    ->waitUntilLoaded()
                    ->assertDontSee('What is Buddhism?')
                    ->click('#header-search-button')
                    ->type('.ais-SearchBox input', 'buddhism')
                    ->assertSee('What is Buddhism?')
                    ->clickLink('What is Buddhism?')
                    ->waitUntilLoaded()
                    ->assertPathIs('/books/1-what-is-buddhism');
        });
    }
}
