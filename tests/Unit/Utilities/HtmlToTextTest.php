<?php

namespace Tests\Unit\Utilities;

use App\Utilities\HtmlToText;
use Tests\TestCase;

class HtmlToTextTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testBasics($html, $defaultExpected, $originalExpected)
    {
        $originalOptions = [
            'do_links' => 'inline',
            'markdown' => true,
            'uppercase' => true,
            'width' => 70,
        ];
        $this->assertEquals($defaultExpected, HtmlToText::toText($html));
        $this->assertEquals($originalExpected, HtmlToText::toText(
            $html,
            $originalOptions
        ));
    }

    public function dataProvider()
    {
        return [
            'Readme usage' => [
                'html'             => 'Hello, &quot;<b>world</b>&quot;',
                'defaulExpected'   => 'Hello, "world"',
                'originalExpected' => 'Hello, "WORLD"',
            ],
            'Unordered list' => [
                'html'             => '<ul><li>Item 1</li><li>Item ' .
                                      '2</li><li>Item 3</li></ul>',
                'defaultExpected'  => "Item 1\nItem 2\nItem 3",
                'originalExpected' => <<<'EOT'
	* Item 1
	* Item 2
	* Item 3


EOT
            ],
            'Mixed example' => [
                'html' => <<<'EOT'
<p>The term <strong>&#8220;upāsikā&#8221; means &#8220;one who sits close by.&#8221;</strong></p>
<h3>Typical Upāsikā Day Schedule</h3>
<ul><li>10:30 am - Refuges and Precepts</li>
<li>10:45 am [11 am DST] - Meal offering</li></ul>
<p>Here are <a href="/visiting/directions" title="directions">directions</a> to the monastery.</p>
EOT
                , 'expected' => <<<'EOT'
The term “upāsikā” means “one who sits close by.”

Typical Upāsikā Day Schedule

10:30 am - Refuges and Precepts
10:45 am [11 am DST] - Meal offering

Here are directions to the monastery.
EOT
                , 'originalExpected' => <<<'EOT'
The term “UPĀSIKĀ” MEANS “ONE WHO SITS CLOSE BY.”

TYPICAL UPĀSIKĀ DAY SCHEDULE

	* 10:30 am - Refuges and Precepts
 	* 10:45 am [11 am DST] - Meal offering

Here are directions [/visiting/directions] to the monastery.

EOT
            ],
        ];
    }
}
