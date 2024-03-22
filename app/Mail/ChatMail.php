<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $user;
    protected $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $order)
    {
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $orderUrl = config('bitoff.bit_front.url') . "/p/orders/{$this->order->hash}";
        $contactUrl = config('bitoff.bit_front.url') . '/support';
        $image = config('bitoff.bit_api.url') . '/mail/email.jpg';

        return $this->to($this->user->email)
            ->subject('You received a message in Bitoff')
            ->markdown('mail.chat', [
                'image' => $image,
                'order' => $this->order,
                'user' => $this->user,
                'orderUrl' => $orderUrl,
                'contactUrl' => $contactUrl,
            ]);
    }
}
