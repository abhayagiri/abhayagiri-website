<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\DatabaseTransactions;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LinkRedirectControllerTest extends TestCase
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
        DB::table('redirects')->delete();
    }

    public function testRedirect()
    {
        $response = $this->get('/somewhere/unknown');
        $response->assertNotFound();

        factory(Redirect::class)->create([
            'from' => 'somewhere/unknown',
            'to' => json_encode(['type' => 'Basic', 'url' => 'about']),
        ]);

        $response = $this->get('/somewhere/unknown');
        $response->assertRedirect(url('about'));
    }
}
