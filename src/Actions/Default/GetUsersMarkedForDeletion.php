<?php

namespace R4nkt\Saasparilla\Actions\Default;

use Illuminate\Support\Enumerable;
use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\GetsResources;

class GetUsersMarkedForDeletion implements GetsResources
{
    use HasParams;

    public function get(): Enumerable
    {
        $model = $this->requiredParam('model');

        return $model::lazyById()
            ->whereNull('email_verified_at') /** @todo Remove once testing demonstrates this "safeguard" isn't necessary. */
            ->where('automatically_delete_at', '<', now());
    }
}
