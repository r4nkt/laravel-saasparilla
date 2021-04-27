<?php

namespace R4nkt\Saasparilla\Tests\Mail;

use R4nkt\ResourceTidier\Actions\Contracts\MarksResource;
use R4nkt\ResourceTidier\Actions\Contracts\NotifiesResourceOwner;
use R4nkt\ResourceTidier\Support\Factories\MarkerFactory;
use R4nkt\ResourceTidier\Support\Factories\NotifierFactory;
use R4nkt\Saasparilla\Mail\CulledUnverifiedUserMail;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class CulledUnverifiedUserMailTest extends TestCase
{
    protected NotifiesResourceOwner $notifier;
    protected MarksResource $marker;

    public function setUp(): void
    {
        parent::setUp();

        $this->marker = MarkerFactory::make('user-for-deletion');
        $this->notifier = NotifierFactory::make('culled-unverified-user');
    }

    /** @test */
    public function it_allows_user_to_verify_email_address()
    {
        $user = User::factory()->create();

        $this->marker->mark($user);

        $this->notifier->notify($user);

        $mail = new CulledUnverifiedUserMail($user);

        $mail->assertSeeInText(__('Verify Email Address'));
    }
}
