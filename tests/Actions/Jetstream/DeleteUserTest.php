<?php

namespace R4nkt\Saasparilla\Tests\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;
use Mockery\MockInterface;
use R4nkt\Saasparilla\Actions\Jetstream\DeleteUser;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\Models\User;

class DeleteUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config(['resource-tidier.tasks.delete-user.class' => DeleteUser::class]);
    }

    /** @test */
    public function it_calls_the_registered_jetstream_delete_user_action_which_calls_the_jetstream_deletes_users_action()
    {
        $user = User::factory()->create();

        $mock = $this->mock(DeletesUsers::class, function (MockInterface $mock) use ($user) {
            $mock->shouldReceive('delete')->once()->with($user);
        });

        $this->tidier->handler()->task()->execute($user);
    }
}
