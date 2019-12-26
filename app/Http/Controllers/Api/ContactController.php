<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactAdminMailer;
use App\Mail\ContactMailer;
use App\Models\ContactOption;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class ContactController extends ApiController
{
    public function send(ContactRequest $request)
    {
        App::setLocale($request->input('language'));
        $contactOption = ContactOption::findOrFail($request->input('contact-option')['id']);
        $name = $request->input('name');
        $email = $request->input('email');
        $message = $request->input('message');

        Mail::to($contactOption->email)
            ->send(new ContactAdminMailer($name, $email, $contactOption, $message));

        Mail::to($email)
            ->send(new ContactMailer($name, $email, $contactOption, $message));

        return response()->json([
            'message' => trans('contact.success-message'),
        ]);
    }
}
