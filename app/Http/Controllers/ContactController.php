<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the new proxy.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        return view('app.new-proxy');
    }

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
