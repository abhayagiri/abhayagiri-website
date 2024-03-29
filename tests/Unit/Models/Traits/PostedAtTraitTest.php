<?php

namespace Tests\Unit\Models\Traits;

use App\Models\Talk;
use App\Models\Traits\LocalDateTimeTrait;
use App\Models\Traits\PostedAtTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostedAtTraitTest extends TestCase
{
    use RefreshDatabase;

    public function testAccessors()
    {
        $m = new class() extends Model {
            use LocalDateTimeTrait;
            use PostedAtTrait;
        };
        $this->assertNull($m->posted_at);

        $m->posted_at = '2001-01-01 12:00:00';


        $this->assertEquals('2001-01-01 12:00:00', $m->posted_at->toDateTimeString());
    }

    public function testWasUpdatedAfterPosting()
    {
        $m = new class() extends Model {
            use PostedAtTrait;
            use LocalDateTimeTrait;
        };

        $minDate = new Carbon('2019-01-01T12:00:00Z');
        $this->assertFalse($m->wasUpdatedAfterPosting($minDate));

        $m->posted_at = new Carbon('2020-01-01T12:00:00Z');
        $this->assertFalse($m->wasUpdatedAfterPosting($minDate));

        $m->updated_at = new Carbon('2020-01-01T12:00:00Z');
        $this->assertFalse($m->wasUpdatedAfterPosting($minDate));

        $m->updated_at = new Carbon('2020-03-01T12:00:00Z');
        $this->assertTrue($m->wasUpdatedAfterPosting($minDate));

        $m->posted_at = new Carbon('2017-03-01T12:00:00Z');
        $this->assertFalse($m->wasUpdatedAfterPosting($minDate));
    }


    /** @test */
    public function stores_posted_at_on_utc()
    {
        $talk = factory(Talk::class)->create([
            'posted_at' => '2020-01-01 12:00:00', // PST
        ]);

        $this->assertEquals('2020-01-01 20:00:00', $talk->getRawOriginal('posted_at'));
    }

    /** @test */
    public function shows_posted_at_on_pst()
    {
        $talk = factory(Talk::class)->create([
              'posted_at' => '2020-01-01 12:00:00', // PST
          ]);

        $this->assertEquals('2020-01-01 12:00:00', $talk->posted_at);
    }
}
