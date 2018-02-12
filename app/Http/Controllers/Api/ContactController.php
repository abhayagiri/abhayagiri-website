<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Http\Controllers\ApiController;
use App\Mail\ContactMailer;

class ContactController extends ApiController
{
    public function send(ContactRequest $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');

        Mail::to(config('abhayagiri.mail.contact_to'))
            ->send(new ContactMailer($name, $email, $request->input('message')));

        return response()->json([
            'message' => trans('contact.success-message'),
        ]);
    }
}
