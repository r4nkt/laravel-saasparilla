<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface SavesDeletableResource
{
    public function setParams(array $params = []);

    public function save($resource): void;
}
