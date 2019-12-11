<?php

namespace Tests\Feature;

use App\Models\Danalist;
use App\Models\Subpage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SubpageControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resetTable('subpages');
    }

    public function testDraft()
    {
        $subpage = factory(Subpage::class)->states('draft')->create(['id' => 123]);

        $response = $this->get(route('subpages.show', $subpage));
        $response->assertForbidden();

        $subpage->draft = false;
        $subpage->save();
        $response = $this->get(route('subpages.show', $subpage));
        $response->assertOk();

        $subpage->posted_at = now()->addDay();
        $subpage->save();
        $response = $this->get(route('subpages.show', $subpage));
        $response->assertForbidden();
    }

    public function testShowById()
    {
        $subpage = factory(Subpage::class)->create(['id' => 123]);

        $response = $this->get(route('subpages.show', $subpage));
        $response->assertOk();

        $response = $this->get(route('th.subpages.show', $subpage));
        $response->assertOk();

        $response = $this->get(route('subpages.show', 456));
        $response->assertNotFound();

        $response = $this->get(route('th.subpages.show', 456));
        $response->assertNotFound();
    }

    public function testShowByPath()
    {
        $subpage = factory(Subpage::class)->create(['page' => 'about', 'subpath' => 'us']);

        $response = $this->get(route('subpages.path', 'about/us'));
        $response->assertOk();

        $response = $this->get(route('subpages.path', 'about'));
        $response->assertOk();

        $response = $this->get(route('th.subpages.path', 'about/us'));
        $response->assertOk();

        $response = $this->get(route('subpages.path', 'about/us/then'));
        $response->assertNotFound();

        $response = $this->get(route('subpages.path', 'about/now'));
        $response->assertNotFound();

        $response = $this->get(route('th.subpages.path', 'about/now'));
        $response->assertNotFound();

        $subpage->draft = true;
        $subpage->save();
        $response = $this->get(route('subpages.path', 'about/us'));
        $response->assertForbidden();
    }

    public function testDanalistMacro()
    {
        $subpage = factory(Subpage::class)->create([
            'page' => 'dana',
            'subpath' => 'list',
            'body_en' => '[!danalist]',
        ]);

        $response = $this->get(route('subpages.path', 'dana/list'));
        $response
            ->assertOk()
            ->assertDontSee('Yoyo');

        DB::table('danalist')->delete();
        factory(Danalist::class)->create(['title' => 'Yoyo']);

        $response = $this->get(route('subpages.path', 'dana/list'));
        $response
            ->assertOk()
            ->assertSee('Yoyo');
    }
}
