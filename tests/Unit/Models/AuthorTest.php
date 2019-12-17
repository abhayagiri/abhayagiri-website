<?php

namespace Tests\Unit;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function testSangha()
    {
        factory(Author::class)->create(['title_en' => 'Abhayagiri Sangha']);
        $this->assertEquals('Abhayagiri Sangha', Author::sangha()->title_en);
    }

}
