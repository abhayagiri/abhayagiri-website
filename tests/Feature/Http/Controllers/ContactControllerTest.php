<?php

namespace Tests\Feature\Http\Controllers;

use App\Mail\ContactAdminMailer;
use App\Mail\ContactMailer;
use App\Models\ContactOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use NoCaptcha;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get(route('contact.index'));
        $response->assertOk()
                 ->assertSee('Contact');

        $response = $this->get(route('th.contact.index'));
        $response->assertOk()
                 ->assertSee('ติดต่อเรา');
    }

    public function testShow()
    {
        $contactOption = factory(ContactOption::class)->create();
        $response = $this->get(route('contact.show', $contactOption));
        $response->assertOk()
                 ->assertSee($contactOption->name_en);

        $response = $this->get(route('th.contact.show', $contactOption));
        $response->assertOk()
                 ->assertSee($contactOption->name_th);
    }

    public function testSendMessageEn()
    {
        $contactOption = factory(ContactOption::class)->create();

        Mail::fake();
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);

        $this->post(route('contact.send-message', $contactOption), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'great work!',
            'recaptcha' => '1',
        ])
            ->assertOk()
            ->assertSee($contactOption->confirmation_en)
            ->assertSee('great work!');

        Mail::assertSent(ContactAdminMailer::class, function ($mail) use ($contactOption) {
            return $mail->hasTo($contactOption->email);
        });

        Mail::assertSent(ContactMailer::class, function ($mail) use ($contactOption) {
            return $mail->hasTo('john@example.com');
        });
    }

    public function testSendMessageTh()
    {
        $contactOption = factory(ContactOption::class)->create();

        Mail::fake();
        NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);

        $this->post(route('th.contact.send-message', $contactOption), [
            'name' => 'Thai Joe',
            'email' => 'joe@th.th',
            'message' => 'zoom zoom',
            'recaptcha' => '1',
        ])
            ->assertOk()
            ->assertSee($contactOption->confirmation_th)
            ->assertSee('zoom zoom');

        Mail::assertSent(ContactAdminMailer::class, function ($mail) use ($contactOption) {
            return $mail->hasTo($contactOption->email);
        });

        Mail::assertSent(ContactMailer::class, function ($mail) use ($contactOption) {
            return $mail->hasTo('joe@th.th');
        });
    }
}
