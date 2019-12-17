<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskBrowser as Browser;
use Tests\DuskTestCase;

class SubpagesTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testResidentsPageEnglish()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/community')
                    ->waitUntilLoaded()
                    ->assertSee('Community')
                    ->assertSee('Residents')
                    ->assertSee('Ajahn Pasanno');
        });
    }

    public function testResidentsPageThai()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/th/community')
                    ->waitUntilLoaded()
                    ->assertSee('หมู่คณะ')
                    ->assertSee('พระภิกษุสงฆ์ นักบวชและอุบาสิกา')
                    ->assertSee('หลวงพ่อ ปสนฺโน');
        });
    }
}
