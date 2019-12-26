<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ContactMailer extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $contactOptionName;

    /**
     * @var string
     */
    public $contactOptionConfirmationMessage;

    /**
     * @var string
     */
    public $content;

    public function __construct($name, $email, $contactOption, $content)
    {
        $lng = App::getLocale();
        $this->name = $name;
        $this->email = $email;
        $this->contactOptionName = $contactOption->{'name_'.$lng};
        $this->contactOptionConfirmationMessage = $contactOption->{'confirmation_html_'.$lng};
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.user_contact')
                    ->from(config('abhayagiri.mail.contact_from'), trans('contact.from-name'))
                    ->subject(trans('contact.confirmation-user-subject'));
    }
}
