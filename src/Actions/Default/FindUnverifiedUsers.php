<?php

namespace R4nkt\Saasparilla\Actions\Default;

use Illuminate\Support\Enumerable;
use R4nkt\ResourceTidier\Actions\Contracts\FindsResources;
use R4nkt\ResourceTidier\Concerns\HasParams;

class FindUnverifiedUsers implements FindsResources
{
    use HasParams;

    public function find(): Enumerable
    {
        $model = $this->requiredParam('model');
        $threshold = $this->requiredParam('threshold');

        return $model::lazyById()
            ->whereNull('email_verified_at')
            ->whereNull('automatically_delete_at')
            ->where('created_at', '<', now()->subDays($threshold));
    }
}
