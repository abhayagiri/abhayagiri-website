<?php

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testGetConfig()
    {
        $config = Abhayagiri\Config::getConfig();
        $this->assertEquals('UTC', $config['default_timezone']);
    }
}

?>
