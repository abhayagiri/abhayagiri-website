<?php

namespace App\Legacy;

class FuncTest extends \PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        $this->func = new Func();
    }

    public function testGetAuthorImagePath()
    {
        $this->assertEquals('/media/images/speakers/speakers_ajahn_pasanno.jpg',
            $this->func->getAuthorImagePath('Ajahn Pasanno'));
        $this->assertEquals('/media/images/speakers/speakers_abhayagiri_sangha.jpg',
            $this->func->getAuthorImagePath('Ajahnx Pasanno'));
        $this->assertEquals('/media/images/speakers/speakers_ajahn_jotipalo.jpg',
            $this->func->getAuthorImagePath('Ajahn Jotipālo'));
    }
}
