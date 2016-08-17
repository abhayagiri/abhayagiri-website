<?php

namespace Abhayagiri;

class _functionsTest extends \PHPUnit_Framework_TestCase
{

    public function testGetGitVersion()
    {
        $this->assertRegExp('/^[a-z0-9]{7} - [-:0-9 ]{25} - .+$/', getGitVersion());
    }

    public function testGetVersionStampWithVersioningEnabled()
    {
        \Config::shouldReceive('get')
            ->once()
            ->with('abhayagiri.git_versioning')
            ->andReturn(true);
        $this->assertRegExp('/^[a-z0-9]{7}$/', getVersionStamp());
    }

    public function testGetVersionStampWithVersioningDisabled()
    {
        \Config::shouldReceive('get')
            ->once()
            ->with('abhayagiri.git_versioning')
            ->andReturn(false);
        $this->assertRegExp('/^[0-9]{10,}$/', getVersionStamp());
    }

    public function testRedirectUrl()
    {
        \Config::shouldReceive('get')
            ->with('app.url')
            ->andReturn('http://localhost');
        \Config::shouldReceive('get')
            ->with('abhayagiri.require_ssl')
            ->andReturn(false);
        $this->assertEquals('http://localhost/', redirectUrl('/'));
        $this->assertEquals('http://localhost/abc?a=3#xyz', redirectUrl('/abc?a=3#xyz'));
        $this->assertEquals('https://localhost/abc', redirectUrl('/abc', true));
        $this->assertEquals('http://foo.com/abc', redirectUrl('http://foo.com/abc'));
        $this->assertEquals('https://foo.com/abc', redirectUrl('http://foo.com/abc', true));
        $this->assertEquals('https://foo.com/abc', redirectUrl('https://foo.com/abc'));
        $this->assertEquals('http://foo.com/abc', redirectUrl('https://foo.com/abc', false));
    }
}

?>
