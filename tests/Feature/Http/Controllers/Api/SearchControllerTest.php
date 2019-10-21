<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Subpage;
use Algolia\ScoutExtended\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var \Laravel\Scout\Builder
     */
    protected $searchBuilder;

    public function testEnglishSearch()
    {
        $subpage = factory(Subpage::class)->create();
        $this->searchBuilder->method('get')->willReturn(Subpage::all());
        $this->getJson(sprintf('/api/search?q=%s&language=%s', $subpage->title, 'en'))->assertOk();
    }

    public function testThaiSearch()
    {
        $subpage = factory(Subpage::class)->create();
        $this->searchBuilder->method('get')->willReturn(Subpage::all());
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

        $this->searchBuilder = $this->createMock(Builder::class);
    }
}
