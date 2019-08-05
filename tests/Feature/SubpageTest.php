<?php

namespace Tests\Feature;

use App\Models\Subpage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;
#use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubpageTest extends TestCase
{
    use DatabaseTransactions;

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
    }

    /**
     * Test show by id.
     *
     * @return void
     */
    public function testShowById() : void
    {
        $subpage = factory(Subpage::class)->create(['id' => 123]);

        $response = $this->get(route('en.subpages.show', $subpage));
        $response->assertOk();

        $response = $this->get(route('th.subpages.show', $subpage));
        $response->assertOk();

        $response = $this->get(route('en.subpages.show', 456));
        $response->assertNotFound();

        $response = $this->get(route('th.subpages.show', 456));
        $response->assertNotFound();
    }

    /**
     * Test draft.
     *
     * @return void
     */
    public function testDraft() : void
    {
        $subpage = factory(Subpage::class)->states('draft')->create(['id' => 123]);

        $response = $this->get(route('en.subpages.show', $subpage));
        $response->assertForbidden();

        $subpage->draft = false;
        $subpage->save();
        $response = $this->get(route('en.subpages.show', $subpage));
        $response->assertOk();

        $subpage->posted_at = Carbon::now()->addDay();
        $subpage->save();
        $response = $this->get(route('en.subpages.show', $subpage));
        $response->assertForbidden();
    }

    /**
     * Test show by path.
     *
     * @return void
     */
    public function testShowByPath()
    {
        $subpage = factory(Subpage::class)->create(['page' => 'about', 'subpath' => 'us']);

        $response = $this->get(route('en.subpages.path', 'about/us'));
        $response->assertOk();

        $response = $this->get(route('en.subpages.path', 'about'));
        $response->assertOk();

        $response = $this->get(route('th.subpages.path', 'about/us'));
        $response->assertOk();

        $response = $this->get(route('en.subpages.path', 'about/us/then'));
        $response->assertNotFound();

        $response = $this->get(route('en.subpages.path', 'about/now'));
        $response->assertNotFound();

        $response = $this->get(route('th.subpages.path', 'about/now'));
        $response->assertNotFound();

        $subpage->draft = true;
        $subpage->save();
        $response = $this->get(route('en.subpages.path', 'about/us'));
        $response->assertForbidden();
    }
}
