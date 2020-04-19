<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testImage()
    {
        $this->withoutImageCreation();
        $book = factory(Author::class)->create(['id' => 123]);
        $response = $this->get(route('authors.image', [$book, 'icon', 'webp']));
        $response->assertOk();
    }
}
