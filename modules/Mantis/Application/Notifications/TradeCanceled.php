<?php

namespace Bitoff\Mantis\Application\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TradeCanceled extends Notification implements ShouldQueue
{
    use Queueable;

    public $tradeHashId;

    public $offerHashId;

    public function __construct(string $tradeHashId, string $offerHashId)
    {
        $this->queue = 'admin-trade';
        $this->tradeHashId = $tradeHashId;
        $this->offerHashId = $offerHashId;
    }

    public function via()
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Trade Dispute Resolution:  '.$this->tradeHashId)
            ->markdown('Mantis::Mail.tradeCanceled', [
                'trade_hash_id' => $this->tradeHashId,
                'offer_hash_id' => $this->offerHashId,
                'user_name' => $notifiable->username,
                'support_link' => config('bitoff.bit_front.url').'/support',
            ]);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
