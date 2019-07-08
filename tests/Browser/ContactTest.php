<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;
use Tests\Browser\Pages\ContactPage;

class ContactTest extends DuskTestCase
{
    public function testNoContactForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ContactPage)
                ->waitUntilLoaded()
                ->assertVisible('@contactOptions')
                ->click('@contactOptions a[href="/contact/get-information-about-requesting-a-book"]')
                ->waitUntilLoaded()
                ->assertMissing('@contactForm');
        });
    }

    public function testWithContactForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ContactPage)
                ->waitUntilLoaded()
                ->assertVisible('@contactOptions')
                ->click('@contactOptions a[href="/contact/other-questions"]')
                ->waitUntilLoaded()
                ->assertVisible('@contactForm');
        });
    }
}
