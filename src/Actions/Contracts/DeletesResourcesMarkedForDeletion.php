<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface DeletesResourcesMarkedForDeletion
{
    public function setParams(array $params = []);

    public function execute(): int;
}
