<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use Illuminate\Support\Facades\Mail;
use R4nkt\Saasparilla\Mail\CulledUnverifiedUserMail;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\Models\User;

class CulledUnverifiedUserNotifierTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Mail::fake();
    }

    /** @test */
    public function it_notifies_a_user()
    {
        $user = User::factory()->create();

        $this->tidier->culler()->notifier()->notify($user);

        Mail::assertQueued(CulledUnverifiedUserMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
