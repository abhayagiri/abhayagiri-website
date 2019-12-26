<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class ContactPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/contact';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->waitUntilLoaded()
                ->assertVisible('@contactContainer');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@contactContainer' => '#main .contact',
            '@contactForm' => 'form.contact-form',
            '@contactOptions' => '.contact .contact-options',
        ];
    }
}
