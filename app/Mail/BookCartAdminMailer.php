<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use stdClass;

class BookCartAdminMailer extends Mailable
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
                    ->replyTo($this->shipping->email, $this->getShippingName())
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
        return __('books.book_request_from', [
            'name' => $this->getShippingName() . ' <' .
                      $this->shipping->email . '>'
        ]);
    }

    /**
     * Return the recipient of this message.
     *
     * @return stdClass
     */
    public function getTo(): stdClass
    {
        return (object) [
            'email' => Config::get('abhayagiri.mail.book_request_to.address'),
            'name' => Config::get('abhayagiri.mail.book_request_to.name'),
        ];
    }

    /**
     * Return the full name of the shipper.
     *
     * @return string
     */
    public function getShippingName(): string
    {
        return $this->shipping->first_name . ' ' . $this->shipping->last_name;
    }
}
