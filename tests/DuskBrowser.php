<?php

namespace Tests;

use Laravel\Dusk\Browser;

class DuskBrowser extends Browser
{

    /**
     * Wait until the page finishes loads for legacy pages.
     *
     * This hooks into a global Javascript function window.isLoading to
     * determine when any AJAX requests and DOM are properly completed.
     *
     * IMPORTANT: This currently only works for legacy pages.
     *
     * @param  int  $timeout
     *
     * @return $this
     */
    public function waitUntilLoaded($timeout = 30)
    {
        return $this->waitUntil('window.isLoading && !window.isLoading()', $timeout);
    }
}
