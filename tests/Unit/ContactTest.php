<?php

namespace Tests\Unit;

use App\Mail\ContactAdminMailer;
use App\Mail\ContactMailer;
use App\Models\ContactOption;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use NoCaptcha;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;
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
            'contact-option' => $contactOption,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'great work!',
            'g-recaptcha-response' => '1',
            'language' => 'en',
        ])
            ->assertSuccessful();

        Mail::assertSent(ContactAdminMailer::class, function ($mail) use ($contactOption) {
            return $mail->to[0]['address'] = $contactOption->email;
        });

        Mail::assertSent(ContactMailer::class, function ($mail) use ($contactOption) {
            return $mail->hasTo('john@example.com');
        });
    }
}
