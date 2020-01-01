<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Subpage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Laravel\Scout\EngineManager
     */
    protected $engineManager;

    /**
     * @var \Laravel\Scout\Engines\Engine
     */
    protected $engine;

    public function testEnglishSearch()
    {
        $subpage = factory(Subpage::class)->create();
        $this->engine->method('get')->willReturn(Subpage::all());
        $this->getJson(sprintf('/api/search?q=%s&language=%s', $subpage->title, 'en'))->assertOk();
    }

    public function testThaiSearch()
    {
        $subpage = factory(Subpage::class)->create();
        $this->engine->method('get')->willReturn(Subpage::all());
        $this->getJson(sprintf('/api/search?q=%s&language=%s', $subpage->title, 'th'))->assertOk();
    }

    /**
     * Setup mock search engine.
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->engine = $this->createMock(Engine::class);
        $this->engineManager = $this->createMock(EngineManager::class);
        $this->engineManager->method('engine')->willReturn($this->engine);
    }
}
