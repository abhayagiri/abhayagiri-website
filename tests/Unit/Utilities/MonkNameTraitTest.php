<?php

namespace Tests\Unit\Utilities;

use App\Utilities\MonkNameTrait;
use Tests\TestCase;

class MonkNameTraitTest extends TestCase
{
    use MonkNameTrait;

    public function testCasingAndWhitespace()
    {
        $this->assertTrue(static::isEqualMonkName('Ajahn Pasanno', 'Ajahn Pasanno'));
        $this->assertTrue(static::isEqualMonkName('Ajahn Pasanno', 'ajahn pasanno'));
        $this->assertTrue(static::isEqualMonkName('Ajahn Pasanno', ' Ajahn Pasanno '));
        $this->assertFalse(static::isEqualMonkName('Ajahn Pasanno', ' Ajahn Passanno '));
    }

    public function testLuangPor()
    {
        $this->assertTrue(static::isEqualMonkName('Luang Por Piek', 'ajahn piek'));
        $this->assertTrue(static::isEqualMonkName('Ajahn Pasanno', 'Luang Por Pasanno'));
        $this->assertTrue(static::isEqualMonkName('luang por pasanno', 'Luang Por Pasanno'));
    }

    public function testEnnay()
    {
        $this->assertTrue(static::isEqualMonkName('Ajahn Ñāniko', 'ajahn naniko'));
        $this->assertTrue(static::isEqualMonkName('Ajahn Ñāniko', 'ajahn nyaniko'));
    }
}
