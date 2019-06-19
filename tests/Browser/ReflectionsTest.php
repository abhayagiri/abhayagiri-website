<?php

namespace Tests\Browser;

use Tests\Browser\Pages\ReflectionsPage;
use Tests\DuskTestCase;
use Tests\DuskBrowser as Browser;

class ReflectionsTest extends DuskTestCase
{
    public function testReflectionsInThai()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/th/reflections')
                    ->waitUntilLoaded()
                    ->on(new ReflectionsPage)
                    ->assertSee('แง่ธรรม')
                    ->assertSee('อ่านต่อ')
                    ->click('.dataTable > tbody > tr:nth-child(2) .btn')
                    ->waitUntilLoaded()
                    ->assertSee('กลับสู่ด้านบน');
        });
    }
}
