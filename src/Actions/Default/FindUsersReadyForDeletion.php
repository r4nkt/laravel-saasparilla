<?php

namespace R4nkt\Saasparilla\Actions\Default;

use Illuminate\Support\Enumerable;
use R4nkt\ResourceTidier\Actions\Contracts\FindsResources;
use R4nkt\ResourceTidier\Concerns\HasParams;

class FindUsersReadyForDeletion implements FindsResources
{
    use HasParams;

    public function find(): Enumerable
    {
        $model = $this->requiredParam('model');

        return $model::lazyById()
            ->whereNull('email_verified_at') /** @todo Remove once testing demonstrates this "safeguard" isn't necessary. */
            ->where('automatically_delete_at', '<', now());
    }
}
