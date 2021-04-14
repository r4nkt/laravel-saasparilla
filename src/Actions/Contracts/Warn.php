<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

use Illuminate\Support\Collection;

interface Warn
{
    public function __construct(array $params);

    public function warn(Collection $users): void;
}
