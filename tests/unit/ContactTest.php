<?php

namespace Tests\Unit;

use Mockery;
use NoCaptcha;
use Tests\TestCase;
use App\Mail\ContactMailer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class MailTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * Test sending a contact message
     */
    public function testContactFormWorks()
    {
        $this->withoutExceptionHandling();
        Mail::fake();

        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);

        $this->post('/api/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'great work!',
            'g-recaptcha-response' => '1',
        ])
            ->assertSuccessful();

        Mail::assertSent(ContactMailer::class, function ($mail) {
            return
                $mail->hasTo(config('abhayagiri.mail.contact_from')) &&
                $mail->content === 'great work!';
        });
    }
}
