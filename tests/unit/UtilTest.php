<?php

namespace App;

use Illuminate\Support\Facades\Config;

class UtilTest extends \PHPUnit_Framework_TestCase
{
    public function testGitRevision()
    {
        $this->assertRegExp('/^[a-z0-9]{7}$/', Util::gitRevision());
    }

    public function testGitDateTime()
    {
        $this->assertInstanceOf('DateTime', Util::gitDateTime());
    }

    public function testGitMessage()
    {
        $this->assertNotEquals('', Util::gitMessage());
    }

    public function testVersionStampWithVersioningEnabled()
    {
        Config::shouldReceive('get')
            ->once()
            ->with('abhayagiri.git_versioning', null)
            ->andReturn(true);
        $this->assertRegExp('/^[a-z0-9]{7}$/', Util::versionStamp());
    }

    public function testVersionStampWithVersioningDisabled()
    {
        Config::shouldReceive('get')
            ->once()
            ->with('abhayagiri.git_versioning', null)
            ->andReturn(false);
        $this->assertRegExp('/^[0-9]{10,}$/', Util::versionStamp());
    }

    public function testRedirectUrl()
    {
        Config::shouldReceive('get')
            ->with('app.url', null)
            ->andReturn('http://localhost');
        Config::shouldReceive('get')
            ->with('abhayagiri.require_ssl', null)
            ->andReturn(false);
        $this->assertEquals('http://localhost/',
            Util::redirectUrl('/'));
        $this->assertEquals('http://localhost/abc?a=3#xyz',
            Util::redirectUrl('/abc?a=3#xyz'));
        $this->assertEquals('https://localhost/abc',
            Util::redirectUrl('/abc', true));
        $this->assertEquals('http://foo.com/abc',
            Util::redirectUrl('http://foo.com/abc'));
        $this->assertEquals('https://foo.com/abc',
            Util::redirectUrl('http://foo.com/abc', true));
        $this->assertEquals('https://foo.com/abc',
            Util::redirectUrl('https://foo.com/abc'));
        $this->assertEquals('http://foo.com/abc',
            Util::redirectUrl('https://foo.com/abc', false));
    }
}
