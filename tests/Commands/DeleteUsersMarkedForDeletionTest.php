<?php

namespace R4nkt\Saasparilla\Tests\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use R4nkt\Saasparilla\Commands\DeleteUsersMarkedForDeletion;
use R4nkt\Saasparilla\Concerns\UsesSaasparillaConfig;
use R4nkt\Saasparilla\MarkerFactory;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class DeleteUsersMarkedForDeletionTest extends TestCase
{
    use UsesSaasparillaConfig;

    public function setUp(): void
    {
        parent::setUp();

        config(['saasparilla.getters.users-marked-for-deletion.params.model' => User::class]);

        $this->travelTo(Carbon::create('2020-01-01 00:00:00'));

        Mail::fake();
    }

    /** @test */
    public function it_will_delete_unverified_users_that_have_been_marked_for_deletion_after_grace_period()
    {
        // Create and mark-for-deletion an unverified user
        $unverifiedUser = User::factory()->unverified()->create();
        $marker = MarkerFactory::make('user-for-deletion');
        $marker->mark($unverifiedUser);

        // Get default configuration settings for grace
        $grace = self::marker('user-for-deletion')['params']['grace'];

        $this->travel($grace)->days();

        $this->artisan(DeleteUsersMarkedForDeletion::class);
        $this->assertTrue($unverifiedUser->exists());

        $this->travel(1)->seconds();

        $this->artisan(DeleteUsersMarkedForDeletion::class);
        $this->assertFalse($unverifiedUser->exists());
    }

    /** @test */
    public function it_will_not_delete_verified_users()
    {
        $user = User::factory()->create(); // verified by default...

        // Get default configuration settings for grace
        $grace = self::marker('user-for-deletion')['params']['grace'];

        $this->travel($grace + 1)->days();

        $this->artisan(DeleteUsersMarkedForDeletion::class);
        $this->assertTrue($user->exists());
    }
}
