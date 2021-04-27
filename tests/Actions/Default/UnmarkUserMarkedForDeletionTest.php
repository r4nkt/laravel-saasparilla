<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use R4nkt\ResourceTidier\Actions\Contracts\MarksResource;
use R4nkt\ResourceTidier\Actions\Contracts\UnmarksResource;
use R4nkt\ResourceTidier\Support\Factories\MarkerFactory;
use R4nkt\ResourceTidier\Support\Factories\UnmarkerFactory;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class UnmarkUserMarkedForDeletionTest extends TestCase
{
    protected MarksResource $marker;

    protected UnmarksResource $unmarker;

    public function setUp(): void
    {
        parent::setUp();

        $this->marker = MarkerFactory::make('user-for-deletion');
        $this->unmarker = UnmarkerFactory::make('user-for-deletion');
    }

    /** @test */
    public function it_marks_a_user()
    {
        $user = User::factory()->create();

        $this->marker->mark($user);

        $this->assertNotNull($user->deleting_soon_mail_sent_at);
        $this->assertNotNull($user->automatically_delete_at);

        $this->unmarker->unmark($user);

        $this->assertNull($user->deleting_soon_mail_sent_at);
        $this->assertNull($user->automatically_delete_at);
    }
}
