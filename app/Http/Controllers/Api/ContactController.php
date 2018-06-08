<?php

namespace App\Http\Controllers\Api;

use App\Mail\ContactMailer;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Http\Controllers\ApiController;
use App\Models\ContactOption;

class ContactController extends ApiController
{
    public function send(ContactRequest $request)
    {
        $contactOption = ContactOption::find($request->input('contact-option-id'));
        $name = $request->input('name');
        $email = $request->input('email');
        $message = $request->input('message');

        Mail::to($contactOption->email)
            ->send(new ContactMailer($name, $email, $contactOption, $message));

        return response()->json([
            'message' => trans('contact.success-message'),
        ]);
    }
}
