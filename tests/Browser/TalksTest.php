<?php

namespace Tests\Browser;

use Tests\Browser\Pages\TalksPage;
use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;

class TalksTest extends DuskTestCase
{
    public function testLatestTalksPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new TalksPage)
                    ->waitUntilLoaded()
                    ->assertVisible('@latestTalks')
                    ->assertSee('Latest Dhamma Talks');
        });
    }

    public function testCollectionCardsOnPhones()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(600, 400)
                    ->visit('/talks/collections')
                    ->waitUntilLoaded()
                    ->on(new TalksPage)
                    ->assertVisible('@collectionCard');
        });
    }

    public function testOldCollectionRedirects()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/talks/6483-20th-anniversary-compilation')
                    ->waitUntilLoaded()
                    ->on(new TalksPage)
                    ->assertVisible('@talkList');
        });

        $this->browse(function (Browser $browser) {
            $browser->visit('/th/audio/2015-thanksgiving-monastic-retreat-entire')
                    ->waitUntilLoaded()
                    ->on(new TalksPage)
                    ->assertVisible('@talkList');
        });
    }

    public function testSingleTalk()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/talks/6832')
                    ->waitUntilLoaded()
                    ->on(new TalksPage)
                    ->assertVisible('@singleTalk');
        });
    }

    public function testNonExistentSingleTalk()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/talks/xyz')
                    ->waitUntilLoaded()
                    ->assertSee('could not be found');
        });
    }

    public function testNewTalksRedirectEnglish()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/new/talks')
                    ->waitUntilLoaded()
                    ->on(new TalksPage)
                    ->assertVisible('@latestTalks')
                    ->assertPathIs('/talks');
        });
    }

    public function testNewTalksRedirectThai()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/new/th/talks')
                    ->waitUntilLoaded()
                    ->on(new TalksPage)
                    ->assertVisible('@latestTalks')
                    ->assertPathIs('/th/talks');
        });
    }
}
