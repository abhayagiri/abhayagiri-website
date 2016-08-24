<?php
namespace Helper;

use GuzzleHttp\Client;

class Acceptance extends \Codeception\Module
{
    private $lastMail;
    private $lastMailBody;

    public function testableMail()
    {
        return \env('MAILTRAP_INBOX_ID') && \env('MAILTRAP_API_TOKEN');
    }

    public function resetMail()
    {
        $path = '/api/v1/inboxes/' . \env('MAILTRAP_INBOX_ID') . '/clean';
        $this->getMailtrapResponse($path, 'PATCH');
        $this->lastMail = null;
        $this->lastMailBody = null;
        return true;
    }

    public function lastMail()
    {
        if ($this->lastMail === null) {
            $path = '/api/v1/inboxes/' . \env('MAILTRAP_INBOX_ID') .
                '/messages';
            $messages = json_decode($this->getMailtrapResponse($path));
            if (count($messages)) {
                $this->lastMail = $messages[0];
            } else {
                $this->fail('No messages in queue');
            }
        }
        return $this->lastMail;
    }

    public function lastMailBody()
    {
        $lastMail = $this->lastMail();
        $this->lastMailBody = $this->getMailtrapResponse(
            $this->lastMail->txt_path);
        return $this->lastMailBody;
    }

    public function seeInLastMailSubject($expected)
    {
        $this->assertContains($expected, $this->lastMail()->subject);
    }

    public function seeInLastMailFrom($expectedEmail, $expectedName)
    {
        $this->assertEquals($expectedEmail, $this->lastMail()->from_email);
        $this->assertEquals($expectedName, $this->lastMail()->from_name);
    }

    public function seeInLastMailTo($expectedEmail, $expectedName)
    {
        $this->assertEquals($expectedEmail, $this->lastMail()->to_email);
        $this->assertEquals($expectedName, $this->lastMail()->to_name);
    }

    public function seeInLastMailBody($expected)
    {
        $this->assertContains($expected, $this->lastMailBody());
    }

    protected function getMailtrapResponse($path, $type = 'GET')
    {
        $mailtrap = new Client([
            'base_uri' => 'https://mailtrap.io',
            'headers' => [ 'Api-Token' => \env('MAILTRAP_API_TOKEN') ],
        ]);
        $response = $mailtrap->request($type, $path);
        return (string) $response->getBody();
    }
}
