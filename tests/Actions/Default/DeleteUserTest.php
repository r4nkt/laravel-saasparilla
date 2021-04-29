<?php

namespace R4nkt\Saasparilla\Tests\Actions\Default;

use R4nkt\ResourceTidier\Actions\Contracts\ExecutesResourceTask;
use R4nkt\Saasparilla\Tests\TestCase;
use R4nkt\Saasparilla\Tests\TestClasses\User;

class DeleteUserTest extends TestCase
{
    /** @test */
    public function it_deletes_a_user()
    {
        $user = User::factory()->create();

        $this->assertTrue($user->exists);

        $this->tidier->handler()->task()->execute($user);

        $this->assertFalse($user->exists);
    }
}
