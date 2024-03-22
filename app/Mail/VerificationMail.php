<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $url = config('bitoff.bit_front.url') . '/auth/email-verify/' . $this->token;
        $supportLink = config('bitoff.bit_front.url') . '/support';
        return $this->to($this->user->email)
            ->markdown('mail.verification_mail', compact('user', 'url', 'supportLink'));
    }
}
