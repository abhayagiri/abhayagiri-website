<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Foundation\Testing\DatabaseTransactions;
#use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AlbumTest extends TestCase
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
        DB::table('album_photo')->delete();
        DB::table('albums')->delete();
        DB::table('photos')->delete();
    }

    /**
     * Test photo ordering.
     *
     * @return void
     */
    public function testPhotoOrdering() : void
    {
        $album = factory(Album::class)->create();
        $photos = factory(Photo::class, 3)->create();
        $album->photos()->sync([
            $photos[0]->id => ['rank' => 1],
            $photos[1]->id => ['rank' => 2],
            $photos[2]->id => ['rank' => 3],
        ]);
        $this->assertEquals([$photos[0]->id, $photos[1]->id, $photos[2]->id],
                            $album->photos()->pluck('id')->toArray());
        $album->photos()->sync([
            $photos[0]->id => ['rank' => 2],
            $photos[1]->id => ['rank' => 3],
            $photos[2]->id => ['rank' => 1],
        ]);
        $this->assertEquals([$photos[2]->id, $photos[0]->id, $photos[1]->id],
                            $album->photos()->pluck('id')->toArray());
    }
}
