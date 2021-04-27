<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use Illuminate\Support\Facades\Mail;
use R4nkt\ResourceTidier\Actions\Contracts\NotifiesResourceOwner;
use R4nkt\ResourceTidier\Support\Factories\NotifierFactory;
use R4nkt\Saasparilla\Mail\CulledUnverifiedUserMail;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class CulledUnverifiedUserNotifierTest extends TestCase
{
    protected NotifiesResourceOwner $notifier;

    public function setUp(): void
    {
        parent::setUp();

        $this->notifier = NotifierFactory::make('culled-unverified-user');

        Mail::fake();
    }

    /** @test */
    public function it_notifies_a_user()
    {
        $user = User::factory()->create();

        $this->notifier->notify($user);

        Mail::assertQueued(CulledUnverifiedUserMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
