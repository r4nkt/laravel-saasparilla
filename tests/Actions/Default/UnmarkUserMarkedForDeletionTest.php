<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class UnmarkUserMarkedForDeletionTest extends TestCase
{
    /** @test */
    public function it_marks_a_user()
    {
        $user = User::factory()->create();

        $this->tidier->culler()->marker()->mark($user);

        $this->assertNotNull($user->deleting_soon_mail_sent_at);
        $this->assertNotNull($user->automatically_delete_at);

        $this->tidier->unmarker()->unmark($user);

        $this->assertNull($user->deleting_soon_mail_sent_at);
        $this->assertNull($user->automatically_delete_at);
    }
}
