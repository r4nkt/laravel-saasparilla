<?php

namespace R4nkt\Saasparilla\Actions\Default;

use Illuminate\Support\Enumerable;
use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\GetsResources;

class GetUnverifiedUsers implements GetsResources
{
    use HasParams;

    public function get(): Enumerable
    {
        $model = $this->requiredParam('model');
        $threshold = $this->requiredParam('threshold');

        return $model::lazyById()
            ->whereNull('email_verified_at')
            ->whereNull('automatically_delete_at')
            ->where('created_at', '<', now()->subDays($threshold));
    }
}
