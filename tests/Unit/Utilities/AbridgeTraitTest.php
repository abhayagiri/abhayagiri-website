<?php

namespace Tests\Unit\Utilities;

use App\Utilities\AbridgeTrait;
use Tests\TestCase;

class AbridgeTraitTest extends TestCase
{
    use AbridgeTrait;

    public function testBasics()
    {
        $html = <<<'EOT'

 <a href="/a">123 
four
</a> 

5 <a href="/b">
six 7</a> 89 


EOT;
        $this->assertEquals('<a href="/a">1&hellip;</a>', static::abridge($html, 0));
        $this->assertEquals('<a href="/a">1&hellip;</a>', static::abridge($html, 1));
        $this->assertEquals('<a href="/a">123 four&hellip;</a>', static::abridge($html, 8));
        $this->assertEquals('<a href="/a">123 four </a>&hellip;', static::abridge($html, 9));
        $this->assertEquals('<a href="/a">123 four </a>&hellip;', static::abridge($html, 10));
        $this->assertEquals('<a href="/a">123 four </a> 5&hellip;', static::abridge($html, 11));
        $this->assertEquals('<a href="/a">123 four </a> 5&hellip;', static::abridge($html, 12));
        $this->assertEquals('<a href="/a">123 four </a> 5 <a href="/b"> &hellip;</a>', static::abridge($html, 13));
        $this->assertEquals('<a href="/a">123 four </a> 5 <a href="/b"> six 7</a> 8&hellip;', static::abridge($html, 20));
        $this->assertEquals('<a href="/a">123 four </a> 5 <a href="/b"> six 7</a> 89', static::abridge($html, 21));
        $this->assertEquals('<a href="/a">123 four </a> 5 <a href="/b"> six 7</a> 89', static::abridge($html, 22));
    }

    public function testEmptyLinks()
    {
        $html = <<<'EOT'
<p><a href="/a"><em>xy</em></a>z</p>
<p><a href="/c"><img src="/d"></a></p>
<p>end</p>
EOT;
        $this->assertEquals('<a href="/a">xy</a>&hellip;', static::abridge($html, 2));
        $this->assertEquals('<a href="/a">xy</a>z&hellip;', static::abridge($html, 3));
        $this->assertEquals('<a href="/a">xy</a>z&hellip;', static::abridge($html, 4));
        $this->assertEquals('<a href="/a">xy</a>z e&hellip;', static::abridge($html, 5));
        $this->assertEquals('<a href="/a">xy</a>z end', static::abridge($html, 60));
    }
}
