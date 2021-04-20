<?php

namespace R4nkt\Saasparilla\Actions\Default;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Enumerable;
use R4nkt\Saasparilla\Actions\Concerns\HasParams;
use R4nkt\Saasparilla\Actions\Contracts\GetsResources;

class GetUnverifiedUsers implements GetsResources
{
    use HasParams;

    public function get(): Enumerable
    {
        $model = $this->param('model', User::class);
        $threshold = $this->param('threshold', 14);

        return $model::lazyById()
            ->whereNull('email_verified_at')
            ->whereNull('automatically_delete_at')
            ->where('created_at', '<', now()->subDays($threshold));
    }
}
