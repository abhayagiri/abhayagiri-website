<?php

namespace App\Legacy;

class RedirectException extends \Exception
{

    /**
     * Redirect to this URL
     *
     * @var string
     */
    public $url;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        parent::__construct();
        $this->url = $url;
    }
}

