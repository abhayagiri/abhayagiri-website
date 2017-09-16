<?php

namespace App\Models;

class BookTest extends \PHPUnit_Framework_TestCase
{
    public function testSetSlug()
    {
        $book = new Book();
        $this->assertNull($book->title);
        $this->assertNull($book->alt_title_en);
        $this->assertNull($book->slug);

        $book->title = 'Fred! ';
        $book->setSlug();
        $this->assertEquals('Fred! ', $book->title);
        $this->assertEquals('fred', $book->slug);

        $book->alt_title_en = 'A translation by fred';
        $book->setSlug();
        $this->assertEquals('A translation by fred', $book->alt_title_en);
        $this->assertEquals('a-translation-by-fred', $book->slug);

        $book->title = 'มรรคมีองค์แปด';
        $book->setSlug();
        $this->assertEquals('a-translation-by-fred', $book->slug);

        $book->alt_title_en = '';
        $book->setSlug();
        $this->assertEquals('unknown', $book->slug);
   }

    public function testPdfUrl()
    {
        // See https://stackoverflow.com/questions/996139/urlencode-vs-rawurlencode
        $book = Book::where('slug', 'the-contemplatives-craft')->firstOrFail();
        $this->assertEquals('/media/books/The%20Contemplative%27s%20Craft.pdf',
            $book->pdf_url);
    }
}
