<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Album;
use App\Models\Author;
use App\Models\Playlist;
use App\Models\Subject;
use App\Models\Talk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LanguagesTableSeeder;
use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAlbums()
    {
        $album = factory(Album::class)->create();

        $response = $this->get(route('api.albums'));
        $response
            ->assertOk()
            ->assertJsonCount(1, 'albums');

        $response = $this->get(route('api.album', $album->id));
        $response
            ->assertOk()
            ->assertJsonFragment(['titleEn' => $album->title_en]);
    }

    public function testAuthors()
    {
        $author = factory(Author::class)->create();

        $response = $this->get(route('api.authors'));
        $response->assertOk();

        $response = $this->get(route('api.author', $author->id));
        $response
            ->assertOk()
            ->assertJsonFragment(['titleEn' => $author->title_en]);
    }

    public function testPlaylists()
    {
        $playlist = factory(Playlist::class)->create();

        $response = $this->get(route('api.playlists'));
        $response->assertOk();

        $response = $this->get(route('api.playlist', $playlist->id));
        $response
            ->assertOk()
            ->assertJsonFragment(['titleEn' => $playlist->title_en]);
    }

    public function testPlaylistGroups()
    {
        // This will also create a PlaylistGroup
        $playlist = factory(Playlist::class)->create();

        $response = $this->get(route('api.playlist-groups'));
        $response->assertOk();

        $response = $this->get(route('api.playlist-group', $playlist->group->id));
        $response
            ->assertOk()
            ->assertJsonFragment(['titleEn' => $playlist->group->title_en]);

        $response = $this->get(route(
            'api.playlist-group.playlists',
            $playlist->group->id
        ));
        $response
            ->assertOk()
            ->assertJsonCount(1);
    }

    public function testSubjects()
    {
        $subject = factory(Subject::class)->create();

        $response = $this->get(route('api.subjects'));
        $response->assertOk();

        $response = $this->get(route('api.subject', $subject->id));
        $response
            ->assertOk()
            ->assertJsonFragment(['titleEn' => $subject->title_en]);
    }

    public function testSubjectGroups()
    {
        // This will also create a SubjectGroup
        $subject = factory(Subject::class)->create();

        $response = $this->get(route('api.subject-groups'));
        $response->assertOk();

        $response = $this->get(route('api.subject-group', $subject->group->id));
        $response
            ->assertOk()
            ->assertJsonFragment(['titleEn' => $subject->group->title_en]);

        $response = $this->get(route(
            'api.subject-group.subjects',
            $subject->group->id
        ));
        $response
            ->assertOk()
            ->assertJsonCount(1);
    }

    public function testTalks()
    {
        // TODO this should be part of the factory
        $this->seed(LanguagesTableSeeder::class);
        // This will also create an Author
        $talk = factory(Talk::class)->create([
            'description_en' => 'b1d51447785310120991174fefb81722eb606872'
        ]);

        $response = $this->get(route('api.talks'));
        $response->assertOk();

        $response = $this->get(route('api.talk', $talk->id));
        $response
            ->assertOk()
            ->assertJsonFragment(['titleEn' => $talk->title_en]);

        $response = $this->get(route('api.talks', ['authorId' => $talk->author->id]));
        $response
            ->assertOk()
            ->assertJsonCount(1, 'result');

        $response = $this->get(route('api.talks', ['searchText' => $talk->description_en]));
        $response
            ->assertOk()
            ->assertJsonCount(1, 'result');
    }
}
