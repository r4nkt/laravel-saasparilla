<?php

namespace R4nkt\Saasparilla\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class UserInactivityMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @param  \Illuminate\Foundation\Auth  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('saasparilla::mail.user-inactivity-notification', ['acceptUrl' => URL::signedRoute('team-invitations.accept', [
            'user' => $this->user,
        ])])->subject(__('Inactivity Notification'));
    }
}
