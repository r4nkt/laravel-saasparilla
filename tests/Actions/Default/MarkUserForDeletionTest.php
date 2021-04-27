<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use R4nkt\ResourceTidier\Actions\Contracts\MarksResource;
use R4nkt\ResourceTidier\Support\Factories\MarkerFactory;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class MarkUserForDeletionTest extends TestCase
{
    protected MarksResource $marker;

    protected int $grace = 20;

    public function setUp(): void
    {
        parent::setUp();

        config(['resource-tidier.markers.user-for-deletion.params.grace' => $this->grace]);

        $this->marker = MarkerFactory::make('user-for-deletion');
    }

    /** @test */
    public function it_marks_a_user()
    {
        $user = User::factory()->create();

        $this->marker->mark($user);

        $this->assertNotNull($user->deleting_soon_mail_sent_at);
        $this->assertNotNull($user->automatically_delete_at);

        $this->assertTrue($user->deleting_soon_mail_sent_at->equalTo(now()->floorSecond()));

        $this->assertTrue($user->automatically_delete_at->equalTo(now()->addDays($this->grace)->floorSecond()));
    }
}
