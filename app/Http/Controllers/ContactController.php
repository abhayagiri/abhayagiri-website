<?php

namespace App\Http\Controllers;

use Config;
use Illuminate\Http\Request;
use Log;
use Mail;
use Validator;

class ContactController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'g-recaptcha-response' => 'required|captcha'
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

        return '1';
    }
}
