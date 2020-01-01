<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class ReflectionsPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/reflections';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->waitUntilLoaded()
                ->assertVisible('@reflectionsEntry');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@reflectionsEntry' => '.dataTable td',
        ];
    }
}
