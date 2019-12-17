<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\ReflectionsPage;
use Tests\DuskBrowser as Browser;
use Tests\DuskTestCase;

class ReflectionsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testReflectionsInThai()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/th/reflections')
                    ->waitUntilLoaded()
                    ->on(new ReflectionsPage)
                    ->assertSee('แง่ธรรม')
                    ->assertSee('อาจารย์ ปสันโน');
        });
    }
}
