<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MailTest extends TestCase
{
    use WithoutMiddleware;

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
        $mailer->shouldReceive('createMessage')
            ->andReturnUsing(function($service) {
                return (new \Swift_Message());
            });
        $mailer->shouldReceive('send')
            ->once()
            ->andReturnUsing(function($message) {
                $this->assertEquals('Message from John Doe <john@example.com>', $message->getSubject());
                $this->assertEquals('great work!', $message->getBody());
            });
        $this->app['mailer']->setSwiftMailer($mailer);

        $this->get('/contact')
            ->assertSuccessful()
            ->assertSeeText('Contact Form');

        $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'great work!',
            'g-recaptcha-response' => '1',
        ])
            ->assertSuccessful()
            ->assertSeeText('1');
    }
}
