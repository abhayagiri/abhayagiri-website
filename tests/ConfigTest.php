<?php

class ConfigTest extends PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        Abhayagiri\Config::resetConfig();
    }

    protected function tearDown()
    {
        Abhayagiri\Config::resetConfig();
    }

    public function testGet()
    {
        $config = array(
            'foo' => 1,
            'bar' => array(
                'baz' => 2,
                'biz' => 3,
            ),
        );
        Abhayagiri\Config::setConfig($config);
        $this->assertEquals($config, Abhayagiri\Config::get());
        $this->assertEquals('1', Abhayagiri\Config::get('foo'));
        $this->assertEquals(null, Abhayagiri\Config::get('fooo'));
        $this->assertEquals($config['bar'], Abhayagiri\Config::get('bar'));
        $this->assertEquals(2, Abhayagiri\Config::get('bar', 'baz'));
        $this->assertEquals(null, Abhayagiri\Config::get('bar', 'baz', 'biz'));
    }

    public function testGetWithRealConfig()
    {
        $this->assertEquals('UTC', Abhayagiri\Config::get('default_timezone'));
    }
}

?>
