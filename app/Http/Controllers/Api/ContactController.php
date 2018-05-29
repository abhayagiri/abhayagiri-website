<?php

namespace App\Http\Controllers\Api;

use App\Mail\ContactMailer;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Http\Controllers\ApiController;

class ContactController extends ApiController
{
    public function send(ContactRequest $request)
    {
        $contactOptionEmail = $request->input('contact-option-email');
        $name = $request->input('name');
        $email = $request->input('email');

        Mail::to($contactOptionEmail)
            ->send(new ContactMailer($name, $email, $request->input('message')));

        return response()->json([
            'message' => trans('contact.success-message'),
        ]);
    }
}
