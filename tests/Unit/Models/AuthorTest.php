<?php

namespace Tests\Unit;

use App\Models\Author;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use DatabaseTransactions;

    public function testSangha()
    {
        DB::table('talks')->delete(); // TODO ick!
        DB::table('books')->delete();
        DB::table('authors')->delete();
        factory(Author::class)->create(['title_en' => 'Abhayagiri Sangha']);
        $this->assertEquals('Abhayagiri Sangha', Author::sangha()->title_en);
    }

}
