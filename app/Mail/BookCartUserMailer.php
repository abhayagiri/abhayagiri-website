<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use stdClass;

class BookCartUserMailer extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The book cart.
     *
     * @var \Illuminate\Support\Collection
     */
    public $cart;

    /**
     * The shipping information.
     *
     * @var stdClass
     */
    public $shipping;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Collection $cart, stdClass $shipping)
    {
        $this->cart = $cart;
        $this->shipping = $shipping;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->getSubject();
        return $this->subject($subject)
                    ->view('emails.book-cart.admin')
                    ->with([
                        'cart' => $this->cart,
                        'shipping' => $this->shipping,
                        'subject' => $subject,
                    ]);
    }

    /**
     * Return the subject of this message.
     *
     * @return string
     */
    public function getSubject(): string
    {
        return __('common.abhayagiri_monastery') . ' - ' .
               __('books.book_request_received');
    }

    /**
     * Return the recipient of this message.
     *
     * @return stdClass
     */
    public function getTo(): stdClass
    {
        return (object) [
            'email' => $this->shipping->email,
            'name' => $this->shipping->first_name . ' ' . $this->shipping->last_name,
        ];
    }
}
