<?php

namespace Tests\Unit;

use App\Util;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

use Tests\TestCase;

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
        Config::shouldReceive('get')->with('abhayagiri.auth.mahapanel_bypass')
                                    ->andReturn(true);
        Config::shouldReceive('get')->with('app.env')
                                    ->andReturn('local');
        $this->assertTrue(Util::devBypassAvailable());
    }

    public function testDevBypassNotAvailableOnProduction()
    {
        Config::shouldReceive('get')->with('abhayagiri.auth.mahapanel_bypass')
                                    ->andReturn(true);
        Config::shouldReceive('get')->with('app.env')
                                    ->andReturn('production');
        $this->assertFalse(Util::devBypassAvailable());
    }

    public function testDevBypassNotAvailableWhenNotSet()
    {
        Config::shouldReceive('get')->with('abhayagiri.auth.mahapanel_bypass')
                                    ->andReturn(false);
        Config::shouldReceive('get')->with('app.env')
                                    ->andReturn('local');
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
        Config::shouldReceive('get')->with('abhayagiri.git_versioning')
                                    ->andReturn(true);
        $this->assertRegExp('/^[a-z0-9]{40}$/', Util::versionStamp());
    }

    public function testVersionStampWithVersioningDisabled()
    {
        Config::set('abhayagiri.git_versioning', false);
        Config::shouldReceive('get')->with('abhayagiri.git_versioning')
                                    ->andReturn(false);
        $this->assertRegExp('/^[0-9]{10,}$/', Util::versionStamp());
    }

    public function testRedirectUrl()
    {
        Config::shouldReceive('get')->with('app.url')
                                    ->andReturn('http://localhost');
        $this->assertEquals(
            'http://localhost/',
            Util::redirectUrl('/')
        );
        $this->assertEquals(
            'http://localhost/abc?a=3#xyz',
            Util::redirectUrl('/abc?a=3#xyz')
        );
        $this->assertEquals(
            'https://localhost/abc',
            Util::redirectUrl('/abc', true)
        );
        $this->assertEquals(
            'http://foo.com/abc',
            Util::redirectUrl('http://foo.com/abc')
        );
        $this->assertEquals(
            'https://foo.com/abc',
            Util::redirectUrl('http://foo.com/abc', true)
        );
        $this->assertEquals(
            'https://foo.com/abc',
            Util::redirectUrl('https://foo.com/abc')
        );
        $this->assertEquals(
            'http://foo.com/abc',
            Util::redirectUrl('https://foo.com/abc', false)
        );
    }
}
