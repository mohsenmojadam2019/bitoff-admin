<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCanceled extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $username;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, string $username)
    {
        $this->order = $order;
        $this->username = $username;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $supportLink = config('bitoff.bit_front.url') . '/support';
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject("Bitoff Support Canceled your Order ID: {$this->order->hash}")
            ->markdown('mail.cancel', [
                'order' => $this->order,
                'username' => $this->username,
                'supportLink' => $supportLink,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
