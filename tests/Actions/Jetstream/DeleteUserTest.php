<?php

namespace R4nkt\Saasparilla\Tests\Actions\Jetstream;

use Carbon\Carbon;
use Mockery\MockInterface;
use R4nkt\Saasparilla\Actions\Contracts\DeletesUser;
use R4nkt\Saasparilla\Actions\Jetstream\DeleteUser;
use R4nkt\Saasparilla\DeleterFactory;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\Models\JetstreamUserWithTeams;

class DeleteUserTest extends TestCase
{
    protected DeleteUser $deleter;
    protected int $threshold = 20;

    public function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::create('2020-01-01 00:00:00'));

        config(['saasparilla.deleters.users.class' => DeleteUser::class]);
        config(['saasparilla.getters.unverified-users.params.model' => JetstreamUserWithTeams::class]);
        config(['saasparilla.getters.unverified-users.params.threshold' => $this->threshold]);

        $this->deleter = DeleterFactory::make('users');
    }

    /** @test */
    public function it_calls_the_registered_jetstream_delete_user_action()
    {
        $mock = $this->mock(DeletesUsers::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')->once();
        });

        $user = JetstreamUserWithTeams::factory()->unverified()->create();

        $this->deleter->delete($user);
    }
}
