<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

class ContactController extends ApiController
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            // 'g-recaptcha-response' => 'required|captcha'
        ]);

        if ($validator->fails()) {
            Log::warning('Contact message invalid');
            Log::warning($validator->errors()->all());
            return '0';
        }

        $name = $request->input('name');
        $email = $request->input('email');

        Mail::send(
            ['text' => 'mail.contact'],
            ['content' => $request->input('message')],
            function ($message) use ($name, $email) {
                $message->from(
                    Config::get('abhayagiri.mail.contact_from'),
                    'Website Contact Form'
                );
                $message->replyTo($email, $name);
                $message->to(Config::get('abhayagiri.mail.contact_to'));
                $message->subject("Message from $name <$email>");
            }
        );

        return response()->json([
            'message' => 'Message sent',
        ])
    }
}
