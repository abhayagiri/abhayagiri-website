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
        $this->assertEquals('Fred! ', $book->title);
        $this->assertEquals('fred', $book->slug);

        $book->alt_title_en = 'A translation by fred';
        $this->assertEquals('A translation by fred', $book->alt_title_en);
        $this->assertEquals('a-translation-by-fred', $book->slug);

        $book->title = 'มรรคมีองค์แปด';
        $this->assertEquals('a-translation-by-fred', $book->slug);

        $book->alt_title_en = '';
        $this->assertEquals('unknown', $book->slug);
   }
}
