<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface MarksResourcesForDeletion
{
    public function setParams(array $params = []);

    public function execute(): int;
}
