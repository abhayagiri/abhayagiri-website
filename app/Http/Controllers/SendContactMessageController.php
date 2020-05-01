<?php

namespace App\Http\Controllers;

use App\Mail\ContactMailer;
use App\Models\ContactOption;
use App\Mail\ContactAdminMailer;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\SendContactMessageRequest;

class SendContactMessageController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function __invoke(SendContactMessageRequest $request, ContactOption $contactOption)
    {
        Mail::to($contactOption->email)
            ->send(new ContactAdminMailer($request->name, $request->email, $contactOption, $request->message));

        Mail::to($request->email)
            ->send(new ContactMailer($request->name, $request->email, $contactOption, $request->message));

        return view('contact.message_sent')
            ->withContactOption($contactOption)
            ->withFormMessage($request->message);
    }
}
