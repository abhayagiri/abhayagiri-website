<?php

namespace Tests\Browser;

use Tests\Browser\Pages\ContactPage;
use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
                    ->assertVisible('@contactForm')
                    ->type('#name', 'John Doe')
                    ->type('#email', 'john@example.com')
                    ->type('#message', 'great work!')
                    ->click('@contactForm button[type="submit"]')
                    ->waitFor('.swal2-content')
                    ->assertSeeIn('.swal2-content', 'Please complete the captcha before sending your message');
        });
    }
}
