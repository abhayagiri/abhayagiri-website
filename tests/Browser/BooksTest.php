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
            $browser->visit('/books/request')
                    ->waitUntilLoaded()
                    ->assertDontSee('important information')
                    ->type('input[name=country]', 'thailand')
                    ->click('textarea[name=comments]')
                    ->assertSee('important information')
                    ->type('input[name=country]', 'us')
                    ->click('textarea[name=comments]')
                    ->assertDontSee('important information');
        });
    }
}
