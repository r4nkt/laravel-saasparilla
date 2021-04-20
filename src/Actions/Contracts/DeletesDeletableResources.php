<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface DeletesDeletableResources
{
    public function setParams(array $params = []);

    public function execute(): int;
}
