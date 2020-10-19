<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testImage()
    {
        $this->withoutImageCreation();
        $book = factory(Book::class)->create(['id' => 123]);
        $response = $this->get(route('books.image', [$book, 'icon', 'webp']));
        $response->assertOk();
    }

    public function testIndex()
    {
        $response = $this->get(route('books.index'));
        $response->assertOk()
                 ->assertSee('Books')
                 ->assertSee('Author')
                 ->assertSee('Language')
                 ->assertSee('Availability');

        $response = $this->get(route('th.books.index'));
        $response->assertOk()
                 ->assertSee('หนังสือ')
                 ->assertSee('ผู้เขียน')
                 ->assertSee('ภาษา')
                 ->assertSee('ความพร้อมใช้งาน');
    }

    public function testShow()
    {
        $book = factory(Book::class)->create(['id' => 123]);

        $response = $this->get(route('books.show', $book));
        $response->assertOk()
                 ->assertSee($book->title);

        $response = $this->get(route('th.books.show', $book));
        $response->assertOk()
                 ->assertSee($book->title);
    }
}
