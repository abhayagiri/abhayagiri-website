<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        $this->name = $name;
        $this->email = $email;
        $this->contactOptionName = tp($contactOption, 'name');
        $this->contactOptionConfirmationMessage = tp($contactOption, 'confirmation_html');
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
            ->from(config('abhayagiri.mail.contact_from'), __('contact.from-name'))
            ->subject(__('contact.confirmation-user-subject'))
            ->replyTo($this->email, $this->name);
    }
}
