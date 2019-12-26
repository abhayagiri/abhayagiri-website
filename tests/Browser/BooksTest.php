<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\BooksPage;
use Tests\DuskBrowser as Browser;
use Tests\DuskTestCase;

class BooksTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testSearchingForABook()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new BooksPage)
                    ->type('@searchInput', 'clear')
                    ->waitUntilLoaded()
                    ->assertSee('concise');
        });
    }
}
