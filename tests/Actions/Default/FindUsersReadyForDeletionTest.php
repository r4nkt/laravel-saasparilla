<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use Carbon\Carbon;
use R4nkt\ResourceTidier\Actions\Contracts\FindsResources;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class FindUsersReadyForDeletionTest extends TestCase
{
    protected FindsResources $finder;
    protected int $grace = 20;

    public function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::create('2020-01-01 00:00:00'));

        config(['resource-tidier.finders.users-ready-for-deletion.params.model' => User::class]);
        config(['resource-tidier.markers.user-for-deletion.params.grace' => $this->grace]);

        $this->finder = $this->tidier->handler()->finder();
    }

    /** @test */
    public function it_does_not_find_unverified_users_that_have_just_been_created()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->finder->find());
    }

    /** @test */
    public function it_does_not_find_verified_users_that_have_just_been_created()
    {
        $newUser = User::factory()->create(); // defaults to verified

        $this->assertCount(0, $this->finder->find());
    }

    /** @test */
    public function it_does_not_find_unverified_users_that_were_created_greater_than_grace_days_earlier()
    {
        $newUser = User::factory()->unverified()->create();

        $this->travel($this->grace)->days();
        $this->travel(1)->seconds();

        $this->assertCount(0, $this->finder->find());
    }

    /** @test */
    public function it_does_not_find_verified_users_that_were_created_greater_than_grace_days_earlier()
    {
        $newUser = User::factory()->create(); // defaults to verified

        $this->travel($this->grace + 1)->days();

        $this->assertCount(0, $this->finder->find());
    }

    /** @test */
    public function it_does_not_find_unverified_users_that_were_marked_for_deletion_exactly_grace_days_earlier()
    {
        $newUser = User::factory()->unverified()->create();

        $this->tidier->culler()->marker()->mark($newUser);

        $this->travel($this->grace)->days();

        $this->assertCount(0, $this->finder->find());
    }

    /** @test */
    public function it_does_find_unverified_users_that_were_marked_for_deletion_greater_than_grace_days_earlier()
    {
        $newUser = User::factory()->unverified()->create();

        $this->tidier->culler()->marker()->mark($newUser);

        $this->travel($this->grace)->days();
        $this->travel(1)->seconds();

        $this->assertCount(1, $this->finder->find());
    }

    /** @test */
    public function it_does_not_find_users_that_were_marked_for_deletion_and_then_unmarked_before_grace_period_expired()
    {
        $newUser = User::factory()->unverified()->create();

        $this->tidier->culler()->marker()->mark($newUser);

        $this->assertCount(0, $this->finder->find());

        $this->tidier->unmarker()->unmark($newUser);

        $this->assertCount(0, $this->finder->find());

        $this->travel($this->grace)->days();
        $this->travel(1)->seconds();

        $this->assertCount(0, $this->finder->find());
    }

    /** @test */
    public function it_does_not_find_users_that_were_marked_for_deletion_before_grace_period_expired_and_then_unmarked_after_grace_period_expired()
    {
        $newUser = User::factory()->unverified()->create();

        $this->tidier->culler()->marker()->mark($newUser);

        $this->assertCount(0, $this->finder->find());

        $this->travel($this->grace)->days();
        $this->travel(1)->seconds();

        $this->assertCount(1, $this->finder->find());

        // If no purging takes place before unmarking, then all's good
        $this->tidier->unmarker()->unmark($newUser);

        $this->assertCount(0, $this->finder->find());
    }
}
