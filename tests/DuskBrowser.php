<?php

namespace Tests;

use Laravel\Dusk\Browser;

class DuskBrowser extends Browser
{

    /**
     * Wait until the page finishes loads.
     *
     * This hooks into a global Javascript function window.isLoading to
     * determine when any AJAX requests and DOM are properly completed.
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
