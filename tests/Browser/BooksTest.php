<?php

namespace Tests\Browser;

use Tests\Browser\Pages\BooksPage;
use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;

class BooksTest extends DuskTestCase
{
    public function testSearchingForABook()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new BooksPage)
                    ->type('@searchInput', "Don't push")
                    ->waitUntilLoaded()
                    ->assertSee('Amaro');
        });
    }
}
