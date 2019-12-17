<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RssControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

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
