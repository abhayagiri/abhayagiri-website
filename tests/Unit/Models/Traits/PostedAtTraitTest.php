<?php

namespace Tests\Unit\Models\Traits;

use App\Models\Traits\PostedAtTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class PostedAtTraitTest extends TestCase
{
    public function testWasUpdatedAfterPosting()
    {
        $m = new class() extends Model {
            use PostedAtTrait;
        };
        $this->assertFalse($m->wasUpdatedAfterPosting());
        $m->posted_at = new Carbon('2020-01-01T12:00:00Z');
        $this->assertFalse($m->wasUpdatedAfterPosting());
        $m->updated_at = new Carbon('2020-01-01T12:00:00Z');
        $this->assertFalse($m->wasUpdatedAfterPosting());
        $m->updated_at = new Carbon('2020-03-01T12:00:00Z');
        $this->assertTrue($m->wasUpdatedAfterPosting());
        $m->posted_at = new Carbon('2017-03-01T12:00:00Z');
        $this->assertFalse($m->wasUpdatedAfterPosting());
    }
}
