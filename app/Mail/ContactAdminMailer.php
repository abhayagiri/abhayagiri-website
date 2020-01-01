<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactAdminMailer extends Mailable
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
    public $content;

    public function __construct($name, $email, $contactOption, $content)
    {
        $this->name = $name;
        $this->email = $email;
        $this->contactOptionName = $contactOption->name_en;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo($this->email, $this->name)
                    ->view(['text' => 'mail.admin_contact'])
                    ->from(config('abhayagiri.mail.contact_from'), 'Website Contact Form')
                    ->subject(sprintf('Message from %s <%s>', $this->name, $this->email));
    }
}
