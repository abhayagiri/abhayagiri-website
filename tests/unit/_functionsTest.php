<?php

namespace Abhayagiri;

class _functionsTest extends \PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        Config::resetConfig();
    }

    protected function tearDown()
    {
        Config::resetConfig();
    }

    public function testGetGitVersion()
    {
        $this->assertRegExp('/^[a-z0-9]{7} - [-:0-9 ]{25} - .+$/', getGitVersion());
    }


    public function testGetVersionStamp()
    {
        Config::setConfig(array(
            'development' => true,
        ));
        $this->assertRegExp('/^[0-9]{10,}$/', getVersionStamp());
        Config::setConfig(array(
            'development' => false,
        ));
        $this->assertRegExp('/^[a-z0-9]{7}$/', getVersionStamp());
    }
    public function testGetWebRoot()
    {
        $this->assertEquals('http://localhost', getWebRoot());
        $this->assertEquals('https://localhost', getWebRoot(true));
        Config::setConfig(array(
            'host' => 'foobar.com',
            'requireSSL' => true,
        ));
        $this->assertEquals('https://foobar.com', getWebRoot());
    }

    public function testGetMahapanelRoot()
    {
        $this->assertEquals('http://localhost/mahapanel', getMahapanelRoot());
        Config::setConfig(array(
            'host' => 'foobar.com',
            'requireMahapanelSSL' => true,
        ));
        $this->assertEquals('https://foobar.com/mahapanel', getMahapanelRoot());
    }

    public function testRedirectUrl()
    {
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
