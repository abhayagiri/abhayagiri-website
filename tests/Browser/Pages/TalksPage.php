<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class TalksPage extends Page
{

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/talks';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->waitUntilLoaded()
                ->assertVisible('@talksContainer');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@talksContainer' => '.talks-container',
            '@latestTalks' => '.talks-container .latest-talks',
            '@singleTalk' => '.talks-container .talk',
            '@talkList' => '.talks-container .talk-list',
            '@collectionCard' => '.card',
        ];
    }
}
