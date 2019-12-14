<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class RssControllerTest extends TestCase
{
    public function testAudioRss()
    {
        $response = $this->get('/audio.rss');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri Audio');

        $response = $this->get('/rss/audio.php');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri Audio');
    }

    public function testNewsRss()
    {
        $response = $this->get('/news.rss');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri News');

        $response = $this->get('/rss/news.php');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri News');
    }

    public function testReflectionsRss()
    {
        $response = $this->get('/reflections.rss');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri Reflections');

        $response = $this->get('/rss/reflections.php');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri Reflections');
    }
}
