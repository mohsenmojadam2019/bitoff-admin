<?php

namespace App\Notifications;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemCanceled extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $item;
    public $username;
    public $description;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, Item $item, string $username, $description = null)
    {
        $this->order = $order;
        $this->item = $item;
        $this->username = $username;
        $this->description = $description;
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
            ->subject("Sorry! your Item from the {$this->order->hash} order ID got canceled")
            ->markdown('mail.remove', [
                'order' => $this->order,
                'item' => $this->item,
                'username' => $this->username,
                'supportLink' => $supportLink,
                'description' => $this->description,
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
