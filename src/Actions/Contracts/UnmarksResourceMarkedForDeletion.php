<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface UnmarksResourceMarkedForDeletion
{
    public function setParams(array $params = []): mixed;

    public function unmark(mixed $resource): void;
}
