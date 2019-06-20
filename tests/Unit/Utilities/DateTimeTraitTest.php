<?php

namespace Tests\Unit\Utilities;

use App\Utilities\DateTimeTrait;
use Tests\TestCase;

class DateTimeTraitTest extends TestCase
{
    use DateTimeTrait;

    public function testIso8601DurationToSeconds()
    {
        $this->assertEquals(static::iso8601DurationToSeconds('PT15M33S'), 933);
        $this->assertEquals(static::iso8601DurationToSeconds('PT2H59M2S'), 10742);
        $this->assertNull(static::iso8601DurationToSeconds('P3Y6M4DT12H30M5S'));
        $this->assertNull(static::iso8601DurationToSeconds('bad'));
        $this->assertNull(static::iso8601DurationToSeconds(null));
    }
}
