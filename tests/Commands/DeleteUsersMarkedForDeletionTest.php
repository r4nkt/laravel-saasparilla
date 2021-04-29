<?php

namespace R4nkt\Saasparilla\Tests\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use R4nkt\ResourceTidier\Concerns\UsesResourceTidierConfig;
use R4nkt\Saasparilla\Commands\DeleteUsersMarkedForDeletion;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\Models\User;

class DeleteUsersMarkedForDeletionTest extends TestCase
{
    use UsesResourceTidierConfig;

    public function setUp(): void
    {
        parent::setUp();

        config(['resource-tidier.finders.users-ready-for-deletion.params.model' => User::class]);

        $this->travelTo(Carbon::create('2020-01-01 00:00:00'));

        Mail::fake();
    }

    /** @test */
    public function it_will_delete_unverified_users_that_have_been_marked_for_deletion_after_grace_period()
    {
        // Create and mark-for-deletion an unverified user
        $unverifiedUser = User::factory()->unverified()->create();
        $this->tidier->culler()->marker()->mark($unverifiedUser);

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
