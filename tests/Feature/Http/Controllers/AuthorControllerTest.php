<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testImage()
    {
        $this->withoutImageCreation();
        $author = factory(Author::class)->create(['id' => 123]);
        $response = $this->get(route('authors.image', [$author, 'icon', 'webp']));
        $response->assertOk();
    }
}
