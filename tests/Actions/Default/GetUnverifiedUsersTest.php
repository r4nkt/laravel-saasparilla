<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use Carbon\Carbon;
use R4nkt\Saasparilla\Actions\Contracts\GetsResources;
use R4nkt\Saasparilla\GetterFactory;
use R4nkt\Saasparilla\Saasparilla;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class GetUnverifiedUsersTest extends TestCase
{
    protected GetsResources $getter;
    protected int $threshold = 20;

    public function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::create('2020-01-01 00:00:00'));

        config(['saasparilla.getters.unverified-users.params.model' => User::class]);
        config(['saasparilla.getters.unverified-users.params.threshold' => $this->threshold]);

        $this->getter = GetterFactory::make('unverified-users');
    }

    /** @test */
    public function it_does_not_find_users_that_have_just_been_created()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->getter->get());
    }

    /** @test */
    public function it_does_not_find_unverified_users_that_were_created_exactly_threshold_days_earlier()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->getter->get());

        $this->travel($this->threshold)->days();

        $this->assertCount(0, $this->getter->get());
    }

    /** @test */
    public function it_does_find_unverified_users_that_were_created_greater_than_threshold_days_earlier()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->getter->get());

        $this->travel($this->threshold)->days();
        $this->travel(1)->seconds();

        $this->assertCount(1, $this->getter->get());
    }

    /** @test */
    public function it_does_not_find_verified_users_that_were_created_greater_than_threshold_days_earlier()
    {
        $newUser = User::factory()->create();

        $this->assertCount(0, $this->getter->get());

        $this->travel($this->threshold + 1)->days();

        $this->assertCount(0, $this->getter->get());
    }
}
