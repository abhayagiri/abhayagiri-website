<?php

namespace Abhayagiri;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        Config::resetConfig();
    }

    protected function tearDown()
    {
        Config::resetConfig();
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
        Config::setConfig($config);
        $this->assertEquals($config, Config::get());
        $this->assertEquals('1', Config::get('foo'));
        $this->assertEquals(null, Config::get('fooo'));
        $this->assertEquals($config['bar'], Config::get('bar'));
        $this->assertEquals(2, Config::get('bar', 'baz'));
        $this->assertEquals(null, Config::get('bar', 'baz', 'biz'));
    }

    public function testGetWithRealConfig()
    {
        $this->assertEquals('UTC', Config::get('default_timezone'));
    }
}

?>
