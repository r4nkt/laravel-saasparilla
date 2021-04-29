<?php

namespace R4nkt\Saasparilla\Tests\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use R4nkt\ResourceTidier\Concerns\UsesResourceTidierConfig;
use R4nkt\Saasparilla\Mail\CulledUnverifiedUserMail;
use R4nkt\Saasparilla\Support\Facades\Saasparilla;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\Models\User;

class MarkUnverifiedUsersForDeletionTest extends TestCase
{
    use UsesResourceTidierConfig;

    protected $saasparilla;

    public function setUp(): void
    {
        parent::setUp();

        config(['resource-tidier.finders.unverified-users.params.model' => User::class]);
        config(['resource-tidier.finders.users-ready-for-deletion.params.model' => User::class]);

        $this->travelTo(Carbon::create('2020-01-01 00:00:00'));

        Mail::fake();
    }

    /** @test */
    public function it_properly_handles_unverified_users_with_default_configuration()
    {
        $unverifiedUser = User::factory()->unverified()->create();

        // Get default configuration settings for threshold and grace
        $threshold = self::finder('unverified-users')['params']['threshold'];
        $grace = self::marker('user-for-deletion')['params']['grace'];

        // After `threshold` days, confirm the unverified user is still "OK"
        $this->travel($threshold)->days();
        $count = Saasparilla::cullUnverifiedUsers();
        $this->assertSame(0, $count);
        $this->assertFalse($unverifiedUser->marked_for_deletion);
        Mail::assertNothingQueued();

        // After one more second (exceeding `threshold`), confirm the unverified user is *not* "OK"
        $this->travel(1)->seconds();
        $count = Saasparilla::cullUnverifiedUsers();
        $this->assertSame(1, $count);
        $this->assertTrue($unverifiedUser->refresh()->marked_for_deletion);
        Mail::assertQueued(CulledUnverifiedUserMail::class, 1);
        $this->assertEquals(now()->addDays($grace), $unverifiedUser->refresh()->automatically_delete_at);

        // Re-running task will not care about already-marked-for-deletion user
        $count = Saasparilla::cullUnverifiedUsers();
        $this->assertSame(0, $count);
        $this->assertTrue($unverifiedUser->refresh()->marked_for_deletion);
        Mail::assertQueued(CulledUnverifiedUserMail::class, 1); // same as before, meaning nothing new was queued

        // After `grace` days, confirm already-marked-for-deletion user won't be deleted
        $this->travel($grace)->days();
        $count = Saasparilla::purgeCulledUsers();
        $this->assertSame(0, $count);
        $this->assertTrue($unverifiedUser->exists());

        // After one more second (exceeding `grace`), confirm already-marked-for-deletion user will be deleted
        $this->travel(1)->seconds();
        $count = Saasparilla::purgeCulledUsers();
        $this->assertSame(1, $count);
        $this->assertFalse($unverifiedUser->exists());
    }
}
