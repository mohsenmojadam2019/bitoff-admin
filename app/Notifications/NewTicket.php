<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class EmailActivate
 */
class NewTicket extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Ticket $ticket
     */
    public $ticket;

    /**
     * Create a new notification instance.
     *
     * @param $ticket
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $supportLink = config('bitoff.bit_front.url') . '/support';
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('New Ticket in your Bitoff')
            ->markdown('mail.newTicket', [
                'id' => $this->ticket->id,
                'supportLink' => $supportLink,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
