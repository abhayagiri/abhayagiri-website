<?php

namespace Tests\Unit;

use NoCaptcha;
use Tests\TestCase;
use App\Mail\ContactMailer;
use App\Models\ContactOption;
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
        $contactOption = factory(ContactOption::class)->create();

        Mail::fake();

        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);

        $this->post('/api/contact', [
            'contact-option-id' => $contactOption->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'great work!',
            'g-recaptcha-response' => '1',
        ])
            ->assertSuccessful();

        Mail::assertSent(ContactMailer::class, function ($mail) use ($contactOption) {
            return
                $mail->hasTo($contactOption->email) &&
                $mail->content === 'great work!';
        });
    }
}
