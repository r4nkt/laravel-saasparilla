<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use Carbon\Carbon;
use R4nkt\ResourceTidier\Actions\Contracts\FindsResources;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\Models\User;

class FindUnverifiedUsersTest extends TestCase
{
    protected FindsResources $finder;
    protected int $threshold = 20;

    public function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::create('2020-01-01 00:00:00'));

        config(['resource-tidier.finders.unverified-users.params.model' => User::class]);
        config(['resource-tidier.finders.unverified-users.params.threshold' => $this->threshold]);

        $this->finder = $this->tidier->culler()->finder();
    }

    /** @test */
    public function it_does_not_find_unverified_users_that_have_just_been_created()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->finder->find());
    }

    /** @test */
    public function it_does_not_find_unverified_users_that_were_created_exactly_threshold_days_earlier()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->finder->find());

        $this->travel($this->threshold)->days();

        $this->assertCount(0, $this->finder->find());
    }

    /** @test */
    public function it_does_find_unverified_users_that_were_created_greater_than_threshold_days_earlier()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->finder->find());

        $this->travel($this->threshold)->days();
        $this->travel(1)->seconds();

        $this->assertCount(1, $this->finder->find());
    }

    /** @test */
    public function it_does_not_find_unverified_users_that_were_created_greater_than_threshold_days_earlier_but_have_already_been_marked_for_deletion()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->finder->find());

        $this->travel($this->threshold)->days();
        $this->travel(1)->seconds();

        $this->tidier->culler()->marker()->mark($newUser);

        $this->assertCount(0, $this->finder->find());
    }

    /** @test */
    public function it_does_not_find_verified_users_that_were_created_greater_than_threshold_days_earlier()
    {
        $newUser = User::factory()->create(); // verified by default

        $this->assertCount(0, $this->finder->find());

        $this->travel($this->threshold + 1)->days();

        $this->assertCount(0, $this->finder->find());
    }
}
