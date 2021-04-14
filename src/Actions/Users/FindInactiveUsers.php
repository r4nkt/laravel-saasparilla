<?php

namespace R4nkt\Saasparilla\Actions\Users;

use Illuminate\Support\Collection;
use R4nkt\Saasparilla\Actions\Contracts\Find;

class FindInactiveUsers implements Find
{
    protected string $model;
    protected string $threshold;

    public function __construct(string $model, array $params)
    {
        $this->model = $model;
        $this->threshold = $params['threshold'] ?? 10;
    }

    public function find(): Collection
    {
        return $this->model::query()
            ->whereNull('email_verified_at')
            ->where('created_at', '<', now()->subDays($this->threshold))
            ->get();
    }
}
