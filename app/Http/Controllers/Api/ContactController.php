<?php

namespace App\Http\Controllers\Api;

use App\Mail\ContactMailer;
use App\Models\ContactOption;
use App\Mail\ContactAdminMailer;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\App;

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
