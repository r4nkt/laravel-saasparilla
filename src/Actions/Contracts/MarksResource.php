<?php

namespace R4nkt\Saasparilla\Actions\Contracts;

interface MarksResource
{
    public function setParams(array $params = []);

    public function mark($resource): void;
}
