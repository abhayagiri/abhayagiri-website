<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Http\Controllers\ApiController;

class ContactController extends ApiController
{
    public function send(ContactRequest $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');

        Mail::send(
            ['text' => 'mail.contact'],
            ['content' => $request->input('message')],
            function ($message) use ($name, $email) {
                $message->from(
                    config('abhayagiri.mail.contact_from'),
                    'Website Contact Form'
                );
                $message->replyTo($email, $name);
                $message->to(config('abhayagiri.mail.contact_to'));
                $message->subject("Message from $name <$email>");
            }
        );

        return response()->json([
            'message' => 'Message sent',
        ]);
    }
}
