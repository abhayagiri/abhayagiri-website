<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SettingsTest extends \PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        DB::table('settings')->where('key_', '=', 'test.foobar')->delete();
        DB::table('settings')->insert([
            'key_' => 'test.foobar',
            'value' => '1',
            'date' => Carbon::now(),
            'user' => 0,
        ]);
    }

    protected function tearDown()
    {
        DB::table('settings')->where('key_', '=', 'test.foobar')->delete();
    }

    public function testGet()
    {
        $this->assertEquals(1, Settings::get('test.foobar'));
    }

    public function testSet()
    {
        Settings::set('test.foobar', 2);
        $this->assertEquals(2, Settings::get('test.foobar'));
        Settings::set('test.foobar', 'okay');
        $this->assertEquals('okay', Settings::get('test.foobar'));
    }
}
