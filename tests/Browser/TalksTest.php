<?php

namespace Tests\Browser;

use App\Models\Playlist;
use App\Models\Redirect;
use App\Models\Talk;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\TalksPage;
use Tests\DuskBrowser as Browser;
use Tests\DuskTestCase;

class TalksTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

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

    public function testOldCollectionRedirectsEnglish()
    {
        factory(Redirect::class)->create([
            'from' => 'talks/6483-20th-anniversary-compilation',
            'to' => json_encode([
                'type' => 'Playlist',
                'id' => 14,
                'lng' => 'en'
            ])
        ]);
        factory(Playlist::class)->create([
            'id' => 14,
            'title_en' => '20th Anniversary Compilation',
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/talks/6483-20th-anniversary-compilation')
                    ->waitUntilLoaded()
                    ->on(new TalksPage)
                    ->assertVisible('@talkList');
        });
    }

    public function testOldCollectionRedirectsThai()
    {
        factory(Redirect::class)->create([
            'from' => 'th/audio/2015-thanksgiving-monastic-retreat-entire',
            'to' => json_encode([
                'type' => 'Playlist',
                'id' => 13,
                'lng' => 'en'
            ])
        ]);
        factory(Playlist::class)->create([
            'id' => 13,
            'title_en' => '2015 Thanksgiving Monastic Retreat',
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/th/audio/2015-thanksgiving-monastic-retreat-entire')
                    ->waitUntilLoaded()
                    ->on(new TalksPage)
                    ->assertVisible('@talkList');
        });
    }

    public function testSingleTalk()
    {
        factory(Talk::class)->create([
            'id' => 6832,
            'title_en' => 'The Way Things Should Be',
        ]);
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
                    ->assertSee('Not Found');
        });
    }
}
