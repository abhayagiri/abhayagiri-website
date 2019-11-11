<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Subpage;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchControllerTest extends TestCase
{
    use DatabaseTransactions;

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
     * "Refresh" the database by deleting everything.
     *
     * TODO: Use RefreshDatabase instead of DatabaseTransaction/this method.
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();
        DB::table('subpages')->delete();

        $this->engine = $this->createMock(Engine::class);
        $this->engineManager = $this->createMock(EngineManager::class);
        $this->engineManager->method('engine')->willReturn($this->engine);
    }
}