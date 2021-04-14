<?php

namespace R4nkt\Saasparilla\Tests\Actions\Users;

use Carbon\Carbon;
use R4nkt\Saasparilla\Contexts\Context;
use R4nkt\Saasparilla\Saasparilla;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class FindInactiveUsersTest extends TestCase
{
    protected Context $context;
    protected int $threshold = 20;

    public function setUp(): void
    {
        parent::setUp();

        $this->travelTo(Carbon::create('2020-01-01 00:00:00'));

        config(['saasparilla.inactive_contexts.users.model' => User::class]);
        config(['saasparilla.inactive_contexts.users.actions.find.params.threshold' => $this->threshold]);

        $this->context = app(Saasparilla::class)->inactiveContext('users');
    }

    /** @test */
    public function it_does_not_find_users_that_have_just_been_created()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->context->find());
    }

    /** @test */
    public function it_does_not_find_unverified_users_that_were_created_exactly_threshold_days_earlier()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->context->find());

        $this->travel($this->threshold)->days();

        $this->assertCount(0, $this->context->find());
    }

    /** @test */
    public function it_does_find_unverified_users_that_were_created_greater_than_threshold_days_earlier()
    {
        $newUser = User::factory()->unverified()->create();

        $this->assertCount(0, $this->context->find());

        $this->travel($this->threshold + 1)->days();

        $this->assertCount(1, $this->context->find());
    }

    /** @test */
    public function it_does_not_find_verified_users_that_were_created_greater_than_threshold_days_earlier()
    {
        $newUser = User::factory()->create();

        $this->assertCount(0, $this->context->find());

        $this->travel($this->threshold + 1)->days();

        $this->assertCount(0, $this->context->find());
    }
}
