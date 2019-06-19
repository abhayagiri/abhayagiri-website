<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BasicsTest extends DuskTestCase
{
    /**
     * Test home page.
     *
     * @return void
     */
    public function testHomePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->waitUntilLoaded()
                    ->assertSee('Abhayagiri');
        });
    }
}
