<?php

namespace Tests\Unit\Models\Traits;

use App\Models\Traits\LocalDateTimeTrait;
use App\Models\Traits\PostedAtTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class PostedAtTraitTest extends TestCase
{
    public function testAccessors()
    {
        $m = new class() extends Model {
            use LocalDateTimeTrait;
            use PostedAtTrait;
        };
        $this->assertNull($m->posted_at);
        $this->assertNull($m->local_posted_at);
        $m->local_posted_at = '2001-01-01 12:00:00';
        $this->assertEquals('2001-01-01 12:00:00', $m->local_posted_at);
        $this->assertEquals('2001-01-01 20:00:00', $m->posted_at);
        $m->local_posted_at = null;
        $this->assertNull($m->posted_at);
        $this->assertNull($m->local_posted_at);
    }

    public function testWasUpdatedAfterPosting()
    {
        $m = new class() extends Model {
            use PostedAtTrait;
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
}
