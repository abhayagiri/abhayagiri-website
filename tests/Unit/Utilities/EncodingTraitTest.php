<?php

namespace Tests\Unit\Utilities;

use App\Utilities\EncodingTrait;
use Tests\TestCase;

class EncodingTraitTest extends TestCase
{
    use EncodingTrait;

    public function testEscapeLikeQueryText()
    {
        $this->assertEquals('fallen pine', $this->escapeLikeQueryText('fallen pine'));
        $this->assertEquals('sneaky\%sea\_rch', $this->escapeLikeQueryText('sneaky%sea_rch'));
    }

    public function testUrlEncodePath()
    {
        $this->assertEquals('si%2Bmp%2Ble', $this->urlEncodePath('si+mp+le'));
        $this->assertEquals('a/b%20c/d', $this->urlEncodePath('a/b c/d'));
    }
}
