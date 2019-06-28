<?php

namespace Tests\Unit;

use App\Models\Language;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    public function testEnglish()
    {
        $this->assertEquals('en', Language::english()->code);
    }

    public function testThai()
    {
        $this->assertEquals('th', Language::thai()->code);
    }
}
