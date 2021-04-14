<?php

namespace R4nkt\Saasparilla\Tests\Actions\Users;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use R4nkt\Saasparilla\Contexts\Context;
use R4nkt\Saasparilla\Mail\UserInactivityMail;
use R4nkt\Saasparilla\Saasparilla;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class WarnInactiveUsersTest extends TestCase
{
    protected Context $context;

    public function setUp(): void
    {
        parent::setUp();

        config(['saasparilla.inactive_contexts.users.model' => User::class]);

        $this->context = app(Saasparilla::class)->inactiveContext('users');

        Mail::fake();
    }

    /** @test */
    public function it_does_not_send_warnings_if_no_users()
    {
        $this->context->warn(User::all()); // no users should exist...

        Mail::assertNothingQueued();
    }

    /** @test */
    public function it_warns_users_via_mail()
    {
        $users = User::factory()->count(5)->create();

        $this->context->warn($users);

        Mail::assertQueued(UserInactivityMail::class, 5);
    }
}
