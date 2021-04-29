<?php

namespace R4nkt\Saasparilla\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CulledUnverifiedUserMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $user;

    /**
     * Create a new message instance.
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
        $verificationUrl = $this->verificationUrl($this->user);

        return $this->markdown('saasparilla::emails.culled-unverified-user', [
                'verifyUrl' => $verificationUrl,
                'automaticallyDeleteAt' => $this->user->automatically_delete_at->toDateTimeString(),
            ])
            ->subject(__('Inactivity Notification'));
    }

    /**
     * Source: Illuminate\Auth\Notifications\VerifyEmail
     *
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
