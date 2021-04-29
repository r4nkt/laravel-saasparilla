<?php

namespace R4nkt\Saasparilla\Tests\Mail;

use R4nkt\ResourceTidier\Contracts\TidiesResources;
use R4nkt\ResourceTidier\Support\Facades\ResourceTidier;
use R4nkt\Saasparilla\Mail\CulledUnverifiedUserMail;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\Models\User;

class CulledUnverifiedUserMailTest extends TestCase
{
    protected TidiesResources $tidier;

    public function setUp(): void
    {
        parent::setUp();

        $this->tidier = ResourceTidier::tidier('unverified-users');
    }

    /** @test */
    public function it_allows_user_to_verify_email_address()
    {
        $user = User::factory()->create();

        $this->tidier->culler()->marker()->mark($user);

        $this->tidier->culler()->notifier()->notify($user);

        $mail = new CulledUnverifiedUserMail($user);

        $mail->assertSeeInText(__('Verify Email Address'));
    }
}
