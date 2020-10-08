<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class InviteUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $guest;
    public $host;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($guest, $host)
    {
        $this->guest = $guest;
        $this->host = $host;
        $this->url = URL::signedRoute('users.invite.edit', ['user' => $this->guest]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {                
        return $this->subject('Collaboration invite')
                    ->markdown('mail.inviteuser');
    }
}
