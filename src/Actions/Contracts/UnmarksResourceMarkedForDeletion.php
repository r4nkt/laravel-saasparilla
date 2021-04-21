<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface UnmarksResourceMarkedForDeletion
{
    public function setParams(array $params = []);

    public function unmark($resource): void;
}
