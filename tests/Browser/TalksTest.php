<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TalksTest extends DuskTestCase
{
    /**
     * Test latest talks.
     *
     * @return void
     */
    public function testLatestTalksPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/home')
                    ->waitUntilLoaded()
                    ->click('#btn-menu')
                    ->click('#btn-talks')
                    ->waitFor('.latest-talks', 10)
                    ->assertSee('Dhamma Talks')
                    ->assertSee('Play');
        });
    }

    /**
     * Test collection cards on phones (small displays).
     *
     * @return void
     */
    public function testCollectionCardsOnPhones()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(600, 400)
                    ->visit('/talks/collections')
                    ->waitFor('.category-list', 10)
                    ->assertVisible('.card');
        });
    }

    /**
     * Test old collections redirects.
     *
     * @return void
     */
    public function testOldCollectionsRedirects()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/talks/6483-20th-anniversary-compilation')
                    ->waitFor('.talks', 10)
                    ->assertVisible('.talks');
            $browser->visit('/th/audio/2015-thanksgiving-monastic-retreat-entire')
                    ->waitFor('.talks', 10)
                    ->assertVisible('.talks');
        });
    }

    /**
     * Test single talk.
     *
     * @return void
     */
    public function testSingleTalk()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/talks/6832')
                    ->waitFor('.talk', 10)
                    ->assertVisible('.talk');
        });
    }

    /**
     * Test nonexistent single talk.
     *
     * @return void
     */
    public function testNonExistentSingleTalk()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/talks/xyz')
                    ->waitUntilLoaded()
                    ->assertSee('could not be found');
        });
    }

    /**
     * Test new talks redirects.
     *
     * @return void
     */
    public function testNewTalksRedirects()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/new/talks')
                    ->waitFor('.latest-talks', 10)
                    ->assertVisible('.latest-talks')
                    ->assertPathIs('/talks');
            $browser->visit('/new/th/talks')
                    ->waitFor('.latest-talks', 10)
                    ->assertVisible('.latest-talks')
                    ->assertPathIs('/th/talks');
        });
    }
}
