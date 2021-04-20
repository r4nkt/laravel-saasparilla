<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface DeletesResource
{
    public function setParams(array $params = []);

    public function delete($resource): bool;
}
