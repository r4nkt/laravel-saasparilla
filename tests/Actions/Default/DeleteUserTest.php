<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use R4nkt\ResourceTidier\Actions\Contracts\ExecutesResourceTask;
use R4nkt\ResourceTidier\Support\Factories\TaskFactory;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class DeleteUserTest extends TestCase
{
    protected ExecutesResourceTask $task;

    public function setUp(): void
    {
        parent::setUp();

        $this->task = TaskFactory::make('delete-user');
    }

    /** @test */
    public function it_deletes_a_user()
    {
        $user = User::factory()->create();

        $this->assertTrue($user->exists);

        $this->task->execute($user);

        $this->assertFalse($user->exists);
    }
}
