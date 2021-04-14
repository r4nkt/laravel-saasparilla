<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

use Illuminate\Support\Collection;

interface Find
{
    public function __construct(string $model, array $params);

    public function find(): Collection;
}
