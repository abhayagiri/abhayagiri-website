<?php

namespace Tests\Unit;

use App\Markdown;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MarkdownTest extends TestCase
{
    public static $commonEmbedHtml = '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" width="560" height="315" src="//www.youtube.com/embed/wg-cx9dTikE" frameborder="0" allowfullscreen></iframe></div>';

    public function testEmbedMacroBasics()
    {
        $md = '[!embed](https://youtu.be/wg-cx9dTikE)';
        $html = Markdown::toHtml($md);
        $this->assertEquals(self::$commonEmbedHtml, $html);
        $md = '[!embed](https://www.youtube.com/watch?v=wg-cx9dTikE)';
        $html = Markdown::toHtml($md);
        $this->assertEquals(self::$commonEmbedHtml, $html);
    }

    public function testEmbedMacroMultipleLines()
    {
        $md = "a\n\n[!embed](https://youtu.be/wg-cx9dTikE)\n\nb";
        $html = Markdown::toHtml($md);
        $this->assertEquals("<p>a</p>\n" . self::$commonEmbedHtml . "\n<p>b</p>", $html);
    }

    public function testEmbedMacroInline()
    {
        $md = 'a [!embed](https://youtu.be/wg-cx9dTikE) b';
        $html = Markdown::toHtml($md);
        $this->assertEquals('<p>a <a href="https://youtu.be/wg-cx9dTikE">!embed</a> b</p>', $html);
    }

    public function testResidentsMacro()
    {
        $md = '[!residents]';
        $html = Markdown::toHtml($md);
        $this->assertStringContainsString('Ajahn Kassapo', $html);
    }

    public function testSingleResidentMacro()
    {
        $md = '[!resident pasanno]';
        $html = Markdown::toHtml($md);
        $this->assertStringContainsString('Ajahn Pasanno', $html);
        $this->assertStringNotContainsString('Ajahn Kasappo', $html);
    }

    public function testNonExistentResidentMacro()
    {
        $md = '[!resident a&b]';
        $html = Markdown::toHtml($md);
        $this->assertEquals('<div><p>No such resident: a&amp;b</p></div>', $html);
    }

    public function testGalleryEmbedMacro()
    {
        $md = '[!embed](/gallery/71)';
        $html = Markdown::toHtml($md);
        $this->assertStringContainsString('https://gallery.abhayagiri.org/', $html);
    }

    public function testDanaListMacro()
    {
        $md = '[!danalist]';
        $html = Markdown::toHtml($md);
        $this->assertStringContainsString('<table', $html);
    }

    public function testCleanInternalLinks()
    {
        $markdown =
            'a [1](https://www.abhayagiri.org/news) ' .
            'b [2](https://gallery.abhayagiri.org/news)' .
            'c [3](https://www.google.com/news) ' .
            'd [4](https://abhayagiri.sfo2.cdn.digitaloceanspaces.com/media/rituals.jpg)';
        $this->assertEquals(
            'a [1](/news) ' .
            'b [2](https://gallery.abhayagiri.org/news)' .
            'c [3](https://www.google.com/news) ' .
            'd [4](/media/rituals.jpg)',
            Markdown::cleanInternalLinks($markdown));
    }

    public function testExpandMediaLinks()
    {
        Config::shouldReceive('get')->with('filesystems.disks.spaces.url')
                                    ->andReturn('https://abhayagiri.sfo2.cdn.digitaloceanspaces.com');
        $markdown =
            'a [1](/news) ' .
            'b [2](https://gallery.abhayagiri.org/news)' .
            'c [3](https://www.google.com/news) ' .
            'd [4](/media/rituals.jpg)';
        $this->assertEquals(
            'a [1](/news) ' .
            'b [2](https://gallery.abhayagiri.org/news)' .
            'c [3](https://www.google.com/news) ' .
            'd [4](https://abhayagiri.sfo2.cdn.digitaloceanspaces.com/media/rituals.jpg)',
            Markdown::expandMediaLinks($markdown));
    }
}
