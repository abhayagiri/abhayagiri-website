<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Talk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TalkCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->actingAsAdmin()
                         ->get(route('admin.talks.index'));
        $response
            ->assertOk()
            ->assertSee('Add talk');
    }

    public function testSearch()
    {
        $response = $this->actingAsAdmin()
                         ->postJson(
                             route('admin.talks.search'),
                             ['length' => 10]
                         );
        $response
            ->assertOk()
            ->assertJsonCount(4);
    }

    /**
     * @test
     */
    public function it_validates_unique_youtube_video_id_when_using_full_url()
    {
        $talk = factory(Talk::class)->create([
            'youtube_video_id' => 'abcdefghijk',
            'deleted_at' => now()->subMinute(),
        ]);

        $this->assertSoftDeleted($talk);

        $this->actingAsAdmin()
            ->post('/admin/talks', [
                'title_en'  => 'my talk',
                'author_id' => 'author-id',
                'language_id'   => 'en',
                'playlists' => ['1', '2'],
                'youtube_video_id'  => 'https://www.youtube.com/watch?v=abcdefghijk',
                'recorded_on'   => now()->toDateString(),
                'local_posted_at' => now()->toDateString(),
            ])
            ->assertSessionHasErrors([
                'youtube_video_id' => 'The youtube video id has already been taken.',
            ]);
    }

    /**
     * @test
     */
    public function it_validates_unique_youtube_video_id_when_using_id()
    {
        $talk = factory(Talk::class)->create([
            'youtube_video_id' => 'abcdefghijk',
            'deleted_at' => now()->subMinute(),
        ]);

        $this->assertSoftDeleted($talk);

        $this->actingAsAdmin()
            ->post('/admin/talks', [
                'title_en'  => 'my talk',
                'author_id' => 'author-id',
                'language_id'   => 'en',
                'playlists' => ['1', '2'],
                'youtube_video_id'  => 'abcdefghijk',
                'recorded_on'   => now()->toDateString(),
                'local_posted_at' => now()->toDateString(),
            ])
            ->assertSessionHasErrors([
                'youtube_video_id' => 'The youtube video id has already been taken.',
            ]);
    }
}
