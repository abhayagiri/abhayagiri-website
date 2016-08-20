<?php

namespace App\Http\Controllers;

use Config;
use Illuminate\Http\Request;
use Mail;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function sendMessage(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');

        if (!$this->spamcheck($email)) {
            return '0';
        }

        Mail::send(['text' => 'mail.contact'],
                ['content' => $request->input('message')],
                function ($message) use ($name, $email) {
            $message->from(Config::get('abhayagiri.mail.contact_from'),
                'Website Contact Form');
            $message->replyTo($email, $name);
            $message->to(Config::get('abhayagiri.mail.contact_to'));
            $message->subject("Message from $name <$email>");
        });

        return '1';
    }

    protected function spamcheck($field)
    {
        $field = filter_var($field, FILTER_SANITIZE_EMAIL);
        if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
}
