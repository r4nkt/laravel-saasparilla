<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

use Illuminate\Support\Enumerable;

interface GetsResources
{
    public function setParams(array $params = []);

    public function get(): Enumerable;
}
