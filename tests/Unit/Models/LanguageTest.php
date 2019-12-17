<?php

namespace Tests\Unit;

use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    use RefreshDatabase;

    public function testEnglish()
    {
        $this->seed();
        $this->assertEquals('en', Language::english()->code);
    }

    public function testThai()
    {
        $this->seed();
        $this->assertEquals('th', Language::thai()->code);
    }
}
