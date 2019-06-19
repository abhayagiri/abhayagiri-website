<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class BooksPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/books';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->waitUntilLoaded()
                ->assertVisible('@bookEntry');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@bookEntry' => '.books',
        ];
    }
}
