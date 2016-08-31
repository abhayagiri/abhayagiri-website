<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MailTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testContactFormWorks()
    {
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);

        NoCaptcha::shouldReceive('display')
            ->once()
            ->andReturn('<input type="hidden" name="g-recaptcha-response" value="1" />');

        $transport = Mockery::mock('Object');
        $transport->shouldReceive('stop')->once();
        $mailer = Mockery::mock('Swift_Mailer');
        $mailer->shouldReceive('getTransport')
            ->andReturn($transport);
        $mailer->shouldReceive('send')
            ->once()
            ->andReturnUsing(function($message) {
                $this->assertEquals('Message from John Doe <john@example.com>', $message->getSubject());
                $this->assertEquals('great work!', $message->getBody());
            });
        $this->app['mailer']->setSwiftMailer($mailer);

        $this->visit('/contact')
            ->assertResponseOk()
            ->see('Contact Form')
            ->type('John Doe', '#name')
            ->type('john@example.com', '#email')
            ->type('great work!', '#message')
            ->press('Submit')
            ->assertResponseOk()
            ->see('1');
    }
}
