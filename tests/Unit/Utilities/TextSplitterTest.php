<?php

namespace Tests\Unit\Utilities;

use App\Utilities\TextSplitter;
use InvalidArgumentException;
use Tests\TestCase;

class TextSplitterTest extends TestCase
{
    public function testMaxEqualsToMin()
    {
        $this->expectException(InvalidArgumentException::class);
        new TextSplitter(0, 0);
    }

    public function testMaxLessThanMin()
    {
        $this->expectException(InvalidArgumentException::class);
        new TextSplitter(1, 3);
    }

    public function testMinBelowZero()
    {
        $this->expectException(InvalidArgumentException::class);
        new TextSplitter(4, -1);
    }

    public function testBisect()
    {
        $t = new TextSplitter(4, 2, false);
        $this->assertEquals(['sādh', 'āvuso'], $t->bisect('sādhāvuso'));
        $t = new TextSplitter(4, 2, true);
        $this->assertEquals(['sā', 'dhāvuso'], $t->bisect('sādhāvuso'));
    }

    protected $words = <<<EOT
        evaṃ me sutaṃ—ekaṃ samayaṃ
        bhagavā uruvelāyaṃ viharati
EOT;

    public function xtestSplitByWords()
    {
        $t = new TextSplitter(10, 4, false);
        $this->assertEquals([
            'evaṃ me',
            'sutaṃ—ekaṃ',
            'samayaṃ',
            'bhagavā',
            'uruvelāyaṃ',
            'viharati',
        ], $t->splitByWords($this->words));

        $t = new TextSplitter(10, 4, true);
        $this->assertEquals([
            'evaṃ',
            'me s',
            'utaṃ—ekaṃ',
            'samayaṃ',
            'bhagavā',
            'uruvelāyaṃ',
            'viharati',
        ], $t->splitByWords($this->words));

        $t = new TextSplitter(15, 8, false);
        $this->assertEquals([
            'evaṃ me sutaṃ—e',
            'kaṃ samayaṃ',
            'bhagavā uruvelā',
            'yaṃ viharati',
        ], $t->splitByWords($this->words));
        $this->assertEquals([
            'evaṃ me sutaṃ—e',
            'kaṃ samayaṃ',
            'bhagavā uruvelāyaṃ viharati',
        ], $t->splitByWords($this->words, 3));

        $t = new TextSplitter(15, 8, true);
        $this->assertEquals([
            'evaṃ me',
            'sutaṃ—ekaṃ',
            'samayaṃ',
            'bhagavā',
            'uruvelāyaṃ',
            'viharati',
        ], $t->splitByWords($this->words));
    }

    protected $paragraphs = <<<EOT
        Cakkhupālattheravatthu

        Manopubbaṅgamā dhammā,
        manoseṭṭhā manomayā;

        Manasā ce paduṭṭhena,
        bhāsati vā karoti vā;

        Tato naṃ dukkhamanveti,
        cakkaṃva vahato padaṃ.
EOT;

    public function testSplitByParagraphs()
    {
        $t = new TextSplitter(100, 10, false);
        $this->assertEquals([
            "Cakkhupālattheravatthu\n\n        " .
            "Manopubbaṅgamā dhammā,\n        manoseṭṭhā manomayā;",
            "Manasā ce paduṭṭhena,\n        bhāsati vā karoti vā;",
            "Tato naṃ dukkhamanveti,\n        cakkaṃva vahato padaṃ.",
        ], $t->splitByParagraphs($this->paragraphs));

        $t = new TextSplitter(100, 10, true);
        $this->assertEquals([
            'Cakkhupālattheravatthu',
            "Manopubbaṅgamā dhammā,\n        manoseṭṭhā manomayā;",
            "Manasā ce paduṭṭhena,\n        bhāsati vā karoti vā;",
            "Tato naṃ dukkhamanveti,\n        cakkaṃva vahato padaṃ.",
        ], $t->splitByParagraphs($this->paragraphs));
    }
}
