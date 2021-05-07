<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use Illuminate\Auth\Events\Verified;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\Models\User;

class UnmarkUserMarkedForDeletionTest extends TestCase
{
    /** @test */
    public function it_unmarks_a_user()
    {
        $user = User::factory()->create();

        $this->tidier->culler()->marker()->mark($user);

        $this->assertNotNull($user->deleting_soon_mail_sent_at);
        $this->assertNotNull($user->automatically_delete_at);

        $this->tidier->unmark($user);

        $this->assertNull($user->deleting_soon_mail_sent_at);
        $this->assertNull($user->automatically_delete_at);
    }

    /** @test */
    public function it_unmarks_a_user_when_verified()
    {
        $user = User::factory()->create();

        $this->tidier->culler()->marker()->mark($user);

        $this->assertNotNull($user->deleting_soon_mail_sent_at);
        $this->assertNotNull($user->automatically_delete_at);

        event(new Verified($user));

        $this->assertNull($user->deleting_soon_mail_sent_at);
        $this->assertNull($user->automatically_delete_at);
    }
}
