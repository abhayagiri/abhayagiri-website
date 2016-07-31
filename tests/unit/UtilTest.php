<?php

class UtilTest extends PHPUnit_Framework_TestCase
{

    public function testGetVersion()
    {
        $this->assertRegExp(
            '/^[a-z0-9]{7} - [-:0-9 ]{25} - .+$/',
            Abhayagiri\Util::getVersion()
        );
    }
}

?>
