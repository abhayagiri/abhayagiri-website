<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubPageTest extends DuskTestCase
{
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
