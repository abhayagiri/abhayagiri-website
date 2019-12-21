<?php

namespace Tests\Unit\Utilities;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DateTimeCarbonTraitTest extends TestCase
{
    public function testEnglishEST()
    {
        Config::set('abhayagiri.human_timezone', 'America/New_York');
        $dt = Carbon::parse('2019-06-23T05:23:49Z');

        $this->assertEquals('Sun Jun 23 2019 01:23:49 GMT-0400', $dt->forUser()->toString());
        $this->assertEquals('June 23, 2019', $dt->formatUserDate());
        $this->assertEquals('June 23, 2019 1:23 AM', $dt->formatUserDateTime());
        $this->assertEquals('<time datetime="2019-06-23T05:23:49.000000Z" title="Sunday, June 23, 2019 1:23 AM -0400" data-toggle="tooltip">June 23, 2019</time>', $dt->formatUserDateHtml());
        $this->assertEquals('<time datetime="2019-06-23T05:23:49.000000Z" title="Sunday, June 23, 2019 1:23 AM -0400" data-toggle="tooltip">June 23, 2019 1:23 AM</time>', $dt->formatUserDateTimeHtml());
    }

    public function testEnglishPST()
    {
        $this->assertEquals('America/Los_Angeles', Config::get('abhayagiri.human_timezone'));
        $dt = Carbon::parse('2019-06-23T05:23:49Z');

        $this->assertEquals('Sat Jun 22 2019 22:23:49 GMT-0700', $dt->forUser()->toString());
        $this->assertEquals('June 22, 2019', $dt->formatUserDate());
        $this->assertEquals('June 22, 2019 10:23 PM', $dt->formatUserDateTime());
        $this->assertEquals('<time datetime="2019-06-23T05:23:49.000000Z" title="Saturday, June 22, 2019 10:23 PM -0700" data-toggle="tooltip">June 22, 2019</time>', $dt->formatUserDateHtml());
        $this->assertEquals('<time datetime="2019-06-23T05:23:49.000000Z" title="Saturday, June 22, 2019 10:23 PM -0700" data-toggle="tooltip">June 22, 2019 10:23 PM</time>', $dt->formatUserDateTimeHtml());
    }

    public function testThaiEST()
    {
        Config::set('abhayagiri.human_timezone', 'America/New_York');
        App::setLocale('th');
        $dt = Carbon::parse('2019-06-23T05:23:49Z');

        $this->assertEquals('Sun Jun 23 2019 01:23:49 GMT-0400', $dt->forUser()->toString());
        $this->assertEquals('23 มิถุนายน 2019', $dt->formatUserDate());
        $this->assertEquals('23 มิถุนายน 2019 เวลา 1:23', $dt->formatUserDateTime());
        $this->assertEquals('<time datetime="2019-06-23T05:23:49.000000Z" title="Lันอาทิตย์ที่ 23 มิถุนายน 2019 เวลา 1:23 -0400" data-toggle="tooltip">23 มิถุนายน 2019</time>', $dt->formatUserDateHtml());
        $this->assertEquals('<time datetime="2019-06-23T05:23:49.000000Z" title="Lันอาทิตย์ที่ 23 มิถุนายน 2019 เวลา 1:23 -0400" data-toggle="tooltip">23 มิถุนายน 2019 เวลา 1:23</time>', $dt->formatUserDateTimeHtml());
    }

    public function testThaiPST()
    {
        $this->assertEquals('America/Los_Angeles', Config::get('abhayagiri.human_timezone'));
        App::setLocale('th');
        $dt = Carbon::parse('2019-06-23T05:23:49Z');

        $this->assertEquals('Sat Jun 22 2019 22:23:49 GMT-0700', $dt->forUser()->toString());
        $this->assertEquals('22 มิถุนายน 2019', $dt->formatUserDate());
        $this->assertEquals('22 มิถุนายน 2019 เวลา 22:23', $dt->formatUserDateTime());
        $this->assertEquals('<time datetime="2019-06-23T05:23:49.000000Z" title="Lันเสาร์ที่ 22 มิถุนายน 2019 เวลา 22:23 -0700" data-toggle="tooltip">22 มิถุนายน 2019</time>', $dt->formatUserDateHtml());
        $this->assertEquals('<time datetime="2019-06-23T05:23:49.000000Z" title="Lันเสาร์ที่ 22 มิถุนายน 2019 เวลา 22:23 -0700" data-toggle="tooltip">22 มิถุนายน 2019 เวลา 22:23</time>', $dt->formatUserDateTimeHtml());
    }
}
