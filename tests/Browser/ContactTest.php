<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\ContactPage;
use Tests\DuskBrowser as Browser;
use Tests\DuskTestCase;

class ContactTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testNoContactForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ContactPage)
                ->waitUntilLoaded()
                ->assertVisible('@contactOptions')
                ->click('@contactOptions a[href="/contact/subscribe-to-our-email-lists"]')
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
                ->click('@contactOptions a[href="/contact/request-an-overnight-stay"]')
                ->waitUntilLoaded()
                ->assertVisible('@contactForm');
        });
    }
}
