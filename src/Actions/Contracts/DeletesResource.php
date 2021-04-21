<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface DeletesResource
{
    public function setParams(array $params = []): mixed;

    public function delete(mixed $resource): bool;
}
