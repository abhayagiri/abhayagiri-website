<?php

namespace Tests\Feature;

use App\Models\Subpage;
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

        $response = $this->get('/subpages/123');
        $response->assertStatus(200);

        $response = $this->get('/subpages/456');
        $response->assertStatus(404);
    }

    /**
     * Test draft.
     *
     * @return void
     */
    public function testDraft() : void
    {
        $subpage = factory(Subpage::class)->states('draft')->create(['id' => 123]);

        $response = $this->get('/subpages/123');
        $response->assertStatus(404);
    }

    /**
     * Test show by path.
     *
     * @return void
     */
    public function testShowByPath()
    {
        $subpage = factory(Subpage::class)->create(['page' => 'about', 'subpath' => 'us']);

        // TODO remove /a testing route prefix
        $response = $this->get('/a/about/us');
        $response->assertStatus(200);

        $response = $this->get('/a/about');
        $response->assertStatus(200);

        $response = $this->get('/th/a/about/us');
        $response->assertStatus(200);

        $response = $this->get('/a/about/us/then');
        $response->assertStatus(404);

        $response = $this->get('/a/about/now');
        $response->assertStatus(404);

        $response = $this->get('/th/a/here/now');
        $response->assertStatus(404);
    }
}
