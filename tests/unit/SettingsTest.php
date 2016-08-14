<?php

namespace Abhayagiri;

class SettingsTest extends \PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        $db = DB::getPDOConnection();
        $db->query("DELETE FROM settings WHERE key_ = 'test.foobar'");
        $db->query("INSERT INTO settings
            (key_, value, date, user) VALUES
            ('test.foobar', '1', NOW(), 0)");
    }

    protected function tearDown()
    {
        $db = DB::getPDOConnection();
        $db->query("DELETE FROM settings WHERE key_ = 'test.foobar'");
    }

    public function testGet()
    {
        $this->assertEquals(1, Settings::get('test.foobar'));
    }

    public function testSet()
    {
        $this->assertEquals(true, Settings::set('test.foobar', 2));
        $this->assertEquals(2, Settings::get('test.foobar'));
        $this->assertEquals(true, Settings::set('test.foobar', 'okay'));
        $this->assertEquals('okay', Settings::get('test.foobar'));
    }
}

?>
