<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMailer extends Mailable
{
    use Queueable, SerializesModels;

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
	public $content;

    public function __construct($name, $email, $content)
    {
        $this->name = $name;
        $this->email = $email;
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
                    ->view(['text' => 'mail.contact'])
                    ->from(config('abhayagiri.mail.contact_from'), 'Website Contact Form')
                    ->subject(sprintf('Message from %s <%s>', $this->name, $this->email));
    }
}
