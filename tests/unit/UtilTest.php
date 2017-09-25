<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

use App\Util;

class UtilTest extends TestCase
{
    public function testChunkHash()
    {
        $this->mockStamp([
            'chunkHash' => 'xyz',
        ]);
        $this->assertEquals('xyz', Util::chunkHash());
    }

    public function testDevBypassAvailableOnLocal()
    {
        Config::set('app.env', 'local');
        Config::set('abhayagiri.auth.mahapanel_bypass', true);
        $this->assertTrue(Util::devBypassAvailable());
    }

    public function testDevBypassNotAvailableOnProduction()
    {
        Config::set('app.env', 'production');
        Config::set('abhayagiri.auth.mahapanel_bypass', true);
        $this->assertFalse(Util::devBypassAvailable());
    }

    public function testDevBypassNotAvailableWhenNotSet()
    {
        Config::set('app.env', 'local');
        Config::set('abhayagiri.auth.mahapanel_bypass', false);
        $this->assertFalse(Util::devBypassAvailable());
    }

    public function testGitDateTime()
    {
        $this->mockStamp([
            'timestamp' => 123,
        ]);
        $this->assertEquals(new \DateTime('@123'), Util::gitDateTime());
    }

    public function testGitMessage()
    {
        $this->mockStamp([
            'message' => 'hello',
        ]);
        $this->assertEquals('hello', Util::gitMessage());
    }

    public function testGitRevision()
    {
        $this->mockStamp([
            'revision' => 'abc',
        ]);
        $this->assertEquals('abc', Util::gitRevision());
    }

    protected function mockStamp($data)
    {
        File::shouldReceive('exists')
            ->once()
            ->with(base_path('.stamp.json'))
            ->andReturn(true);
        File::shouldReceive('get')
            ->once()
            ->with(base_path('.stamp.json'))
            ->andReturn(json_encode($data));
    }

    public function testVersionStampWithVersioningEnabled()
    {
        Config::set('abhayagiri.git_versioning', true);
        $this->assertRegExp('/^[a-z0-9]{40}$/', Util::versionStamp());
    }

    public function testVersionStampWithVersioningDisabled()
    {
        Config::set('abhayagiri.git_versioning', false);
        $this->assertRegExp('/^[0-9]{10,}$/', Util::versionStamp());
    }

    public function testRedirectUrl()
    {
        Config::set('app.url', 'http://localhost');
        Config::set('abhayagiri.require_ssl', false);
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
