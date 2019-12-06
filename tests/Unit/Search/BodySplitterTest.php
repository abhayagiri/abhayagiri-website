<?php

namespace Tests\Unit;

use App\Search\Splitters\BodySplitter;
use Tests\TestCase;

class BodySplitterTest extends TestCase
{
    public function testChunkText()
    {
        $bodyHtml = '<p>This is a test</p> <p>นี่คือการทดสอบ</p>';
        $this->assertEquals([
            '<p>This is a',
            ' test</p> <p',
            '>นี่คือการทด',
            'สอบ</p>',
        ], BodySplitter::chunkText($bodyHtml, 12));
    }
}
